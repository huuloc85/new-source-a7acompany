<style>
    @media (max-width: 999px) {
        .swiper-wrapper {
            flex-wrap: wrap;
            gap: 32px;
            transition: none !important;
            transform: none !important;
        }

        .swiper-wrapper>li {
            flex: calc(100%/2 - 70px);
            box-sizing: border-box;
            padding: 10px;
            margin-right: 0 !important;
        }

        .d-slider1 .swiper-button.swiper-button-prev,
        .d-slider1 .swiper-button.swiper-button-next {
            display: none;
        }
    }

    @media (max-width: 550px) {
        .swiper-wrapper {
            flex-wrap: wrap;
            gap: 0;
        }

        .swiper-wrapper>li {
            flex: none;
            box-sizing: unset;
            padding: 0;
            margin-right: 32px;
        }
    }

    .card-body .progress-detail h6 {
        white-space: nowrap;
        /* Ngăn không cho văn bản xuống dòng */
        text-overflow: ellipsis;
        /* Hiển thị dấu chấm (...) khi văn bản dài hơn */
        overflow: hidden;
        /* Ẩn bớt phần văn bản dài hơn */
        max-width: 160px;
        /* Đặt độ rộng tối đa để văn bản không quá dài */
        display: inline-block;
        /* Để các thẻ li không nhảy xuống hàng */
        vertical-align: middle;
        /* Căn giữa theo chiều dọc */
        margin-bottom: 5px;
        /* Khoảng cách giữa các thẻ li */
    }

    .card-body .progress-detail h6:hover {
        white-space: normal;
        /* Hiển thị toàn bộ nội dung khi hover */
    }
