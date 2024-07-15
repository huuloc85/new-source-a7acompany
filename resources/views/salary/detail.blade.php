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

        .test-bg {
            background-color: #eaf8d2 !important
        }

        .bg-2 {
            background-color: #ebebeb !important
        }

        .bg-3 {
            background-color: #919cc9 !important
        }

        .bg-4 {
            background-color: #d7a95f !important
        }

        .pd-0 {
            padding: 0px !important
        }

        .mg-top-9 {
            margin-top: 9px;
        }

        .heigh-31 {
            height: 31px !important;
        }

        .pd-top-8 {
            padding-top: 8px;
        }

        .check-w {
            max-width: 70px !important;
        }

        .salary-detail-css {
            overflow: auto
        }

        .category-right {
            overflow: auto;
        }

        .timekeeping-right {
            overflow: auto
        }

        .timekeeping-left {
            max-width: 100% !important;
        }

        .table-responsive {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
        }

        .category-left,
        .category-right {
            flex: 0 0 auto;
        }

        .timekeeping-left,
        .timekeeping-right {
            flex: 0 0 auto;
        }

        .table {
            min-width: 100%;
        }

        @media (max-width: 768px) {

            .category-left,
            .category-right {
                flex: 0 0 auto;
                min-width: 500px;
            }

            .timekeeping-left,
            .timekeeping-right {
                flex: 0 0 auto;
                min-width: 2000px;
            }

            .category-right {
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
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
                    <a class="btn btn-success" href="{{ route('admin.salary.home') }}">Danh sách bảng lương</a>
                </div>
                <div class="px-0 pb-2">
                    <ul class="nav nav-tabs px-4" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link category active" id="VVP-tab" data-bs-toggle="tab"
                                data-bs-target="#VVP" type="button" role="tab" aria-controls="VVP"
                                aria-selected="true">VVP</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link category" id="A7A-tab" data-bs-toggle="tab" data-bs-target="#A7A"
                                type="button" role="tab" aria-controls="A7A" aria-selected="false">A7A</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active category" id="VVP" role="tabpanel"
                            aria-labelledby="VVP-tab">
                            <div class="px-0 pb-2">
                                <ul class="nav nav-tabs px-4" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link tab-vvp active" id="categoryVVP-tab" data-bs-toggle="tab"
                                            data-bs-target="#categoryVVP" type="button" role="tab"
                                            aria-controls="categoryVVP" aria-selected="true">Danh mục</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link tab-vvp" id="salaryVVP-tab" data-bs-toggle="tab"
                                            data-bs-target="#salaryVVP" type="button" role="tab"
                                            aria-controls="salaryVVP" aria-selected="false">Bảng lương thanh toán</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link tab-vvp" id="salary-detailVVP-tab" data-bs-toggle="tab"
                                            data-bs-target="#salary-detailVVP" type="button" role="tab"
                                            aria-controls="salary-detailVVP" aria-selected="false">Bảng lương chi
                                            tiết</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link tab-vvp" id="timekeepingVVP-tab" data-bs-toggle="tab"
                                            data-bs-target="#timekeepingVVP" type="button" role="tab"
                                            aria-controls="timekeepingVVP" aria-selected="false">Bảng chấm công</button>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane tab-vvp fade show active" id="categoryVVP" role="tabpanel"
                                        aria-labelledby="categoryVVP-tab">
                                        <div class="px-0 pb-2">
                                            <div class="table-responsive p-0 d-flex flex-nowrap">
                                                <div class="col-4 category-left">
                                                    <table class="table align-items-center mb-0 table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                                    rowspan="2">STT</th>
                                                                <th class="text-uppercase text-xxs font-weight-bolder col-3"
                                                                    rowspan="2">MS NV</th>
                                                                <th class="text-uppercase text-xxs font-weight-bolder ps-2 col-4"
                                                                    rowspan="2">Họ và tên</th>
                                                                <th class="text-uppercase text-xxs font-weight-bolder ps-2 col-3"
                                                                    rowspan="2">Bộ phận</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($salaryOfficialsVVP as $key => $salaryOfficialVVP)
                                                                <tr>
                                                                    <td>
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $loop->iteration }}</div>
                                                                    </td>
                                                                    <td>
                                                                        <p class="text-xs font-weight-bold mb-0 px-3">
                                                                            {{ $salaryOfficialVVP->employee->code }}</p>
                                                                    </td>
                                                                    <td>
                                                                        <p class="text-xs font-weight-bold mb-0">
                                                                            {{ $salaryOfficialVVP->employee->name }}</p>
                                                                    </td>
                                                                    <td>
                                                                        <p class="text-xs font-weight-bold mb-0">
                                                                            {{ $salaryOfficialVVP->employee->role->role_name }}
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-8 category-right">
                                                    <table class="table align-items-center mb-0 table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Lương ngày (áp dụng tháng đầu)</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Lương đêm (áp dụng tháng đầu)</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 bg-3">
                                                                    Lương CB thử việc / 26 ngày</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 bg-2">
                                                                    Lương CB thử việc / 1 giờ</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 bg-3">
                                                                    Lương CB thử việc tăng ca / 1 giờ</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 bg-2">
                                                                    Phụ cấp học việc</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 bg-3">
                                                                    Lương CB chính thức/ 26 ngày</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 bg-2">
                                                                    Lương CB/ giờ</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 bg-3">
                                                                    Lương TC/ giờ</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 bg-2">
                                                                    Chuyên cần</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 bg-3">
                                                                    Trách nhiệm</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 bg-2">
                                                                    Phụ cấp tăng ca/ ngày</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 bg-3">
                                                                    Phụ cấp đêm</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 bg-2">
                                                                    Phụ cấp cơm trưa</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 bg-3">
                                                                    BHXH công ty đóng</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 bg-2">
                                                                    BHXH người lao động đóng</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($salaryOfficialsVVP as $key => $salaryOfficialVVP)
                                                                <tr>
                                                                    <td class="bg-3">
                                                                        <div class="text-center px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->salary_day) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="px-3 py-1 text-center">
                                                                            {{ number_format($salaryOfficialVVP->salary_night) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="text-center px-3 py-1">
                                                                            {{ number_format(floatval($salaryOfficialVVP->probationary_salary_basic_26days)) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="text-center px-3 py-1">
                                                                            {{ number_format(floatval($salaryOfficialVVP->probationary_salary_basic_hours)) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="text-center px-3 py-1">
                                                                            {{ number_format(floatval($salaryOfficialVVP->probationary_salary_basic_extra_hours)) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="text-center px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->allowance_apprentice) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="text-center px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->salary_basic) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="text-center px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->regular_salary_hour) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="text-center px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->salary_overtime) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="text-center px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->allowance_diligence) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="text-center px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->allowance_responsibility) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="text-center px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->allowance_overtime) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="text-center px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->allowance_night) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="text-center px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->allowance_rice) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="text-center px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->company_insurance) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="text-center px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->insurance) }}
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div
                                                style="display: flex; justify-content: center; align-items: center; margin:20px">
                                                <div>
                                                    {{ $salaryOfficialsVVP->appends(request()->all())->links() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane tab-vvp fade" id="salaryVVP" role="tabpanel"
                                        aria-labelledby="salaryVVP-tab">
                                        <div class="px-0 pb-2">
                                            <div class="table-responsive p-0 d-flex">
                                                <table
                                                    class="table align-items-center mb-0 table-hover salary-detail-css">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-uppercase text-xxs font-weight-bolder col-1">
                                                                STT</th>
                                                            <th class="text-uppercase text-xxs font-weight-bolder">MS NV
                                                            </th>
                                                            <th class="text-uppercase text-xxs font-weight-bolder ps-2">Họ
                                                                và tên</th>
                                                            <th class="text-uppercase text-xxs font-weight-bolder ps-2">Bộ
                                                                phận</th>
                                                            <th class="text-uppercase text-xxs font-weight-bolder ps-2">
                                                                Tổng lương</th>
                                                            <th class="text-uppercase text-xxs font-weight-bolder ps-2">Trừ
                                                                bảo hiểm</th>
                                                            <th class="text-uppercase text-xxs font-weight-bolder ps-2">Tạm
                                                                ứng</th>
                                                            <th class="text-uppercase text-xxs font-weight-bolder ps-2">BH
                                                                công ty đóng(21%)</th>
                                                            <th class="text-uppercase text-xxs font-weight-bolder ps-2">KPI
                                                            </th>
                                                            <th class="text-uppercase text-xxs font-weight-bolder ps-2">Nợ
                                                                Kỳ Trước
                                                            </th>
                                                            <th class="text-uppercase text-xxs font-weight-bolder ps-2">
                                                                Thực lãnh</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($salaryOfficialsVVP as $key => $salaryOfficialVVP)
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $loop->iteration }}
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <p class="text-xs font-weight-bold mb-0 px-3">
                                                                        {{ $salaryOfficialVVP->employee->code }}</p>
                                                                </td>
                                                                <td>
                                                                    <p class="text-xs font-weight-bold mb-0">
                                                                        {{ $salaryOfficialVVP->employee->name }}</p>
                                                                </td>
                                                                <td>
                                                                    <p class="text-xs font-weight-bold mb-0">
                                                                        {{ $salaryOfficialVVP->employee->role->role_name }}
                                                                    </p>
                                                                </td>
                                                                <td>
                                                                    <p class="text-xs font-weight-bold mb-0">
                                                                        {{ number_format($salaryOfficialVVP->salary_total) }}
                                                                    </p>
                                                                </td>
                                                                <td>
                                                                    <p class="text-xs font-weight-bold mb-0">
                                                                        {{ number_format($salaryOfficialVVP->insurance_payroll) }}
                                                                    </p>
                                                                </td>
                                                                <td>
                                                                    <p class="text-xs font-weight-bold mb-0">
                                                                        {{ number_format($salaryOfficialVVP->advance_money_payroll) }}
                                                                    </p>
                                                                </td>
                                                                <td>
                                                                    <p class="text-xs font-weight-bold mb-0">
                                                                        {{ number_format($salaryOfficialVVP->company_insurance_payroll) }}
                                                                    </p>
                                                                </td>
                                                                <td>
                                                                    <p class="text-xs font-weight-bold mb-0">
                                                                        {{ number_format($salaryOfficialVVP->KPI_Subtraction_payroll) }}
                                                                    </p>
                                                                </td>
                                                                <td>
                                                                    <p class="text-xs font-weight-bold mb-0">
                                                                        {{ number_format($salaryOfficialVVP->previous_period_debt_payroll) }}
                                                                    </p>
                                                                </td>
                                                                <td>
                                                                    <p class="text-xs font-weight-bold mb-0">
                                                                        {{ number_format($salaryOfficialVVP->actually_received_payroll) }}
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div
                                            style="display: flex; justify-content: center; align-items: center; margin:20px">
                                            <div>
                                                {{ $salaryOfficialsVVP->appends(request()->all())->links() }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane tab-vvp fade" id="salary-detailVVP" role="tabpanel"
                                        aria-labelledby="salary-detailVVP-tab">
                                        <div class="px-0 pb-2">
                                            <div class="table-responsive p-0 d-flex">
                                                <div class="col-4 category-left">
                                                    <table class="table align-items-center mb-0 table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1">
                                                                    STT</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-2">
                                                                    MS NV</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder ps-2 col-2">
                                                                    Họ và tên</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder ps-2 col-2">
                                                                    Bộ phận</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($salaryOfficialsVVP as $key => $salaryOfficialVVP)
                                                                <tr>
                                                                    <td>
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $loop->iteration }}
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <p class="text-xs font-weight-bold mb-0 px-3">
                                                                            {{ $salaryOfficialVVP->employee->code }}</p>
                                                                    </td>
                                                                    <td>
                                                                        <p class="text-xs font-weight-bold mb-0">
                                                                            {{ $salaryOfficialVVP->employee->name }}</p>
                                                                    </td>
                                                                    <td>
                                                                        <p class="text-xs font-weight-bold mb-0">
                                                                            {{ $salaryOfficialVVP->employee->role->role_name }}
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-8 category-right">
                                                    <table class="table align-items-center mb-0 table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Số công ngày</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Lương ca ngày (thử việc)</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Ghi chú</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Số công đêm
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Lương ca đêm (thử việc)</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Ghi chú</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Số giờ tăng ca ( thử việc)</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Lương tăng ca (thử việc)</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Ghi chú</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Số công</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Phụ cấp học việc</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Ghi chú</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Số giờ chính</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Lương chính thức</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Ghi chú</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Chuyên cần</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Số công làm
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Ghi chú</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Số công làm
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Trách nhiệm</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Ghi chú</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Số giờ tăng ca</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Lương tăng ca</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Ghi chú</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Số công ngày</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Phụ cấp cơm ca ngày</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Ghi chú</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Số công đêm</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Phụ cấp ca đêm</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Ghi chú</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Số ngày tăng ca</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Phụ cấp tăng ca
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Ghi chú</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Số ngày lễ tết</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Tiền lễ tết</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Ghi chú
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Phép năm</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3 ">
                                                                    Tiền phép năm</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Ghi chú
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Số giờ đi công tác
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Đơn giá đi công tác/ giờ
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Lương đi công tác GCN
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Ghi chú
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Số lần đi công tác
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Đơn giá xăng công tác/ ngày
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Phụ cấp xăng đi GCN
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Ghi chú
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Tiền giới thiệu người
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Ghi chú
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Phụ cấp khác
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Ghi chú
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Tiền thưởng đạt chuyên cần
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Ghi chú
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Ốm đau
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Ghi chú
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Ma chay
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Ghi chú
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Tiền sinh nhật
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Ghi chú
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Tiền lương tháng trước bị thiếu
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Ghi chú
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Tổng thu nhập
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Khấu trừ BHXH 10.5%
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Ghi chú
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Tạm ứng
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Ghi chú
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Số lần vi phạm
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Trừ vi phạm
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Ghi chú
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Số nghỉ có phép
                                                                </th>

                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Trừ tiền
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Ghi chú
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Số nghỉ không có phép
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Trừ tiền
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Ghi chú
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Số lỗi nặng
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Trừ tiền
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Ghi chú
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Ghi chú Số lỗi nhẹ
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Trừ tiền
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                    Ghi chú
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Trừ KPI
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Ghi chú
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Thực lãnh
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    Hình thức thanh toán
                                                                </th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                    BHXH (21.5%) công ty đóng cho NLĐ
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($salaryOfficialsVVP as $key => $salaryOfficialVVP)
                                                                <tr>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->number_of_work_days_trial) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->day_shift_salary_trial) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->day_shift_salary_trial_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->number_of_work_nights_trial) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->night_shift_salary_trial) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->night_shift_salary_trial_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->overtime_hours_trial, 1) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->overtime_salary_trial) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->overtime_salary_trial_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->number_of_work) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->allowance_apprentice_detail) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->allowance_apprentice_detail_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->core_hours, 1) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->official_salary) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->official_salary_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->number_of_hours_worked) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->allowance_diligence_detail) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->allowance_diligence_detail_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->number_of_jobs) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->allowance_responsibility_detail) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->allowance_responsibility_detail_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->overtime_hours_detail, 1) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->overtime_salary, 1) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->overtime_salary_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->number_of_work_days) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->allowance_rice_detail) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->allowance_rice_detail_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->number_of_work_nights) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->allowance_shift_night) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->allowance_shift_night_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->overtime_day_count_detail, 1) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->allowance_overtime_detail, 1) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->allowance_overtime_detail_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->holidays_count_detail, 1) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->holidays_money, 1) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->holidays_money_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->paid_holidays_count_detail, 1) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->paid_holidays_money, 1) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->paid_holidays_money_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->business_travel_hours) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->business_travel_unit_price_hour, 1) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->gcn_business_travel_salary) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->gcn_business_travel_salary_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->number_of_business_trips) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->business_fuel_unit_price_day, 1) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->allowance_gcn_business_fuel) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->allowance_gcn_business_fuel_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->money_referral_people) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->money_referral_people_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->allowance_diffrent) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->allowance_diffrent_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->bonuses_for_attendance) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->bonuses_for_attendance_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->sickness) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->sickness_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->funeral) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->funeral_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->birthday_money) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->birthday_money_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->previous_period_debt) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->previous_period_debt_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->total_income) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->insurance_detail) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->insurance_detail_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->advance_money, 1) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->advance_money_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->number_of_violations) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->subtract_of_violations) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->subtract_of_violations_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->daysleave_allowed) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->subtract_daysleave_allowed) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->subtract_daysleave_allowed_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->daysleave_notallowed) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->subtract_daysleave_notallowed) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->subtract_daysleave_notallowed_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->error_serious) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->subtract_error_serious) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->subtract_error_serious_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->error_minor) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->subtract_error_minor) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->subtract_error_minor_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->kpi_subtraction) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->kpi_subtraction_notice }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->actually_received) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $salaryOfficialVVP->forms_of_payment }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->company_insurance_detail) }}
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div
                                                style="display: flex; justify-content: center; align-items: center; margin:20px">
                                                <div>
                                                    {{ $salaryOfficialsVVP->appends(request()->all())->links() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane tab-vvp fade" id="timekeepingVVP" role="tabpanel"
                                        aria-labelledby="timekeepingVVP-tab">
                                        <div class="px-0 pb-2">
                                            <div class="table-responsive p-0 d-flex flex-nowrap">
                                                <div class="col-11 timekeeping-left">
                                                    <table class="table align-items-center mb-0 table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1">
                                                                    STT</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-2">
                                                                    MS NV</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder ps-2 col-2">
                                                                    Họ và tên</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder ps-2 col-2">
                                                                    Bộ phận</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder ps-2 col-2 bg-3">
                                                                    Số giờ làm ngày</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder ps-2 col-2 bg-3">
                                                                    Số giờ làm đêm</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder ps-2 col-2 bg-3">
                                                                    Số giờ tăng ca</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder ps-2 col-2 test-bg">
                                                                    Tính lương ngày</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder ps-2 col-2 test-bg">
                                                                    Tính lương đêm</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder ps-2 col-2 test-bg">
                                                                    Tăng ca thử việc</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder ps-2 col-2 bg-2">
                                                                    Phụ cấp tiền cơm ngày</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder ps-2 col-2 bg-2">
                                                                    Phụ cấp ca đêm</th>
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder ps-2 col-2 bg-2">
                                                                    Phụ cấp tăng ca</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($salaryOfficialsVVP as $key => $salaryOfficialVVP)
                                                                <tr>
                                                                    <td>
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ $loop->iteration }}
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <p class="text-xs font-weight-bold mb-0 px-3">
                                                                            {{ $salaryOfficialVVP->employee->code }}</p>
                                                                    </td>
                                                                    <td>
                                                                        <p class="text-xs font-weight-bold mb-0">
                                                                            {{ $salaryOfficialVVP->employee->name }}</p>
                                                                    </td>
                                                                    <td>
                                                                        <p class="text-xs font-weight-bold mb-0">
                                                                            {{ $salaryOfficialVVP->employee->role->role_name }}
                                                                        </p>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->total_day_offical, 1) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->total_night_offical, 1) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->total_overtime_offical, 1) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="test-bg">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->workday_count_trial, 1) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="test-bg">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->worknight_count_trial, 1) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="test-bg">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->overtime_day_count_trial, 1) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->allowance_rice_day_timekeeping, 1) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->allowance_rice_night_timekeeping, 1) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($salaryOfficialVVP->allowance_overtime_timekeeping, 1) }}
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-7 timekeeping-right">
                                                    <table class="table align-items-center mb-0 table-hover">
                                                        <thead>
                                                            <tr>
                                                                @foreach ($salaryOfficialsVVP as $key => $salaryOfficialVVP)
                                                                    @foreach ($salaryOfficialVVP->SalaryOfficialVVPTimekeepings as $key => $SalaryOfficialVVPTimekeeping)
                                                                        <th colspan="3"
                                                                            class="text-uppercase text-center text-xxs font-weight-bolder col-1 pd-0">
                                                                            {{ date('d/m/Y', strtotime($SalaryOfficialVVPTimekeeping->timekeeping_date)) }}<br>
                                                                            <div class="d-flex mg-top-9">
                                                                                <div class="col-4 bg-4 heigh-31 pd-top-8">
                                                                                    Ngày</div>
                                                                                <div class="col-4 bg-2 pd-top-8">Đêm</div>
                                                                                <div class="col-4 bg-3 pd-top-8">TC</div>
                                                                            </div>
                                                                        </th>
                                                                    @endforeach
                                                                @break
                                                            @endforeach
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1">
                                                                Số ngày<br>
                                                                <div class="text-center">lễ , tết</div>
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1">
                                                                Số ngày<br>
                                                                <div class="text-center">phép năm</div>
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1">
                                                                <div class="text-center">Có phép</div>
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1">
                                                                <div class="text-center">Không phép</div>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($salaryOfficialsVVP as $key => $salaryOfficialVVP)
                                                            <tr>
                                                                @foreach ($salaryOfficialVVP->SalaryOfficialVVPTimekeepings as $key => $SalaryOfficialVVPTimekeeping)
                                                                    <td class="bg-4 check-w">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($SalaryOfficialVVPTimekeeping->timekeeping_day, 2) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-2 check-w">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($SalaryOfficialVVPTimekeeping->timekeeping_night, 2) }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="bg-3 check-w">
                                                                        <div class="d-flex px-3 py-1">
                                                                            {{ number_format($SalaryOfficialVVPTimekeeping->timekeeping_overtime, 2) }}
                                                                        </div>
                                                                    </td>
                                                                @endforeach
                                                                <td>
                                                                    <div class="d-flex px-3 py-1 text-center">
                                                                        {{ number_format($salaryOfficialVVP->holidays_count) }}
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex px-3 py-1 text-center">
                                                                        {{ number_format($salaryOfficialVVP->paid_holidays_count) }}
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex px-3 py-1 text-center">
                                                                        {{ number_format($salaryOfficialVVP->daysleave_allowed_timekeeping) }}
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex px-3 py-1 text-center">
                                                                        {{ number_format($salaryOfficialVVP->daysleave_notallowed_timekeeping) }}
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div
                                            style="display: flex; justify-content: center; align-items: center; margin:20px">
                                            <div>
                                                {{ $salaryOfficialsVVP->appends(request()->all())->links() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade category" id="A7A" role="tabpanel" aria-labelledby="A7A-tab">
                        <div class="px-0 pb-2">
                            <ul class="nav nav-tabs px-4" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link tab-a7a active" id="categoryA7A-tab" data-bs-toggle="tab"
                                        data-bs-target="#categoryA7A" type="button" role="tab"
                                        aria-controls="categoryA7A" aria-selected="true">Danh mục</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link tab-a7a" id="salaryA7A-tab" data-bs-toggle="tab"
                                        data-bs-target="#salaryA7A" type="button" role="tab"
                                        aria-controls="salaryA7A" aria-selected="false">Bảng lương thanh toán</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link tab-a7a" id="salary-detailA7A-tab" data-bs-toggle="tab"
                                        data-bs-target="#salary-detailA7A" type="button" role="tab"
                                        aria-controls="salary-detailA7A" aria-selected="false">Bảng lương chi
                                        tiết</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link tab-a7a" id="timekeepingA7A-tab" data-bs-toggle="tab"
                                        data-bs-target="#timekeepingA7A" type="button" role="tab"
                                        aria-controls="timekeepingA7A" aria-selected="false">Bảng chấm công</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane tab-a7a fade show active" id="categoryA7A" role="tabpanel"
                                    aria-labelledby="categoryA7A-tab">
                                    <div class="px-0 pb-2">
                                        <div class="table-responsive p-0 d-flex">
                                            <div class="col-4 category-left">
                                                <table class="table align-items-center mb-0 table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                                rowspan="2">STT</th>
                                                            <th class="text-uppercase text-xxs font-weight-bolder col-3"
                                                                rowspan="2">MS NV</th>
                                                            <th class="text-uppercase text-xxs font-weight-bolder ps-2 col-4"
                                                                rowspan="2">Họ và tên</th>
                                                            <th class="text-uppercase text-xxs font-weight-bolder ps-2 col-3"
                                                                rowspan="2">Bộ phận</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($salaryOfficialsA7A as $key => $salaryOfficialA7A)
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $loop->iteration }}
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <p class="text-xs font-weight-bold mb-0 px-3">
                                                                        {{ $salaryOfficialA7A->employee->code }}</p>
                                                                </td>
                                                                <td>
                                                                    <p class="text-xs font-weight-bold mb-0">
                                                                        {{ $salaryOfficialA7A->employee->name }}</p>
                                                                </td>
                                                                <td>
                                                                    <p class="text-xs font-weight-bold mb-0">
                                                                        {{ $salaryOfficialA7A->employee->role->role_name }}
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-8 category-right">
                                                <table class="table align-items-center mb-0 table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Lương ngày (áp dụng tháng đầu)</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Lương đêm (áp dụng tháng đầu)</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 bg-3">
                                                                Lương CB thử việc / 26 ngày</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 bg-2">
                                                                Lương CB thử việc / 1 giờ</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 bg-3">
                                                                Lương CB thử việc tăng ca / 1 giờ</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 bg-2">
                                                                Phụ cấp học việc</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 bg-3">
                                                                Lương CB chính thức/ 26 ngày</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 bg-2">
                                                                Lương CB/ giờ</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 bg-3">
                                                                Lương TC/ giờ</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 bg-2">
                                                                Chuyên cần</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 bg-3">
                                                                Trách nhiệm</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 bg-2">
                                                                Phụ cấp tăng ca/ ngày</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 bg-3">
                                                                Phụ cấp đêm</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 bg-2">
                                                                Phụ cấp cơm trưa</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 bg-3">
                                                                BHXH công ty đóng</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 bg-2">
                                                                BHXH người lao động đóng</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($salaryOfficialsA7A as $key => $salaryOfficialA7A)
                                                            <tr>
                                                                <td class="bg-3">
                                                                    <div class="text-center px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->salary_day) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="px-3 py-1 text-center">
                                                                        {{ number_format($salaryOfficialA7A->salary_night) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="text-center px-3 py-1">
                                                                        {{ number_format(floatval($salaryOfficialA7A->probationary_salary_basic_26days)) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="text-center px-3 py-1">
                                                                        {{ number_format(floatval($salaryOfficialA7A->probationary_salary_basic_hours)) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="text-center px-3 py-1">
                                                                        {{ number_format(floatval($salaryOfficialA7A->probationary_salary_basic_extra_hours)) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="text-center px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->allowance_apprentice) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="text-center px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->salary_basic) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="text-center px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->regular_salary_hour) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="text-center px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->salary_overtime) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="text-center px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->allowance_diligence) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="text-center px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->allowance_responsibility) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="text-center px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->allowance_overtime) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="text-center px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->allowance_night) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="text-center px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->allowance_rice) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="text-center px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->company_insurance) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2 ">
                                                                    <div class="text-center px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->insurance) }}
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                        {{-- <div
                                        style="display: flex; justify-content: center; align-items: center; margin:20px">
                                        <div>
                                            {{ $salaryOfficialsA7A->appends(request()->all())->links() }}
                                        </div>
                                    </div> --}}
                                    </div>
                                </div>
                                <div class="tab-pane tab-a7a fade" id="salaryA7A" role="tabpanel"
                                    aria-labelledby="salaryA7A-tab">
                                    <div class="px-0 pb-2">
                                        <div class="table-responsive p-0 d-flex salary-detail-css">
                                            <table
                                                class="table align-items-center mb-0 table-hover salary-detail-css">
                                                <thead>
                                                    <tr>
                                                        <th class="text-uppercase text-xxs font-weight-bolder col-1">
                                                            STT</th>
                                                        <th class="text-uppercase text-xxs font-weight-bolder">MS NV
                                                        </th>
                                                        <th class="text-uppercase text-xxs font-weight-bolder ps-2">Họ
                                                            và tên</th>
                                                        <th class="text-uppercase text-xxs font-weight-bolder ps-2">Bộ
                                                            phận</th>
                                                        <th class="text-uppercase text-xxs font-weight-bolder ps-2">
                                                            Tổng lương</th>
                                                        <th class="text-uppercase text-xxs font-weight-bolder ps-2">Trừ
                                                            bảo hiểm</th>
                                                        <th class="text-uppercase text-xxs font-weight-bolder ps-2">Tạm
                                                            ứng</th>
                                                        <th class="text-uppercase text-xxs font-weight-bolder ps-2">BH
                                                            công ty đóng(21%)</th>
                                                        <th class="text-uppercase text-xxs font-weight-bolder ps-2">KPI
                                                        </th>
                                                        <th class="text-uppercase text-xxs font-weight-bolder ps-2">Nợ
                                                            Kỳ Trước
                                                        </th>
                                                        <th class="text-uppercase text-xxs font-weight-bolder ps-2">
                                                            Thực lãnh</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($salaryOfficialsA7A as $key => $salaryOfficialA7A)
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-3 py-1">
                                                                    {{ $loop->iteration }}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs font-weight-bold mb-0 px-3">
                                                                    {{ $salaryOfficialA7A->employee->code }}</p>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs font-weight-bold mb-0">
                                                                    {{ $salaryOfficialA7A->employee->name }}</p>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs font-weight-bold mb-0">
                                                                    {{ $salaryOfficialA7A->employee->role->role_name }}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs font-weight-bold mb-0">
                                                                    {{ number_format($salaryOfficialA7A->salary_total) }}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs font-weight-bold mb-0">
                                                                    {{ number_format($salaryOfficialA7A->insurance_payroll) }}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs font-weight-bold mb-0">
                                                                    {{ number_format($salaryOfficialA7A->advance_money_payroll) }}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs font-weight-bold mb-0">
                                                                    {{ number_format($salaryOfficialA7A->company_insurance_payroll) }}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs font-weight-bold mb-0">
                                                                    {{ number_format($salaryOfficialA7A->KPI_Subtraction_payroll) }}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs font-weight-bold mb-0">
                                                                    {{ number_format($salaryOfficialA7A->previous_period_debt_payroll) }}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs font-weight-bold mb-0">
                                                                    {{ number_format($salaryOfficialA7A->actually_received_payroll) }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    {{-- <div
                                    style="display: flex; justify-content: center; align-items: center; margin:20px">
                                    <div>
                                        {{ $salaryOfficialsA7A->appends(request()->all())->links() }}
                                    </div>
                                </div> --}}
                                </div>
                                <div class="tab-pane tab-a7a fade" id="salary-detailA7A" role="tabpanel"
                                    aria-labelledby="salary-detailA7A-tab">
                                    <div class="px-0 pb-2">
                                        <div class="table-responsive p-0 d-flex">
                                            <div class="col-4 category-left">
                                                <table class="table align-items-center mb-0 table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1">
                                                                STT</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-2">
                                                                MS NV</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder ps-2 col-2">
                                                                Họ và tên</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder ps-2 col-2">
                                                                Bộ phận</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($salaryOfficialsA7A as $key => $salaryOfficialA7A)
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $loop->iteration }}
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <p class="text-xs font-weight-bold mb-0 px-3">
                                                                        {{ $salaryOfficialA7A->employee->code }}</p>
                                                                </td>
                                                                <td>
                                                                    <p class="text-xs font-weight-bold mb-0">
                                                                        {{ $salaryOfficialA7A->employee->name }}</p>
                                                                </td>
                                                                <td>
                                                                    <p class="text-xs font-weight-bold mb-0">
                                                                        {{ $salaryOfficialA7A->employee->role->role_name }}
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-8 category-right">
                                                <table class="table align-items-center mb-0 table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Số công ngày</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Lương ca ngày (thử việc)</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Ghi chú</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Số công đêm
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Lương ca đêm (thử việc)</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Ghi chú</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Số giờ tăng ca ( thử việc)</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Lương tăng ca (thử việc)</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Ghi chú</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Số công</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Phụ cấp học việc</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Ghi chú</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Số giờ chính</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Lương chính thức</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Ghi chú</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Chuyên cần</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Số công làm
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Ghi chú</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Số công làm
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Trách nhiệm</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Ghi chú</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Số giờ tăng ca</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Lương tăng ca</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Ghi chú</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Số công ngày</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Phụ cấp cơm ca ngày</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Ghi chú</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Số công đêm</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Phụ cấp ca đêm</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Ghi chú</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Số ngày tăng ca</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Phụ cấp tăng ca
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Ghi chú</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Số ngày lễ tết</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Tiền lễ tết</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Ghi chú
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Phép năm</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3 ">
                                                                Tiền phép năm</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Ghi chú
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Số giờ đi công tác
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Đơn giá đi công tác/ giờ
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Lương đi công tác GCN
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Ghi chú
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Số lần đi công tác
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Đơn giá xăng công tác/ ngày
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Phụ cấp xăng đi GCN
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Ghi chú
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Tiền giới thiệu người
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Ghi chú
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Phụ cấp khác
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Ghi chú
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Tiền thưởng đạt chuyên cần
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Ghi chú
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Ốm đau
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Ghi chú
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Ma chay
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Ghi chú
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Tiền sinh nhật
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Ghi chú
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Tiền lương tháng trước bị thiếu
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Ghi chú
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Tổng thu nhập
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Khấu trừ BHXH 10.5%
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Ghi chú
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Tạm ứng
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Ghi chú
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Số lần vi phạm
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Trừ vi phạm
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Ghi chú
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Số nghỉ có phép
                                                            </th>

                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Trừ tiền
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Ghi chú
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Số nghỉ không có phép
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Trừ tiền
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Ghi chú
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Số lỗi nặng
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Trừ tiền
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Ghi chú
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Ghi chú Số lỗi nhẹ
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Trừ tiền
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-3">
                                                                Ghi chú
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Trừ KPI
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Ghi chú
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Thực lãnh
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                Hình thức thanh toán
                                                            </th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 bg-2">
                                                                BHXH (21.5%) công ty đóng cho NLĐ
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($salaryOfficialsA7A as $key => $salaryOfficialA7A)
                                                            <tr>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->number_of_work_days_trial) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->day_shift_salary_trial) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->day_shift_salary_trial_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->number_of_work_nights_trial) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->night_shift_salary_trial) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->night_shift_salary_trial_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->overtime_hours_trial, 1) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->overtime_salary_trial) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->overtime_salary_trial_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->number_of_work) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->allowance_apprentice_detail) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->allowance_apprentice_detail_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->core_hours, 1) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->official_salary) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->official_salary_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->number_of_hours_worked) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->allowance_diligence_detail) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->allowance_diligence_detail_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->number_of_jobs) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->allowance_responsibility_detail) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->allowance_responsibility_detail_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->overtime_hours_detail, 1) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->overtime_salary, 1) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->overtime_salary_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->number_of_work_days) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->allowance_rice_detail) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->allowance_rice_detail_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->number_of_work_nights) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->allowance_shift_night) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->allowance_shift_night_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->overtime_day_count_detail, 1) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->allowance_overtime_detail, 1) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->allowance_overtime_detail_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->holidays_count_detail, 1) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->holidays_money, 1) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->holidays_money_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->paid_holidays_count_detail, 1) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->paid_holidays_money, 1) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->paid_holidays_money_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->business_travel_hours) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->business_travel_unit_price_hour, 1) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->gcn_business_travel_salary) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->gcn_business_travel_salary_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->number_of_business_trips) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->business_fuel_unit_price_day, 1) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->allowance_gcn_business_fuel) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->allowance_gcn_business_fuel_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->money_referral_people) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->money_referral_people_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->allowance_diffrent) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->allowance_diffrent_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->bonuses_for_attendance) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->bonuses_for_attendance_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->sickness) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->sickness_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->funeral) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->funeral_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->birthday_money) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->birthday_money_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->previous_period_debt) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->previous_period_debt_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->total_income) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->insurance_detail) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->insurance_detail_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->advance_money, 1) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->advance_money_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->number_of_violations) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->subtract_of_violations) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->subtract_of_violations_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->daysleave_allowed) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->subtract_daysleave_allowed) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->subtract_daysleave_allowed_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->daysleave_notallowed) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->subtract_daysleave_notallowed) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->subtract_daysleave_notallowed_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->error_serious) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->subtract_error_serious) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->subtract_error_serious_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->error_minor) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->subtract_error_minor) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->subtract_error_minor_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->kpi_subtraction) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->kpi_subtraction_notice }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->actually_received) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $salaryOfficialA7A->forms_of_payment }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->company_insurance_detail) }}
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        {{-- <div
                                        style="display: flex; justify-content: center; align-items: center; margin:20px">
                                        <div>
                                            {{ $salaryOfficialsA7A->appends(request()->all())->links() }}
                                        </div>
                                    </div> --}}
                                    </div>
                                </div>
                                <div class="tab-pane tab-a7a fade" id="timekeepingA7A" role="tabpanel"
                                    aria-labelledby="timekeepingA7A-tab">
                                    <div class="px-0 pb-2">
                                        <div class="table-responsive p-0 d-flex flex-nowrap">
                                            <div class="col-11 timekeeping-left">
                                                <table class="table align-items-center mb-0 table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1">
                                                                STT</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-2">
                                                                MS NV</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder ps-2 col-2">
                                                                Họ và tên</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder ps-2 col-2">
                                                                Bộ phận</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder ps-2 col-2 bg-3">
                                                                Số giờ làm ngày</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder ps-2 col-2 bg-3">
                                                                Số giờ làm đêm</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder ps-2 col-2 bg-3">
                                                                Số giờ tăng ca</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder ps-2 col-2 test-bg">
                                                                Tính lương ngày</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder ps-2 col-2 test-bg">
                                                                Tính lương đêm</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder ps-2 col-2 test-bg">
                                                                Tăng ca thử việc</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder ps-2 col-2 bg-2">
                                                                Phụ cấp tiền cơm ngày</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder ps-2 col-2 bg-2">
                                                                Phụ cấp ca đêm</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder ps-2 col-2 bg-2">
                                                                Phụ cấp tăng ca</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($salaryOfficialsA7A as $key => $salaryOfficialA7A)
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ $loop->iteration }}
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <p class="text-xs font-weight-bold mb-0 px-3">
                                                                        {{ $salaryOfficialA7A->employee->code }}</p>
                                                                </td>
                                                                <td>
                                                                    <p class="text-xs font-weight-bold mb-0">
                                                                        {{ $salaryOfficialA7A->employee->name }}</p>
                                                                </td>
                                                                <td>
                                                                    <p class="text-xs font-weight-bold mb-0">
                                                                        {{ $salaryOfficialA7A->employee->role->role_name }}
                                                                    </p>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->total_day_offical, 1) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->total_night_offical, 1) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->total_overtime_offical, 1) }}
                                                                    </div>
                                                                </td>
                                                                <td class="test-bg">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->workday_count_trial, 1) }}
                                                                    </div>
                                                                </td>
                                                                <td class="test-bg">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->worknight_count_trial, 1) }}
                                                                    </div>
                                                                </td>
                                                                <td class="test-bg">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->overtime_day_count_trial, 1) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->allowance_rice_day_timekeeping, 1) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->allowance_rice_night_timekeeping, 1) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($salaryOfficialA7A->allowance_overtime_timekeeping, 1) }}
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-7 timekeeping-right">
                                                <table class="table align-items-center mb-0 table-hover">
                                                    <thead>
                                                        <tr>
                                                            @foreach ($salaryOfficialsA7A as $key => $salaryOfficialA7A)
                                                                @foreach ($salaryOfficialA7A->SalaryOfficialA7ATimekeepings as $key => $SalaryOfficialA7ATimekeeping)
                                                                    <th colspan="3"
                                                                        class="text-uppercase text-center text-xxs font-weight-bolder col-1 pd-0">
                                                                        {{ date('d/m/Y', strtotime($SalaryOfficialA7ATimekeeping->timekeeping_date)) }}<br>
                                                                        <div class="d-flex mg-top-9">
                                                                            <div class="col-4 bg-4 heigh-31 pd-top-8">
                                                                                Ngày</div>
                                                                            <div class="col-4 bg-2 pd-top-8">Đêm</div>
                                                                            <div class="col-4 bg-3 pd-top-8">TC</div>
                                                                        </div>
                                                                    </th>
                                                                @endforeach
                                                            @break
                                                        @endforeach
                                                        <th
                                                            class="text-uppercase text-xxs font-weight-bolder col-1">
                                                            Số ngày<br>
                                                            <div class="text-center">lễ , tết</div>
                                                        </th>
                                                        <th
                                                            class="text-uppercase text-xxs font-weight-bolder col-1">
                                                            Số ngày<br>
                                                            <div class="text-center">phép năm</div>
                                                        </th>
                                                        <th
                                                            class="text-uppercase text-xxs font-weight-bolder col-1">
                                                            <div class="text-center">Có phép</div>
                                                        </th>
                                                        <th
                                                            class="text-uppercase text-xxs font-weight-bolder col-1">
                                                            <div class="text-center">Không phép</div>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($salaryOfficialsA7A as $key => $salaryOfficialA7A)
                                                        <tr>
                                                            @foreach ($salaryOfficialA7A->SalaryOfficialA7ATimekeepings as $key => $SalaryOfficialA7ATimekeeping)
                                                                <td class="bg-4 check-w">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($SalaryOfficialA7ATimekeeping->timekeeping_day, 1) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-2 check-w">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($SalaryOfficialA7ATimekeeping->timekeeping_night, 1) }}
                                                                    </div>
                                                                </td>
                                                                <td class="bg-3 check-w">
                                                                    <div class="d-flex px-3 py-1">
                                                                        {{ number_format($SalaryOfficialA7ATimekeeping->timekeeping_overtime, 1) }}
                                                                    </div>
                                                                </td>
                                                            @endforeach
                                                            <td>
                                                                <div class="d-flex px-3 py-1 text-center">
                                                                    {{ number_format($salaryOfficialA7A->holidays_count) }}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex px-3 py-1 text-center">
                                                                    {{ number_format($salaryOfficialA7A->paid_holidays_count) }}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex px-3 py-1 text-center">
                                                                    {{ number_format($salaryOfficialA7A->daysleave_allowed_timekeeping) }}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex px-3 py-1 text-center">
                                                                    {{ number_format($salaryOfficialA7A->daysleave_notallowed_timekeeping) }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    {{-- <div
                                    style="display: flex; justify-content: center; align-items: center; margin:20px">
                                    <div>
                                        {{ $salaryOfficialsA7A->appends(request()->all())->links() }}
                                    </div>
                                </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<script>
    $(document).ready(function() {
        var activeCategory = false;
        var activePayroll = false;
        var activeDetail = false;
        var activeTimekeeping = false;

        checkLocalStorage();

        function checkLocalStorage() {
            let category = getLocalStorage('category');
            if (category && category != null) {
                if (category == 'VVP') {
                    let page = getLocalStorage('page');
                    if (page && page != null) {
                        switch (page) {
                            case 'activeCategory':
                                activeCategory = true;
                                resetCategory();
                                resetVVP();
                                handleActive('VVP', 'VVP-tab');
                                handleActive('categoryVVP', 'categoryVVP-tab')
                                break;
                            case 'activePayroll':
                                activePayroll = true;
                                resetCategory();
                                resetVVP();
                                handleActive('VVP', 'VVP-tab');
                                handleActive('salaryVVP', 'salaryVVP-tab')
                                break;
                            case 'activeDetail':
                                activeDetail = true;
                                resetCategory();
                                resetVVP();
                                handleActive('VVP', 'VVP-tab');
                                handleActive('salary-detailVVP', 'salary-detailVVP-tab')
                                break;
                            case 'activeTimekeeping':
                                activeTimekeeping = true;
                                resetCategory();
                                resetVVP();
                                handleActive('VVP', 'VVP-tab');
                                handleActive('timekeepingVVP', 'timekeepingVVP-tab')
                                break;
                        }
                    }
                }

                if (category == 'A7A') {
                    let page = getLocalStorage('page');
                    if (page && page != null) {
                        switch (page) {
                            case 'activeCategory':
                                activeCategory = true;
                                resetCategory();
                                resetA7A();
                                handleActive('A7A', 'A7A-tab');
                                handleActive('categoryA7A', 'categoryA7A-tab')
                                break;
                            case 'activePayroll':
                                activePayroll = true;
                                resetCategory();
                                resetA7A();
                                handleActive('A7A', 'A7A-tab');
                                handleActive('salaryA7A', 'salaryA7A-tab')
                                break;
                            case 'activeDetail':
                                activeDetail = true;
                                resetCategory();
                                resetA7A();
                                handleActive('A7A', 'A7A-tab');
                                handleActive('salary-detailA7A', 'salary-detailA7A-tab')
                                break;
                            case 'activeTimekeeping':
                                activeTimekeeping = true;
                                resetCategory();
                                resetA7A();
                                handleActive('A7A', 'A7A-tab');
                                handleActive('timekeepingA7A', 'timekeepingA7A-tab')
                                break;
                        }
                    }
                }
            }
        }

        $('#categoryVVP-tab').click(function() {
            resetClick();
            activeCategory = true;
            resetLocalStorage();
            addLocalStorage('activeCategory');
            addLocalStorageCategory('VVP');
        });

        $('#salaryVVP-tab').click(function() {
            resetClick();
            activePayroll = true;
            resetLocalStorage();
            addLocalStorage('activePayroll');
            addLocalStorageCategory('VVP');
        });

        $('#salary-detailVVP-tab').click(function() {
            resetClick();
            activeDetail = true;
            resetLocalStorage();
            addLocalStorage('activeDetail');
            addLocalStorageCategory('VVP');

        });

        $('#timekeepingVVP-tab').click(function() {
            resetClick();
            activeTimekeeping = true;
            resetLocalStorage();
            addLocalStorage('activeTimekeeping');
            addLocalStorageCategory('VVP');
        });

        $('#categoryA7A-tab').click(function() {
            resetClick();
            activeCategory = true;
            resetLocalStorage();
            addLocalStorage('activeCategory');
            addLocalStorageCategory('A7A');
        });

        $('#salaryA7A-tab').click(function() {
            resetClick();
            activePayroll = true;
            resetLocalStorage();
            addLocalStorage('activePayroll');
            addLocalStorageCategory('A7A');
        });

        $('#salary-detailA7A-tab').click(function() {
            resetClick();
            activeDetail = true;
            resetLocalStorage();
            addLocalStorage('activeDetail');
            addLocalStorageCategory('A7A');
        });

        $('#timekeepingA7A-tab').click(function() {
            resetClick();
            resetLocalStorage();
            activeTimekeeping = true;
            addLocalStorage('activeTimekeeping');
            addLocalStorageCategory('A7A');
        });

        $('#A7A-tab').click(function() {
            if (activePayroll) {
                resetA7A();
                handleActive('salaryA7A', 'salaryA7A-tab')
            }
            if (activeCategory) {
                resetA7A();
                handleActive('categoryA7A', 'categoryA7A-tab')
            }
            if (activeDetail) {
                resetA7A();
                handleActive('salary-detailA7A', 'salary-detailA7A-tab')
            }
            if (activeTimekeeping) {
                resetA7A();
                handleActive('timekeepingA7A', 'timekeepingA7A-tab')
            }
            addLocalStorageCategory('A7A');
        });

        $('#VVP-tab').click(function() {
            if (activePayroll) {
                resetVVP();
                handleActive('salaryVVP', 'salaryVVP-tab')
            }
            if (activeCategory) {
                resetVVP();
                handleActive('categoryVVP', 'categoryVVP-tab')
            }
            if (activeDetail) {
                resetVVP();
                handleActive('salary-detailVVP', 'salary-detailVVP-tab')
            }
            if (activeTimekeeping) {
                resetVVP();
                handleActive('timekeepingVVP', 'timekeepingVVP-tab')
            }
            addLocalStorageCategory('VVP');
        });

        function handleActive(attribute, attribute_tab) {
            var el = document.getElementById(attribute);
            if (el) {
                if (!el.classList.contains('active')) {
                    el.classList.add('active');
                }
                if (!el.classList.contains('show')) {
                    el.classList.add('show');
                }
            }

            var elTab = document.getElementById(attribute_tab);
            if (elTab) {
                if (!elTab.classList.contains('active')) {
                    elTab.classList.add('active');
                }
                if (!elTab.classList.contains('show')) {
                    elTab.classList.add('show');
                }
            }
        }

        function resetA7A() {
            var listTab = document.getElementsByClassName('tab-a7a');
            if (listTab && listTab.length > 0) {
                for (let i = 0; i < listTab.length; i++) {
                    if (listTab[i].classList.contains('show')) {
                        listTab[i].classList.remove('show');
                    }
                    if (listTab[i].classList.contains('active')) {
                        listTab[i].classList.remove('active');
                    }
                }
            }
        };

        function resetVVP() {
            var listTab = document.getElementsByClassName('tab-vvp');
            if (listTab && listTab.length > 0) {
                for (let i = 0; i < listTab.length; i++) {
                    if (listTab[i].classList.contains('show')) {
                        listTab[i].classList.remove('show');
                    }
                    if (listTab[i].classList.contains('active')) {
                        listTab[i].classList.remove('active');
                    }
                }
            }
        };

        function resetCategory() {
            var listTab = document.getElementsByClassName('category');
            if (listTab && listTab.length > 0) {
                for (let i = 0; i < listTab.length; i++) {
                    if (listTab[i].classList.contains('show')) {
                        listTab[i].classList.remove('show');
                    }
                    if (listTab[i].classList.contains('active')) {
                        listTab[i].classList.remove('active');
                    }
                }
            }
        };

        function resetClick() {
            activeCategory = false;
            activePayroll = false;
            activeDetail = false;
            activeTimekeeping = false;
        }

        function addLocalStorageCategory(key) {
            localStorage.setItem('category', key);
        }

        function getLocalStorage(key) {
            return localStorage.getItem(key);
        }

        function addLocalStorage(key) {
            localStorage.setItem('page', key);
        }

        function removeLocalStorage(key) {
            localStorage.removeItem(key);
        }

        function resetLocalStorage() {
            localStorage.removeItem('activeCategory');
            localStorage.removeItem('activePayroll');
            localStorage.removeItem('activeDetail');
            localStorage.removeItem('activeTimekeeping');
        }
    });
</script>
@endsection
