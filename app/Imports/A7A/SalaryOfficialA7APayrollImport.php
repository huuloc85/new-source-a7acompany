<?php

namespace App\Imports\A7A;

use App\Helpers\LogHelper;
use App\Models\Employee;
use App\Models\SalaryOfficialA7A;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SalaryOfficialA7APayrollImport implements
    ToArray,
    HasReferencesToOtherSheets,
    WithHeadingRow,
    SkipsOnFailure,
    SkipsEmptyRows,
    WithValidation,
    WithStartRow
{
    public $salaryManagerId;
    public function __construct($salaryManagerId)
    {
        $this->salaryManagerId = $salaryManagerId;
    }

    public function sheet(): string
    {
        return 'Bang Thanh Toan Luong'; // Đặt tên sheet ở đây
    }

    /**
     * @param array $rows
     */
    public function array(array $rows)
    {
        try {
            foreach ($rows as $row) {
                if ($row[1] != null && $row[1] != '') {
                    $employee = Employee::where('code', $row[1])->first();
                    if ($employee != null && $this->salaryManagerId != null) {
                        $salaryManager = SalaryOfficialA7A::where('salaries_manager_id', $this->salaryManagerId)->where('employee_id', $employee->id)->first();
                        if ($salaryManager) {
                            //bảng lương
                            $salaryManager->salary_total = $row[4] ?? null;                      //tổng lương
                            $salaryManager->insurance_payroll = $row[5] ?? null;                 //trừ bảo hiểm
                            $salaryManager->advance_money_payroll = $row[6] ?? null;             //tạm ứng
                            $salaryManager->company_insurance_payroll = $row[7] ?? null;         //bảo hiểm công ty đóng
                            $salaryManager->KPI_Subtraction_payroll = $row[8] ?? null;           //trừ KPI
                            $salaryManager->previous_period_debt_payroll = $row[9] ?? null;      //Nợ kỳ trước
                            $salaryManager->actually_received_payroll = $row[10] ?? null;        //thực lãnh detail
                            $salaryManager->save();
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            LogHelper::saveLog('Import-Payroll-A7A', $e->getMessage(), $e->getLine());
            Log::error('errors payroll-a7a::: ' . $e->getMessage() . ' getLine' . $e->getLine());
        }
    }

    //validate


    /**
     * @param Failure[] $failures
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $key => $failure) {
            LogHelper::saveLog('Import-A7A-Bảng lương', $failure->errors()[0], $failure->row());
        }
    }

    public function startRow(): int
    {
        //5
        return 8;
    }
    public function rules(): array
    {
        $listCode = Employee::all()->pluck('code')->toArray();
        return [
            '1' => ['required', 'in:' . implode(',', $listCode)],
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
