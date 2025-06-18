<?php

namespace App\Utils;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SearchFilter
{
    public static function apply(
        Builder $query,
        Request $request,
        array $rules,
        array $sortableFields = []
    ): Builder {
        // Apply filters based on the rules
        foreach ($rules as $field => $type) {
            $value = $request->input($field);

            if (is_null($value)) {
                continue;
            }

            match ($type) {
                'string' => $query->where($field, 'like', "%{$value}%"),
                'exact' => $query->where($field, $value),
                'min' => $query->where($field, '>=', $value),
                'max' => $query->where($field, '<=', $value),
                'range' => self::applyRangeFilter($query, $field, $value),
                default => null,
            };
        }

        // Handle multiple sort parameters
        if ($request->filled('sort') && strpos($request->sort, ':')) {
            // Split the sort parameter into field and direction
            [$field, $dir] = explode(':', $request->sort);

            // Ensure field is allowed and direction is valid
            if (in_array($field, $sortableFields) && in_array($dir, ['asc', 'desc'])) {
                $query->orderBy($field, $dir);
            }
        }

        return $query;
    }

    public static function applyRangeFilter(Builder $query, string $field, string $value): Builder
    {
        // Expected value format: "min_value-max_value" or "min_value|" or "|max_value"
        $values = explode('|', $value);

        // If the range contains two values (min and max)
        if (count($values) === 2) {
            $min = trim($values[0]);
            $max = trim($values[1]);

            // Apply the range filter only if both values are not empty
            if (! empty($min) && ! empty($max)) {
                return $query->whereBetween($field, [$min, $max]);
            }

            // Handle cases where the min or max value is missing
            if (empty($min)) {
                // Apply only the max value (interpreting as "less than or equal to")
                return $query->where($field, '<=', $max);
            }

            if (empty($max)) {
                // Apply only the min value (interpreting as "greater than or equal to")
                return $query->where($field, '>=', $min);
            }
        }

        // If the format is incorrect or not a valid range, return the original query
        return $query;
    }
}
