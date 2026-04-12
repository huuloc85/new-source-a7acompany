<?php

namespace App\Imports\A7A;

use App\Helpers\LogHelper;
use App\Models\Employee;
use App\Models\SalaryOfficialA7A;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Validators\Failure;

class SalaryOfficialA7ACategoryImport implements HasReferencesToOtherSheets, SkipsEmptyRows, SkipsOnFailure, ToArray, WithHeadingRow, WithStartRow
, WithCalculatedFormulas{
    public $salaryManagerId;

    public function __construct($salaryManagerId)
    {
        $this->salaryManagerId = $salaryManagerId;
    }

    public function sheet(): string
    {
        return 'Danh muc'; // Đặt tên sheet ở đây
    }

    public function array(array $rows)
    {
        // dd($rows);
        try {
            $insertData = [];
            $employeeIds = [];
            foreach ($rows as $row) {
                if (!empty($row[1])) {
                    $employeeIds[] = $row[1];
                }
            }
            $employeeIdsClean = array_map(function($id) { return ltrim($id, '0'); }, $employeeIds);
            $dbEmployees = Employee::whereIn('id', $employeeIds)
                ->orWhereIn(\Illuminate\Support\Facades\DB::raw("TRIM(LEADING '0' FROM id)"), $employeeIdsClean)
                ->get();
            $employees = [];
            foreach ($dbEmployees as $emp) {
                $employees[$emp->id] = $emp->id;
                $employees[ltrim($emp->id, '0')] = $emp->id;
            }

            foreach ($rows as $row) {
                if (!empty($row[1])) {
                    $stripped = ltrim($row[1], '0');
                    if (isset($employees[$stripped]) && $this->salaryManagerId != null) {
                        $insertData[] = [
                            'salaries_manager_id' => $this->salaryManagerId,
                            'employee_id' => $employees[$stripped],
                            'salary_day' => (is_numeric($row[7] ?? null) ? (float)$row[7] : null),                                        // Lương Ngày
                            'salary_night' => (is_numeric($row[8] ?? null) ? (float)$row[8] : null),                                  // Lương Đêm
                            'probationary_salary_basic_26days' => (is_numeric($row[9] ?? null) ? (float)$row[9] : null),                // Lương CB thử việc / 26 ngày
                            'probationary_salary_basic_hours' => (is_numeric($row[10] ?? null) ? (float)$row[10] : null),                   // Lương CB thử việc / 1 giờ
                            'probationary_salary_basic_extra_hours' => (is_numeric($row[11] ?? null) ? (float)$row[11] : null),     // Lương CB thử việc tăng ca / 1 giờ
                            'allowance_apprentice' => (is_numeric($row[12] ?? null) ? (float)$row[12] : null),                                     // phụ cấp học việc
                            'salary_basic' => (is_numeric($row[13] ?? null) ? (float)$row[13] : null),                                  // Lương CB chính thức/ 26 ngày
                            'regular_salary_hour' => (is_numeric($row[14] ?? null) ? (float)$row[14] : null),                                          // Lương CB/ giờ
                            'salary_overtime' => (is_numeric($row[15] ?? null) ? (float)$row[15] : null),                                              // Lương tăng ca/giờ
                            'allowance_diligence' => (is_numeric($row[16] ?? null) ? (float)$row[16] : null),                                            // Chuyên cần
                            'allowance_responsibility' => (is_numeric($row[17] ?? null) ? (float)$row[17] : null),                                      // Trách nhiệm
                            'allowance_overtime' => (is_numeric($row[18] ?? null) ? (float)$row[18] : null),                                   // Phụ cấp tăng ca/ ngày
                            'allowance_night' => (is_numeric($row[19] ?? null) ? (float)$row[19] : null),                                               // Phụ cấp đêm
                            'allowance_rice' => (is_numeric($row[20] ?? null) ? (float)$row[20] : null),                                           // Phụ cấp cơm trưa
                            'company_insurance' => (is_numeric($row[21] ?? null) ? (float)$row[21] : null),                                       // BHXH công ty đóng
                            'insurance' => (is_numeric($row[22] ?? null) ? (float)$row[22] : null),                                        // BHXH người lao động đóng
                            'created_at' => \Carbon\Carbon::now(),
                            'updated_at' => \Carbon\Carbon::now(),
                        ];
                    }
                }
            }

            if (!empty($insertData)) {
                foreach (array_chunk($insertData, 500) as $chunk) {
                    SalaryOfficialA7A::insert($chunk);
                }
            }
        } catch (\Exception $e) {
            LogHelper::saveLog('Import-Category-A7A', $e->getMessage(), $e->getLine());
            Log::error('errors cate-a7a::: '.$e->getMessage().' getLine'.$e->getLine());
        }
    }

    public function startRow(): int
    {
        // 5
        return 8;
    }

    /**
     * @param  Failure[]  $failures
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $key => $failure) {
            LogHelper::saveLog('Import-A7A-Danh mục', $failure->errors()[0], $failure->row());
        }
    }

    // validate

    /**
     * @return array
     */
}
