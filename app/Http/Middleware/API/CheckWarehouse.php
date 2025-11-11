<?php

namespace App\Http\Middleware\API;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckWarehouse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        $user = auth()->user();

        // Danh sách role hợp lệ, viết thường hết
        $allowedRoles = [
            'admin',
            'kho',
            'super admin',
            'co admin',
        ];

        if (
            $user &&
            isset($user->role) &&
            in_array(strtolower(trim($user->role->role_name)), $allowedRoles, true)
        ) {
            return $next($request);
        }

        return response()->json([
            'success' => false,
            'message' => 'Bạn không có quyền truy cập!',
        ], 403);
    }
}
