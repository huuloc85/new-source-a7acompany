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
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Validators\Failure;

class SalaryOfficialVVPDetailImport implements HasReferencesToOtherSheets, SkipsEmptyRows, SkipsOnFailure, ToArray, WithCalculatedFormulas, WithChunkReading, WithEvents, WithStartRow, WithValidation
{
    use OptimizesSalaryImport;

    public $roleIgnore;

    public $salaryManagerId;

    private $employeeMap = [];

    private $salaryMap = [];

    public function __construct($salaryManagerId)
    {
        $this->salaryManagerId = $salaryManagerId;
        $this->roleIgnore = [6, 9, 12, 15, 18, 21, 24, 27, 30, 33, 36, 39, 42, 46, 50, 52, 54, 56, 58, 60, 62, 64, 67, 69, 72, 75, 78, 81, 84, 87];
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function sheet(): string
    {
        return 'Bảng tính toán'; // Đặt tên sheet ở đây
    }

    public function array(array $rows)
    {
        try {
            // Preload data once before processing
            if (empty($this->employeeMap)) {
                $this->employeeMap = $this->getPreloadedEmployees();
            }
            if (empty($this->salaryMap) && $this->salaryManagerId) {
                $this->salaryMap = $this->getPreloadedSalaryRecords(SalaryOfficialVVP::class, $this->salaryManagerId);
            }

            $updates = [];
            $now = now();

            foreach ($rows as $row) {
                if ($row[1] != null && $row[1] != '') {
                    $employee = $this->getEmployeeFast($row[1], $this->employeeMap);
                    if ($employee != null && $this->salaryManagerId != null) {
                        $salaryManager = $this->getSalaryRecordFast($this->salaryMap, $employee->id);
                        if ($salaryManager) {
                            // detail
                            $salaryManager->number_of_work_days_trial = (is_numeric($row[4] ?? null) ? (float) $row[4] : null);                            // Số công ngày (thử việc)
                            $salaryManager->day_shift_salary_trial = (is_numeric($row[5] ?? null) ? (float) $row[5] : null);                             // Lương ca ngày (thử việc)
                            $salaryManager->day_shift_salary_trial_notice = (is_numeric($row[6] ?? null) ? (float) $row[6] : null);                      // Lương ca ngày (thử việc) Ghi Chú
                            $salaryManager->number_of_work_nights_trial = (is_numeric($row[7] ?? null) ? (float) $row[7] : null);                        // Số công đêm (thử việc)
                            $salaryManager->night_shift_salary_trial = (is_numeric($row[8] ?? null) ? (float) $row[8] : null);                            // Lương ca đêm (thử việc)
                            $salaryManager->night_shift_salary_trial_notice = (is_numeric($row[9] ?? null) ? (float) $row[9] : null);                     // Lương ca đêm (thử việc) Ghi Chú
                            $salaryManager->overtime_hours_trial = (is_numeric($row[10] ?? null) ? (float) $row[10] : null);                               // Số giờ tăng ca ( thử việc)
                            $salaryManager->overtime_salary_trial = (is_numeric($row[11] ?? null) ? (float) $row[11] : null);                              // Lương tăng ca (thử việc)
                            $salaryManager->overtime_salary_trial_notice = (is_numeric($row[12] ?? null) ? (float) $row[12] : null);                       // Lương tăng ca (thử vifệc) Ghi Chú
                            $salaryManager->number_of_work = (is_numeric($row[13] ?? null) ? (float) $row[13] : null);                                     // Số Công
                            $salaryManager->allowance_apprentice_detail = (is_numeric($row[14] ?? null) ? (float) $row[14] : null);                        // phụ cấp học việc detail
                            $salaryManager->allowance_apprentice_detail_notice = (is_numeric($row[15] ?? null) ? (float) $row[15] : null);                 // phụ cấp học việc detail Ghi Chú
                            $salaryManager->core_hours = (is_numeric($row[16] ?? null) ? (float) $row[16] : null);                                         // số giờ chính detail
                            $salaryManager->official_salary = (is_numeric($row[17] ?? null) ? (float) $row[17] : null);                                    // lương chính thức
                            $salaryManager->official_salary_notice = (is_numeric($row[18] ?? null) ? (float) $row[18] : null);                              // lương chính thức Ghi Chú
                            $salaryManager->number_of_hours_worked = (is_numeric($row[19] ?? null) ? (float) $row[19] : null);                              // số công làm
                            $salaryManager->allowance_diligence_detail = (is_numeric($row[20] ?? null) ? (float) $row[20] : null);                          // chuyên cần detail
                            $salaryManager->allowance_diligence_detail_notice = (is_numeric($row[21] ?? null) ? (float) $row[21] : null);                   // chuyên cần detail Ghi Chú
                            $salaryManager->number_of_jobs = (is_numeric($row[22] ?? null) ? (float) $row[22] : null);                                      // Số công làm
                            $salaryManager->allowance_responsibility_detail = (is_numeric($row[23] ?? null) ? (float) $row[23] : null);                     // trách nhiệm detail
                            $salaryManager->allowance_responsibility_detail_notice = (is_numeric($row[24] ?? null) ? (float) $row[24] : null);              // trách nhiệm detail Ghi Chú
                            $salaryManager->overtime_hours_detail = (is_numeric($row[25] ?? null) ? (float) $row[25] : null);                               // số giờ tăng ca
                            $salaryManager->overtime_salary = (is_numeric($row[26] ?? null) ? (float) $row[26] : null);                                     // lương tăng ca
                            $salaryManager->overtime_salary_notice = (is_numeric($row[27] ?? null) ? (float) $row[27] : null);                              // lương tăng ca Ghi Chú
                            $salaryManager->number_of_work_days = (is_numeric($row[28] ?? null) ? (float) $row[28] : null);                                 // Số công ngày
                            $salaryManager->allowance_rice_detail = (is_numeric($row[29] ?? null) ? (float) $row[29] : null);                               // phụ cấp cơm ca ngày
                            $salaryManager->allowance_rice_detail_notice = (is_numeric($row[30] ?? null) ? (float) $row[30] : null);                        // phụ cấp cơm ca ngày Ghi Chú
                            $salaryManager->number_of_work_nights = (is_numeric($row[31] ?? null) ? (float) $row[31] : null);                               // Số công đêm
                            $salaryManager->allowance_shift_night = (is_numeric($row[32] ?? null) ? (float) $row[32] : null);                               // phụ cấp ca đêm
                            $salaryManager->allowance_shift_night_notice = (is_numeric($row[33] ?? null) ? (float) $row[33] : null);                        // phụ cấp ca đêm Ghi Chú
                            $salaryManager->overtime_day_count_detail = (is_numeric($row[34] ?? null) ? (float) $row[34] : null);                            // số ngày tăng ca
                            $salaryManager->allowance_overtime_detail = (is_numeric($row[35] ?? null) ? (float) $row[35] : null);                            // phụ cấp tăng ca
                            $salaryManager->allowance_overtime_detail_notice = (is_numeric($row[36] ?? null) ? (float) $row[36] : null);                     // phụ cấp tăng ca Ghi Chú
                            $salaryManager->holidays_count_detail = (is_numeric($row[37] ?? null) ? (float) $row[37] : null);                                // số ngày lễ tết
                            $salaryManager->holidays_money = (is_numeric($row[38] ?? null) ? (float) $row[38] : null);                                       // tiền lễ tết
                            $salaryManager->holidays_money_notice = (is_numeric($row[39] ?? null) ? (float) $row[39] : null);                                // tiền lễ tết Ghi Chú
                            $salaryManager->paid_holidays_count_detail = (is_numeric($row[40] ?? null) ? (float) $row[40] : null);                           // số ngày phép năm
                            $salaryManager->paid_holidays_money = (is_numeric($row[41] ?? null) ? (float) $row[41] : null);                                  // số tiền phép năm
                            $salaryManager->paid_holidays_money_notice = (is_numeric($row[42] ?? null) ? (float) $row[42] : null);                      // Số tiền phép năm Ghi Chú
                            $salaryManager->business_travel_hours = (is_numeric($row[43] ?? null) ? (float) $row[43] : null);                                // Số giờ đi công tác
                            $salaryManager->business_travel_unit_price_hour = (is_numeric($row[44] ?? null) ? (float) $row[44] : null);                      // Đơn giá đi công tác/ giờ
                            $salaryManager->gcn_business_travel_salary = (is_numeric($row[45] ?? null) ? (float) $row[45] : null);                           // Lương đi công tác GCN
                            $salaryManager->gcn_business_travel_salary_notice = (is_numeric($row[46] ?? null) ? (float) $row[46] : null);                    // Lương đi công tác GCN Ghi Chú
                            $salaryManager->number_of_business_trips = (is_numeric($row[47] ?? null) ? (float) $row[47] : null);                             // Số lần đi công tác
                            $salaryManager->business_fuel_unit_price_day = (is_numeric($row[48] ?? null) ? (float) $row[48] : null);                         // Đơn giá xăng công tác/ ngày
                            $salaryManager->allowance_gcn_business_fuel = (is_numeric($row[49] ?? null) ? (float) $row[49] : null);                          // Phụ cấp xăng đi GCN
                            $salaryManager->allowance_gcn_business_fuel_notice = (is_numeric($row[50] ?? null) ? (float) $row[50] : null);                   // Phụ cấp xăng đi GCN Ghi Chú
                            $salaryManager->money_referral_people = (is_numeric($row[51] ?? null) ? (float) $row[51] : null);                                 // Tiền giới thiệu người
                            $salaryManager->money_referral_people_notice = (is_numeric($row[52] ?? null) ? (float) $row[52] : null);                          // Tiền giới thiệu người Ghi Chú
                            $salaryManager->allowance_diffrent = (is_numeric($row[53] ?? null) ? (float) $row[53] : null);                                    // phụ cấp khác
                            $salaryManager->allowance_diffrent_notice = (is_numeric($row[54] ?? null) ? (float) $row[54] : null);                             // phụ cấp khác Ghi Chú
                            $salaryManager->bonuses_for_attendance = (is_numeric($row[55] ?? null) ? (float) $row[55] : null);                                // Tiền thưởng đạt chuyên cần
                            $salaryManager->bonuses_for_attendance_notice = (is_numeric($row[56] ?? null) ? (float) $row[56] : null);                        // Tiền thưởng đạt chuyên cần Ghi Chú
                            $salaryManager->sickness = (is_numeric($row[57] ?? null) ? (float) $row[57] : null);                                              // Ốm đau
                            $salaryManager->sickness_notice = (is_numeric($row[58] ?? null) ? (float) $row[58] : null);                                       // Ốm đau Ghi Chú
                            $salaryManager->funeral = (is_numeric($row[59] ?? null) ? (float) $row[59] : null);                                               // Ma chay
                            $salaryManager->funeral_notice = (is_numeric($row[60] ?? null) ? (float) $row[60] : null);                                        // Ma chay Ghi Chú
                            $salaryManager->birthday_money = (is_numeric($row[61] ?? null) ? (float) $row[61] : null);                                         // Tiền sinh nhật
                            $salaryManager->birthday_money_notice = (is_numeric($row[62] ?? null) ? (float) $row[62] : null);                                  // Tiền sinh nhật Ghi Chú
                            $salaryManager->previous_period_debt = (is_numeric($row[63] ?? null) ? (float) $row[63] : null);                                   // Tiền lương tháng trước bị thiếu
                            $salaryManager->previous_period_debt_notice = (is_numeric($row[64] ?? null) ? (float) $row[64] : null);                            // Tiền lương tháng trước bị thiếu Ghi Chú
                            $salaryManager->total_income = (is_numeric($row[65] ?? null) ? (float) $row[65] : null);                                           // Tổng thu nhập
                            $salaryManager->insurance_detail = (is_numeric($row[66] ?? null) ? (float) $row[66] : null);                                       // Khấu trừ BHXH 10.5%
                            $salaryManager->insurance_detail_notice = (is_numeric($row[67] ?? null) ? (float) $row[67] : null);                                // Khấu trừ BHXH 10.5% Ghi Chú
                            $salaryManager->advance_money = (is_numeric($row[68] ?? null) ? (float) $row[68] : null);                                          // tạm ứng
                            $salaryManager->advance_money_notice = (is_numeric($row[69] ?? null) ? (float) $row[69] : null);                                   // tạm ứng Ghi Chú
                            $salaryManager->number_of_violations = (is_numeric($row[70] ?? null) ? (float) $row[70] : null);                                    // Số lần vi phạm
                            $salaryManager->unicon_deduction = (is_numeric($row[71] ?? null) ? (float) $row[71] : null);                                        // Trừ vi phạm
                            $salaryManager->unicon_deduction_notice = (is_numeric($row[72] ?? null) ? (float) $row[72] : null);                                 // Trừ vi phạm Ghi Chú
                            $salaryManager->daysleave_allowed = (is_numeric($row[73] ?? null) ? (float) $row[73] : null);                                       // số ngày nghỉ có phép
                            $salaryManager->subtract_daysleave_allowed = (is_numeric($row[74] ?? null) ? (float) $row[74] : null);                              // Trừ tiền nghỉ có phép
                            $salaryManager->subtract_daysleave_allowed_notice = (is_numeric($row[75] ?? null) ? (float) $row[75] : null);                       // Trừ tiền nghỉ có phép Ghi Chú
                            $salaryManager->daysleave_notallowed = (is_numeric($row[76] ?? null) ? (float) $row[76] : null);                                    // số ngày nghĩ không phép
                            $salaryManager->subtract_daysleave_notallowed = (is_numeric($row[77] ?? null) ? (float) $row[77] : null);                           // Trừ tiền nghỉ không phép
                            $salaryManager->subtract_daysleave_notallowed_notice = (is_numeric($row[78] ?? null) ? (float) $row[78] : null);                    // Trừ tiền nghỉ không phép Ghi Chú
                            $salaryManager->error_serious = (is_numeric($row[79] ?? null) ? (float) $row[79] : null);                                          // số lỗi nặng
                            $salaryManager->subtract_error_serious = (is_numeric($row[80] ?? null) ? (float) $row[80] : null);                                  // Trừ tiền số lỗi nặng
                            $salaryManager->subtract_error_serious_notice = (is_numeric($row[81] ?? null) ? (float) $row[81] : null);                           // Trừ tiền số lỗi nặng Ghi Chú
                            $salaryManager->error_minor = (is_numeric($row[82] ?? null) ? (float) $row[82] : null);                                             // số lỗi nhẹ
                            $salaryManager->subtract_error_minor = (is_numeric($row[83] ?? null) ? (float) $row[83] : null);                                     // Trừ tiền số lỗi nhẹ
                            $salaryManager->subtract_error_minor_notice = (is_numeric($row[84] ?? null) ? (float) $row[84] : null);                              // Trừ tiền số lỗi nhẹ Ghi Chú
                            $salaryManager->kpi_subtraction = (is_numeric($row[85] ?? null) ? (float) $row[85] : null);                                           // trừ KPI
                            $salaryManager->kpi_subtraction_notice = null;                                                                                          // trừ KPI Ghi Chú
                            $salaryManager->actually_received = $this->numericValue($row[86] ?? null);                                                              // thực lãnh
                            $salaryManager->forms_of_payment = $this->stringValue($row[87] ?? null);                                                               // hình thức thanh toán
                            $salaryManager->company_insurance_detail = (is_numeric($row[88] ?? null) ? (float) $row[88] : null);                                   // BHXH (21.5%) công ty đóng cho NLĐ
                            $salaryManager->salary_total = $salaryManager->total_income;
                            $salaryManager->insurance_payroll = $salaryManager->insurance_detail;
                            $salaryManager->advance_money_payroll = $salaryManager->advance_money;
                            $salaryManager->company_insurance_payroll = $salaryManager->company_insurance_detail;
                            $salaryManager->KPI_Subtraction_payroll = $salaryManager->kpi_subtraction;
                            $salaryManager->previous_period_debt_payroll = $salaryManager->previous_period_debt;
                            $salaryManager->actually_received_payroll = $salaryManager->actually_received;

                            $attributes = $salaryManager->getAttributes();
                            $attributes['updated_at'] = $now;
                            $updates[] = $attributes;
                        }
                    }
                }
            }

            $this->batchUpsertByIdUsingRecordColumns(SalaryOfficialVVP::class, $updates, 100);
        } catch (\Exception $e) {
            LogHelper::saveLog('Import-Detail-VVP', $e->getMessage(), $e->getLine());
            Log::error('errors detail:: '.$e->getMessage().' getLine'.$e->getLine());
            throw $e;
        }
    }

    public function startRow(): int
    {
        return 8; // Start importing from row 8
    }

    public function prepareForValidation(array $row, int $index): array
    {
        $row[86] = $this->normalizeNumericInput($row[86] ?? null);

        return $row;
    }

    // validate
    public function rules(): array
    {
        $rules = [];
        $listCode = $this->getValidEmployeeIds();
        $rules['1'] = ['required', 'in:'.implode(',', $listCode)];
        for ($i = 4; $i <= 88; $i++) {
            if (! in_array($i, $this->roleIgnore)) {
                $rules[$i] = ['nullable', 'numeric'];
            } else {
                $rules[$i] = ['nullable'];
            }
        }

        return $rules;
    }

    public function customValidationMessages()
    {
        $messages = [];
        $validations[1 .'required'] = 'Mã nhân viên không được để trống!';
        $validations[1 .'in'] = 'Mã nhân viên không tồn tại!';
        $role = [
            'Số công ngày (thử việc)',
            'Lương ca ngày (thử việc)',
            'Lương ca ngày (thử việc) Ghi Chú',
            'Số công đêm (thử việc)',
            'Lương ca đêm (thử việc)',
            'Lương ca đêm (thử việc) Ghi Chú',
            'Số giờ tăng ca ( thử việc)',
            'Lương tăng ca (thử việc)',
            'Lương tăng ca (thử vifệc) Ghi Chú',
            'Số Công',
            'phụ cấp học việc detail',
            'phụ cấp học việc detail Ghi Chú',
            'số giờ chính detail',
            'lương chính thức',
            'lương chính thức Ghi Chú',
            'số công làm',
            'chuyên cần detail',
            'chuyên cần detail Ghi Chú',
            'Số công làm',
            'trách nhiệm detail',
            'trách nhiệm detail Ghi Chú',
            'số giờ tăng ca',
            'lương tăng ca',
            'lương tăng ca Ghi Chú',
            'Số công ngày',
            'phụ cấp cơm ca ngày',
            'phụ cấp cơm ca ngày Ghi Chú',
            'Số công đêm',
            'phụ cấp ca đêm',
            'phụ cấp ca đêm Ghi Chú',
            'số ngày tăng ca',
            'phụ cấp tăng ca',
            'phụ cấp tăng ca Ghi Chú',
            'số ngày lễ tết',
            'tiền lễ tết',
            'tiền lễ tết Ghi Chú',
            'số ngày phép năm',
            'số tiền phép năm',
            'Số tiền phép năm Ghi Chú',
            'Số giờ đi công tác',
            'Đơn giá đi công tác/ giờ',
            'Lương đi công tác GCN',
            'Lương đi công tác GCN Ghi Chú',
            'Số lần đi công tác',
            'Đơn giá xăng công tác/ ngày',
            'Phụ cấp xăng đi GCN',
            'Phụ cấp xăng đi GCN Ghi Chú',
            'Tiền giới thiệu người',
            'Tiền giới thiệu người Ghi Chú',
            'phụ cấp khác',
            'phụ cấp khác Ghi Chú',
            'Tiền thưởng đạt chuyên cần',
            'Tiền thưởng đạt chuyên cần Ghi Chú',
            'Ốm đau',
            'Ốm đau Ghi Chú',
            'Ma chay',
            'Ma chay Ghi Chú',
            'Tiền sinh nhật',
            'Tiền sinh nhật Ghi Chú',
            'Tiền lương tháng trước bị thiếu',
            'Tiền lương tháng trước bị thiếu Ghi Chú',
            'Tổng thu nhập',
            'Khấu trừ BHXH 10.5%',
            'Khấu trừ BHXH 10.5% Ghi Chú',
            'tạm ứng',
            'tạm ứng Ghi Chú',
            'Số lần vi phạm',
            'Trừ vi phạm',
            'Trừ vi phạm Ghi Chú',
            'số ngày nghỉ có phép',
            'Trừ tiền nghỉ có phép',
            'Trừ tiền nghỉ có phép Ghi Chú',
            'số ngày nghĩ không phép',
            'Trừ tiền nghỉ không phép',
            'Trừ tiền nghỉ không phép Ghi Chú',
            'số lỗi nặng',
            'Trừ tiền số lỗi nặng',
            'Trừ tiền số lỗi nặng Ghi Chú',
            'số lỗi nhẹ',
            'Trừ tiền số lỗi nhẹ',
            'Trừ tiền số lỗi nhẹ Ghi Chú',
            'trừ KPI',
            'thực lãnh',
            'hình thức thanh toán',
            'BHXH (21.5%) công ty đóng cho NLĐ',
        ];

        for ($i = 4; $i <= 88; $i++) {
            if (! in_array($i, $this->roleIgnore)) {
                $messages[$i.'.numeric'] = 'Trường '.$role[$i - 4].' không đúng định dạng!';
            }
        }

        return $messages;
    }

    /**
     * @param  Failure[]  $failures
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $key => $failure) {
            LogHelper::saveLog('Import-VVP-Chi tiết', $this->formatImportFailure($this->sheet(), $failure), $failure->row());
        }
    }

    /**
     * Register events for the import process
     */
    public function registerEvents(): array
    {
        return [
            AfterImport::class => function () {
                $this->clearEmployeeCache();
                if ($this->salaryManagerId) {
                    $this->clearSalaryCache(SalaryOfficialVVP::class, $this->salaryManagerId);
                }
                $this->clearValidationCache();
            },
        ];
    }
}
