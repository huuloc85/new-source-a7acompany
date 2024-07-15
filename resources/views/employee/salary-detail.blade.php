@extends('master')
@section('content')
<style>
    .form-control {
        border: 1px solid #d2d6da !important;
        padding-left: 10px;
    }

    .active>.page-link {
        color: white !important
    }

    .href {
        color: blue !important;
    }

    .search-role {
        height: 37px;
    }

    .test {
        max-width: 55%;
        overflow-x: auto;
    }

    .test-detail {
        max-width: 38%;
        overflow: auto
    }

    /* .text-start {
                                                                                                                            width: 120px;
                                                                                                                        }

                                                                                                                        .text-end {
                                                                                                                            width: 120px;
                                                                                                                        } */

    hr {
        margin: 0 !important;
    }
    
</style>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Chi Tiết Bảng Lương</h4>
                </div>
            </div>
            <div class="p-4 pb-0 d-flex">
                <a class="btn btn-success" href="{{ route('admin.employee-show.salary') }}">Danh sách bảng lương</a>
            </div>
            <div class="px-4 pb-2">
                @if (isset($salaryOfficialsVVP))
                <h5 class="text-center">Thông tin bảng lương</h5>
                <p class="text-center">
                    Từ {{ $salaryManager->formatDateDMY($salaryManager->start_date) }} đến
                    {{ $salaryManager->formatTimeDMY($salaryManager->end_date) }}
                </p>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div>
                            <label class="form-label fw-bold" for="">Tên nhân viên:
                                <label>{{ Auth()->user()->name ?? '' }}</label></label>
                        </div>
                        <div>
                            <label class="form-label fw-bold" for="">Mã nhân viên:
                                <label>{{ Auth()->user()->code ?? '' }}</label></label>
                        </div>
                        <div>
                            <label class="form-label fw-bold" for="">Bộ phận:
                                <label>{{ Auth()->user()->role->role_name ?? '' }}</label></label>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <h6 class="mb-0">Diễn giải</h6>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <h6 class="mb-0">Số giờ / Ngày</h6>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <h6 class="mb-0">Thành tiền</h6>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <h6 class="mb-0">Ghi chú</h6>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Các
                                        khoản lương thu nhập: </label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;"></label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->salary_total) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;"></label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Lương ca ngày (thử việc):</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->number_of_work_days_trial, 2) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->day_shift_salary_trial) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->day_shift_salary_trial_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Lương ca đêm (thử việc):</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->number_of_work_nights_trial, 2) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->night_shift_salary_trial) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->night_shift_salary_trial_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Lương tăng ca (thử việc):</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->overtime_hours_trial, 2) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->overtime_salary_trial) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->overtime_salary_trial_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Phụ cấp học việc:</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->number_of_work, 1) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->allowance_apprentice_detail) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->allowance_apprentice_detail_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Số giờ chính:</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->core_hours, 2) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->official_salary) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->official_salary_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Chuyên cần:</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;"></label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->allowance_diligence_detail) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->allowance_diligence_detail_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Trách Nhiệm:</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;"></label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->allowance_responsibility_detail) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->allowance_responsibility_detail_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Số giờ tăng ca:</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">
                                        {{ number_format($salaryOfficialsVVP->overtime_hours_detail, 2) ?? '' }}
                                    </label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->overtime_salary) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->overtime_salary_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Phụ cấp cơm ca ngày:</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->number_of_work_days) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->allowance_rice_detail) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->allowance_rice_detail_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Phụ cấp cơm ca đêm:</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->number_of_work_nights) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->allowance_shift_night) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->allowance_shift_night_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Phụ cấp tăng ca:</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->overtime_day_count_detail) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->allowance_overtime_detail) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->allowance_overtime_detail_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Tiền lễ tết:</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->holidays_count_detail) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->holidays_money) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->holidays_money_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Tiền phép năm:</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->paid_holidays_count_detail) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->paid_holidays_money) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->paid_holidays_money_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Lương đi công tác GCN:</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->business_travel_hours) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->gcn_business_travel_salary) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->gcn_business_travel_salary_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Phụ cấp xăng đi GCN:</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->number_of_business_trips) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->allowance_gcn_business_fuel) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->allowance_gcn_business_fuel_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Tiền giới thiệu người:</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;"></label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->money_referral_people) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->money_referral_people_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Phụ cấp khác:</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;"></label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->allowance_diffrent) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->allowance_diffrent_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Tiền thưởng đạt chuyên cần:</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;"></label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->bonuses_for_attendance) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->bonuses_for_attendance_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Tiền ốm đau:</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;"></label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->sickness) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->sickness_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Tiền ma chay:</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;"></label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->funeral) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->funeral_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Tiền sinh nhật:</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;"></label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->birthday_money) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->birthday_money_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Tiền lương tháng trước bị thiếu:</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;"></label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->previous_period_debt) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->previous_period_debt_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <h6 class="mb-0">Các khoản trừ:</h6>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    {{-- <h6 class="mb-0">Số giờ / Ngày</h6> --}}
                                </div>
                            </div>
                            <?php
                            // Tính tổng các giá trị
                            $total = $salaryOfficialsVVP->insurance_detail + $salaryOfficialsVVP->advance_money + $salaryOfficialsVVP->subtract_of_violations + $salaryOfficialsVVP->subtract_daysleave_allowed + $salaryOfficialsVVP->subtract_daysleave_notallowed + $salaryOfficialsVVP->subtract_error_serious + $salaryOfficialsVVP->subtract_error_minor + $salaryOfficialsVVP->kpi_subtraction;
                            ?>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <h6 class="mb-0">{{ number_format($total) }}</h6>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <h6 class="mb-0">Ghi chú</h6>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Khấu trừ BHXH (10.5%):</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;"></label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->insurance_detail) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->insurance_detail_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Tạm ứng:</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;"></label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->advance_money) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->advance_money_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Trừ vi phạm:</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->number_of_violations) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->subtract_of_violations) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->subtract_of_violations_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Nghỉ có phép:</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->daysleave_allowed) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->subtract_daysleave_allowed) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->subtract_daysleave_allowed_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Nghỉ không phép:</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->daysleave_notallowed) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->subtract_daysleave_notallowed) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->subtract_daysleave_notallowed_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Lỗi nặng:</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->error_serious) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->subtract_error_serious) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->subtract_error_serious_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Lỗi nhẹ:</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->error_minor) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->subtract_error_minor) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->subtract_error_minor_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-start pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">Trừ KPI:</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;"></label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsVVP->kpi_subtraction) ?? '' }}</label>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsVVP->kpi_subtraction_notice ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-5">
                                <div class="text-start pt-1">
                                    <h6 class="mb-0">Thực nhận tiền lương:</h6>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-center pt-1">
                                    <h6 class="mb-0">
                                        {{ number_format($salaryOfficialsVVP->actually_received) ?? '' }}
                                    </h6>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center pt-1">
                                    <h6 class="mb-0">{{ $salaryOfficialsVVP->forms_of_payment ?? '' }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-5">
                                <div class="text-start pt-1">
                                    <h6 class="mb-0">Phần thông tin thêm: BHXH (21,5%) công ty đóng cho người lao
                                        động:</h6>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-center pt-3">
                                    <h6 class="mb-0">
                                        {{ number_format($salaryOfficialsVVP->company_insurance_detail) ?? '' }}
                                    </h6>
                                </div>
                            </div>

                        </div>
                    </div>
                    @elseif(isset($salaryOfficialsA7A))
                    <h5 class="text-center">Thông tin bảng lương</h5>
                    <p class="text-center">
                        Từ {{ $salaryManager->formatDateDMY($salaryManager->start_date) }} đến
                        {{ $salaryManager->formatTimeDMY($salaryManager->end_date) }}
                    </p>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div>
                                <label class="form-label fw-bold" for="">Tên nhân viên:
                                    <label>{{ Auth()->user()->name ?? '' }}</label></label>
                            </div>
                            <div>
                                <label class="form-label fw-bold" for="">Mã nhân viên:
                                    <label>{{ Auth()->user()->code ?? '' }}</label></label>
                            </div>
                            <div>
                                <label class="form-label fw-bold" for="">Bộ phận:
                                    <label>{{ Auth()->user()->role->role_name ?? '' }}</label></label>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Diễn giải</h6>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <h6 class="mb-0">Số giờ / Ngày</h6>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <h6 class="mb-0">Thành tiền</h6>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <h6 class="mb-0">Ghi chú</h6>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Các
                                            khoản lương thu nhập: </label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;"></label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->salary_total, 0) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;"></label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Lương ca ngày (thử việc):</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->number_of_work_days_trial, 2) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->day_shift_salary_trial) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->day_shift_salary_trial_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Lương ca đêm (thử việc):</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->number_of_work_nights_trial, 2) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->night_shift_salary_trial) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->night_shift_salary_trial_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Lương tăng ca (thử việc):</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->overtime_hours_trial, 2) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->overtime_salary_trial) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->overtime_salary_trial_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Phụ cấp học việc:</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->number_of_work, 2) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->allowance_apprentice_detail) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->allowance_apprentice_detail_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Số giờ chính:</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->core_hours, 2) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->official_salary) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->official_salary_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Chuyên cần:</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;"></label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->allowance_diligence_detail) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->allowance_diligence_detail_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Trách Nhiệm:</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;"></label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->allowance_responsibility_detail) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->allowance_responsibility_detail_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Số giờ tăng ca:</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->overtime_hours_detail, 2) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->overtime_salary) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->overtime_salary_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Phụ cấp cơm ca ngày:</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->number_of_work_days) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->allowance_rice_detail) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->allowance_rice_detail_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Phụ cấp cơm ca đêm:</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->number_of_work_nights) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->allowance_shift_night) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->allowance_shift_night_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Phụ cấp tăng ca:</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->overtime_day_count_detail) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->allowance_overtime_detail) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->allowance_overtime_detail_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Tiền lễ tết:</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->holidays_count_detail) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->holidays_money) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->holidays_money_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Tiền phép năm:</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->paid_holidays_count_detail) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->paid_holidays_money) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->paid_holidays_money_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Lương đi công tác GCN:</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->business_travel_hours) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->gcn_business_travel_salary) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->gcn_business_travel_salary_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Phụ cấp xăng đi GCN:</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->number_of_business_trips) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->allowance_gcn_business_fuel) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->allowance_gcn_business_fuel_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Tiền giới thiệu người:</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;"></label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->money_referral_people) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->money_referral_people_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Phụ cấp khác:</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;"></label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->allowance_diffrent) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->allowance_diffrent_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Tiền thưởng đạt chuyên cần:</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;"></label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->bonuses_for_attendance) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->bonuses_for_attendance_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Tiền ốm đau:</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;"></label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->sickness) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->sickness_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Tiền ma chay:</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;"></label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->funeral) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->funeral_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Tiền sinh nhật:</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;"></label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->birthday_money) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->birthday_money_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Tiền lương tháng trước bị
                                            thiếu:</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;"></label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->previous_period_debt) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->previous_period_debt_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Các khoản trừ:</h6>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        {{-- <h6 class="mb-0">Số giờ / Ngày</h6> --}}
                                    </div>
                                </div>
                                <?php
                                // Tính tổng các giá trị
                                $total = $salaryOfficialsA7A->insurance_detail + $salaryOfficialsA7A->advance_money + $salaryOfficialsA7A->subtract_of_violations + $salaryOfficialsA7A->subtract_daysleave_allowed + $salaryOfficialsA7A->subtract_daysleave_notallowed + $salaryOfficialsA7A->subtract_error_serious + $salaryOfficialsA7A->subtract_error_minor + $salaryOfficialsA7A->kpi_subtraction;
                                ?>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <h6 class="mb-0">{{ number_format($total) }}</h6>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <h6 class="mb-0">Ghi chú</h6>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Khấu trừ BHXH (10.5%):</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;"></label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->insurance_detail) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->insurance_detail_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Tạm ứng:</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;"></label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->advance_money) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->advance_money_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Trừ vi phạm:</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->number_of_violations) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->subtract_of_violations) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->subtract_of_violations_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Nghỉ có phép:</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->daysleave_allowed) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->subtract_daysleave_allowed) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->subtract_daysleave_allowed_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Nghỉ không phép:</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->daysleave_notallowed) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->subtract_daysleave_notallowed) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->subtract_daysleave_notallowed_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Lỗi nặng:</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->error_serious) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->subtract_error_serious) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->subtract_error_serious_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Lỗi nhẹ:</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->error_minor) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->subtract_error_minor) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->subtract_error_minor_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-start pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">Trừ KPI:</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;"></label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ number_format($salaryOfficialsA7A->kpi_subtraction) ?? '' }}</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <label class="form-label mb-0" for="" style="word-wrap: break-word;">{{ $salaryOfficialsA7A->kpi_subtraction_notice ?? '' }}</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-5">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Thực nhận tiền lương:</h6>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-center pt-1">
                                        <h6 class="mb-0">
                                            {{ number_format($salaryOfficialsA7A->actually_received) ?? '' }}
                                        </h6>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center pt-1">
                                        <h6 class="mb-0">{{ $salaryOfficialsA7A->forms_of_payment ?? '' }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-5">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Phần thông tin thêm: BHXH (21,5%) công ty đóng cho
                                            người lao
                                            động:</h6>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-center pt-3">
                                        <h6 class="mb-0">
                                            {{ number_format($salaryOfficialsA7A->company_insurance_detail) ?? '' }}
                                        </h6>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    @elseif(isset($salaryParttimes))
                    <h5 class="text-center">Thông tin bảng lương</h5>
                    <p class="text-center">
                        Từ {{ $salaryManager->formatDateDMY($salaryManager->start_date) }} đến
                        {{ $salaryManager->formatTimeDMY($salaryManager->end_date) }}
                    </p>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div>
                                <label class="form-label fw-bold" for="">Tên nhân viên:
                                    <label>{{ Auth()->user()->name ?? '' }}</label></label>
                            </div>
                            <div>
                                <label class="form-label fw-bold" for="">Mã nhân viên:
                                    <label>{{ Auth()->user()->code ?? '' }}</label></label>
                            </div>
                            <div>
                                <label class="form-label fw-bold" for="">Bộ phận:
                                    <label>{{ Auth()->user()->role->role_name ?? '' }}</label></label>
                            </div>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <label class="form-label" for="">Lương CB:</label>
                                </div>
                                <div class="text-end pt-1">
                                    <label></label>
                                </div>
                            </div>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <label class="form-label" for="">Trách nhiệm:</label>
                                </div>
                                <div class="text-end pt-1">
                                    <label></label>
                                </div>
                            </div>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <h6 class="mb-0">Diễn giải</h6>
                                </div>
                                <div class="text-center pt-1">
                                    <h6 class="mb-0">Số giờ/Ngày</h6>
                                </div>
                                <div class="text-end pt-1">
                                    <h6 class="mb-0">Thành tiền</h6>
                                </div>
                            </div>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <label class="form-label fw-bold" for="">Phát sinh:</label>
                                </div>
                                <div class="text-end pt-1">
                                    <label>{{ number_format($salaryParttimes->salary_total_2) ?? '' }}</label>
                                </div>
                            </div>
                            <hr>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <label class="form-label" for="">Chuyên cần:</label>
                                </div>
                                <div class="text-end pt-1">
                                    <label></label>
                                </div>
                            </div>
                            <hr>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <label class="form-label" for="">Trách nhiệm:</label>
                                </div>
                                <div class="text-end pt-1">
                                    <label></label>
                                </div>
                            </div>
                            <hr>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <label class="form-label" for="">PC học việc:</label>
                                </div>
                                <div class="text-end pt-1">
                                    <label></label>
                                </div>
                            </div>
                            <hr>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <label class="form-label" for="">Số giờ chính:</label>
                                </div>
                                <div class="text-end pt-1">
                                    <label></label>
                                </div>
                            </div>
                            <hr>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <label class="form-label" for="">Số giờ TC:</label>
                                </div>
                                <div class="text-end pt-1">
                                    <label></label>
                                </div>
                            </div>
                            <hr>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <label class="form-label" for="">Số công đêm:</label>
                                </div>
                                <div class="text-center pt-1">
                                    <label>{{ number_format($salaryParttimes->total_night) ?? '' }}</label>
                                </div>
                                <div class="text-end pt-1">
                                    <label>{{ number_format($salaryParttimes->worknight_money) ?? '' }}</label>
                                </div>
                            </div>
                            <hr>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <label class="form-label" for="">Số công ngày:</label>
                                </div>
                                <div class="text-center pt-1">
                                    <label>{{ number_format($salaryParttimes->total_day) ?? '' }}</label>
                                </div>
                                <div class="text-end pt-1">
                                    <label>{{ number_format($salaryParttimes->workday_money) ?? '' }}</label>
                                </div>
                            </div>
                            <hr>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <label class="form-label" for="">PC tăng ca:</label>
                                </div>
                                <div class="text-end pt-1">
                                    <label></label>
                                </div>
                            </div>
                            <hr>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <label class="form-label" for="">Ngày hết việc:</label>
                                </div>
                                <div class="text-center pt-1">
                                    <label>{{ number_format($salaryParttimes->outwork_day_count) ?? '' }}</label>
                                </div>
                                <div class="text-end pt-1">
                                    <label>{{ number_format($salaryParttimes->allowance_outwork) ?? '' }}</label>
                                </div>
                            </div>
                            <hr>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <label class="form-label" for="">Ngày lễ tết:</label>
                                </div>
                                <div class="text-end pt-1">
                                    <label></label>
                                </div>
                            </div>
                            <hr>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <label class="form-label" for="">Ngày phép năm:</label>
                                </div>
                                <div class="text-end pt-1">
                                    <label></label>
                                </div>
                            </div>
                            <hr>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <label class="form-label" for="">Gia công ngoài:</label>
                                </div>
                                <div class="text-end pt-1">
                                    <label></label>
                                </div>
                            </div>
                            <hr>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <label class="form-label" for="">Giới thiệu:</label>
                                </div>
                                <div class="text-end pt-1">
                                    <label></label>
                                </div>
                            </div>
                            <hr>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <label class="form-label" for="">PC khác:</label>
                                </div>
                                <div class="text-end pt-1">
                                    <label></label>
                                </div>
                            </div>
                            <hr>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <label class="form-label fw-bold" for="">Các khoản trừ:</label>
                                </div>
                            </div>
                            <hr>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <label class="form-label" for="">Trừ BHXH(10.5%):</label>
                                </div>
                                <div class="text-end pt-1">
                                    <label></label>
                                </div>
                            </div>
                            <hr>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <label class="form-label" for="">Trừ tạm ứng:</label>
                                </div>
                                <div class="text-end pt-1">
                                    <label></label>
                                </div>
                            </div>
                            <hr>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <label class="form-label" for="">BH Cty đóng(21.5%):</label>
                                </div>
                                <div class="text-end pt-1">
                                    <label></label>
                                </div>
                            </div>
                            <hr>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <label class="form-label" for="">Nghĩ có phép:</label>
                                </div>
                                <div class="text-end pt-1">
                                    <label></label>
                                </div>
                            </div>
                            <hr>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <label class="form-label" for="">Nghĩ không phép:</label>
                                </div>
                                <div class="text-end pt-1">
                                    <label></label>
                                </div>
                            </div>
                            <hr>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <label class="form-label" for="">Lỗi nặng:</label>
                                </div>
                                <div class="text-end pt-1">
                                    <label></label>
                                </div>
                            </div>
                            <hr>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <label class="form-label" for="">Lỗi nhẹ:</label>
                                </div>
                                <div class="text-end pt-1">
                                    <label></label>
                                </div>
                            </div>
                            <hr>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <label class="form-label" for="">Trừ KPI:</label>
                                </div>
                                <div class="text-end pt-1">
                                    <label></label>
                                </div>
                            </div>
                            <hr>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <label class="form-label fw-bold" for="">Thực nhận:</label>
                                </div>
                                <div class="text-end pt-1">
                                    <label>{{ number_format($salaryParttimes->salary_total_2) ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">

                        </div>
                    </div>
                    @else
                    <h5 class="text-center">Hiện tại chưa có thông tin về bảng lương này</h5>
                    @endif
                </div>
                <div class="p-4 pb-0 d-flex">
                    <a class="btn btn-success" href="{{ route('admin.employee-show.salary') }}">Danh sách bảng
                        lương</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection