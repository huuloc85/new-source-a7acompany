<?php

namespace App\Http\Middleware\API;

use Closure;
use Illuminate\Http\Request;

class CheckSuperAdmin
{
    /**
     * Handle an incoming request.
     * Only allow Super Admin role to access RBAC & Permission management.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (
            ! $user ||
            ! isset($user->role) ||
            strtolower(trim($user->role->role_name)) !== 'super admin'
        ) {
            return response()->json(['message' => 'Bạn không có quyền truy cập. Chỉ Super Admin mới có quyền quản lý RBAC.'], 403);
        }

        return $next($request);
    }
}
