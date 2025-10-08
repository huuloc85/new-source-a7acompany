<?php

namespace App\Http\Middleware\API;

use Closure;
use Illuminate\Http\Request;

class CheckEmployee
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        $forbiddenRoles = [
            'admin',
            'super admin',
            'co admin',
        ];

        if (
            $user &&
            isset($user->role) &&
            in_array(strtolower(trim($user->role->role_name)), $forbiddenRoles, true)
        ) {
            return response()->json([
                'message' => 'Bạn không có quyền truy cập!',
            ], 403);
        }

        return $next($request);
    }
}
