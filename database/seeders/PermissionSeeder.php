<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $permissions = [
            ['key' => 'view_total_employees', 'name' => 'Tổng nhân viên', 'type' => 'admin'],
            ['key' => 'view_attendance_history', 'name' => 'Bảng Lịch Sử Chấm Công', 'type' => 'admin'],
            ['key' => 'view_attendance_calculation', 'name' => 'Bảng Tính Toán Chấm Công', 'type' => 'admin'],
            ['key' => 'view_total_positions', 'name' => 'Tổng chức vụ', 'type' => 'admin'],
            ['key' => 'view_production_plan', 'name' => 'Kế hoạch sản xuất', 'type' => 'admin'],
            ['key' => 'view_today_employees', 'name' => 'Danh sách NV làm việc trong ngày', 'type' => 'admin'],
            ['key' => 'view_total_schedule', 'name' => 'Tổng lịch làm việc', 'type' => 'admin'],
            ['key' => 'view_total_products', 'name' => 'Tổng sản phẩm', 'type' => 'admin'],
            ['key' => 'view_total_history', 'name' => 'Tổng lịch sử', 'type' => 'admin'],
            ['key' => 'view_po_list', 'name' => 'Danh sách PO', 'type' => 'admin'],
            ['key' => 'view_labels_to_print', 'name' => 'Danh Sách Tem Cần In', 'type' => 'both'],
            ['key' => 'view_export_warehouse', 'name' => 'Kho Xuất Hàng', 'type' => 'admin'],
            ['key' => 'view_total_salary', 'name' => 'Tổng bảng lương', 'type' => 'admin'],

            ['key' => 'view_attendance_sheet_history', 'name' => 'Bảng lịch sử chấm công', 'type' => 'employee'],
            ['key' => 'view_work_time_calc_sheet', 'name' => 'Bảng tính công', 'type' => 'employee'],
            ['key' => 'view_work_schedule', 'name' => 'Lịch làm việc', 'type' => 'employee'],
            ['key' => 'view_salary_sheet', 'name' => 'Bảng lương', 'type' => 'employee'],
            ['key' => 'view_employee_schedule', 'name' => 'Lịch làm việc nhân viên', 'type' => 'employee'],
            ['key' => 'view_daily_activities', 'name' => 'Lịch Hoạt Động Trong Ngày', 'type' => 'employee'],
            ['key' => 'scan_barcode', 'name' => 'Quét Barcode', 'type' => 'employee'],
            ['key' => 'scan_qrcode', 'name' => 'Quét QR Code', 'type' => 'employee'],
            ['key' => 'view_exported_warehouse', 'name' => 'Kho Đã Xuất Hàng', 'type' => 'employee'],
            ['key' => 'select_active_product', 'name' => 'Chọn sản phẩm hoạt động', 'type' => 'employee'],
            ['key' => 'view_daily_import_history', 'name' => 'Lịch sử nhập hàng ngày', 'type' => 'employee'],
            ['key' => 'request_label_printing', 'name' => 'Yêu Cầu In Tem', 'type' => 'employee'],
            ['key' => 'create_carton_label', 'name' => 'Tạo Tem Thùng', 'type' => 'employee'],
            ['key' => 'create_bag_label', 'name' => 'Tạo Tem Bịch', 'type' => 'employee'],
            ['key' => 'view_account_info', 'name' => 'Thông tin tài khoản', 'type' => 'both'],
            ['key' => 'logout', 'name' => 'Đăng xuất', 'type' => 'both'],
        ];

        foreach ($permissions as $perm) {
            Permission::updateOrCreate(
                ['key' => $perm['key']],
                ['name' => $perm['name'], 'type' => $perm['type']]
            );
        }

        $this->command->info('✔️ Permissions seeded with type successfully!');
    }
}
