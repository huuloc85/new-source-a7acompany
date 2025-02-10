@extends('layouts.layout')
@section('styles')
    <style>
        .test-bg {
            background-color: #eaf8d2 !important;
        }

        .bg-2 {
            background-color: #ebebeb !important;
        }

        .bg-3 {
            background-color: #919cc9 !important;
        }

        .bg-4 {
            background-color: #d7a95f !important;
        }
    </style>
@endsection

@php
    $rootTab = [
        'VVP' => [
            'id' => 'vvpTab',
            'title' => 'VVP',
            'tab' => [
                'categoryVVP' => [
                    'title' => 'Danh mục',
                ],
                'salaryVVP' => [
                    'title' => 'Bảng lương thanh toán',
                ],
                'salary-detailVVP' => [
                    'title' => 'Bảng lương chi tiết',
                ],
                'timekeepingVVP' => [
                    'title' => 'Bảng chấm công',
                ],
            ],
            'data' => $salaryOfficialsVVP,
        ],
        'A7A' => [
            'id' => 'a7aTab',
            'title' => 'A7A',
            'tab' => [
                'categoryA7A' => [
                    'title' => 'Danh mục',
                ],
                'salaryA7A' => [
                    'title' => 'Bảng lương thanh toán',
                ],
                'salary-detailA7A' => [
                    'title' => 'Bảng lương chi tiết',
                ],
                'timekeepingA7A' => [
                    'title' => 'Bảng chấm công',
                ],
            ],
            'data' => $salaryOfficialsA7A,
        ],
    ];
