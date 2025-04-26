@extends('layouts.'.$layout)

<?php

if (isset($salaryOfficialsA7A)) {
    $salaryOfficialsVVP = $salaryOfficialsA7A;
}

/* Income */
$number_of_work_days_trial = number_format($salaryOfficialsVVP->number_of_work_days_trial, 2) ?? 0;
$day_shift_salary_trial = number_format($salaryOfficialsVVP->day_shift_salary_trial) ?? 0;
$day_shift_salary_trial_notice = $salaryOfficialsVVP->day_shift_salary_trial_notice ?? '';

$number_of_work_nights_trial = number_format($salaryOfficialsVVP->number_of_work_nights_trial, 2) ?? 0;
$night_shift_salary_trial = number_format($salaryOfficialsVVP->night_shift_salary_trial) ?? 0;
$night_shift_salary_trial_notice = $salaryOfficialsVVP->night_shift_salary_trial_notice ?? '';

$overtime_hours_trial = number_format($salaryOfficialsVVP->overtime_hours_trial, 2) ?? 0;
$overtime_salary_trial = number_format($salaryOfficialsVVP->overtime_salary_trial) ?? 0;
$overtime_salary_trial_notice = $salaryOfficialsVVP->overtime_salary_trial_notice ?? '';

$number_of_work = number_format($salaryOfficialsVVP->number_of_work, 2) ?? 0;
$allowance_apprentice_detail = number_format($salaryOfficialsVVP->allowance_apprentice_detail) ?? 0;
$allowance_apprentice_detail_notice = $salaryOfficialsVVP->allowance_apprentice_detail_notice ?? '';

$core_hours = number_format($salaryOfficialsVVP->core_hours, 2) ?? 0;
$official_salary = number_format($salaryOfficialsVVP->official_salary) ?? 0;
$official_salary_notice = $salaryOfficialsVVP->official_salary_notice ?? '';

$allowance_diligence_detail = number_format($salaryOfficialsVVP->allowance_diligence_detail) ?? 0;
$allowance_diligence_detail_notice = $salaryOfficialsVVP->allowance_diligence_detail_notice ?? '';

$allowance_responsibility_detail = number_format($salaryOfficialsVVP->allowance_responsibility_detail) ?? 0;
$allowance_responsibility_detail_notice = $salaryOfficialsVVP->allowance_responsibility_detail_notice ?? '';

$overtime_hours_detail = number_format($salaryOfficialsVVP->overtime_hours_detail, 2) ?? 0;
$overtime_salary = number_format($salaryOfficialsVVP->overtime_salary) ?? 0;
$overtime_salary_notice = $salaryOfficialsVVP->overtime_salary_notice ?? '';

$number_of_work_days = number_format($salaryOfficialsVVP->number_of_work_days) ?? 0;
$allowance_rice_detail = number_format($salaryOfficialsVVP->allowance_rice_detail) ?? 0;
$allowance_rice_detail_notice = $salaryOfficialsVVP->allowance_rice_detail_notice ?? '';

$number_of_work_nights = number_format($salaryOfficialsVVP->number_of_work_nights) ?? 0;
$allowance_shift_night = number_format($salaryOfficialsVVP->allowance_shift_night) ?? 0;
$allowance_shift_night_notice = $salaryOfficialsVVP->allowance_shift_night_notice ?? '';

$overtime_day_count_detail = number_format($salaryOfficialsVVP->overtime_day_count_detail) ?? 0;
$allowance_overtime_detail = number_format($salaryOfficialsVVP->allowance_overtime_detail) ?? 0;
$allowance_overtime_detail_notice = $salaryOfficialsVVP->allowance_overtime_detail_notice ?? '';

$holidays_count_detail = number_format($salaryOfficialsVVP->holidays_count_detail) ?? 0;
$holidays_money = number_format($salaryOfficialsVVP->holidays_money) ?? 0;
$holidays_money_notice = $salaryOfficialsVVP->holidays_money_notice ?? '';

