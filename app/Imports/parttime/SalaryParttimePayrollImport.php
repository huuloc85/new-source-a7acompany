<?php

namespace App\Imports\parttime;

use App\Helpers\LogHelper;
use App\Models\Employee;
use App\Models\SalaryParttime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class SalaryParttimePayrollImport implements 
ToArray, 
HasReferencesToOtherSheets, 
WithHeadingRow,
WithValidation, 
SkipsOnFailure, 
SkipsEmptyRows
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
                        //bảng lương
                        SalaryParttime::create([
                            'salaries_manager_id' => $this->salaryManagerId,
                            'employee_id' => $employee->id,
                            'salary_total' => $row[4] ?? null,                      //tổng lương
                            'insurance' => $row['tru_bao_hiem_105'] ?? null,        //trừ bảo hiểm
                            'advance_money' => $row['tam_ung'] ?? null,             //tạm ứng
                            'company_insurance' => $row['bh_cty_dong_215'] ?? null, //bảo hiểm công ty đóng
                            'debt_last' => $row['no_ky_truoc'] ?? null,             //nợ kỳ trước
                            'actually_received' => $row[9] ?? null,                 //thực lãnh
                        ]);
                    }
                }

            }
        }catch (\Exception $e) {
            LogHelper::saveLog('Import-Payroll-Parttime', $e->getMessage(), $e->getLine());
            Log::error('errors payroll-parttime::: ' . $e->getMessage() . ' getLine' . $e->getLine());
        }
    }

    public function headingRow(): int
    {
        //5
        return 5;
    }

    //validate
    public function rules(): array
    {
        $listCode = Employee::all()->pluck('code')->toArray();
        return [
            '1' => ['required', 'in:'.implode(',', $listCode)],
            '4' => ['nullable','numeric'],
            'tru_bao_hiem_105' => ['nullable','numeric'],
            'tam_ung' => ['nullable','numeric'],
            'bh_cty_dong_215' => ['nullable','numeric'],
            'no_ky_truoc' => ['nullable','numeric'],
            '9' => ['nullable','numeric'],
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
            'tru_bao_hiem_105.numeric' => 'Trừ bảo hiểm không đúng định dạng!',
            'tam_ung.numeric' => 'Tạm ứng không đúng định dạng!',
            'bh_cty_dong_215.numeric' => 'Bảo hiểm công ty phải đóng không đúng định dạng!',
            'no_ky_truoc.numeric' => 'Nợ kỳ trước không đúng định dạng!',
            '9.numeric' => 'Thực lãnh không đúng định dạng!',
        ];
    }

    /**
     * @param Failure[] $failures
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $key => $failure) {
            LogHelper::saveLog('Import-Parttime-Bảng lương', $failure->errors()[0], $failure->row());
        }
    }
}
