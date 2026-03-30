<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Đồng bộ permissions cho Admin và Co Admin roles.
     * Fix: Co Admin thiếu view_salary_total, Admin thiếu view_feedbacks.
     */
    public function up(): void
    {
        // Lấy role IDs
        $adminRoleId = DB::table('roles')->where('role_name', 'Admin')->value('id');
        $coAdminRoleId = DB::table('roles')->where('role_name', 'Co Admin')->value('id');

        if (! $adminRoleId || ! $coAdminRoleId) {
            Log::warning('sync_rbac_permissions: Admin or Co Admin role not found, skipping.');

            return;
        }

        // Lấy tất cả admin permissions hiện tại của Admin role
        $adminPermissionIds = DB::table('role_permission')
            ->where('role_id', $adminRoleId)
            ->pluck('permission_id')
            ->toArray();

        // Lấy tất cả permissions hiện tại của Co Admin
        $coAdminPermissionIds = DB::table('role_permission')
            ->where('role_id', $coAdminRoleId)
            ->pluck('permission_id')
            ->toArray();

        // 1. Thêm view_feedbacks (86) cho Admin nếu chưa có
        $viewFeedbacksId = DB::table('permissions')->where('key', 'view_feedbacks')->value('id');
        if ($viewFeedbacksId && ! in_array($viewFeedbacksId, $adminPermissionIds)) {
            DB::table('role_permission')->insert([
                'role_id' => $adminRoleId,
                'permission_id' => $viewFeedbacksId,
            ]);
            Log::info('sync_rbac_permissions: Added view_feedbacks to Admin role.');
        }

        // 2. Thêm view_salary_total cho Co Admin nếu chưa có
        $viewSalaryTotalId = DB::table('permissions')->where('key', 'view_salary_total')
            ->where('display_area', 'sidebar')
            ->value('id');
        if ($viewSalaryTotalId && ! in_array($viewSalaryTotalId, $coAdminPermissionIds)) {
            DB::table('role_permission')->insert([
                'role_id' => $coAdminRoleId,
                'permission_id' => $viewSalaryTotalId,
            ]);
            Log::info('sync_rbac_permissions: Added view_salary_total to Co Admin role.');
        }

        // 3. Thêm view_feedbacks cho Co Admin nếu chưa có (refresh list)
        $coAdminPermissionIds = DB::table('role_permission')
            ->where('role_id', $coAdminRoleId)
            ->pluck('permission_id')
            ->toArray();
        if ($viewFeedbacksId && ! in_array($viewFeedbacksId, $coAdminPermissionIds)) {
            DB::table('role_permission')->insert([
                'role_id' => $coAdminRoleId,
                'permission_id' => $viewFeedbacksId,
            ]);
            Log::info('sync_rbac_permissions: Added view_feedbacks to Co Admin role.');
        }

        // 4. Thêm history_print cho Admin nếu chưa có
        $historyPrintId = DB::table('permissions')->where('key', 'view_history_print')->value('id');
        if ($historyPrintId && ! in_array($historyPrintId, $adminPermissionIds)) {
            DB::table('role_permission')->insert([
                'role_id' => $adminRoleId,
                'permission_id' => $historyPrintId,
            ]);
            Log::info('sync_rbac_permissions: Added view_history_print to Admin role.');
        }
    }

    public function down(): void
    {
        // Rollback không cần thiết vì đây là sync data
    }
};
