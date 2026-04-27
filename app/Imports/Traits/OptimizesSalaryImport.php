<?php

namespace App\Imports\Traits;

use App\Models\Employee;
use Illuminate\Support\Facades\Cache;

trait OptimizesSalaryImport
{
    /**
     * Cache key for employees data
     */
    protected function getEmployeeCacheKey(): string
    {
        return 'import_employees_all';
    }

    /**
     * Preload and cache all employees for fast lookup
     * Returns associative array with both original and trimmed IDs as keys
     */
    protected function getPreloadedEmployees(): array
    {
        // Try to get from cache first (5 minutes)
        $cacheKey = $this->getEmployeeCacheKey();
        $cached = Cache::get($cacheKey);

        if ($cached !== null) {
            return $cached;
        }

        // Load all employees once
        $employees = Employee::all();
        $employeeMap = [];

        foreach ($employees as $emp) {
            $employeeMap[$emp->id] = $emp;
            $employeeMap[ltrim($emp->id, '0')] = $emp;
        }

        // Cache for 5 minutes (during import session)
        Cache::put($cacheKey, $employeeMap, 300);

        return $employeeMap;
    }

    /**
     * Clear employee cache after import completes
     */
    protected function clearEmployeeCache(): void
    {
        Cache::forget($this->getEmployeeCacheKey());
    }

    /**
     * Preload salary records for a specific manager to avoid N+1 queries
     */
    protected function getPreloadedSalaryRecords(string $modelClass, int $managerId): array
    {
        $cacheKey = "salary_{$modelClass}_manager_{$managerId}";
        $cached = Cache::get($cacheKey);

        if ($cached !== null) {
            return $cached;
        }

        $records = $modelClass::where('salaries_manager_id', $managerId)->get();
        $recordMap = [];

        foreach ($records as $record) {
            $recordMap[$record->employee_id] = $record;
        }

        // Cache during import (5 minutes)
        Cache::put($cacheKey, $recordMap, 300);

        return $recordMap;
    }

    /**
     * Clear salary records cache
     */
    protected function clearSalaryCache(string $modelClass, int $managerId): void
    {
        $cacheKey = "salary_{$modelClass}_manager_{$managerId}";
        Cache::forget($cacheKey);
    }

    /**
     * Get employee by ID using preloaded data (fast O(1) lookup)
     */
    protected function getEmployeeFast(string $employeeId, array $employeeMap)
    {
        $stripped = ltrim($employeeId, '0');

        return $employeeMap[$employeeId] ?? ($employeeMap[$stripped] ?? null);
    }

    /**
     * Get salary record by employee ID using preloaded data
     */
    protected function getSalaryRecordFast(array $salaryMap, int $employeeId)
    {
        return $salaryMap[$employeeId] ?? null;
    }

    /**
     * Execute batch update/insert for better performance
     */
    protected function batchUpdateOrInsert(string $modelClass, array $records, int $batchSize = 500): void
    {
        if (empty($records)) {
            return;
        }

        foreach (array_chunk($records, $batchSize) as $chunk) {
            // Use insert for new records or bulk update as needed
            $modelClass::insert($chunk);
        }
    }

    /**
     * Bulk update existing salary rows by primary key.
     */
    protected function batchUpsertById(string $modelClass, array $records, array $columns, int $batchSize = 200): void
    {
        if (empty($records) || empty($columns)) {
            return;
        }

        foreach (array_chunk($records, $batchSize) as $chunk) {
            $modelClass::query()->upsert($chunk, ['id'], $columns);
        }
    }

    protected function batchUpsertByIdUsingRecordColumns(string $modelClass, array $records, int $batchSize = 200): void
    {
        if (empty($records)) {
            return;
        }

        $columns = array_values(array_diff(array_keys($records[0]), ['id', 'created_at']));
        $this->batchUpsertById($modelClass, $records, $columns, $batchSize);
    }

    protected function numericValue($value): ?float
    {
        return is_numeric($value ?? null) ? (float) $value : null;
    }

    /**
     * Get valid employee IDs for validation (cached)
     */
    protected function getValidEmployeeIds(): array
    {
        $cacheKey = 'employee_ids_valid_list';
        $cached = Cache::get($cacheKey);

        if ($cached !== null) {
            return $cached;
        }

        $listCode = Employee::all()->pluck('id')->toArray();
        $listCode = array_merge($listCode, array_map(function($id) {
            return ltrim($id, '0');
        }, $listCode));

        Cache::put($cacheKey, $listCode, 300);

        return $listCode;
    }

    /**
     * Clear validation cache
     */
    protected function clearValidationCache(): void
    {
        Cache::forget('employee_ids_valid_list');
        Cache::forget($this->getEmployeeCacheKey());
    }
}
