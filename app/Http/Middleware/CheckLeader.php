<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth()->user();
        $roleName = strtolower(trim($user->role->role_name ?? ''));
        $allowedRoles = [
            'super admin',
            'admin',
            'tổ trưởng ngoại quan',
            'tổ phó sản xuất',
            'tổ trưởng sản xuất',
            'tổ trưởng qc',
            'tổ trưởng kho',
        ];
        $allowedRoleIds = [23, 24];

        if (in_array($roleName, $allowedRoles, true) || in_array($user->role_id, $allowedRoleIds, true)) {
            return $next($request);
        }
        toast('Bạn không có quyền truy cập!', 'error', 'top-right');

        return redirect()->route('admin.home');
    }
}
