<?php

namespace App\Imports\A7A;

use App\Helpers\LogHelper;
use App\Imports\Traits\OptimizesSalaryImport;
use App\Models\SalaryOfficialA7A;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Validators\Failure;

class SalaryOfficialA7APayrollImport implements HasReferencesToOtherSheets, SkipsEmptyRows, SkipsOnFailure, ToArray, WithChunkReading, WithEvents, WithStartRow, WithValidation, WithCalculatedFormulas
{
    use OptimizesSalaryImport;

    public $salaryManagerId;

    private $employeeMap = [];
    private $salaryMap = [];

    public function __construct($salaryManagerId)
    {
        $this->salaryManagerId = $salaryManagerId;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function sheet(): string
    {
        return 'Bang Thanh Toan Luong'; // Đặt tên sheet ở đây
    }

    public function array(array $rows)
    {
        try {
            // Preload data once before processing
            if (empty($this->employeeMap)) {
                $this->employeeMap = $this->getPreloadedEmployees();
            }
            if (empty($this->salaryMap) && $this->salaryManagerId) {
                $this->salaryMap = $this->getPreloadedSalaryRecords(SalaryOfficialA7A::class, $this->salaryManagerId);
            }

            $updates = [];
            $now = now();
            $updateColumns = [
                'salary_total',
                'insurance_payroll',
                'advance_money_payroll',
                'company_insurance_payroll',
                'KPI_Subtraction_payroll',
                'previous_period_debt_payroll',
                'actually_received_payroll',
                'updated_at',
            ];

            foreach ($rows as $row) {
                if ($row[1] != null && $row[1] != '') {
                    $employee = $this->getEmployeeFast($row[1], $this->employeeMap);
                    if ($employee != null && $this->salaryManagerId != null) {
                        $salaryManager = $this->getSalaryRecordFast($this->salaryMap, $employee->id);
                        if ($salaryManager) {
                            $updates[] = [
                                'id' => $salaryManager->id,
                                'salaries_manager_id' => $salaryManager->salaries_manager_id,
                                'employee_id' => $salaryManager->employee_id,
                                'salary_total' => $this->numericValue($row[4] ?? null),
                                'insurance_payroll' => $this->numericValue($row[5] ?? null),
                                'advance_money_payroll' => $this->numericValue($row[6] ?? null),
                                'company_insurance_payroll' => $this->numericValue($row[7] ?? null),
                                'KPI_Subtraction_payroll' => $this->numericValue($row[8] ?? null),
                                'previous_period_debt_payroll' => $this->numericValue($row[9] ?? null),
                                'actually_received_payroll' => $this->numericValue($row[10] ?? null),
                                'created_at' => $salaryManager->created_at,
                                'updated_at' => $now,
                            ];
                        }
                    }
                }
            }

            $this->batchUpsertById(SalaryOfficialA7A::class, $updates, $updateColumns);
        } catch (\Exception $e) {
            LogHelper::saveLog('Import-Payroll-A7A', $e->getMessage(), $e->getLine());
            Log::error('errors payroll-a7a::: '.$e->getMessage().' getLine'.$e->getLine());
            throw $e;
        }
    }

    public function isEmptyWhen(array $row): bool
    {
        if (empty($row[1])) {
            return true;
        }

        if (empty($this->employeeMap)) {
            $this->employeeMap = $this->getPreloadedEmployees();
        }

        return $this->getEmployeeFast($row[1], $this->employeeMap) === null;
    }

    // validate

    /**
     * @param  Failure[]  $failures
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $key => $failure) {
            LogHelper::saveLog('Import-A7A-Bảng lương', $failure->errors()[0], $failure->row());
        }
    }

    /**
     * Register events for the import process
     */
    public function registerEvents(): array
    {
        return [
            AfterImport::class => function() {
                $this->clearEmployeeCache();
                if ($this->salaryManagerId) {
                    $this->clearSalaryCache(SalaryOfficialA7A::class, $this->salaryManagerId);
                }
                $this->clearValidationCache();
            },
        ];
    }

    public function startRow(): int
    {
        // 5
        return 8;
    }

    public function rules(): array
    {
        $listCode = $this->getValidEmployeeIds();

        return [
            '1' => ['required', 'in:'.implode(',', $listCode)],
            '4' => ['nullable', 'numeric'],
            '5' => ['nullable', 'numeric'],
            '6' => ['nullable', 'numeric'],
            '7' => ['nullable', 'numeric'],
            '8' => ['nullable', 'numeric'],
            '9' => ['nullable', 'numeric'],
            '10' => ['nullable', 'numeric'],
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            '1.required' => 'Mã nhân viên không được để trống!',
            '1.in' => 'Mã nhân viên không tồn tại!',
            '4.numeric' => 'Tổng lương không đúng định dạng!',
            '5.numeric' => 'Trừ bảo hiểm không đúng định dạng!',
            '6.numeric' => 'Tạm ứng không đúng định dạng!',
            '7.numeric' => 'Bảo hiểm công ty phải đóng không đúng định dạng!',
            '8.numeric' => 'KPI không đúng định dạng!',
            '9.numeric' => 'Nợ kỳ trước không đúng định dạng!',
            '10.numeric' => 'Thực lãnh không đúng định dạng!',
        ];
    }
}