$paid_holidays_count_detail = number_format($salaryOfficialsVVP->paid_holidays_count_detail) ?? 0;
$paid_holidays_money = number_format($salaryOfficialsVVP->paid_holidays_money) ?? 0;
$paid_holidays_money_notice = $salaryOfficialsVVP->paid_holidays_money_notice ?? '';

$business_travel_hours = number_format($salaryOfficialsVVP->business_travel_hours) ?? 0;
$gcn_business_travel_salary = number_format($salaryOfficialsVVP->gcn_business_travel_salary) ?? 0;
$gcn_business_travel_salary_notice = $salaryOfficialsVVP->gcn_business_travel_salary_notice ?? '';

$number_of_business_trips = number_format($salaryOfficialsVVP->number_of_business_trips) ?? 0;
$allowance_gcn_business_fuel = number_format($salaryOfficialsVVP->allowance_gcn_business_fuel) ?? 0;
$allowance_gcn_business_fuel_notice = $salaryOfficialsVVP->allowance_gcn_business_fuel_notice ?? '';

$money_referral_people = number_format($salaryOfficialsVVP->money_referral_people) ?? 0;
$money_referral_people_notice = $salaryOfficialsVVP->money_referral_people_notice ?? '';

$allowance_different = number_format($salaryOfficialsVVP->allowance_diffrent) ?? 0;
$allowance_different_notice = $salaryOfficialsVVP->allowance_diffrent_notice ?? '';

$bonuses_for_attendance = number_format($salaryOfficialsVVP->bonuses_for_attendance) ?? 0;
$bonuses_for_attendance_notice = $salaryOfficialsVVP->bonuses_for_attendance_notice ?? '';

$birthday_money = number_format($salaryOfficialsVVP->birthday_money) ?? 0;
$birthday_money_notice = $salaryOfficialsVVP->birthday_money_notice ?? '';

$previous_period_debt = number_format($salaryOfficialsVVP->previous_period_debt) ?? 0;
$previous_period_debt_notice = $salaryOfficialsVVP->previous_period_debt_notice ?? '';

// Total income
$salary_total = number_format($salaryOfficialsVVP->salary_total, 0) ?? 0;

/* Deductions */
$insurance_detail = number_format($salaryOfficialsVVP->insurance_detail) ?? 0;
$insurance_detail_notice = $salaryOfficialsVVP->insurance_detail_notice ?? '';

$advance_money = number_format($salaryOfficialsVVP->advance_money) ?? 0;
$advance_money_notice = $salaryOfficialsVVP->advance_money_notice ?? '';

$number_of_violations = number_format($salaryOfficialsVVP->number_of_violations) ?? 0;
$subtract_of_violations = number_format($salaryOfficialsVVP->subtract_of_violations) ?? 0;
$subtract_of_violations_notice = $salaryOfficialsVVP->subtract_of_violations_notice ?? '';

$days_leave_allowed = number_format($salaryOfficialsVVP->daysleave_allowed) ?? 0;
$subtract_days_leave_allowed = number_format($salaryOfficialsVVP->subtract_daysleave_allowed) ?? 0;
$subtract_days_leave_allowed_notice = $salaryOfficialsVVP->subtract_daysleave_allowed_notice ?? '';

$days_leave_not_allowed = number_format($salaryOfficialsVVP->daysleave_notallowed) ?? 0;
$subtract_days_leave_not_allowed = number_format($salaryOfficialsVVP->subtract_daysleave_notallowed) ?? 0;
$subtract_days_leave_not_allowed_notice = $salaryOfficialsVVP->subtract_daysleave_notallowed_notice ?? '';

$error_serious = number_format($salaryOfficialsVVP->error_serious) ?? 0;
$subtract_error_serious = number_format($salaryOfficialsVVP->subtract_error_serious) ?? 0;
$subtract_error_serious_notice = $salaryOfficialsVVP->subtract_error_serious_notice ?? '';

