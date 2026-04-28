<?php

namespace App\Imports\VVP;

use App\Helpers\LogHelper;
use App\Imports\Traits\OptimizesSalaryImport;
use App\Models\SalaryOfficialVVP;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Validators\Failure;

class SalaryOfficialVVPCategoryImport implements HasReferencesToOtherSheets, SkipsEmptyRows, SkipsOnFailure, ToArray, WithStartRow, WithChunkReading, WithEvents, WithCalculatedFormulas
{
    use OptimizesSalaryImport;

    private array $employeeMap = [];

    public function __construct(public $salaryManagerId) {}

    public function chunkSize(): int
    {
        return 500;
    }

    public function sheet(): string
    {
        return 'Danh muc';
    }

    public function array(array $rows)
    {
        try {
            if (empty($this->employeeMap)) {
                $this->employeeMap = $this->getPreloadedEmployees();
            }

            $insertData = [];
            $now = now();

            foreach ($rows as $row) {
                $employeeCode = $row[1] ?? null;
                if (empty($employeeCode) || $this->salaryManagerId == null) {
                    continue;
                }

                $employee = $this->getEmployeeFast((string) $employeeCode, $this->employeeMap);
                if ($employee === null) {
                    continue;
                }

                $insertData[] = [
                    'salaries_manager_id' => $this->salaryManagerId,
                    'employee_id' => $employee->id,
                    'salary_day' => $this->numericValue($row[7] ?? null),
                    'salary_night' => $this->numericValue($row[8] ?? null),
                    'probationary_salary_basic_26days' => $this->numericValue($row[9] ?? null),
                    'probationary_salary_basic_hours' => $this->numericValue($row[10] ?? null),
                    'probationary_salary_basic_extra_hours' => $this->numericValue($row[11] ?? null),
                    'allowance_apprentice' => $this->numericValue($row[12] ?? null),
                    'salary_basic' => $this->numericValue($row[13] ?? null),
                    'regular_salary_hour' => $this->numericValue($row[14] ?? null),
                    'salary_overtime' => $this->numericValue($row[15] ?? null),
                    'allowance_diligence' => $this->numericValue($row[16] ?? null),
                    'allowance_responsibility' => $this->numericValue($row[17] ?? null),
                    'allowance_overtime' => $this->numericValue($row[18] ?? null),
                    'allowance_night' => $this->numericValue($row[19] ?? null),
                    'allowance_rice' => $this->numericValue($row[20] ?? null),
                    'company_insurance' => $this->numericValue($row[21] ?? null),
                    'insurance' => $this->numericValue($row[22] ?? null),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            $this->batchUpdateOrInsert(SalaryOfficialVVP::class, $insertData, 500);
        } catch (\Exception $e) {
            LogHelper::saveLog('Import-Category-VVP', $e->getMessage(), $e->getLine());
            Log::error('Import Category VVP error: '.$e->getMessage().' line '.$e->getLine());
            throw $e;
        }
    }

    public function startRow(): int
    {
        return 8;
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            LogHelper::saveLog('Import-VVP-Danh mục', $failure->errors()[0], $failure->row());
        }
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function() {
                $this->clearEmployeeCache();
                $this->clearValidationCache();
            },
        ];
    }
}
