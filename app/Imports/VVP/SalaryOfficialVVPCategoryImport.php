<?php

namespace App\Imports\VVP;

use App\Helpers\LogHelper;
use App\Models\Employee;
use App\Models\SalaryOfficialVVP;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Validators\Failure;

class SalaryOfficialVVPCategoryImport implements HasReferencesToOtherSheets, SkipsEmptyRows, SkipsOnFailure, ToArray, WithHeadingRow, WithStartRow
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
                    SalaryOfficialVVP::insert($chunk);
                }
            }
        } catch (\Exception $e) {
            LogHelper::saveLog('Import-Category-VVP', $e->getMessage(), $e->getLine());
            Log::error('errors cate::: '.$e->getMessage().' getLine'.$e->getLine());
            throw $e;
        }
    }

    // validate
    // public function rules(): array
    // {
    //     $listCode = Employee::all()->pluck('id')->toArray();
    //     return [
    //         'ma_nv' => ['required', 'in:' . implode(',', $listCode)],
    //         'luong_ngay_ap_dung_thang_dau' => ['nullable', 'numeric'],
    //         'luong_dem_ap_dung_thang_dau' => ['nullable', 'numeric'],
    //         'luong_cb_thu_viec_26_ngay' => ['nullable', 'numeric'],
    //         'luong_cb_thu_viec_1_gio' => ['nullable', 'numeric'],
    //         'luong_cb_thu_viec_tang_ca_1_gio' => ['nullable', 'numeric'],
    //         'phu_cap_hoc_viec' => ['nullable', 'numeric'],
    //         'luong_cb_chinh_thuc_26_ngay' => ['nullable', 'numeric'],
    //         'luong_cb_gio' => ['nullable', 'numeric'],
    //         'luong_tc_gio' => ['nullable', 'numeric'],
    //         'chuyen_can' => ['nullable', 'numeric'],
    //         'trach_nhiem' => ['nullable', 'numeric'],
    //         'phu_cap_tang_ca_ngay' => ['nullable', 'numeric'],
    //         'phu_cap_dem' => ['nullable', 'numeric'],
    //         'phu_cap_com_trua' => ['nullable', 'numeric'],
    //         'bhxh_cong_ty_dong' => ['nullable', 'numeric'],
    //         'bhxh_nguoi_lao_dong_dong' => ['nullable', 'numeric'],
    //     ];
    // }

    // public function customValidationMessages()
    // {
    //     return [
    //         'ma_nv.required' => 'Mã nhân viên không được để trống!',
    //         'ma_nv.in' => 'Mã nhân viên không tồn tại!',
    //         'luong_ngay_ap_dung_thang_dau.numeric' => 'Lương Ngày không đúng định dạng!',
    //         'luong_dem_ap_dung_thang_dau.numeric' => 'Lương Đêm không đúng định dạng!',
    //         'luong_cb_thu_viec_26_ngay.numeric' => 'Lương CB thử việc / 26 ngày không đúng định dạng!',
    //         'luong_cb_thu_viec_1_gio.numeric' => 'Lương CB thử việc / 1 giờ không đúng định dạng!',
    //         'luong_cb_thu_viec_tang_ca_1_gio.numeric' => 'Lương CB thử việc tăng ca / 1 giờ không đúng định dạng!',
    //         'phu_cap_hoc_viec.numeric' => 'Phụ cấp học việc không đúng định dạng!',
    //         'luong_cb_chinh_thuc_26_ngay.numeric' => 'Lương CB chính thức/ 26 ngày không đúng định dạng!',
    //         'luong_cb_gio.numeric' => 'Lương CB/ giờ không đúng định dạng!',
    //         'luong_tc_gio.numeric' => 'Lương tăng ca/giờ không đúng định dạng!',
    //         'chuyen_can.numeric' => 'Chuyên cần không đúng định dạng!',
    //         'trach_nhiem.numeric' => 'Trách nhiệm không đúng định dạng!',
    //         'phu_cap_tang_ca_ngay.numeric' => 'Phụ cấp tăng ca/ ngày không đúng định dạng!',
    //         'phu_cap_dem.numeric' => 'Phu cấp đêm không đúng định dạng!',
    //         'phu_cap_com_trua.numeric' => 'Phụ cấp cơm trưa không đúng định dạng!',
    //         'bhxh_cong_ty_dong.numeric' => 'BHXH công ty đóng không đúng định dạng!',
    //         'bhxh_nguoi_lao_dong_dong.numeric' => 'BHXH người lao động đóng không đúng định dạng!',
    //     ];
    // }

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
            LogHelper::saveLog('Import-VVP-Danh mục', $failure->errors()[0], $failure->row());
        }
    }
}
