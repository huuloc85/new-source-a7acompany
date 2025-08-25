<?php

namespace App\Http\Middleware\API;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckLeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        $user = auth()->user();

        // Cho phép các role này
        $allowedRoles = [
            'super admin',
            'admin',
            'co admin',
            'tổ trưởng ngoại quan',
            'tổ phó sản xuất',
            'tổ trưởng sản xuất',
        ];

        if (
            $user &&
            isset($user->role) &&
            in_array(strtolower(trim($user->role->role_name)), $allowedRoles, true)
        ) {
            return $next($request);
        }

        return response()->json([
            'message' => 'Bạn không có quyền truy cập!',
        ], 403);
    }
}
