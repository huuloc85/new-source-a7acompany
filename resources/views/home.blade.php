@extends('layouts.'.$layout)

@section('styles')
    <style>
        .titleWidget {
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        }

        .titleWidget:hover {
            white-space: normal;
        }
    </style>
@endsection

@php
    $roleId = Auth()->user()->role_id;

    $isManager = Auth()->user()->role->role_name == 'admin';
    $phone = Auth()->user()->phone;
    $isQA = in_array($roleId, [8, 13]);
    $isStorage = $roleId == 4;
    // Ngoại quan + sản suất
    $isReqRole = in_array($roleId, [9, 14, 18, 19]);

    $isEmployee = ! $isManager && ! $isQA && ! $isStorage && ! $isReqRole;

    $managerWidgets = [
        [
            'title' => 'Tổng nhân viên',
            'icon' => 'fas fa-users fa-2x',
            'link' => route('admin.employee.home'),
            'data' => $totalEmployee,
        ],
        [
            'title' => 'Bảng Lịch Sử Chấm Công',
            'icon' => 'fas fa-clock fa-2x',
            'link' => route('admin.attendence.index'),
            'data' => $totalRecord,
        ],
        [
            'title' => 'Bảng Tính Toán Chấm Công',
            'icon' => 'fas fa-calculator fa-2x',
            'link' => route('admin.attendence.records'),
            'data' => $today->format('m-Y'),
        ],
        [
            'title' => 'Tổng chức vụ',
            'icon' => 'fas fa-user-tag fa-2x',
            'link' => route('admin.role.home'),
            'data' => $totalRole,
        ],
        [
            'title' => 'Kế hoạch sản xuất',
            'icon' => 'fas fa-clipboard-list fa-2x',
            'link' => route('admin.product-plan.index'),
            'data' => $totalPlan,
        ],
        [
            'title' => 'Danh sách NV làm việc trong ngày',
            'icon' => 'fas fa-user-check fa-2x',
            'link' => route('admin.checkemployee.view-employee-todo'),
            'data' => $totalCheckEmployee,
        ],
        [
            'title' => 'Tổng lịch làm việc',
            'icon' => 'far fa-calendar-alt fa-2x',
            'link' => route('admin.celender.home'),
            'data' => $totalCelender,
        ],
        [
            'title' => 'Tổng sản phẩm',
            'icon' => 'fas fa-boxes fa-2x',
            'link' => route('admin.product.home'),
            'data' => $totalProduct,
        ],
        [
            'title' => 'Tổng lịch sử',
            'icon' => 'fas fa-history fa-2x',
            'link' => route('admin.history.home'),
            'data' => $totalHistory,
        ],
        [
            'title' => 'Danh sách PO',
            'icon' => 'fas fa-file-invoice-dollar fa-2x',
            'link' => route('admin.checkpo.index'),
            'data' => $today->format('m-Y'),
        ],
        [
            'title' => 'Danh Sách Tem Cần In',
            'icon' => 'fas fa-print fa-2x',
            'link' => route('admin.checkstamp'),
            'data' => $today->format('d-m'),
        ],
        [
            'title' => 'Kho Xuất Hàng',
            'icon' => 'fas fa-box fa-2x',
            'link' => route('admin.storage.index'),
            'data' => 'Tháng '.$today->format('m'),
        ],
    ];
    if (in_array($phone, ['ctyvinhvinhphat1', 'ctyvinhvinhphat2', 'ctyvinhvinhphat5'])) {
        array_unshift($managerWidgets, [
            'title' => 'Tổng bảng lương',
            'icon' => 'fas fa-money-check-alt fa-2x',
            'link' => route('admin.salary.home'),
            'data' => $totalSalary,
        ]);
    }

    $employeeWidgets = [
        [
            'title' => 'Bảng lịch sử chấm công',
            'icon' => 'fas fa-clipboard-user fa-2x',
            'link' => route('admin.employee.attendence'),
        ],
        [
            'title' => 'Bảng tính công',
            'icon' => 'fas fa-calculator fa-2x',
            'link' => route('admin.employee.attendence_caculate_records'),
        ],
        [
            'title' => 'Lịch làm việc',
            'icon' => 'fas fa-calendar-day fa-2x',
            'link' => route('admin.employee-show.celender'),
        ],
        [
            'title' => 'Bảng lương',
            'icon' => 'fas fa-money-check-alt fa-2x',
            'link' => route('admin.employee-show.salary'),
        ],
    ];

    if (in_array($roleId, [14, 18, 19])) {
        array_unshift($employeeWidgets, [
            'title' => 'Lịch làm việc nhân viên',
            'icon' => 'fas fa-calendar-alt fa-2x',
            'link' => route('admin.celender.home'),
        ]);
        array_unshift($employeeWidgets, [
            'title' => 'Lịch Hoạt Động Trong Ngày',
            'icon' => 'fas fa-chart-line fa-2x',
            'link' => route('admin.checkemployee.view-employee-todo'),
        ]);
    }
    if ($isStorage) {
        array_unshift($employeeWidgets, [
            'title' => 'Quét Barcode',
            'icon' => 'fas fa-barcode fa-2x',
            'link' => route('admin.barcode.scan'),
        ]);
        array_unshift($employeeWidgets, [
            'title' => 'Quét QR Code',
            'icon' => 'fas fa-qrcode fa-2x',
            'link' => route('admin.barcode.scanQr'),
        ]);
        array_unshift($employeeWidgets, [
            'title' => 'Kho Đã Xuất Hàng',
            'icon' => 'fas fa-box-open fa-2x',
            'link' => route('admin.storage.index'),
        ]);
    }

    if (in_array($roleId, [9, 10]) || $isEmployee || $isReqRole) {
        array_unshift($employeeWidgets, [
            'title' => 'Chọn sản phẩm hoạt động',
            'icon' => 'fas fa-boxes fa-2x',
            'link' => route('admin.employee.check-employee-todo'),
        ]);
        array_unshift($employeeWidgets, [
            'title' => 'Lịch sử nhập hàng ngày',
            'icon' => 'fas fa-history fa-2x',
            'link' => route('admin.employee-history-check'),
        ]);
    }

    if ($isReqRole) {
        array_unshift($employeeWidgets, [
            'title' => 'Yêu Cầu In Tem',
            'icon' => 'fas fa-print fa-2x',
            'link' => route('admin.send-stamp'),
        ]);
    }

    if ($isQA) {
        array_unshift($employeeWidgets, [
            'title' => 'Tạo Tem Thùng',
            'icon' => 'fas fa-box fa-2x',
            'link' => route('admin.product.barcode'),
            'data' => $today->format('d-m'),
        ]);
        array_unshift($employeeWidgets, [
            'title' => 'Tạo Tem Bịch',
            'icon' => 'fas fa-sheet-plastic fa-2x',
            'link' => route('admin.product.packing'),
            'data' => $today->format('d-m'),
        ]);
        array_unshift($employeeWidgets, [
            'title' => 'Danh Sách Tem Cần In',
            'icon' => 'fas fa-print fa-2x',
            'link' => route('admin.checkstamp'),
            'data' => $today->format('d-m'),
        ]);
    }

    array_push($employeeWidgets, [
        'title' => 'Thông tin tài khoản',
        'icon' => 'fas fa-id-card fa-2x',
        'link' => route('admin.profile'),
    ]);

    array_push($employeeWidgets, [
        'title' => 'Đăng xuất',
        'icon' => 'fas fa-right-from-bracket fa-2x',
        'link' => route('logout'),
    ]);
