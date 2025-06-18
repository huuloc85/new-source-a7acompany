<?php

namespace App\Helpers;

class ValidationHelper
{
    public static function mapValidationErrors(array $errors): array
    {
        $mapped = [];

        foreach ($errors as $field => $messages) {
            $mapped[$field] = array_map(function ($msg) {
                $msg = strtolower($msg);

                return self::matchToCode($msg);
            }, $messages);
        }

        return $mapped;
    }

    private static function matchToCode(string $msg): string
    {
        return match (true) {
            str_contains($msg, 'required') => 'REQUIRED_FIELD',
            str_contains($msg, 'must be a valid email') => 'INVALID_EMAIL',
            str_contains($msg, 'must be a valid date') => 'INVALID_DATE',
            str_contains($msg, 'must be a valid url') => 'INVALID_URL',
            str_contains($msg, 'does not match') => 'INVALID_CONFIRMATION',
            str_contains($msg, 'must match the format') => 'INVALID_FORMAT',
            str_contains($msg, 'format is invalid') => 'INVALID_FORMAT',
            str_contains($msg, 'regex') => 'INVALID_PATTERN',
            str_contains($msg, 'not a valid') => 'INVALID_INPUT',
            str_contains($msg, 'must be at least') => 'TOO_SHORT',
            str_contains($msg, 'must not be greater') => 'TOO_LONG',
            str_contains($msg, 'already been taken') => 'DUPLICATE',
            str_contains($msg, 'must be a file') => 'INVALID_FILE',
            str_contains($msg, 'must be an image') => 'INVALID_IMAGE',
            str_contains($msg, 'must be a number') => 'INVALID_NUMBER',
            str_contains($msg, 'must be an integer') => 'INVALID_INTEGER',
            str_contains($msg, 'must exist') => 'NOT_FOUND',
            str_contains($msg, 'must be accepted') => 'UNACCEPTED',
            str_contains($msg, 'must be one of') => 'INVALID_OPTION',
            str_contains($msg, 'must be before') => 'INVALID_DATE_RANGE_BEFORE',
            str_contains($msg, 'must be after') => 'INVALID_DATE_RANGE_AFTER',
            // str_contains($msg, 'must be unique') => 'DUPLICATE',
            // str_contains($msg, 'not in the list') => 'INVALID_OPTION',
            str_contains($msg, 'of type') => 'INVALID_FILE_TYPE',
            str_contains($msg, 'must be a string') => 'INVALID_STRING',
            str_contains($msg, 'must be a boolean') => 'INVALID_BOOLEAN',
            str_contains($msg, 'must be a valid json') => 'INVALID_JSON',
            str_contains($msg, 'must be an array') => 'INVALID_ARRAY',
            str_contains($msg, 'must be a valid') => 'INVALID_INPUT',
            default => $msg
        };
    }
}