@endphp

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
                    <a href="{{ route('admin.salary.home') }}" class="btn btn-link mb-3">
                        <i class="fas fa-arrow-left"></i>
                        Danh sách bảng lương
                    </a>
                    <section>
                        <ul class="nav nav-tabs flex-nowrap text-nowrap overflow-x-auto overflow-y-hidden" id="rootTab"
                            role="tablist">
                            @foreach ($rootTab as $key => $root)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ $key }}-tab"
                                        data-bs-toggle="tab" data-bs-target="#{{ $key }}" type="button"
                                        role="tab" aria-controls="{{ $key }}"
                                        aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                        {{ $root['title'] }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content" id="rootTabContent">
                            {{-- ------------------------------------------------- --}}
                            @foreach ($rootTab as $keyRoot => $leafTab)
                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $keyRoot }}"
                                    role="tabpanel" aria-labelledby="{{ $keyRoot }}-tab">
                                    <section>
                                        <ul class="nav nav-tabs flex-nowrap text-nowrap overflow-x-auto overflow-y-hidden"
                                            id="{{ $leafTab['id'] }}" role="tablist">
                                            @foreach ($leafTab['tab'] as $keyTab => $tab)
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                                        id="{{ $keyTab }}-tab" data-bs-toggle="tab"
                                                        data-bs-target="#{{ $keyTab }}" type="button" role="tab"
                                                        aria-controls="{{ $keyTab }}"
                                                        aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                                        {{ $tab['title'] }}
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="tab-content" id="{{ $leafTab['id'] }}Content">
                                            <div class="tab-pane fade show active" id="category{{ $keyRoot }}"
                                                role="tabpanel" aria-labelledby="category{{ $keyRoot }}-tab">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered">
                                                        <thead class="table-light text-uppercase text-center align-middle">
                                                            <tr>
                                                                <th>STT</th>
                                                                <th>MS NV</th>
                                                                <th>
                                                                    Họ và tên
                                                                </th>
                                                                <th>Bộ phận</th>

                                                                <th class="bg-3">
                                                                    Lương ngày
                                                                    <br />
                                                                    (áp dụng
                                                                    tháng đầu)
                                                                </th>
                                                                <th class="bg-2">
                                                                    Lương đêm
                                                                    <br />
                                                                    (áp dụng
                                                                    tháng đầu)
                                                                </th>
                                                                <th class="bg-3">
                                                                    Lương CB thử
                                                                    việc / 26
                                                                    ngày
                                                                </th>
                                                                <th class="bg-2">
                                                                    Lương CB thử
                                                                    việc / 1 giờ
                                                                </th>
                                                                <th class="bg-3">
                                                                    Lương CB thử
                                                                    việc tăng ca
                                                                    / 1 giờ
                                                                </th>
                                                                <th class="bg-2">
                                                                    Phụ cấp học
                                                                    việc
                                                                </th>
                                                                <th class="bg-3">
                                                                    Lương CB
                                                                    chính thức/
                                                                    26 ngày
                                                                </th>
                                                                <th class="bg-2">
                                                                    Lương CB/
                                                                    giờ
                                                                </th>
                                                                <th class="bg-3">
                                                                    Lương TC/
                                                                    giờ
                                                                </th>
                                                                <th class="bg-2">
                                                                    Chuyên cần
                                                                </th>
                                                                <th class="bg-3">
                                                                    Trách nhiệm
                                                                </th>
                                                                <th class="bg-2">
                                                                    Phụ cấp tăng
                                                                    ca/ ngày
                                                                </th>
                                                                <th class="bg-3">
                                                                    Phụ cấp đêm
                                                                </th>
                                                                <th class="bg-2">
                                                                    Phụ cấp cơm
                                                                    trưa
                                                                </th>
                                                                <th class="bg-3">
                                                                    BHXH công ty
                                                                    đóng
                                                                </th>
                                                                <th class="bg-2">
                                                                    BHXH người
                                                                    lao động
                                                                    đóng
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="text-center align-middle">
                                                            @foreach ($leafTab['data'] as $keyCategory => $category)
                                                                <tr>
                                                                    <th>
                                                                        {{ $loop->iteration }}
                                                                    </th>
                                                                    <td>
                                                                        {{ $category->employee->code }}
                                                                    </td>
                                                                    <td class="text-start">
                                                                        {{ $category->employee->name }}
                                                                    </td>
                                                                    <td class="text-start">
                                                                        {{ $category->employee->role->role_name }}
                                                                    </td>

                                                                    <td class="bg-3">
                                                                        {{ number_format($category->salary_day) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($category->salary_night) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format(floatval($category->probationary_salary_basic_26days)) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format(floatval($category->probationary_salary_basic_hours)) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format(floatval($category->probationary_salary_basic_extra_hours)) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($category->allowance_apprentice) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($category->salary_basic) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($category->regular_salary_hour) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($category->salary_overtime) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($category->allowance_diligence) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($category->allowance_responsibility) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($category->allowance_overtime) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($category->allowance_night) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($category->allowance_rice) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($category->company_insurance) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($category->insurance) }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="d-flex justify-content-center">
                                                    {{ $leafTab['data']->appends(request()->all())->links() }}
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="salary{{ $keyRoot }}" role="tabpanel"
                                                aria-labelledby="salary{{ $keyRoot }}-tab">
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <thead class="table-light text-center align-middle">
                                                            <tr>
                                                                <th>STT</th>
                                                                <th>MS NV</th>
                                                                <th>
                                                                    Họ và tên
                                                                </th>
                                                                <th>Bộ phận</th>
                                                                <th>
                                                                    Tổng lương
                                                                </th>
                                                                <th>
                                                                    Trừ bảo hiểm
                                                                </th>
                                                                <th>Tạm ứng</th>
                                                                <th>
                                                                    BH công ty
                                                                    đóng(21%)
                                                                </th>
                                                                <th>KPI</th>
                                                                <th>
                                                                    Nợ Kỳ Trước
                                                                </th>
                                                                <th>
                                                                    Thực lãnh
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="align-middle">
                                                            @foreach ($leafTab['data'] as $key => $salary)
                                                                <tr>
                                                                    <th class="text-center">
                                                                        {{ $loop->iteration }}
                                                                    </th>
                                                                    <td>
                                                                        {{ $salary->employee->code }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $salary->employee->name }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $salary->employee->role->role_name }}
                                                                    </td>
                                                                    <td>
                                                                        {{ number_format($salary->salary_total) }}
                                                                    </td>
                                                                    <td>
                                                                        {{ number_format($salary->insurance_payroll) }}
                                                                    </td>
                                                                    <td>
                                                                        {{ number_format($salary->advance_money_payroll) }}
                                                                    </td>
                                                                    <td>
                                                                        {{ number_format($salary->company_insurance_payroll) }}
                                                                    </td>
                                                                    <td>
                                                                        {{ number_format($salary->KPI_Subtraction_payroll) }}
                                                                    </td>
                                                                    <td>
                                                                        {{ number_format($salary->previous_period_debt_payroll) }}
                                                                    </td>
                                                                    <td>
                                                                        {{ number_format($salary->actually_received_payroll) }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="d-flex justify-content-center">
                                                    {{ $leafTab['data']->appends(request()->all())->links() }}
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="salary-detail{{ $keyRoot }}"
                                                role="tabpanel" aria-labelledby="salary-detail{{ $keyRoot }}-tab">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>STT</th>
                                                                <th>MS NV</th>
                                                                <th class="ps-2">
                                                                    Họ và tên
                                                                </th>
                                                                <th class="ps-2">
                                                                    Bộ phận
                                                                </th>
                                                                <th class="bg-2">
                                                                    Số công ngày
                                                                </th>
                                                                <th class="bg-2">
                                                                    Lương ca
                                                                    ngày (thử
                                                                    việc)
                                                                </th>
                                                                <th class="bg-2">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-2">
                                                                    Số công đêm
                                                                </th>
                                                                <th class="bg-3">
                                                                    Lương ca đêm
                                                                    (thử việc)
                                                                </th>
                                                                <th class="bg-3">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-3">
                                                                    Số giờ tăng
                                                                    ca ( thử
                                                                    việc)
                                                                </th>
                                                                <th class="bg-3">
                                                                    Lương tăng
                                                                    ca (thử
                                                                    việc)
                                                                </th>
                                                                <th class="bg-2">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-2">
                                                                    Số công
                                                                </th>
                                                                <th class="bg-2">
                                                                    Phụ cấp học
                                                                    việc
                                                                </th>
                                                                <th class="bg-2">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-3">
                                                                    Số giờ chính
                                                                </th>
                                                                <th class="bg-3">
                                                                    Lương chính
                                                                    thức
                                                                </th>
                                                                <th class="bg-3">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-3">
                                                                    Chuyên cần
                                                                </th>
                                                                <th class="bg-3">
                                                                    Số công làm
                                                                </th>
                                                                <th class="bg-2">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-2">
                                                                    Số công làm
                                                                </th>
                                                                <th class="bg-2">
                                                                    Trách nhiệm
                                                                </th>
                                                                <th class="bg-2">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-3">
                                                                    Số giờ tăng
                                                                    ca
                                                                </th>
                                                                <th class="bg-3">
                                                                    Lương tăng
                                                                    ca
                                                                </th>
                                                                <th class="bg-3">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-3">
                                                                    Số công ngày
                                                                </th>
                                                                <th class="bg-2">
                                                                    Phụ cấp cơm
                                                                    ca ngày
                                                                </th>
                                                                <th class="bg-2">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-2">
                                                                    Số công đêm
                                                                </th>
                                                                <th class="bg-2">
                                                                    Phụ cấp ca
                                                                    đêm
                                                                </th>
                                                                <th class="bg-3">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-3">
                                                                    Số ngày tăng
                                                                    ca
                                                                </th>
                                                                <th class="bg-3">
                                                                    Phụ cấp tăng
                                                                    ca
                                                                </th>

                                                                <th class="bg-3">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-2">
                                                                    Số ngày lễ
                                                                    tết
                                                                </th>
                                                                <th class="bg-2">
                                                                    Tiền lễ tết
                                                                </th>
                                                                <th class="bg-2">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-2">
                                                                    Phép năm
                                                                </th>
                                                                <th class="bg-3">
                                                                    Tiền phép
                                                                    năm
                                                                </th>
                                                                <th class="bg-3">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-3">
                                                                    Số giờ đi
                                                                    công tác
                                                                </th>
                                                                <th class="bg-3">
                                                                    Đơn giá đi
                                                                    công tác/
                                                                    giờ
                                                                </th>
                                                                <th class="bg-2">
                                                                    Lương đi
                                                                    công tác GCN
                                                                </th>
                                                                <th class="bg-2">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-2">
                                                                    Số lần đi
                                                                    công tác
                                                                </th>
                                                                <th class="bg-2">
                                                                    Đơn giá xăng
                                                                    công tác/
                                                                    ngày
                                                                </th>
                                                                <th class="bg-3">
                                                                    Phụ cấp xăng
                                                                    đi GCN
                                                                </th>
                                                                <th class="bg-3">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-3">
                                                                    Tiền giới
                                                                    thiệu người
                                                                </th>
                                                                <th class="bg-3">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-2">
                                                                    Phụ cấp khác
                                                                </th>
                                                                <th class="bg-2">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-2">
                                                                    Tiền thưởng
                                                                    đạt chuyên
                                                                    cần
                                                                </th>
                                                                <th class="bg-2">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-3">
                                                                    Ốm đau
                                                                </th>
                                                                <th class="bg-3">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-3">
                                                                    Ma chay
                                                                </th>
                                                                <th class="bg-3">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-2">
                                                                    Tiền sinh
                                                                    nhật
                                                                </th>
                                                                <th class="bg-2">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-2">
                                                                    Tiền lương
                                                                    tháng trước
                                                                    bị thiếu
                                                                </th>
                                                                <th class="bg-2">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-3">
                                                                    Tổng thu
                                                                    nhập
                                                                </th>
                                                                <th class="bg-3">
                                                                    Khấu trừ
                                                                    BHXH 10.5%
                                                                </th>
                                                                <th class="bg-3">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-3">
                                                                    Tạm ứng
                                                                </th>
                                                                <th class="bg-2">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-2">
                                                                    Số lần vi
                                                                    phạm
                                                                </th>
                                                                <th class="bg-2">
                                                                    Phí công
                                                                    đoàn 1%
                                                                </th>
                                                                <th class="bg-2">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-3">
                                                                    Số nghỉ có
                                                                    phép
                                                                </th>

                                                                <th class="bg-3">
                                                                    Trừ tiền
                                                                </th>
                                                                <th class="bg-3">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-3">
                                                                    Số nghỉ
                                                                    không có
                                                                    phép
                                                                </th>
                                                                <th class="bg-2">
                                                                    Trừ tiền
                                                                </th>
                                                                <th class="bg-2">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-2">
                                                                    Số lỗi nặng
                                                                </th>
                                                                <th class="bg-2">
                                                                    Trừ tiền
                                                                </th>
                                                                <th class="bg-3">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-3">
                                                                    Ghi chú Số
                                                                    lỗi nhẹ
                                                                </th>
                                                                <th class="bg-3">
                                                                    Trừ tiền
                                                                </th>
                                                                <th class="bg-3">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-2">
                                                                    Trừ KPI
                                                                </th>
                                                                <th class="bg-2">
                                                                    Ghi chú
                                                                </th>
                                                                <th class="bg-2">
                                                                    Thực lãnh
                                                                </th>
                                                                <th class="bg-2">
                                                                    Hình thức
                                                                    thanh toán
                                                                </th>
                                                                <th class="bg-2">
                                                                    BHXH (21.5%)
                                                                    <br />
                                                                    công ty đóng
                                                                    cho NLĐ
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="text-center align-middle">
                                                            @foreach ($leafTab['data'] as $key => $salary_detail)
                                                                <tr>
                                                                    <td>
                                                                        {{ $loop->iteration }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $salary_detail->employee->code }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $salary_detail->employee->name }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $salary_detail->employee->role->role_name }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($salary_detail->number_of_work_days_trial) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($salary_detail->day_shift_salary_trial) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ $salary_detail->day_shift_salary_trial_notice }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($salary_detail->number_of_work_nights_trial) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($salary_detail->night_shift_salary_trial) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ $salary_detail->night_shift_salary_trial_notice }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($salary_detail->overtime_hours_trial, 1) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($salary_detail->overtime_salary_trial) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ $salary_detail->overtime_salary_trial_notice }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($salary_detail->number_of_work) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($salary_detail->allowance_apprentice_detail) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ $salary_detail->allowance_apprentice_detail_notice }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($salary_detail->core_hours, 1) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($salary_detail->official_salary) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ $salary_detail->official_salary_notice }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($salary_detail->number_of_hours_worked) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($salary_detail->allowance_diligence_detail) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ $salary_detail->allowance_diligence_detail_notice }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($salary_detail->number_of_jobs) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($salary_detail->allowance_responsibility_detail) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ $salary_detail->allowance_responsibility_detail_notice }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($salary_detail->overtime_hours_detail, 1) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($salary_detail->overtime_salary, 1) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ $salary_detail->overtime_salary_notice }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($salary_detail->number_of_work_days) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($salary_detail->allowance_rice_detail) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ $salary_detail->allowance_rice_detail_notice }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($salary_detail->number_of_work_nights) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($salary_detail->allowance_shift_night) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ $salary_detail->allowance_shift_night_notice }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($salary_detail->overtime_day_count_detail, 1) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($salary_detail->allowance_overtime_detail, 1) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ $salary_detail->allowance_overtime_detail_notice }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($salary_detail->holidays_count_detail, 1) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($salary_detail->holidays_money, 1) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ $salary_detail->holidays_money_notice }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($salary_detail->paid_holidays_count_detail, 1) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($salary_detail->paid_holidays_money, 1) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ $salary_detail->paid_holidays_money_notice }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($salary_detail->business_travel_hours) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($salary_detail->business_travel_unit_price_hour, 1) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($salary_detail->gcn_business_travel_salary) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ $salary_detail->gcn_business_travel_salary_notice }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($salary_detail->number_of_business_trips) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($salary_detail->business_fuel_unit_price_day, 1) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($salary_detail->allowance_gcn_business_fuel) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ $salary_detail->allowance_gcn_business_fuel_notice }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($salary_detail->money_referral_people) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ $salary_detail->money_referral_people_notice }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($salary_detail->allowance_diffrent) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ $salary_detail->allowance_diffrent_notice }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($salary_detail->bonuses_for_attendance) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ $salary_detail->bonuses_for_attendance_notice }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($salary_detail->sickness) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ $salary_detail->sickness_notice }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($salary_detail->funeral) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ $salary_detail->funeral_notice }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($salary_detail->birthday_money) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ $salary_detail->birthday_money_notice }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($salary_detail->previous_period_debt) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ $salary_detail->previous_period_debt_notice }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($salary_detail->total_income) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($salary_detail->insurance_detail) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ $salary_detail->insurance_detail_notice }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($salary_detail->advance_money, 1) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ $salary_detail->advance_money_notice }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($salary_detail->number_of_violations) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($salary_detail->unicon_deduction) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ $salary_detail->unicon_deduction_notice }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($salary_detail->daysleave_allowed) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($salary_detail->subtract_daysleave_allowed) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ $salary_detail->subtract_daysleave_allowed_notice }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($salary_detail->daysleave_notallowed) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($salary_detail->subtract_daysleave_notallowed) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ $salary_detail->subtract_daysleave_notallowed_notice }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($salary_detail->error_serious) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($salary_detail->subtract_error_serious) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ $salary_detail->subtract_error_serious_notice }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($salary_detail->error_minor) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ number_format($salary_detail->subtract_error_minor) }}
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        {{ $salary_detail->subtract_error_minor_notice }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($salary_detail->kpi_subtraction) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ $salary_detail->kpi_subtraction_notice }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($salary_detail->actually_received) }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ $salary_detail->forms_of_payment }}
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        {{ number_format($salary_detail->company_insurance_detail) }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="d-flex justify-content-center">
                                                    {{ $leafTab['data']->appends(request()->all())->links() }}
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="timekeeping{{ $keyRoot }}"
                                                role="tabpanel" aria-labelledby="timekeeping{{ $keyRoot }}-tab">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered">
                                                        <thead class="table-light text-center align-middle">
                                                            <tr>
                                                                <th rowspan="2">
                                                                    STT
                                                                </th>
                                                                <th rowspan="2">
                                                                    MS NV
                                                                </th>
                                                                <th rowspan="2">
                                                                    Họ và tên
                                                                </th>
                                                                <th rowspan="2">
                                                                    Bộ phận
                                                                </th>
                                                                <th rowspan="2" class="bg-3">
                                                                    Số giờ làm
                                                                    ngày
                                                                </th>
                                                                <th rowspan="2" class="bg-3">
                                                                    Số giờ làm
                                                                    đêm
                                                                </th>
                                                                <th rowspan="2" class="bg-3">
                                                                    Số giờ tăng
                                                                    ca
                                                                </th>
                                                                <th rowspan="2" class="test-bg">
                                                                    Tính lương
                                                                    ngày
                                                                </th>
                                                                <th rowspan="2" class="test-bg">
                                                                    Tính lương
                                                                    đêm
                                                                </th>
                                                                <th rowspan="2" class="test-bg">
                                                                    Tăng ca thử
                                                                    việc
                                                                </th>
                                                                <th rowspan="2" class="bg-2">
                                                                    Phụ cấp tiền
                                                                    cơm ngày
                                                                </th>
                                                                <th rowspan="2" class="bg-2">
                                                                    Phụ cấp ca
                                                                    đêm
                                                                </th>
                                                                <th rowspan="2" class="bg-2">
                                                                    Phụ cấp tăng
                                                                    ca
                                                                </th>
                                                                @foreach ($leafTab['data'] as $key => $attendance)
                                                                    @foreach ($attendance['SalaryOfficial' . $keyRoot . 'Timekeepings'] as $key => $attendanceTimekeeping)
                                                                        <th colspan="3">
                                                                            {{ date('d/m/Y', strtotime($attendanceTimekeeping->timekeeping_date)) }}
                                                                        </th>
                                                                    @endforeach
                                                                @break
                                                            @endforeach

                                                            <th rowspan="2">
                                                                Số ngày
                                                                <br />
                                                                lễ , tết
                                                            </th>
                                                            <th rowspan="2">
                                                                Số ngày
                                                                <br />
                                                                phép năm
                                                            </th>
                                                            <th rowspan="2">
                                                                Có phép
                                                            </th>
                                                            <th rowspan="2">
                                                                Không phép
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            @foreach ($leafTab['data'] as $key => $attendance)
                                                                @foreach ($attendance['SalaryOfficial' . $keyRoot . 'Timekeepings'] as $key => $attendanceTimekeeping)
                                                                    <th class="bg-4">
                                                                        Ngày
                                                                    </th>
                                                                    <th class="bg-2">
                                                                        Đêm
                                                                    </th>
                                                                    <th class="bg-3">
                                                                        TC
                                                                    </th>
                                                                @endforeach
                                                            @break
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($leafTab['data'] as $key => $attendance)
                                                        <tr>
                                                            <td>
                                                                {{ $loop->iteration }}
                                                            </td>
                                                            <td>
                                                                {{ $attendance->employee->code }}
                                                            </td>
                                                            <td>
                                                                {{ $attendance->employee->name }}
                                                            </td>
                                                            <td>
                                                                {{ $attendance->employee->role->role_name }}
                                                            </td>
                                                            <td class="bg-3">
                                                                {{ number_format($attendance->total_day_offical, 1) }}
                                                            </td>
                                                            <td class="bg-3">
                                                                {{ number_format($attendance->total_night_offical, 1) }}
                                                            </td>
                                                            <td class="bg-3">
                                                                {{ number_format($attendance->total_overtime_offical, 1) }}
                                                            </td>
                                                            <td class="test-bg">
                                                                {{ number_format($attendance->workday_count_trial, 1) }}
                                                            </td>
                                                            <td class="test-bg">
                                                                {{ number_format($attendance->worknight_count_trial, 1) }}
                                                            </td>
                                                            <td class="test-bg">
                                                                {{ number_format($attendance->overtime_day_count_trial, 1) }}
                                                            </td>
                                                            <td class="bg-2">
                                                                {{ number_format($attendance->allowance_rice_day_timekeeping, 1) }}
                                                            </td>
                                                            <td class="bg-2">
                                                                {{ number_format($attendance->allowance_rice_night_timekeeping, 1) }}
                                                            </td>
                                                            <td class="bg-2">
                                                                {{ number_format($attendance->allowance_overtime_timekeeping, 1) }}
                                                            </td>
                                                            @foreach ($attendance['SalaryOfficial' . $keyRoot . 'Timekeepings'] as $key => $attendanceTimekeeping)
                                                                <td class="bg-4">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($attendanceTimekeeping->timekeeping_day, 2) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($attendanceTimekeeping->timekeeping_night, 2) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($attendanceTimekeeping->timekeeping_overtime, 2) }}
                                                                    </div>
                                                                </td>
                                                            @endforeach

                                                            <td>
                                                                {{ number_format($attendance->holidays_count) }}
                                                            </td>
                                                            <td>
                                                                {{ number_format($attendance->paid_holidays_count) }}
                                                            </td>
                                                            <td>
                                                                {{ number_format($attendance->daysleave_allowed_timekeeping) }}
                                                            </td>
                                                            <td>
                                                                {{ number_format($attendance->daysleave_notallowed_timekeeping) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            {{ $leafTab['data']->appends(request()->all())->links() }}
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
</div>
</div>
@endsection
