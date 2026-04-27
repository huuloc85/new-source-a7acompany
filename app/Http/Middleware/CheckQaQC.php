<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckQaQC
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
            'admin',
            'qa-qc',
            'qc',
            'super admin',
            'tổ trưởng qc',
        ];

        if (in_array($roleName, $allowedRoles, true) || $user->role_id === 23) {
            return $next($request);
        }
        toast('Bạn không có quyền truy cập!', 'error', 'top-right');

        return redirect()->route('admin.home');
    }
}