$error_minor = number_format($salaryOfficialsVVP->error_minor) ?? 0;
$subtract_error_minor = number_format($salaryOfficialsVVP->subtract_error_minor) ?? 0;
$subtract_error_minor_notice = $salaryOfficialsVVP->subtract_error_minor_notice ?? '';

$kpi_subtraction = number_format($salaryOfficialsVVP->kpi_subtraction) ?? 0;
$kpi_subtraction_notice = $salaryOfficialsVVP->kpi_subtraction_notice ?? '';

// Total deductions
$total = $salaryOfficialsVVP->insurance_detail + $salaryOfficialsVVP->advance_money + $salaryOfficialsVVP->subtract_of_violations + $salaryOfficialsVVP->subtract_daysleave_allowed + $salaryOfficialsVVP->subtract_daysleave_notallowed + $salaryOfficialsVVP->subtract_error_serious + $salaryOfficialsVVP->subtract_error_minor + $salaryOfficialsVVP->kpi_subtraction;

/* More information */
$start_date = $salaryManager->formatTimeDMY($salaryManager->start_date) ?? 'dd/mm/yyyy';
$end_date = $salaryManager->formatTimeDMY($salaryManager->end_date) ?? 'dd/mm/yyyy';

$name = Auth()->user()->name ?? 'your name';
$code = Auth()->user()->code ?? 'your code';
$role = Auth()->user()->role->role_name ?? 'your role';
$date_show = $salaryManager->formatTimeDMY($salaryManager->date_show) ?? 'dd/mm/yyyy';

$actually_received = number_format($salaryOfficialsVVP->actually_received) ?? 0;
$forms_of_payment = $salaryOfficialsVVP->forms_of_payment ?? '';
$salaryInWords = $salaryInWords ?? '';

$company_insurance_detail = $salaryOfficialsVVP->company_insurance_detail ?? 0;
$number_of_violations = $salaryOfficialsVVP->number_of_violations ?? '';
$actually_received = $salaryOfficialsVVP->actually_received ?? 0;
$unicon_deduction = $salaryOfficialsVVP->unicon_deduction ?? 0;
$totalSalary = $actually_received + $unicon_deduction * 2 + $company_insurance_detail;

$otherNote = '........';

?>

