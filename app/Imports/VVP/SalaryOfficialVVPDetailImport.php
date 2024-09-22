<?php

namespace App\Imports\VVP;

use App\Helpers\LogHelper;
use App\Models\Employee;
use App\Models\SalaryOfficialVVP;
use Closure;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class SalaryOfficialVVPDetailImport implements
    ToArray,
    HasReferencesToOtherSheets,
    SkipsOnFailure,
    SkipsEmptyRows,
    WithValidation,
    WithStartRow
{
    public $roleIgnore;
    public $salaryManagerId;
    public function __construct($salaryManagerId)
    {
        $this->salaryManagerId = $salaryManagerId;
        $this->roleIgnore = [6, 9, 12, 15, 18, 21, 24, 27, 30, 33, 36, 39, 42, 46, 50, 52, 54, 56, 58, 60, 62, 64, 67, 69, 72, 75, 78, 81, 84, 86, 88];
    }

    public function sheet(): string
    {
        return 'Bảng tính toán'; // Đặt tên sheet ở đây
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
                        $salaryManager = SalaryOfficialVVP::where('salaries_manager_id', $this->salaryManagerId)->where('employee_id', $employee->id)->first();
                        if ($salaryManager) {
                            //detail
                            $salaryManager->number_of_work_days_trial = $row[4] ?? null;                            // Số công ngày (thử việc)
                            $salaryManager->day_shift_salary_trial = $row[5] ?? null;                             // Lương ca ngày (thử việc)
                            $salaryManager->day_shift_salary_trial_notice = $row[6] ?? null;                      // Lương ca ngày (thử việc) Ghi Chú
                            $salaryManager->number_of_work_nights_trial = $row[7] ?? null;                        // Số công đêm (thử việc)
                            $salaryManager->night_shift_salary_trial = $row[8] ?? null;                            // Lương ca đêm (thử việc)
                            $salaryManager->night_shift_salary_trial_notice = $row[9] ?? null;                     // Lương ca đêm (thử việc) Ghi Chú
                            $salaryManager->overtime_hours_trial = $row[10] ?? null;                               // Số giờ tăng ca ( thử việc)
                            $salaryManager->overtime_salary_trial = $row[11] ?? null;                              // Lương tăng ca (thử việc)
                            $salaryManager->overtime_salary_trial_notice = $row[12] ?? null;                       // Lương tăng ca (thử vifệc) Ghi Chú
                            $salaryManager->number_of_work = $row[13] ?? null;                                     // Số Công
                            $salaryManager->allowance_apprentice_detail = $row[14] ?? null;                        // phụ cấp học việc detail
                            $salaryManager->allowance_apprentice_detail_notice = $row[15] ?? null;                 // phụ cấp học việc detail Ghi Chú
                            $salaryManager->core_hours = $row[16] ?? null;                                         // số giờ chính detail
                            $salaryManager->official_salary = $row[17] ?? null;                                    // lương chính thức
                            $salaryManager->official_salary_notice = $row[18] ?? null;                              // lương chính thức Ghi Chú
                            $salaryManager->number_of_hours_worked = $row[19] ?? null;                              // số công làm
                            $salaryManager->allowance_diligence_detail = $row[20] ?? null;                          // chuyên cần detail
                            $salaryManager->allowance_diligence_detail_notice = $row[21] ?? null;                   // chuyên cần detail Ghi Chú
                            $salaryManager->number_of_jobs = $row[22] ?? null;                                      // Số công làm
                            $salaryManager->allowance_responsibility_detail = $row[23] ?? null;                     // trách nhiệm detail
                            $salaryManager->allowance_responsibility_detail_notice = $row[24] ?? null;              // trách nhiệm detail Ghi Chú
                            $salaryManager->overtime_hours_detail = $row[25] ?? null;                               // số giờ tăng ca
                            $salaryManager->overtime_salary = $row[26] ?? null;                                     // lương tăng ca
                            $salaryManager->overtime_salary_notice = $row[27] ?? null;                              // lương tăng ca Ghi Chú
                            $salaryManager->number_of_work_days = $row[28] ?? null;                                 // Số công ngày
                            $salaryManager->allowance_rice_detail = $row[29] ?? null;                               // phụ cấp cơm ca ngày
                            $salaryManager->allowance_rice_detail_notice = $row[30] ?? null;                        // phụ cấp cơm ca ngày Ghi Chú
                            $salaryManager->number_of_work_nights = $row[31] ?? null;                               // Số công đêm
                            $salaryManager->allowance_shift_night = $row[32] ?? null;                               // phụ cấp ca đêm
                            $salaryManager->allowance_shift_night_notice = $row[33] ?? null;                        // phụ cấp ca đêm Ghi Chú
                            $salaryManager->overtime_day_count_detail = $row[34] ?? null;                            // số ngày tăng ca
                            $salaryManager->allowance_overtime_detail = $row[35] ?? null;                            // phụ cấp tăng ca
                            $salaryManager->allowance_overtime_detail_notice = $row[36] ?? null;                     // phụ cấp tăng ca Ghi Chú
                            $salaryManager->holidays_count_detail = $row[37] ?? null;                                // số ngày lễ tết
                            $salaryManager->holidays_money = $row[38] ?? null;                                       // tiền lễ tết
                            $salaryManager->holidays_money_notice = $row[39] ?? null;                                // tiền lễ tết Ghi Chú
                            $salaryManager->paid_holidays_count_detail = $row[40] ?? null;                           // số ngày phép năm
                            $salaryManager->paid_holidays_money = $row[41] ?? null;                                  // số tiền phép năm
                            $salaryManager->paid_holidays_money_notice = $row[42] ?? null;                      // Số tiền phép năm Ghi Chú
                            $salaryManager->business_travel_hours = $row[43] ?? null;                                // Số giờ đi công tác
                            $salaryManager->business_travel_unit_price_hour = $row[44] ?? null;                      // Đơn giá đi công tác/ giờ
                            $salaryManager->gcn_business_travel_salary = $row[45] ?? null;                           // Lương đi công tác GCN
                            $salaryManager->gcn_business_travel_salary_notice = $row[46] ?? null;                    // Lương đi công tác GCN Ghi Chú
                            $salaryManager->number_of_business_trips = $row[47] ?? null;                             // Số lần đi công tác
                            $salaryManager->business_fuel_unit_price_day = $row[48] ?? null;                         // Đơn giá xăng công tác/ ngày
                            $salaryManager->allowance_gcn_business_fuel = $row[49] ?? null;                          // Phụ cấp xăng đi GCN
                            $salaryManager->allowance_gcn_business_fuel_notice = $row[50] ?? null;                   // Phụ cấp xăng đi GCN Ghi Chú
                            $salaryManager->money_referral_people = $row[51] ?? null;                                 // Tiền giới thiệu người
                            $salaryManager->money_referral_people_notice = $row[52] ?? null;                          // Tiền giới thiệu người Ghi Chú
                            $salaryManager->allowance_diffrent = $row[53] ?? null;                                    // phụ cấp khác
                            $salaryManager->allowance_diffrent_notice = $row[54] ?? null;                             // phụ cấp khác Ghi Chú
                            $salaryManager->bonuses_for_attendance = $row[55] ?? null;                                // Tiền thưởng đạt chuyên cần
                            $salaryManager->bonuses_for_attendance_notice = $row[56] ?? null;                        // Tiền thưởng đạt chuyên cần Ghi Chú
                            $salaryManager->sickness = $row[57] ?? null;                                              // Ốm đau
                            $salaryManager->sickness_notice = $row[58] ?? null;                                       // Ốm đau Ghi Chú
                            $salaryManager->funeral = $row[59] ?? null;                                               // Ma chay
                            $salaryManager->funeral_notice = $row[60] ?? null;                                        // Ma chay Ghi Chú
                            $salaryManager->birthday_money = $row[61] ?? null;                                         // Tiền sinh nhật
                            $salaryManager->birthday_money_notice = $row[62] ?? null;                                  // Tiền sinh nhật Ghi Chú
                            $salaryManager->previous_period_debt = $row[63] ?? null;                                   // Tiền lương tháng trước bị thiếu
                            $salaryManager->previous_period_debt_notice = $row[64] ?? null;                            // Tiền lương tháng trước bị thiếu Ghi Chú
                            $salaryManager->total_income = $row[65] ?? null;                                           // Tổng thu nhập
                            $salaryManager->insurance_detail = $row[66] ?? null;                                       // Khấu trừ BHXH 10.5%
                            $salaryManager->insurance_detail_notice = $row[67] ?? null;                                // Khấu trừ BHXH 10.5% Ghi Chú
                            $salaryManager->advance_money = $row[68] ?? null;                                          // tạm ứng
                            $salaryManager->advance_money_notice = $row[69] ?? null;                                   // tạm ứng Ghi Chú
                            $salaryManager->number_of_violations = $row[70] ?? null;                                    // Số lần vi phạm
                            $salaryManager->subtract_of_violations = $row[71] ?? null;                                 // Trừ vi phạm
                            $salaryManager->subtract_of_violations_notice = $row[72] ?? null;                          // Trừ vi phạm Ghi Chú
                            $salaryManager->daysleave_allowed = $row[73] ?? null;                                       // số ngày nghỉ có phép
                            $salaryManager->subtract_daysleave_allowed = $row[74] ?? null;                              // Trừ tiền nghỉ có phép
                            $salaryManager->subtract_daysleave_allowed_notice = $row[75] ?? null;                       // Trừ tiền nghỉ có phép Ghi Chú
                            $salaryManager->daysleave_notallowed = $row[76] ?? null;                                    // số ngày nghĩ không phép
                            $salaryManager->subtract_daysleave_notallowed = $row[77] ?? null;                           // Trừ tiền nghỉ không phép
                            $salaryManager->subtract_daysleave_notallowed_notice = $row[78] ?? null;                    // Trừ tiền nghỉ không phép Ghi Chú
                            $salaryManager->error_serious = $row[79] ?? null;                                          // số lỗi nặng
                            $salaryManager->subtract_error_serious = $row[80] ?? null;                                  // Trừ tiền số lỗi nặng
                            $salaryManager->subtract_error_serious_notice = $row[81] ?? null;                           // Trừ tiền số lỗi nặng Ghi Chú
                            $salaryManager->error_minor = $row[82] ?? null;                                             // số lỗi nhẹ
                            $salaryManager->subtract_error_minor = $row[83] ?? null;                                     // Trừ tiền số lỗi nhẹ
                            $salaryManager->subtract_error_minor_notice = $row[84] ?? null;                              // Trừ tiền số lỗi nhẹ Ghi Chú
                            $salaryManager->kpi_subtraction = $row[85] ?? null;                                           // trừ KPI
                            $salaryManager->kpi_subtraction_notice = $row[86] ?? null;                                    // trừ KPI Ghi Chú
                            $salaryManager->actually_received = $row[87] ?? null;                                          // thực lãnh
                            $salaryManager->forms_of_payment = $row[88] ?? null;                                           // hình thức thanh toán
                            $salaryManager->company_insurance_detail = $row[89] ?? null;                                   // BHXH (21.5%) công ty đóng cho NLĐ

                            $salaryManager->save();
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            LogHelper::saveLog('Import-Detail-VVP', $e->getMessage(), $e->getLine());
            Log::error('errors detail:: ' . $e->getMessage() . ' getLine' . $e->getLine());
        }
    }

    public function startRow(): int
    {
        return 8; // Start importing from row 8
    }

    //validate
    public function rules(): array
    {
        $rules = [];
        $listCode = Employee::all()->pluck('code')->toArray();
        $rules['1'] = ['required', 'in:' . implode(',', $listCode)];
        for ($i = 4; $i <= 89; $i++) {
            if (!in_array($i, $this->roleIgnore)) {
                $rules[$i] = ['nullable', 'numeric'];
            } else {
                $rules[$i] = ['nullable'];
            }
        }
        return $rules;
    }

    /**
     *
     */
    public function customValidationMessages()
    {
        $messages = [];
        $validations[1 . 'required'] = 'Mã nhân viên không được để trống!';
        $validations[1 . 'in'] = 'Mã nhân viên không tồn tại!';
        $role = [
            'Số công ngày (thử việc)', 'Lương ca ngày (thử việc)', 'Lương ca ngày (thử việc) Ghi Chú', 'Số công đêm (thử việc)',
            'Lương ca đêm (thử việc)', 'Lương ca đêm (thử việc) Ghi Chú', 'Số giờ tăng ca ( thử việc)', 'Lương tăng ca (thử việc)',
            'Lương tăng ca (thử vifệc) Ghi Chú', 'Số Công', 'phụ cấp học việc detail', 'phụ cấp học việc detail Ghi Chú',
            'số giờ chính detail', 'lương chính thức', 'lương chính thức Ghi Chú', 'số công làm',
            'chuyên cần detail', 'chuyên cần detail Ghi Chú', 'Số công làm', 'trách nhiệm detail',
            'trách nhiệm detail Ghi Chú', 'số giờ tăng ca', 'lương tăng ca', 'lương tăng ca Ghi Chú',
            'Số công ngày', 'phụ cấp cơm ca ngày', 'phụ cấp cơm ca ngày Ghi Chú', 'Số công đêm',
            'phụ cấp ca đêm', 'phụ cấp ca đêm Ghi Chú', 'số ngày tăng ca', 'phụ cấp tăng ca',
            'phụ cấp tăng ca Ghi Chú', 'số ngày lễ tết', 'tiền lễ tết', 'tiền lễ tết Ghi Chú',
            'số ngày phép năm', 'số tiền phép năm', 'Số tiền phép năm Ghi Chú', 'Số giờ đi công tác',
            'Đơn giá đi công tác/ giờ', 'Lương đi công tác GCN', 'Lương đi công tác GCN Ghi Chú', 'Số lần đi công tác',
            'Đơn giá xăng công tác/ ngày', 'Phụ cấp xăng đi GCN', 'Phụ cấp xăng đi GCN Ghi Chú', 'Tiền giới thiệu người',
            'Tiền giới thiệu người Ghi Chú', 'phụ cấp khác', 'phụ cấp khác Ghi Chú', 'Tiền thưởng đạt chuyên cần',
            'Tiền thưởng đạt chuyên cần Ghi Chú', 'Ốm đau', 'Ốm đau Ghi Chú', 'Ma chay',
            'Ma chay Ghi Chú', 'Tiền sinh nhật', 'Tiền sinh nhật Ghi Chú', 'Tiền lương tháng trước bị thiếu',
            'Tiền lương tháng trước bị thiếu Ghi Chú', 'Tổng thu nhập', 'Khấu trừ BHXH 10.5%', 'Khấu trừ BHXH 10.5% Ghi Chú',
            'tạm ứng', 'tạm ứng Ghi Chú', 'Số lần vi phạm', 'Trừ vi phạm',
            'Trừ vi phạm Ghi Chú', 'số ngày nghỉ có phép', 'Trừ tiền nghỉ có phép', 'Trừ tiền nghỉ có phép Ghi Chú',
            'số ngày nghĩ không phép', 'Trừ tiền nghỉ không phép', 'Trừ tiền nghỉ không phép Ghi Chú', 'số lỗi nặng',
            'Trừ tiền số lỗi nặng', 'Trừ tiền số lỗi nặng Ghi Chú', 'số lỗi nhẹ', 'Trừ tiền số lỗi nhẹ',
            'Trừ tiền số lỗi nhẹ Ghi Chú', 'trừ KPI', 'trừ KPI Ghi Chú', 'thực lãnh',
            'hình thức thanh toán', 'BHXH (21.5%) công ty đóng cho NLĐ'
        ];

        for ($i = 4; $i <= 89; $i++) {
            if (!in_array($i, $this->roleIgnore)) {
                $messages[$i . '.numeric'] = 'Trường ' . $role[$i - 4] . ' không đúng định dạng!';
            }
        }
        return $messages;
    }

    /**
     * @param Failure[] $failures
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $key => $failure) {
            LogHelper::saveLog('Import-VVP-Chi tiết', $failure->errors()[0], $failure->row());
        }
    }
}
