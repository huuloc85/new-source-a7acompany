<?php

namespace App\Helpers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class HandleError
{
    public static function handle(Throwable $e)
    {
        $status = 500;
        $message = 'Something went wrong.';
        $errors = null;

        // Laravel-specific exceptions
        if ($e instanceof ValidationException) {
            $status = 422;
            $message = 'Validation failed.';
            $errors = ValidationHelper::mapValidationErrors($e->errors());
        } elseif ($e instanceof ModelNotFoundException) {
            $status = 404;
            $message = 'Resource not found.';
        } elseif ($e instanceof AuthenticationException) {
            $status = 401;
            $message = 'Unauthenticated.';
        } elseif ($e instanceof AuthorizationException) {
            $status = 403;
            $message = 'Unauthorized.';
        } elseif ($e instanceof NotFoundHttpException) {
            $status = 404;
            $message = 'Endpoint not found.';
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            $status = 405;
            $message = 'HTTP method not allowed.';
        } elseif ($e instanceof QueryException) {
            $status = 500;
            $message = 'Database query error.';
        }

        // Log every exception (you can make this smarter too)
        Log::error(
            'Exception handled:',
            [
                'type' => get_class($e),
                'code' => $status,
                'message' => $e->getMessage(),
                // 'errors' =>  $e->errors(),
                'detals' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
            ]
        );

        // Response
        return response()->json([
            'error' => config('app.debug') ? [
                'code' => $status,
                'message' => $message,
                'errors' => $errors,
                'line' => $e->getLine(),
                'details' => [
                    $e,
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'previous' => $e->getPrevious(),
                    'traceString' => $e->getTraceAsString(),
                ],
            ] : [
                'code' => $status,
                'message' => $message,
                'errors' => $errors,
            ],
        ], $status);

    }
}