@section('styles')
    <style>
        th {
            white-space: normal;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Chi Tiết Bảng Lương</h4>
                    </div>
                </div>
                <div class="card-body">
                    <a
                        class="btn btn-link mb-3"
                        href="{{ route('admin.employee-show.salary') }}"
                    >
                        <i class="fas fa-arrow-left"></i>
                        Quay lại
                    </a>
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="text-center">
                                <h3>Thông tin bảng lương</h3>
                                <h6>
                                    Từ {{ $start_date }} đến {{ $end_date }}
                                </h6>
                            </div>
                            <div class="mb-3">
                                <div>
                                    <strong class="fw-bold">
                                        Tên nhân viên:
                                    </strong>
                                    {{ $name }}
                                </div>
                                <div>
                                    <strong class="fw-bold">
                                        Mã nhân viên:
                                    </strong>
                                    {{ $code }}
                                </div>
                                <div>
                                    <strong class="fw-bold">Bộ phận:</strong>
                                    {{ $role }}
                                </div>
                                <div>
                                    <strong class="fw-bold">
                                        Ngày nhận lương:
                                    </strong>
                                    {{ $date_show }}
                                </div>
                            </div>
                            <div class="mb-3 text-wrap">
                                <div class="fw-bold">Các khoảng lương</div>
                                <div>
                                    <div class="row bg-light">
                                        <div class="col-3 fw-bold border py-2">
                                            Diễn giải
                                        </div>
                                        <div class="col-3 fw-bold border py-2">
                                            Số giờ / Ngày
                                        </div>
                                        <div class="col-3 fw-bold border py-2">
                                            Thành tiền
                                        </div>
                                        <div class="col-3 fw-bold border py-2">
                                            Ghi chú
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 fw-bold border py-2">
                                            Lương ca ngày (thử việc)
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $number_of_work_days_trial }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $day_shift_salary_trial }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $day_shift_salary_trial_notice }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 fw-bold border py-2">
                                            Lương ca đêm (thử việc)
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $number_of_work_nights_trial }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $night_shift_salary_trial }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $night_shift_salary_trial_notice }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 fw-bold border py-2">
                                            Lương tăng ca (thử việc)
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $overtime_hours_trial }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $overtime_salary_trial }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $overtime_salary_trial_notice }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 fw-bold border py-2">
                                            Phụ cấp học việc
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $number_of_work }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $allowance_apprentice_detail }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $allowance_apprentice_detail_notice }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 fw-bold border py-2">
                                            Số giờ chính
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $core_hours }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $official_salary }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $official_salary_notice }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 fw-bold border py-2">
                                            Chuyên cần
                                        </div>
                                        <div
                                            class="col-3 border text-sm py-2"
                                        ></div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $allowance_diligence_detail }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $allowance_diligence_detail_notice }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 fw-bold border py-2">
                                            Trách Nhiệm
                                        </div>
                                        <div
                                            class="col-3 border text-sm py-2"
                                        ></div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $allowance_responsibility_detail }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $allowance_responsibility_detail_notice }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 fw-bold border py-2">
                                            Số giờ tăng ca
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $overtime_hours_detail }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $overtime_salary }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $overtime_salary_notice }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 fw-bold border py-2">
                                            Phụ cấp cơm ca ngày
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $number_of_work_days }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $allowance_rice_detail }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $allowance_rice_detail_notice }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 fw-bold border py-2">
                                            Phụ cấp cơm ca đêm
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $number_of_work_nights }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $allowance_shift_night }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $allowance_shift_night_notice }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 fw-bold border py-2">
                                            Phụ cấp tăng ca
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $overtime_day_count_detail }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $allowance_overtime_detail }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $allowance_overtime_detail_notice }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 fw-bold border py-2">
                                            Tiền lễ tết
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $holidays_count_detail }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $holidays_money }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $holidays_money_notice }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 fw-bold border py-2">
                                            Tiền phép năm
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $paid_holidays_count_detail }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $paid_holidays_money }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $paid_holidays_money_notice }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 fw-bold border py-2">
                                            Lương đi công tác GCN
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $business_travel_hours }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $gcn_business_travel_salary }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $gcn_business_travel_salary_notice }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 fw-bold border py-2">
                                            Phụ cấp xăng đi GCN
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $number_of_business_trips }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $allowance_gcn_business_fuel }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $allowance_gcn_business_fuel_notice }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 fw-bold border py-2">
                                            Tiền giới thiệu người
                                        </div>
                                        <div
                                            class="col-3 border text-sm py-2"
                                        ></div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $money_referral_people }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $money_referral_people_notice }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 fw-bold border py-2">
                                            Phụ cấp khác
                                        </div>
                                        <div
                                            class="col-3 border text-sm py-2"
                                        ></div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $allowance_different }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $allowance_different_notice }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 fw-bold border py-2">
                                            Tiền thưởng đạt chuyên cần
                                        </div>
                                        <div
                                            class="col-3 border text-sm py-2"
                                        ></div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $bonuses_for_attendance }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $bonuses_for_attendance_notice }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 fw-bold border py-2">
                                            Tiền sinh nhật
                                        </div>
                                        <div
                                            class="col-3 border text-sm py-2"
                                        ></div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $birthday_money }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $birthday_money_notice }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 fw-bold border py-2">
                                            Tiền lương tháng trước bị thiếu
                                        </div>
                                        <div
                                            class="col-3 border text-sm py-2"
                                        ></div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $previous_period_debt }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $previous_period_debt_notice }}
                                        </div>
                                    </div>
                                    <div class="row bg-light">
                                        <div class="col-3 fw-bold border py-2">
                                            Tổng thu nhập
                                        </div>
                                        <div
                                            class="col-3 border text-sm py-2"
                                        ></div>
                                        <div
                                            class="col-3 fw-bold border py-2 text-sm"
                                        >
                                            {{ $salary_total }}
                                        </div>
                                        <div
                                            class="col-3 border text-sm py-2"
                                        ></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="fw-bold">Các khoảng trừ</div>
                                <div>
                                    <div class="row bg-light">
                                        <div class="col-3 fw-bold border py-2">
                                            Diễn giải
                                        </div>
                                        <div class="col-3 fw-bold border py-2">
                                            Số giờ / Ngày
                                        </div>
                                        <div class="col-3 fw-bold border py-2">
                                            Thành tiền
                                        </div>
                                        <div class="col-3 fw-bold border py-2">
                                            Ghi chú
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 fw-bold border py-2">
                                            Khấu trừ BHXH (10.5%)
                                        </div>
                                        <div
                                            class="col-3 border text-sm py-2"
                                        ></div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $insurance_detail }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $insurance_detail_notice }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 fw-bold border py-2">
                                            Tạm ứng
                                        </div>
                                        <div
                                            class="col-3 border text-sm py-2"
                                        ></div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $advance_money }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $advance_money_notice }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 fw-bold border py-2">
                                            Phí công đoàn 1%
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $number_of_violations }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $unicon_deduction }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $subtract_of_violations_notice }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 fw-bold border py-2">
                                            Nghỉ có phép
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $days_leave_allowed }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $subtract_days_leave_allowed }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $subtract_days_leave_allowed_notice }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 fw-bold border py-2">
                                            unicon_deduction Nghỉ không phép
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $days_leave_not_allowed }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $subtract_days_leave_not_allowed }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $subtract_days_leave_not_allowed_notice }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 fw-bold border py-2">
                                            Lỗi nặng
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $error_serious }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $subtract_error_serious }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $subtract_error_serious_notice }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 fw-bold border py-2">
                                            Lỗi nhẹ
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $error_minor }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $subtract_error_minor }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $subtract_error_minor_notice }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 fw-bold border py-2">
                                            Trừ KPI
                                        </div>
                                        <div
                                            class="col-3 border text-sm py-2"
                                        ></div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $kpi_subtraction }}
                                        </div>
                                        <div class="col-3 border text-sm py-2">
                                            {{ $kpi_subtraction_notice }}
                                        </div>
                                    </div>
                                    <div class="row bg-light">
                                        <div class="col-3 fw-bold border py-2">
                                            Tổng trừ
                                        </div>
                                        <div
                                            class="col-3 border text-sm py-2"
                                        ></div>
                                        <div
                                            class="col-3 fw-bold border py-2 text-sm"
                                        >
                                            {{ number_format($total) }}
                                        </div>
                                        <div
                                            class="col-3 border text-sm py-2"
                                        ></div>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div>
                                    <strong class="fw-bold">
                                        Thực nhận tiền lương:
                                    </strong>
                                    {{ number_format($actually_received) }}
                                </div>
                                <div>
                                    <strong class="fw-bold">Bằng chữ:</strong>
                                    {{ $salaryInWords }}
                                </div>
                                <div>
                                    <strong class="fw-bold">
                                        Phương thức:
                                    </strong>
                                    {{ $forms_of_payment }}
                                </div>
                                <br />
                                <div>
                                    <strong class="fw-bold">
                                        Công ty phải đóng BHXH 21,5% cho người
                                        lao động:
                                    </strong>
                                    {{ number_format($company_insurance_detail) }}
                                </div>
                                <div>
                                    <strong class="fw-bold text-danger">
                                        Công ty phải đóng Kinh phí công đoàn 2%
                                        cho người lao động:
                                    </strong>
                                    {{ number_format((float) str_replace([',', ','], '', $unicon_deduction) * 2, 0, ',', ',') }}
                                </div>
                                <div>
                                    <strong class="fw-bold text-danger">
                                        Công ty phải tổng trả chi phí lương cho
                                        01 người lao động / tháng:
                                    </strong>
                                    {{ number_format($totalSalary) }}
                                </div>
                                <br />
                                <div>
                                    <strong class="fw-bold">Ghi chú:</strong>
                                    {{ $otherNote }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