@endphp

@section('content')
    @if (session('birthday_check'))
        @include('modal.birthday')
    @endif

    @if (session('has_duties'))
        @include('modal.on-duty')
    @endif

    @if ($isManager)
        <div class="row">
            <div class="col-12">
                <div class="row">
                    @foreach ($managerWidgets as $key => $widget)
                        <div class="col-12 col-md-6 col-lg-4 col-xxl-3">
                            <a
                                class="card bg-body-tertiary border-transparent shadow text-decoration-none"
                                href="{{ $widget['link'] }}"
                                role="button"
                            >
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col overflow-hidden">
                                            <div
                                                class="text-xs fw-normal text-body-secondary mb-1 titleWidget"
                                            >
                                                {{ $widget['title'] }}
                                            </div>
                                            <div class="fs-4 fw-semibold">
                                                {{ $widget['data'] }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div
                                                class="avatar avatar-lg bg-body text-black"
                                            >
                                                <i
                                                    class="{{ $widget['icon'] }}"
                                                ></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-lg-8 col-12">
                        <div class="card">
                            <div class="card-header pb-0">
                                <div class="row">
                                    <div class="col-lg-6 col-7">
                                        <h6>
                                            Danh sách 10 bảng lương gần nhất
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body px-0 pb-2">
                                <div class="table-responsive">
                                    <table
                                        class="table align-items-center mb-0 table-hover"
                                    >
                                        <thead>
                                            <tr>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                                >
                                                    STT
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2"
                                                >
                                                    Tiêu đề
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2"
                                                >
                                                    Tổng(VND)
                                                </th>
                                                <th
                                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                                >
                                                    Ngày bắt đầu
                                                </th>
                                                <th
                                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                                >
                                                    Ngày kết thúc
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($salaryManagers as $key => $salaryManager)
                                                <tr>
                                                    <td>
                                                        <div
                                                            class="d-flex px-3 py-1"
                                                        >
                                                            {{ $loop->iteration }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p
                                                            class="text-xs font-weight-bold mb-0 px-3"
                                                        >
                                                            <a
                                                                href="{{ route('admin.salary.detail', $salaryManager->id) }}"
                                                            >
                                                                {{ $salaryManager->title }}
                                                            </a>
                                                        </p>
                                                    </td>
                                                    <td
                                                        class="align-middle text-sm"
                                                    >
                                                        <p
                                                            class="text-xs font-weight-bold mb-0"
                                                        >
                                                            {{ number_format($salaryManager->total, 2) }}
                                                        </p>
                                                    </td>
                                                    <td
                                                        class="align-middle text-center text-sm"
                                                    >
                                                        <p
                                                            class="text-xs font-weight-bold mb-0"
                                                        >
                                                            {{ $salaryManager->formatTimeDMY($salaryManager->start_date) }}
                                                        </p>
                                                    </td>
                                                    <td
                                                        class="align-middle text-center text-sm"
                                                    >
                                                        <p
                                                            class="text-xs font-weight-bold mb-0"
                                                        >
                                                            {{ $salaryManager->formatTimeDMY($salaryManager->end_date) }}
                                                        </p>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            @if ($totalSalary == 0)
                                                <tr>
                                                    <td
                                                        colspan="4"
                                                        class="text-center pt-4"
                                                    >
                                                        Hiện tại chưa có bảng
                                                        lương nào.
                                                        <br />
                                                        Vui lòng
                                                        <a
                                                            class="href"
                                                            href="{{ route('admin.salary.getimport') }}"
                                                        >
                                                            Thêm bảng lương
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-12">
                        <div class="card">
                            <div class="card-header pb-0">
                                <h6>Danh sách 10 lịch làm việc gần nhất</h6>
                            </div>
                            <div class="card-body px-0">
                                <div class="table-responsive">
                                    <table
                                        class="table align-items-center mb-0 table-hover"
                                    >
                                        <thead>
                                            <tr>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                                >
                                                    STT
                                                </th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2"
                                                >
                                                    Tiêu đề
                                                </th>
                                                <th
                                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                                >
                                                    Ngày bắt đầu
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($celenders as $key => $celender)
                                                <tr>
                                                    <td>
                                                        <div
                                                            class="d-flex px-3 py-1"
                                                        >
                                                            {{ $loop->iteration }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p
                                                            class="text-xs font-weight-bold mb-0 px-3"
                                                        >
                                                            <a
                                                                href="{{ route('admin.celender.detail', $celender->id) }}"
                                                            >
                                                                {{ $celender->title }}
                                                            </a>
                                                        </p>
                                                    </td>
                                                    <td
                                                        class="align-middle text-center text-sm"
                                                    >
                                                        <p
                                                            class="text-xs font-weight-bold mb-0"
                                                        >
                                                            {{ $celender->formatTimeDMY($celender->date) }}
                                                        </p>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            @if ($totalCelender == 0)
                                                <tr>
                                                    <td
                                                        colspan="4"
                                                        class="text-center pt-4"
                                                    >
                                                        Chưa có lịch làm việc
                                                        nào.
                                                        <br />
                                                        Đi đến
                                                        <a
                                                            href="{{ route('admin.celender.home') }}"
                                                        >
                                                            danh sách lịch làm
                                                            việc.
                                                        </a>
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
        </div>
    @else
        <div class="row">
            @foreach ($employeeWidgets as $key => $widget)
                <div class="col-12 col-md-6 col-lg-4 col-xxl-3">
                    <a
                        class="card bg-body-tertiary border-transparent shadow text-decoration-none"
                        href="{{ $widget['link'] }}"
                        role="button"
                    >
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <p
                                        class="fw-semibold align-items-center text-capitalize"
                                    >
                                        {{ $widget['title'] }}
                                    </p>
                                </div>
                                <div class="col-auto">
                                    <div
                                        class="avatar avatar-lg bg-body text-black"
                                    >
                                        <i class="{{ $widget['icon'] }}"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#birthdayModal').modal('show');
        });
        $(document).ready(function () {
            // Kiểm tra nếu modal tồn tại, hiển thị nó
            if ($('#cleaningDutyModal').length) {
                $('#cleaningDutyModal').modal('show');
            }
        });
    </script>
@endsection
