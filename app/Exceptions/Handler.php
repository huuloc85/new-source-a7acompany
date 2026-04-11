<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Giảm nhiễu log với lỗi rớt mạng Database / MySQL
            if ($e instanceof \Illuminate\Database\QueryException && str_contains($e->getMessage(), '2002')) {
                \Illuminate\Support\Facades\Log::error('Database Connection Refused (Network Unreachable) - Check your MySQL Server or Internet Connection.');
                return false;
            }
            if ($e instanceof \PDOException && $e->getCode() == 2002) {
                \Illuminate\Support\Facades\Log::error('PDO Connection Refused (Network Unreachable).');
                return false;
            }

            // Giảm nhiễu log khi dùng Redis mà service bị sập
            if ($e instanceof \Predis\Connection\ConnectionException || str_contains($e->getMessage(), 'Predis')) {
                \Illuminate\Support\Facades\Log::error('Redis Connection Refused - Please start Redis service or change CACHE/SESSION_DRIVER to file.');
                return false;
            }
        });
    }
}
