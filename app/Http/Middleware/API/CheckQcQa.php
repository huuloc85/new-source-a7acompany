<?php

namespace App\Http\Middleware\API;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckQcQa
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        $user = auth()->user();

        $allowedRoles = [
            'admin',
            'qa-qc',
            'qc',
            'super admin',
            'co admin',
            'tổ trưởng qc',
        ];

        if (
            $user &&
            isset($user->role) &&
            (
                in_array(strtolower(trim($user->role->role_name)), $allowedRoles, true) ||
                $user->role_id === 23
            )
        ) {
            return $next($request);
        }

        return response()->json([
            'message' => 'Bạn không có quyền truy cập!',
        ], 403);
    }
}
