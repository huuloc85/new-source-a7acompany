<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckTokenExpiration
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Lấy token hiện tại
        $token = $request->user()->currentAccessToken();

        // Kiểm tra xem token có tồn tại trong bảng personal_access_tokens không
        if (! $token) {
            return response()->json(['message' => 'Token không hợp lệ'], 401);
        }

        // Thêm cột expires_at vào bảng personal_access_tokens
        // php artisan make:migration add_expires_at_to_personal_access_tokens_table

        // Kiểm tra thời gian hết hạn
        if ($token->expires_at && now()->isAfter($token->expires_at)) {
            // Token đã hết hạn, xóa token
            $token->delete();

            return response()->json(['message' => 'Token đã hết hạn'], 401);
        }

        return $next($request);
    }
}
