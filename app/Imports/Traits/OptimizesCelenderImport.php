<?php

namespace App\Imports\Traits;

use App\Models\Employee;

trait OptimizesCelenderImport
{
    private ?array $employeeMap = null;
    private ?array $validEmployeeIds = null;

    protected function normalizeEmployeeId($rawEmployeeId): ?string
    {
        if ($rawEmployeeId === null) {
            return null;
        }

        $employeeId = trim((string) $rawEmployeeId);
        if ($employeeId === '') {
            return null;
        }

        $map = $this->getEmployeeMap();
        $stripped = ltrim($employeeId, '0');

        return $map[$employeeId] ?? ($map[$stripped] ?? null);
    }

    protected function getEmployeeMap(): array
    {
        if ($this->employeeMap !== null) {
            return $this->employeeMap;
        }

        $map = [];
        foreach (Employee::query()->select('id')->get() as $employee) {
            $id = (string) $employee->id;
            $map[$id] = $id;
            $map[ltrim($id, '0')] = $id;
        }

        $this->employeeMap = $map;

        return $map;
    }

    protected function getValidEmployeeIds(): array
    {
        if ($this->validEmployeeIds !== null) {
            return $this->validEmployeeIds;
        }

        $employeeIds = Employee::query()->pluck('id')->map(fn ($id) => (string) $id)->all();
        $trimmedIds = array_map(static fn ($id) => ltrim($id, '0'), $employeeIds);

        $this->validEmployeeIds = array_values(array_unique(array_merge($employeeIds, $trimmedIds)));

        return $this->validEmployeeIds;
    }

    protected function getExistingEmployeeSet(string $modelClass, int $celenderId): array
    {
        $existingIds = $modelClass::query()
            ->where('celender_id', $celenderId)
            ->pluck('employee_id')
            ->map(fn ($id) => (string) $id)
            ->all();

        return array_fill_keys($existingIds, true);
    }

    protected function extractDays(array $row, int $startIndex, int $dayCount): array
    {
        $days = [];
        for ($i = 1; $i <= $dayCount; $i++) {
            $value = $row[$startIndex + $i - 1] ?? null;
            $days['day'.$i] = $value !== '' ? $value : null;
        }

        return $days;
    }

    protected function batchInsert(string $modelClass, array $rows, int $chunkSize = 500): void
    {
        if (empty($rows)) {
            return;
        }

        foreach (array_chunk($rows, $chunkSize) as $chunk) {
            $modelClass::query()->insert($chunk);
        }
    }
}