</style>
@extends('master')
@section('content')
    @if (Auth()->user()->role->role_name == 'admin' ||
            Auth()->user()->role->role_name == 'manager' ||
            Auth()->user()->role->role_name == 'accountant')
        <div class="container-fluid content-inner mt-n5 py-0">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="row row-cols-1">
                        <div class="d-slider1 overflow-hidden">
                            <ul class="swiper-wrapper list-inline m-0 p-0 mb-2 slide-hover">
                                <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                                    <div class="card-body">
                                        <a href="{{ route('admin.employee.home') }}">
                                            <div class="progress-widget">
                                                <div id="circle-progress-01"
                                                    class="circle-progress-01 circle-progress circle-progress-primary text-center"
                                                    data-min-value="0" data-max-value="100" data-value="90"
                                                    data-type="percent">
                                                </div>
                                                <div class="progress-detail">
                                                    <h6 class="text-sm mb-0 text-capitalize">
                                                        <i class="fas fa-users fa-lg mr-2"></i>
                                                        <!-- Icon for Total Employees -->
                                                        Tổng nhân viên
                                                    </h6>
                                                    <h4 class="mb-0">{{ $totalEmployee }}</h4>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="card-footer p-3">
                                            <p class="mb-2">Đi đến <a href="{{ route('admin.employee.home') }}">danh sách
                                                    nhân viên.</a></p>
                                        </div>
                                    </div>
                                </li>
                                <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                                    <div class="card-body">
                                        <a href="{{ route('admin.role.home') }}">
                                            <div class="progress-widget">
                                                <div id="circle-progress-03"
                                                    class="circle-progress-03 circle-progress circle-progress-primary text-center"
                                                    data-min-value="0" data-max-value="100" data-value="90"
                                                    data-type="percent">
                                                </div>
                                                <div class="progress-detail">
                                                    <h6 class="text-sm mb-0 text-capitalize">
                                                        <i class="fas fa-money-bill-wave fa-lg mr-2"></i>

                                                        Tổng chức vụ
                                                    </h6>
                                                    <h4 class="mb-0">{{ $totalRole }}</h4>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="card-footer p-3">
                                            <p class="mb-2">Đi đến <a href="{{ route('admin.role.home') }}">danh sách chức
                                                    vụ.</a></p>
                                        </div>
                                    </div>
                                </li>
                                <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                                    <div class="card-body">
                                        <a href="{{ route('admin.product-plan.index') }}">
                                            <div class="progress-widget">
                                                <div id="circle-progress-03"
                                                    class="circle-progress-03 circle-progress circle-progress-primary text-center"
                                                    data-min-value="0" data-max-value="100" data-value="90"
                                                    data-type="percent">
                                                </div>
                                                <div class="progress-detail">
                                                    <h6 class="text-sm mb-0 text-capitalize">
                                                        <i class="fas fa-money-bill-wave fa-lg mr-2"></i>

                                                        Kế hoạch sản xuất
                                                    </h6>
                                                    <h4 class="mb-0">{{ $totalRole }}</h4>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="card-footer p-3">
                                            <p class="mb-2">Đi đến <a href="{{ route('admin.product-plan.index') }}">Kế
                                                    hoạch sản xuất
                                                </a></p>
                                        </div>
                                    </div>
                                </li>
                                <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                                    <div class="card-body">
                                        <a href="{{ route('admin.salary.home') }}">
                                            <div class="progress-widget">
                                                <div id="circle-progress-03"
                                                    class="circle-progress-03 circle-progress circle-progress-primary text-center"
                                                    data-min-value="0" data-max-value="100" data-value="90"
                                                    data-type="percent">
                                                </div>
                                                <div class="progress-detail">
                                                    <h6 class="text-sm mb-0 text-capitalize">
                                                        <i class="fas fa-money-bill-wave fa-lg mr-2"></i>
                                                        <!-- Icon for Total Salary -->
                                                        Tổng bảng lương
                                                    </h6>
                                                    <h4 class="mb-0">{{ $totalSalary }}</h4>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="card-footer p-3">
                                            <p class="mb-2">Đi đến <a href="{{ route('admin.salary.home') }}">danh sách
                                                    bảng lương.</a></p>
                                        </div>
                                    </div>
                                </li>
                                <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                                    <div class="card-body">
                                        <a href="{{ route('admin.checkemployee.view-employee-todo') }}">
                                            <div class="progress-widget">
                                                <div id="circle-progress-04"
                                                    class="circle-progress-04 circle-progress circle-progress-primary text-center"
                                                    data-min-value="0" data-max-value="100" data-value="90"
                                                    data-type="percent">
                                                </div>
                                                <div class="progress-detail">
                                                    <h6 class="text-sm mb-0 text-capitalize">
                                                        <i class="fas fa-user-check fa-lg mr-2"></i>
                                                        <!-- Icon for Check Employee -->
                                                        Danh sách NV làm việc trong ngày
                                                    </h6>
                                                    <h4 class="mb-0">{{ $totalCheckEmployee }}</h4>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="card-footer p-3">
                                            <p class="mb-2">Đi đến <a
                                                    href="{{ route('admin.checkemployee.view-employee-todo') }}">Danh sách
                                                    NV làm việc trong ngày.</a></p>
                                        </div>
                                    </div>
                                </li>
                                <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                                    <div class="card-body">
                                        <a href="{{ route('admin.celender.home') }}">
                                            <div class="progress-widget">
                                                <div id="circle-progress-05"
                                                    class="circle-progress-05 circle-progress circle-progress-primary text-center"
                                                    data-min-value="0" data-max-value="100" data-value="90"
                                                    data-type="percent">
                                                </div>
                                                <div class="progress-detail">
                                                    <h6 class="text-sm mb-0 text-capitalize">
                                                        <i class="far fa-calendar-alt fa-lg mr-2"></i>
                                                        <!-- Icon for Calendar -->
                                                        Tổng lịch làm việc
                                                    </h6>
                                                    <h4 class="mb-0">{{ $totalCelender }}</h4>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="card-footer p-3">
                                            <p class="mb-2">Đi đến <a href="{{ route('admin.celender.home') }}">danh
                                                    sách lịch làm việc.</a></p>
                                        </div>
                                    </div>
                                </li>
                                <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                                    <div class="card-body">
                                        <a href="{{ route('admin.product.home') }}">
                                            <div class="progress-widget">
                                                <div id="circle-progress-06"
                                                    class="circle-progress-06 circle-progress circle-progress-primary text-center"
                                                    data-min-value="0" data-max-value="100" data-value="90"
                                                    data-type="percent">
                                                </div>
                                                <div class="progress-detail">
                                                    <h6 class="text-sm mb-0 text-capitalize">
                                                        <i class="fas fa-boxes fa-lg mr-2"></i>
                                                        <!-- Icon for Total Products -->
                                                        Tổng sản phẩm
                                                    </h6>
                                                    <h4 class="mb-0">{{ $totalProduct }}</h4>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="card-footer p-3">
                                            <p class="mb-2">Đi đến <a href="{{ route('admin.product.home') }}">danh
                                                    sách sản phẩm.</a></p>
                                        </div>
                                    </div>
                                </li>
                                <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                                    <div class="card-body">
                                        <a href="{{ route('admin.history.home') }}">
                                            <div class="progress-widget">
                                                <div id="circle-progress-07"
                                                    class="circle-progress-07 circle-progress circle-progress-primary text-center"
                                                    data-min-value="0" data-max-value="100" data-value="90"
                                                    data-type="percent">
                                                </div>
                                                <div class="progress-detail">
                                                    <h6 class="text-sm mb-0 text-capitalize">
                                                        <i class="fas fa-history fa-lg mr-2"></i> <!-- Icon for History -->
                                                        Tổng lịch sử
                                                    </h6>
                                                    <h4 class="mb-0">{{ $totalHistory }}</h4>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="card-footer p-3">
                                            <p class="mb-2">Đi đến <a href="{{ route('admin.history.home') }}">danh
                                                    sách lịch sử.</a></p>
                                        </div>
                                    </div>
                                </li>
                                <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                                    <div class="card-body">
                                        <a href="{{ route('admin.checkpo.index') }}">
                                            <div class="progress-widget">
                                                <div id="circle-progress-08"
                                                    class="circle-progress-08 circle-progress circle-progress-primary text-center"
                                                    data-min-value="0" data-max-value="100" data-value="90"
                                                    data-type="percent">
                                                </div>
                                                <div class="progress-detail">
                                                    <h6 class="text-sm mb-0 text-capitalize">
                                                        <i class="fas fa-file-invoice-dollar fa-lg mr-2"></i>
                                                        <!-- Icon for PO -->
                                                        Danh sách PO
                                                    </h6>
                                                    <h5 class="text-md mb-0">Từ {{ $startOfMonth->format('d/m/Y') }} đến
                                                        {{ $endOfMonth->format('d/m/Y') }}
                                                    </h5>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="card-footer p-3">
                                            <p class="mb-2">Đi đến <a href="{{ route('admin.checkpo.index') }}">kiểm
                                                    tra PO.</a></p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <div class="swiper-button swiper-button-next"></div>
                            <div class="swiper-button swiper-button-prev"></div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <div class="row">
                                        <div class="col-lg-6 col-7">
                                            <h6>Danh sách 10 bảng lương gần nhất</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body px-0 pb-2">
                                    <div class="table-responsive">
                                        <table class="table align-items-center mb-0 table-hover">
                                            <thead>
                                                <tr>
                                                    <th
                                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        STT
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                        Tiêu đề</th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                        Tổng(VND)</th>
                                                    <th
                                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Ngày bắt đầu</th>
                                                    <th
                                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Ngày kết thúc</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($salaryManagers as $key => $salaryManager)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ $loop->iteration }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <p class="text-xs font-weight-bold mb-0 px-3"><a
                                                                    href="{{ route('admin.salary.detail', $salaryManager->id) }}">{{ $salaryManager->title }}</a>
                                                            </p>
                                                        </td>
                                                        <td class="align-middle text-sm">
                                                            <p class="text-xs font-weight-bold mb-0">
                                                                {{ number_format($salaryManager->total, 2) }}
                                                            </p>
                                                        </td>
                                                        <td class="align-middle text-center text-sm">
                                                            <p class="text-xs font-weight-bold mb-0">
                                                                {{ $salaryManager->formatTimeDMY($salaryManager->start_date) }}
                                                            </p>
                                                        </td>
                                                        <td class="align-middle text-center text-sm">
                                                            <p class="text-xs font-weight-bold mb-0">
                                                                {{ $salaryManager->formatTimeDMY($salaryManager->end_date) }}
                                                            </p>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @if ($totalSalary == 0)
                                                    <tr>
                                                        <td colspan="4" class="text-center pt-4">Hiện tại chưa có bảng
                                                            lương
                                                            nào.<br>Vui lòng
                                                            <a class="href"
                                                                href="{{ route('admin.salary.getimport') }}">Thêm bảng
                                                                lương</a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="card h-100">
                                <div class="card-header pb-0">
                                    <h6>Danh sách 10 lịch làm việc gần nhất</h6>
                                </div>
                                <div class="card-body px-0 pb-2">
                                    <div class="table-responsive">
                                        <table class="table align-items-center mb-0 table-hover">
                                            <thead>
                                                <tr>
                                                    <th
                                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        STT
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                        Tiêu đề</th>
                                                    <th
                                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Ngày bắt đầu</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($celenders as $key => $celender)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ $loop->iteration }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <p class="text-xs font-weight-bold mb-0 px-3"><a
                                                                    href="{{ route('admin.celender.detail', $celender->id) }}">{{ $celender->title }}</a>
                                                            </p>
                                                        </td>
                                                        <td class="align-middle text-center text-sm">
                                                            <p class="text-xs font-weight-bold mb-0">
                                                                {{ $celender->formatTimeDMY($celender->date) }}
                                                            </p>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @if ($totalCelender == 0)
                                                    <tr>
                                                        <td colspan="4" class="text-center pt-4">Chưa có lịch làm việc
                                                            nào.<br>Đi
                                                            đến
                                                            <a href="{{ route('admin.celender.home') }}">danh sách lịch
                                                                làm việc.
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row mb-4">
                            @if (Auth()->user()->role_id == 14 || Auth()->user()->role_id == 18)
                                <div class="col-md-12 col-lg-12">
                                    <div class="row row-cols-1">
                                        <div class="d-slider1 overflow-hidden">
                                            <ul class="swiper-wrapper list-inline m-0 p-0 mb-2">
                                                <li class="swiper-slide card card-slide" data-aos="fade-up"
                                                    data-aos-delay="700">
                                                    <div class="card-body">
                                                        <a href="{{ route('admin.celender.home') }}"><i
                                                                class="material-icons opacity-10"></i>
                                                            <div class="progress-widget">
                                                                <div id="circle-progress-01"
                                                                    class="circle-progress-01 circle-progress circle-progress-primary text-center"
                                                                    data-min-value="0" data-max-value="100"
                                                                    data-value="90" data-type="percent">
                                                                    <svg version="1.1" id="Layer_1"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                                                                        y="0px" viewBox="0 0 122.88 88.24"
                                                                        style="enable-background:new 0 0 122.88 88.24"
                                                                        xml:space="preserve">
                                                                        <style type="text/css">
                                                                            .st0 {
                                                                                fill-rule: evenodd;
                                                                                clip-rule: evenodd;
                                                                            }
                                                                        </style>
                                                                        <g>
                                                                            <path class="st0"
                                                                                d="M30.5,59.7c4.97,10.37,19.55,10.73,24.31,0.06c-1.3-1.31-2.26-2.48-3.22-3.88c-0.16-0.23-0.31-0.46-0.47-0.69 c-2.3,1.82-4.84,2.74-8.48,2.73c-3.86-0.01-6.76-1.48-9.47-4.13c-0.35,0.96-0.79,2.11-1.26,3.19C31.46,58.02,30.97,59,30.5,59.7 L30.5,59.7z M103.72,61.17c10.91,4.55,17.81,10.43,19.02,23.22c0.34,3.61,0.31,3.45-3.15,3.52c-38,0.75-77.07,0-115.06,0 c-8.27-0.63-3.17-14.93-0.68-18.22c1.2-1.58,2.68-2.75,4.33-3.66c4.91-2.74,15.67-3.65,20.55-7.33c0.4-0.59,0.84-1.47,1.26-2.43 c0.61-1.4,1.17-2.93,1.53-3.97c-1.49-1.76-2.77-3.74-4-5.69l-4.04-6.43c-1.48-2.21-2.25-4.22-2.29-5.87 c-0.02-0.78,0.11-1.48,0.4-2.1c0.3-0.65,0.76-1.19,1.39-1.61c0.29-0.2,0.62-0.36,0.98-0.5c-0.26-3.49-0.36-7.88-0.19-11.56 c0.09-0.87,0.25-1.75,0.5-2.62c1.03-3.69,3.62-6.66,6.83-8.7c1.77-1.13,3.7-1.98,5.73-2.55c1.28-0.36-1.09-4.47,0.23-4.6 c6.42-0.66,16.8,5.2,21.28,10.05c2.25,2.43,3.65,5.65,3.96,9.91L62.04,30.5v0c1.12,0.34,1.84,1.05,2.13,2.2 c0.33,1.27-0.03,3.07-1.11,5.52l0,0c-0.02,0.04-0.04,0.09-0.07,0.13l-4.61,7.59c-1.69,2.79-3.41,5.58-5.65,7.79 c0.2,0.29,0.4,0.58,0.6,0.88c0.91,1.33,1.83,2.68,3.01,3.87c0.05,0.05,0.1,0.11,0.13,0.17c3.56,2.71,8.93,3.92,13.49,5.45 c2.08-1.09,4.28-2.11,6.73-3.12c0.51-0.47,1.13-1.03,1.79-1.61c1.73-1.54,3.71-3.29,3.98-3.86c-0.6-0.6-1.15-1.23-1.69-1.86 L78.48,51l-1.71,0.04l-3.26,0.09c-2.79-3.21-2.62-10.12-2.35-14.11c1.3-8.01,4.4-13.78,9.45-17.12c4.58-3.03,13.42-3.55,18.05-0.51 c9.69,6.38,12.69,21.43,7.96,31.58h-3.38l-0.22,0.26l-0.3,0.35l-1.47,1.77c-0.86,1.03-1.72,2.06-2.74,2.96 c0.61,0.8,2.32,2.3,3.84,3.63C102.83,60.38,103.3,60.79,103.72,61.17L103.72,61.17z M24.25,32.48c-0.27,0.18-0.46,0.4-0.58,0.67 c-0.14,0.3-0.2,0.66-0.19,1.08c0.04,1.23,0.68,2.84,1.93,4.69l0.02,0.03l0,0l4.04,6.43c1.62,2.58,3.32,5.2,5.43,7.14 c2.03,1.86,4.5,3.11,7.75,3.12c3.53,0.01,6.11-1.3,8.2-3.26c2.18-2.04,3.9-4.83,5.59-7.62l4.55-7.5c0.85-1.94,1.16-3.23,0.96-3.99 c-0.26-1.02-3.26-0.63-4.26-0.77l1.56-6.9c-11.57,1.82-20.23-6.77-32.46-1.72l0.88,8.14C26.45,32.06,25.41,31.7,24.25,32.48 L24.25,32.48z M83.75,56.74c-0.73,1.01-2.52,2.6-4.12,4.02l-0.53,0.47c0.46,8.62,19.54,11.2,21.62-0.28 c-1.44-1.26-2.95-2.61-3.63-3.48l-0.07,0.05c-3.65,2.49-8.46,2.53-12.13,0.07C84.49,57.33,84.11,57.04,83.75,56.74L83.75,56.74z M79.36,49.81c-2.7-9.26-1.68-17.76,5.6-25.04c1.29,4.16,4.17,7.6,9.09,10.14c2.35,1.74,4.62,3.85,6.82,6.27 c0.39-1.6-1.1-3.55-2.9-5.56c1.67,0.82,3.2,1.98,4.29,4.2c1.26,2.57,1.24,4.74,0.83,7.54c-0.13,0.86-0.56,1.68-0.8,2.46 c-0.08,0.04-0.14,0.09-0.19,0.16c-1.66,2.04-3.74,4.85-5.83,6.28c-3.08,1.88-7.52,1.96-10.63,0.07c-1.41-0.94-2.53-2.21-3.61-3.46 l-2.44-2.87C79.52,49.91,79.44,49.85,79.36,49.81L79.36,49.81z" />
                                                                        </g>
                                                                    </svg>
                                                                </div>
                                                                <div class="progress-detail">
                                                                    <h4 class="text-sm mb-0 text-capitalize">Lịch làm việc
                                                                        nhân
                                                                        viên</h4>
                                                                </div>
                                                            </div>
                                                        </a>
                                                        <div class="card-footer p-3">
                                                            <p class="mb-0">Đi đến <a
                                                                    href="{{ route('admin.celender.home') }}">lịch làm
                                                                    việc nhân
                                                                    viên.</a></p>

                                                            <div class="col-lg-4 col-md-6">
                                                                <div class="card h-100">
                                                                    <div class="card-header pb-0">
                                                                        <h6>Danh sách 10 lịch làm việc gần nhất</h6>
                                                                    </div>
                                                                    <div class="card-body px-0 pb-2">
                                                                        <div class="table-responsive">
                                                                            <table
                                                                                class="table align-items-center mb-0 table-hover">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th
                                                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-3">
                                                                                            STT
                                                                                        </th>
                                                                                        <th
                                                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-4">
                                                                                            Tiêu đề</th>
                                                                                        <th
                                                                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                                                            Ngày bắt đầu</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach ($celenders as $key => $celender)
                                                                                        <tr>
                                                                                            <td>
                                                                                                <div
                                                                                                    class="d-flex px-3 py-1">
                                                                                                    {{ $loop->iteration }}
                                                                                                </div>
                                                                                            </td>
                                                                                            <td>
                                                                                                <p
                                                                                                    class="text-xs font-weight-bold mb-0 px-3">
                                                                                                    <a
                                                                                                        href="{{ route('admin.celender.detail', $celender->id) }}">{{ $celender->title }}</a>
                                                                                                </p>
                                                                                            </td>
                                                                                            <td
                                                                                                class="align-middle text-center text-sm">
                                                                                                <p
                                                                                                    class="text-xs font-weight-bold mb-0">
                                                                                                    {{ $celender->formatTimeDMY($celender->date) }}
                                                                                                </p>
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                    @if ($totalCelender == 0)
                                                                                        <tr>
                                                                                            <td colspan="4"
                                                                                                class="text-center pt-4">
                                                                                                Chưa có lịch làm việc
                                                                                                nào.<br>Đi
                                                                                                đến
                                                                                                <a
                                                                                                    href="{{ route('admin.celender.home') }}">danh
                                                                                                    sách lịch
                                                                                                    làm việc.
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endif
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="d-slider1 overflow-hidden">
                                <ul class="swiper-wrapper list-inline m-0 p-0 mb-2">
                                    <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                                        <div class="card-body">
                                            <a href="{{ route('admin.employee-show.celender') }}"><i
                                                    class="material-icons opacity-10 text-white">weekend</i>
                                                <div class="progress-widget">
                                                    <div id="circle-progress-01"
                                                        class="circle-progress-01 circle-progress circle-progress-primary text-center"
                                                        data-min-value="0" data-max-value="100" data-value="90"
                                                        data-type="percent">
                                                        <svg width="20" height="20" id="Layer_1"
                                                            data-name="Layer 1" xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 122.88 118.34">
                                                            <defs>
                                                                <style>
                                                                    .cls-1 {
                                                                        fill-rule: evenodd;
                                                                    }
                                                                </style>
                                                            </defs>
                                                            <title>Work Schedule</title>
                                                            <path class="cls-1"
                                                                d="M95.53,63.65A27.35,27.35,0,1,1,68.19,91,27.35,27.35,0,0,1,95.53,63.65ZM71.59,4.05c0-2.23,2.21-4,4.94-4s4.94,1.82,4.94,4.05V22.9c0,2.24-2.21,4.05-4.94,4.05s-4.94-1.81-4.94-4.05V4.05Zm-44.26,0c0-2.23,2.21-4,4.94-4s4.95,1.82,4.95,4.05V22.9C37.22,25.14,35,27,32.27,27s-4.94-1.81-4.94-4.05V4.05ZM63.91,111.92H10.24A10.28,10.28,0,0,1,0,101.68V20.54A10.29,10.29,0,0,1,10.24,10.3h9.44V22.9a11.24,11.24,0,0,0,4.26,8.75,13.25,13.25,0,0,0,16.67,0,11.24,11.24,0,0,0,4.26-8.75V10.3H63.94V22.9a11.23,11.23,0,0,0,4.25,8.75,13.26,13.26,0,0,0,16.68,0,11.26,11.26,0,0,0,4.25-8.75V10.3H99a10.28,10.28,0,0,1,10.24,10.24V55.63a38.34,38.34,0,0,0-4.37-1.4V39.94H4.37V99.5a8.08,8.08,0,0,0,8.05,8h49a40.11,40.11,0,0,0,2.5,4.37ZM19.68,56.24l3.46,3.25,7.09-7.21c.73-.75,1.2-1.35,2.11-.41l3,3c1,1,.91,1.52,0,2.42L24.82,67.58c-1.92,1.89-1.59,2-3.55.07l-6.56-6.53a.85.85,0,0,1,.08-1.33l3.43-3.55c.51-.54.93-.51,1.46,0ZM48,51.71H62.68a1.87,1.87,0,0,1,1.87,1.86V65.78a1.89,1.89,0,0,1-1.87,1.87H48a1.88,1.88,0,0,1-1.87-1.87V53.57A1.88,1.88,0,0,1,48,51.71Zm29.59,0H92.27a1.89,1.89,0,0,1,1.81,1.4,37.79,37.79,0,0,0-18.35,5.55V53.57a1.87,1.87,0,0,1,1.87-1.86ZM48,77.66H60A37.81,37.81,0,0,0,57.62,91c0,.87,0,1.74.09,2.6H48a1.88,1.88,0,0,1-1.87-1.87V79.53A1.88,1.88,0,0,1,48,77.66Zm-29.58,0H33.1A1.87,1.87,0,0,1,35,79.53v12.2A1.89,1.89,0,0,1,33.1,93.6H18.43a1.87,1.87,0,0,1-1.87-1.87V79.53a1.87,1.87,0,0,1,1.87-1.87Zm73.31-.43h3.34a1.12,1.12,0,0,1,1.12,1.12V91.23H108a1.12,1.12,0,0,1,1.12,1.11v3.35A1.12,1.12,0,0,1,108,96.8H90.63V78.35a1.12,1.12,0,0,1,1.11-1.12Zm3.79-7.37A21.14,21.14,0,1,1,74.4,91,21.13,21.13,0,0,1,95.53,69.86Z" />
                                                        </svg>
                                                    </div>
                                                    <div class="progress-detail">
                                                        <h4 class="text-sm mb-0 text-capitalize">Lịch làm việc</h4>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="card-footer p-3">
                                                <p class="mb-0">Đi đến <a
                                                        href="{{ route('admin.employee-show.celender') }}">lịch làm
                                                        việc.</a></p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                                        <div class="card-body">
                                            <a href="{{ route('admin.employee-show.salary') }}"><i
                                                    class="material-icons opacity-10 text-white">weekend</i>
                                                <div class="progress-widget">
                                                    <div id="circle-progress-01"
                                                        class="circle-progress-01 circle-progress circle-progress-primary text-center"
                                                        data-min-value="0" data-max-value="100" data-value="90"
                                                        data-type="percent">
                                                        <svg width="20" height="20" id="Layer_1"
                                                            data-name="Layer 1" xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 122.88 120.1">
                                                            <defs>
                                                                <style>
                                                                    .cls-1 {
                                                                        fill-rule: evenodd;
                                                                    }
                                                                </style>
                                                            </defs>
                                                            <title>Salary</title>
                                                            <path class="cls-1"
                                                                d="M65.82,3.83C65.82,1.73,67.9,0,70.49,0s4.67,1.71,4.67,3.83V20.57c0,2.1-2.07,3.83-4.67,3.83s-4.67-1.71-4.67-3.83V3.83Zm57.06,53L81,120.1,48.52,99.26h-41A7.55,7.55,0,0,1,0,91.72V17.35A7.55,7.55,0,0,1,7.54,9.81h9.1a2.59,2.59,0,0,1,0,5.17H7.54a2.33,2.33,0,0,0-1.66.69,2.36,2.36,0,0,0-.69,1.66V36.61H68.3l-3.41,5.16H5.14V91.69a2.35,2.35,0,0,0,.69,1.66A2.41,2.41,0,0,0,7.49,94H46.41l3.42,0,24.44,15.52.3.21a5,5,0,0,0,6.86-1.58L82.77,106l.07,0L89.15,95.8l28.7-42.22,5,3.22ZM13.56,73.65h10.7a1.24,1.24,0,0,1,1.24,1.23v7.91A1.24,1.24,0,0,1,24.26,84H13.56a1.24,1.24,0,0,1-1.24-1.23V74.88a1.23,1.23,0,0,1,1.24-1.23Zm41-22.54H58.7L53.29,59.3V52.36a1.25,1.25,0,0,1,1.24-1.25ZM34,51.11h10.7A1.25,1.25,0,0,1,46,52.35v7.91a1.24,1.24,0,0,1-1.24,1.23H34a1.23,1.23,0,0,1-1.23-1.23V52.35A1.24,1.24,0,0,1,34,51.11Zm-20.48,0h10.7a1.25,1.25,0,0,1,1.24,1.24v7.91a1.24,1.24,0,0,1-1.24,1.23H13.56a1.24,1.24,0,0,1-1.24-1.23V52.35a1.25,1.25,0,0,1,1.24-1.24ZM34,73.65H43.8l-5.29,8a6.19,6.19,0,0,0-.9,2.38H34a1.23,1.23,0,0,1-1.23-1.23V74.88A1.22,1.22,0,0,1,34,73.65ZM23.9,3.83C23.9,1.73,26,0,28.57,0s4.67,1.71,4.67,3.83V20.57c0,2.1-2.08,3.83-4.67,3.83s-4.67-1.71-4.67-3.83V3.83ZM94,19.78V17.33a2.34,2.34,0,0,0-.68-1.66A2.41,2.41,0,0,0,91.69,15H83.17a2.59,2.59,0,1,1,0-5.17h8.52a7.55,7.55,0,0,1,7.54,7.54v5.76L94,19.78ZM40.7,15a2.59,2.59,0,0,1,0-5.18H58.05a2.59,2.59,0,0,1,0,5.18ZM119,43.09,77.1,106.38,43.71,85,85.57,21.68,119,43.09ZM88.44,60.37a9,9,0,1,1-12.32-3.3,9,9,0,0,1,12.32,3.3ZM107,50.57,78.93,92.33a5.88,5.88,0,0,0-8.1,1.78L57.62,85.66a5.87,5.87,0,0,0-1.78-8.1L83.92,35.78A5.86,5.86,0,0,0,92,34l13.21,8.45A5.88,5.88,0,0,0,107,50.57Z" />
                                                        </svg>
                                                    </div>
                                                    <div class="progress-detail">
                                                        <h4 class="text-sm mb-0 text-capitalize">Bảng lương.</h4>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="card-footer p-3">
                                                <p class="mb-2">Đi đến <a
                                                        href="{{ route('admin.employee-show.salary') }}">bảng
                                                        lương.</a></p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                                        <div class="card-body">
                                            <a href="{{ route('admin.employee.check-employee-todo') }}"><i
                                                    class="material-icons opacity-10 text-white">weekend</i>
                                                <div class="progress-widget">
                                                    <div id="circle-progress-01"
                                                        class="circle-progress-01 circle-progress circle-progress-primary text-center"
                                                        data-min-value="0" data-max-value="100" data-value="90"
                                                        data-type="percent">
                                                        <svg width="800px" height="800px" viewBox="0 0 32 32"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <title>Todo</title>
                                                            <polygon
                                                                points="17.866 15.509 17.866 15.509 16.886 16.489 15.906 15.511 15.906 15.511 13.949 13.554 14.926 12.577 16.883 14.534 21.774 9.64 22.754 10.617 17.866 15.509"
                                                                style="fill:green" />
                                                            <rect x="14.454" y="21.443" width="8.303" height="1.383"
                                                                style="fill:#c00000" />
                                                            <path
                                                                d="M2,5.74V29.449H26.909V5.74ZM25.477,28.189,3.394,28.131,3.417,7.157H25.494ZM6.151,10.951v5.534h5.534V10.951ZM10.3,15.1H7.534V12.334H10.3Zm-4.151,4.22v5.534h5.534V19.323ZM10.3,23.474H7.534V20.709H10.3ZM30,2.551V26.24H28.569L28.549,4l-22.4-.029V2.551H30Z"
                                                                style="fill:#cfcfcf" />
                                                        </svg>
                                                    </div>
                                                    <div class="progress-detail">
                                                        <h4 class="text-sm mb-0 text-capitalize">Chọn sản phẩm hoạt động
                                                        </h4>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="card-footer p-3">
                                                <p class="mb-0">Đi đến <a
                                                        href="{{ route('admin.employee.check-employee-todo') }}">chọn
                                                        sản phẩm cần hoạt động.</a>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                                        <div class="card-body">
                                            <a href="{{ route('admin.employee-history-check') }}"><i
                                                    class="material-icons opacity-10 text-white">history</i>
                                                <div class="progress-widget">
                                                    <div id="circle-progress-01"
                                                        class="circle-progress-01 circle-progress circle-progress-primary text-center"
                                                        data-min-value="0" data-max-value="100" data-value="90"
                                                        data-type="percent">
                                                        <svg fill="#000000" width="800px" height="800px"
                                                            viewBox="0 0 100 100" version="1.1" xml:space="preserve"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink">
                                                            <g>
                                                                <path
                                                                    d="M78.9,40.4C79,40.3,79,40.1,79,40V9c0-2.8-2.2-5-5-5H14c-2.8,0-5,2.2-5,5v60c0,2.8,2.2,5,5,5h16c0.1,0,0.2,0,0.3-0.1    C34.2,86.7,46,96,60,96c17.1,0,31-13.9,31-31C91,55,86.3,46.1,78.9,40.4z M14,6h60c1.7,0,3,1.3,3,3v7H11V9C11,7.3,12.3,6,14,6z     M14,72c-1.7,0-3-1.3-3-3V18h66v21c-3.3-2.1-7-3.7-11-4.5V30c0-0.4-0.2-0.7-0.5-0.9c-0.3-0.2-0.7-0.2-1,0l-13,8    C51.2,37.3,51,37.7,51,38c0,0.3,0.2,0.7,0.5,0.9l13,8c0.3,0.2,0.7,0.2,1,0c0.3-0.2,0.5-0.5,0.5-0.9v-4.2C76.5,44.5,84,54,84,65    c0,13.2-10.8,24-24,24S36,78.2,36,65c0-8.4,4.5-16.3,11.7-20.6c0.4-0.3,0.6-0.8,0.4-1.3l-2-4.6c-0.1-0.3-0.3-0.5-0.6-0.5    c-0.3-0.1-0.6-0.1-0.8,0.1C35,43.5,29,53.9,29,65c0,2.4,0.3,4.7,0.8,7H14z M60,94c-16,0-29-13-29-29c0-10,5.2-19.4,13.7-24.6    l1.2,2.8C38.6,47.9,34,56.2,34,65c0,14.3,11.7,26,26,26s26-11.7,26-26c0-12.3-8.7-23-20.8-25.5c-0.3-0.1-0.6,0-0.8,0.2    c-0.2,0.2-0.4,0.5-0.4,0.8v3.7L53.9,38L64,31.8v3.6c0,0.5,0.4,0.9,0.8,1C78.8,38.8,89,50.8,89,65C89,81,76,94,60,94z" />
                                                                <path
                                                                    d="M60,69c1.9,0,3.4-1.3,3.9-3H79c0.6,0,1-0.4,1-1s-0.4-1-1-1H63.9c-0.4-1.4-1.5-2.5-2.9-2.9V52c0-0.6-0.4-1-1-1s-1,0.4-1,1    v9.1c-1.7,0.4-3,2-3,3.9C56,67.2,57.8,69,60,69z M60,63c1.1,0,2,0.9,2,2s-0.9,2-2,2s-2-0.9-2-2S58.9,63,60,63z" />
                                                                <circle cx="71" cy="11" r="2" />
                                                                <circle cx="65" cy="11" r="2" />
                                                                <circle cx="59" cy="11" r="2" />
                                                                <path
                                                                    d="M23,21h-7c-0.6,0-1,0.4-1,1s0.4,1,1,1h7c0.6,0,1-0.4,1-1S23.6,21,23,21z" />
                                                                <path
                                                                    d="M23,25h-7c-0.6,0-1,0.4-1,1s0.4,1,1,1h7c0.6,0,1-0.4,1-1S23.6,25,23,25z" />
                                                                <path
                                                                    d="M23,29h-7c-0.6,0-1,0.4-1,1s0.4,1,1,1h7c0.6,0,1-0.4,1-1S23.6,29,23,29z" />
                                                            </g>
                                                        </svg>
                                                    </div>
                                                    <div class="progress-detail">
                                                        <h4 class="text-sm mb-0 text-capitalize">Lịch sử nhập hàng ngày
                                                        </h4>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="card-footer p-3">
                                                <p class="mb-0">Đi đến <a
                                                        href="{{ route('admin.employee-history-check') }}">Đi
                                                        đến lịch sử
                                                        nhập sản phẩm.</a>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                    @if (Auth()->user()->role_id == 4)
                                        <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                                            <div class="card-body">
                                                <a href="{{ route('admin.barcode.scan') }}"><i
                                                        class="material-icons opacity-10 text-white">history</i>
                                                    <div class="progress-widget">
                                                        <div id="circle-progress-01"
                                                            class="circle-progress-01 circle-progress circle-progress-primary text-center"
                                                            data-min-value="0" data-max-value="100" data-value="90"
                                                            data-type="percent">
                                                            <i class="icon-svg">
                                                                <svg style="color:#323232"
                                                                    xmlns="http://www.w3.org/2000/svg" width="50"
                                                                    height="50" fill="currentColor"
                                                                    class="bi bi-qr-code-scan" viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M0 .5A.5.5 0 0 1 .5 0h3a.5.5 0 0 1 0 1H1v2.5a.5.5 0 0 1-1 0zm12 0a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0V1h-2.5a.5.5 0 0 1-.5-.5M.5 12a.5.5 0 0 1 .5.5V15h2.5a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1 0-1H15v-2.5a.5.5 0 0 1 .5-.5M4 4h1v1H4z" />
                                                                    <path d="M7 2H2v5h5zM3 3h3v3H3zm2 8H4v1h1z" />
                                                                    <path d="M7 9H2v5h5zm-4 1h3v3H3zm8-6h1v1h-1z" />
                                                                    <path
                                                                        d="M9 2h5v5H9zm1 1v3h3V3zM8 8v2h1v1H8v1h2v-2h1v2h1v-1h2v-1h-3V8zm2 2H9V9h1zm4 2h-1v1h-2v1h3zm-4 2v-1H8v1z" />
                                                                    <path d="M12 9h2V8h-2z" />
                                                                </svg>
                                                            </i>
                                                        </div>
                                                        <div class="progress-detail">
                                                            <h4 class="text-sm mb-0 text-capitalize">Quét mã vạch</h4>
                                                        </div>
                                                    </div>
                                                </a>
                                                <div class="card-footer p-3">
                                                    <p class="mb-0">Đi đến <a
                                                            href="{{ route('admin.barcode.scan') }}">quét mã
                                                            vạch.</a>
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                    <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                                        <div class="card-body">
                                            <a href="{{ route('admin.profile') }}"><i
                                                    class="material-icons opacity-10 text-white">weekend</i>
                                                <div class="progress-widget">
                                                    <div id="circle-progress-01"
                                                        class="circle-progress-01 circle-progress circle-progress-primary text-center"
                                                        data-min-value="0" data-max-value="100" data-value="90"
                                                        data-type="percent">
                                                        <svg fill="#000000" width="800px" height="800px"
                                                            viewBox="0 0 32 32" version="1.1"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <title>Profile</title>
                                                            <path
                                                                d="M28 9h-1.958v-2.938l-4.042-0.062 0.021 3h-12.146l0.083-3-3.958 0.062v3l-2-0.062c-1.104 0-2 0.896-2 2v14c0 1.104 0.896 2 2 2h24c1.104 0 2-0.896 2-2v-14c0-1.104-0.896-2-2-2zM23 7h2v4h-2v-4zM10 13.812c1.208 0 2.188 1.287 2.188 2.875s-0.98 2.875-2.188 2.875-2.188-1.287-2.188-2.875 0.98-2.875 2.188-2.875zM7 7h2v4h-2v-4zM5.667 22.948c0 0 0.237-1.902 0.776-2.261s2.090-0.598 2.090-0.598 1.006 1.075 1.434 1.075c0.427 0 1.433-1.075 1.433-1.075s1.552 0.238 2.091 0.598c0.633 0.422 0.791 2.261 0.791 2.261h-8.615zM26 22h-9v-1h9v1zM26 20h-9v-1h9v1zM26 18h-9v-1h9v1zM26 16h-9v-1h9v1z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                    <div class="progress-detail">
                                                        <h4 class="text-sm mb-0 text-capitalize">Thông tin tài khoản</h4>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="card-footer p-3">
                                                <p class="mb-0">Đi đến <a href="{{ route('admin.profile') }}">thông tin
                                                        tài
                                                        khoản.</a></p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                                        <div class="card-body">
                                            <a href="#"><i class="material-icons opacity-10 text-white">weekend</i>
                                                <div class="progress-widget">
                                                    <div id="circle-progress-01"
                                                        class="circle-progress-01 circle-progress circle-progress-primary text-center"
                                                        data-min-value="0" data-max-value="100" data-value="90"
                                                        data-type="percent">
                                                        <svg width="1024px" height="1024px" viewBox="0 0 1024 1024"
                                                            xmlns="http://www.w3.org/2000/svg" class="icon">
                                                            <title>Logout</title>
                                                            <path
                                                                d="M868 732h-70.3c-4.8 0-9.3 2.1-12.3 5.8-7 8.5-14.5 16.7-22.4 24.5a353.84 353.84 0 0 1-112.7 75.9A352.8 352.8 0 0 1 512.4 866c-47.9 0-94.3-9.4-137.9-27.8a353.84 353.84 0 0 1-112.7-75.9 353.28 353.28 0 0 1-76-112.5C167.3 606.2 158 559.9 158 512s9.4-94.2 27.8-137.8c17.8-42.1 43.4-80 76-112.5s70.5-58.1 112.7-75.9c43.6-18.4 90-27.8 137.9-27.8 47.9 0 94.3 9.3 137.9 27.8 42.2 17.8 80.1 43.4 112.7 75.9 7.9 7.9 15.3 16.1 22.4 24.5 3 3.7 7.6 5.8 12.3 5.8H868c6.3 0 10.2-7 6.7-12.3C798 160.5 663.8 81.6 511.3 82 271.7 82.6 79.6 277.1 82 516.4 84.4 751.9 276.2 942 512.4 942c152.1 0 285.7-78.8 362.3-197.7 3.4-5.3-.4-12.3-6.7-12.3zm88.9-226.3L815 393.7c-5.3-4.2-13-.4-13 6.3v76H488c-4.4 0-8 3.6-8 8v56c0 4.4 3.6 8 8 8h314v76c0 6.7 7.8 10.5 13 6.3l141.9-112a8 8 0 0 0 0-12.6z" />
                                                        </svg>
                                                    </div>
                                                    <div class="progress-detail">
                                                        <h4 class="text-sm mb-0 text-capitalize">Đăng xuất</h4>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="card-footer p-3">
                                                <p class="mb-0"><a href="{{ route('logout') }}">Đăng xuất.</a></p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <div class="swiper-button swiper-button-prev"></div>
                                <div class="swiper-button swiper-button-next"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    @endif
@endsection
