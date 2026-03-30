<?php

namespace App\Http\Middleware\API;

use Closure;
use Illuminate\Http\Request;

class CheckEmployee
{
    /**
     * Handle an incoming request.
     * Chỉ cho phép Employee truy cập. Admin/Co Admin bị chặn.
     * Super Admin luôn bypass (quyền truy cập toàn hệ thống).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // User chưa được gán role → chặn
        if (! $user->role) {
            return response()->json(['message' => 'Bạn chưa được gán vai trò.'], 403);
        }

        $roleName = strtolower(trim($user->role->role_name));

        // Super Admin luôn bypass — quyền truy cập toàn hệ thống
        if ($roleName === 'super admin') {
            return $next($request);
        }

        // Admin và Co Admin không được truy cập các route dành cho Employee
        $adminRoles = ['admin', 'co admin'];

        if (in_array($roleName, $adminRoles, true)) {
            return response()->json([
                'message' => 'Chức năng này chỉ dành cho nhân viên.',
            ], 403);
        }

        return $next($request);
    }
}
