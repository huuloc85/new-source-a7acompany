<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // app/Http/Middleware/CheckPermission.php
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (
            ! $user ||
            ! isset($user->role) ||
            strtolower(trim($user->role->role_name)) !== 'super admin'
        ) {
            toast('Bạn không có quyền truy cập!', 'error', 'top-right');

            return redirect()->route('admin.home');
        }

        return $next($request);
    }
}
