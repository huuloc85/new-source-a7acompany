<?php

namespace App\Imports\Celender;

use App\Helpers\LogHelper;
use App\Imports\Traits\OptimizesCelenderImport;
use App\Models\CelenderDetailWC;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class CelenderWCImport implements SkipsEmptyRows, SkipsOnFailure, ToArray, WithStartRow, WithValidation
{
    use OptimizesCelenderImport;

    public function __construct(public int $celenderId) {}

    public function sheet(): string
    {
        return 'ĐỔ RÁC WC';
    }

    public function array(array $rows)
    {
        try {
            $existing = $this->getExistingEmployeeSet(CelenderDetailWC::class, $this->celenderId);
            $now = now();
            $insertRows = [];

            foreach ($rows as $row) {
                $employeeId = $this->normalizeEmployeeId($row[1] ?? null);
                if ($employeeId === null || isset($existing[$employeeId])) {
                    continue;
                }

                $insertRows[] = array_merge([
                    'celender_id' => $this->celenderId,
                    'employee_id' => $employeeId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ], $this->extractDays($row, 3, 5));

                $existing[$employeeId] = true;
            }

            $this->batchInsert(CelenderDetailWC::class, $insertRows);
        } catch (\Throwable $e) {
            LogHelper::saveLog('Import-Celender-WC', $e->getMessage(), $e->getLine());
            Log::error('Import Celender WC error: '.$e->getMessage().' line '.$e->getLine());
            throw $e;
        }
    }

    public function rules(): array
    {
        return [
            '1' => ['required', 'in:'.implode(',', $this->getValidEmployeeIds())],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '1.required' => 'Mã nhân viên không được để trống!',
            '1.in' => 'Mã nhân viên không tồn tại!',
        ];
    }

    public function startRow(): int
    {
        return 7;
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            LogHelper::saveLog('Import-Celender-WC', $failure->errors()[0], $failure->row());
        }
    }
}
