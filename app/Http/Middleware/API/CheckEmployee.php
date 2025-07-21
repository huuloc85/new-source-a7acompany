<?php

namespace App\Http\Middleware\API;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckEmployee
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        $user = auth()->user();

        $forbiddenRoles = [
            'Admin',
            'Super Admin',
        ];

        if ($user && in_array($user->role->role_name, $forbiddenRoles)) {
            return response()->json([
                'message' => 'Bạn không có quyền truy cập!',
            ], 403);
        }

        return $next($request);
    }
}
