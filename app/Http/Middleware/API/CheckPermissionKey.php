<?php

namespace App\Http\Middleware\API;

use App\Models\Permission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CheckPermissionKey
{
    /**
     * Dynamic RBAC middleware.
     * Kiểm tra user có permission key cụ thể không (bao gồm grant/deny).
     *
     * Sử dụng: middleware('api.can:manage_employees')
     *          middleware('api.can:view_dashboard,manage_employees') // OR logic
     *
     * Logic:
     *   1. Super Admin / Admin / Co Admin → luôn được phép (admin-level roles)
     *   2. Check exact key match trước
     *   3. Nếu không match exact → check theo module (cùng module thì pass)
     *   4. Nếu truyền nhiều permission keys (phân cách bằng dấu phẩy) → chỉ cần 1 match
     *
     * @param  string  ...$permissionKeys  Một hoặc nhiều permission keys
     */
    public function handle(Request $request, Closure $next, string ...$permissionKeys)
    {
        $user = auth()->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Đảm bảo có role
        if (! $user->role) {
            return response()->json(['message' => 'Bạn chưa được gán vai trò.'], 403);
        }

        $roleName = strtolower(trim($user->role->role_name));

        // Admin-level roles luôn bypass — họ là quản trị viên
        $adminRoles = ['super admin', 'admin', 'co admin'];

        if (in_array($roleName, $adminRoles, true)) {
            return $next($request);
        }

        // Các role khác: kiểm tra permission keys (OR logic - chỉ cần 1 match)
        foreach ($permissionKeys as $key) {
            $key = trim($key);

            // Check 1: Exact match
            if ($user->hasPermission($key)) {
                return $next($request);
            }

            // Check 2: Module-based match
            // Tìm module của permission key được yêu cầu, rồi check user có BẤT KỲ permission nào trong module đó không
            if ($this->hasModulePermission($user, $key)) {
                return $next($request);
            }
        }

        Log::warning('Permission denied', [
            'user_id' => $user->id,
            'role' => $roleName,
            'required' => $permissionKeys,
            'url' => $request->fullUrl(),
        ]);

        return response()->json([
            'message' => 'Bạn không có quyền truy cập chức năng này.',
        ], 403);
    }

    /**
     * Kiểm tra user có bất kỳ permission nào trong cùng module không.
     *
     * Ví dụ: route yêu cầu 'view_schedule'
     *   → tìm module của 'view_schedule' = 'schedule'
     *   → lấy tất cả keys trong module 'schedule': [view_schedule, view_schedule_categories]
     *   → check user có bất kỳ key nào trong đó
     */
    private function hasModulePermission($user, string $requiredKey): bool
    {
        // Cache module map 60 phút để tránh query liên tục
        $moduleMap = Cache::remember('permissions_module_map', 3600, function () {
            return Permission::whereNotNull('module')
                ->pluck('module', 'key')
                ->toArray();
        });

        // Tìm module của key được yêu cầu
        $module = $moduleMap[$requiredKey] ?? null;

        if (! $module) {
            return false;
        }

        // Lấy tất cả keys trong cùng module
        $moduleKeys = array_keys(array_filter($moduleMap, fn ($m) => $m === $module));

        // Kiểm tra user có bất kỳ permission nào trong module
        foreach ($moduleKeys as $moduleKey) {
            if ($moduleKey !== $requiredKey && $user->hasPermission($moduleKey)) {
                return true;
            }
        }

        return false;
    }
}
