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
use Maatwebsite\Excel\Concerns\WithStartRow;

use Maatwebsite\Excel\Validators\Failure;

class SalaryOfficialA7ACategoryImport implements
    ToArray,
    HasReferencesToOtherSheets,
    WithHeadingRow,
    SkipsOnFailure,
    SkipsEmptyRows,
    WithStartRow

{
    public $salaryManagerId;
    public function __construct($salaryManagerId)
    {
        $this->salaryManagerId = $salaryManagerId;
    }

    public function sheet(): string
    {
        return 'Danh muc'; // Đặt tên sheet ở đây
    }

    /**
     * @param array $rows
     */
    public function array(array $rows)
    {
        // dd($rows);
        try {
            foreach ($rows as $row) {
                if ($row[1] != null && $row[1] != '') {
                    $employee = Employee::where('code', $row[1])->first();
                    if ($employee != null && $this->salaryManagerId != null) {
                        SalaryOfficialA7A::create([
                            'salaries_manager_id' => $this->salaryManagerId,
                            'employee_id' => $employee->id,
                            'salary_day' => $row[7] ?? null,                                        // Lương Ngày
                            'salary_night' => $row[8] ?? null,                                  // Lương Đêm
                            'probationary_salary_basic_26days' => $row[9] ?? null,                // Lương CB thử việc / 26 ngày
                            'probationary_salary_basic_hours' => $row[10] ?? null,                   // Lương CB thử việc / 1 giờ
                            'probationary_salary_basic_extra_hours' => $row[11] ?? null,     // Lương CB thử việc tăng ca / 1 giờ
                            'allowance_apprentice' => $row[12] ?? null,                                     // phụ cấp học việc
                            'salary_basic' => $row[13] ?? null,                                  // Lương CB chính thức/ 26 ngày
                            'regular_salary_hour' => $row[14] ?? null,                                          // Lương CB/ giờ
                            'salary_overtime' => $row[15] ?? null,                                              // Lương tăng ca/giờ
                            'allowance_diligence' => $row[16] ?? null,                                            // Chuyên cần
                            'allowance_responsibility' => $row[17] ?? null,                                      // Trách nhiệm
                            'allowance_overtime' => $row[18] ?? null,                                   // Phụ cấp tăng ca/ ngày
                            'allowance_night' => $row[19] ?? null,                                               // Phụ cấp đêm
                            'allowance_rice' => $row[20] ?? null,                                           // Phụ cấp cơm trưa
                            'company_insurance' => $row[21] ?? null,                                       // BHXH công ty đóng
                            'insurance' => $row[22] ?? null,                                        // BHXH người lao động đóng
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            LogHelper::saveLog('Import-Category-A7A', $e->getMessage(), $e->getLine());
            Log::error('errors cate-a7a::: ' . $e->getMessage() . ' getLine' . $e->getLine());
        }
    }


    public function startRow(): int
    {
        //5
        return 8;
    }

    /**
     * @param Failure[] $failures
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $key => $failure) {
            LogHelper::saveLog('Import-A7A-Danh mục', $failure->errors()[0], $failure->row());
        }
    }

    //validate


    /**
     * @return array
     */
}
