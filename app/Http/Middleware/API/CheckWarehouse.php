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

        $allowedRoles = [
            'Admin',
            'Kho',
            'Super Admin',
        ];

        if ($user && in_array($user->role->role_name, $allowedRoles)) {
            return $next($request);
        }

        return response()->json([
            'message' => 'Bạn không có quyền truy cập!',
        ], 403);
    }
}
