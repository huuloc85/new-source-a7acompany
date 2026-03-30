<?php

namespace App\Http\Middleware\API;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     * Chỉ cho phép Admin / Co Admin / Super Admin truy cập.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        $user = auth()->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if (! $user->role) {
            return response()->json(['message' => 'Bạn chưa được gán vai trò.'], 403);
        }

        $roleName = strtolower(trim($user->role->role_name));
        $adminRoles = ['admin', 'super admin', 'co admin'];

        if (! in_array($roleName, $adminRoles, true)) {
            return response()->json(['message' => 'Bạn không có quyền truy cập chức năng này.'], 403);
        }

        return $next($request);
    }
}
