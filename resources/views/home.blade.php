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

@section('content')
    @if (session('birthday_check'))
        @include('modal.birthday')
    @endif

    @if (session('has_duties'))
        @include('modal.on-duty')
    @endif

    @php
        // Lấy user & permissions thuộc display_area = 'home'
        $user = Auth::user();
        $homePerms = $user?->role?->permissions?->where('display_area', 'home') ?? collect();

        // Mảng key & title chỉ của các permission thuộc 'home'
        $permissions = $homePerms->pluck('key')->toArray();
        $permissionTitles = $homePerms->pluck('name', 'key')->toArray();

        $widgets = [];

        // admin
        if (in_array('view_total_employees', $permissions)) {
            $widgets[] = [
                'title' => $permissionTitles['view_total_employees'] ?? 'Tên mặc định',
                'icon' => 'fas fa-users fa-2x',
                'link' => route('admin.employee.home'),
                'data' => $totalEmployee ?? 0,
            ];
        }

        if (in_array('view_attendance_history', $permissions)) {
            $widgets[] = [
                'title' => 'Bảng Lịch Sử Chấm Công',
                'icon' => 'fas fa-clock fa-2x',
                'link' => route('admin.attendence.index'),
                'data' => $totalRecord ?? 0,
            ];
        }

        if (in_array('view_attendance_calculation', $permissions)) {
            $widgets[] = [
                'title' => 'Bảng Tính Toán Chấm Công',
                'icon' => 'fas fa-calculator fa-2x',
                'link' => route('admin.attendence.records'),
                'data' => $today->format('m-Y'),
            ];
        }

        if (in_array('view_total_positions', $permissions)) {
            $widgets[] = [
                'title' => 'Tổng chức vụ',
                'icon' => 'fas fa-user-tag fa-2x',
                'link' => route('admin.role.home'),
                'data' => $totalRole ?? 0,
            ];
        }

        if (in_array('view_production_plan', $permissions)) {
            $widgets[] = [
                'title' => 'Kế hoạch sản xuất',
                'icon' => 'fas fa-clipboard-list fa-2x',
                'link' => route('admin.product-plan.index'),
                'data' => $totalPlan ?? 0,
            ];
        }

        if (in_array('view_today_employees', $permissions)) {
            $widgets[] = [
                'title' => 'Danh sách NV làm việc trong ngày',
                'icon' => 'fas fa-user-check fa-2x',
                'link' => route('admin.checkemployee.view-employee-todo'),
                'data' => $totalCheckEmployee ?? 0,
            ];
        }

        if (in_array('view_total_schedule', $permissions)) {
            $widgets[] = [
                'title' => 'Tổng lịch làm việc',
                'icon' => 'far fa-calendar-alt fa-2x',
                'link' => route('admin.celender.home'),
                'data' => $totalCelender ?? 0,
            ];
        }

        if (in_array('view_total_products', $permissions)) {
            $widgets[] = [
                'title' => 'Tổng sản phẩm',
                'icon' => 'fas fa-boxes fa-2x',
                'link' => route('admin.product.home'),
                'data' => $totalProduct ?? 0,
            ];
        }

        if (in_array('view_total_history', $permissions)) {
            $widgets[] = [
                'title' => 'Tổng lịch sử',
                'icon' => 'fas fa-history fa-2x',
                'link' => route('admin.history.home'),
                'data' => $totalHistory ?? 0,
            ];
        }

        if (in_array('view_po_list', $permissions)) {
            $widgets[] = [
                'title' => 'Danh sách PO',
                'icon' => 'fas fa-file-invoice-dollar fa-2x',
                'link' => route('admin.checkpo.index'),
                'data' => $today->format('m-Y'),
            ];
        }

        if (in_array('view_labels_to_print', $permissions)) {
            $widgets[] = [
                'title' => 'Danh Sách Tem Cần In',
                'icon' => 'fas fa-print fa-2x',
                'link' => route('admin.checkstamp'),
                'data' => $today->format('d-m'),
            ];
        }

        if (in_array('view_export_warehouse', $permissions)) {
            $widgets[] = [
                'title' => 'Kho Xuất Hàng',
                'icon' => 'fas fa-box fa-2x', // Đã sửa 'icon  ' thành 'icon'
                'link' => route('admin.storage.index'),
                'data' => 'Tháng '.$today->format('m'),
            ];
        }

        if (in_array('view_total_salary', $permissions)) {
            $widgets[] = [
                'title' => 'Tổng bảng lương',
                'icon' => 'fas fa-money-check-alt fa-2x',
                'link' => route('admin.salary.home'),
                'data' => $totalSalary ?? 0,
            ];
        }

        // employee
        if (in_array('view_attendance_sheet_history', $permissions)) {
            $widgets[] = [
                'title' => $permissionTitles['view_attendance_sheet_history'] ?? 'Tên mặc định',
                'icon' => 'fas fa-clock fa-2x',
                'link' => route('admin.employee.attendence'),
            ];
        }

        if (in_array('view_work_time_calc_sheet', $permissions)) {
            $widgets[] = [
                'title' => $permissionTitles['view_work_time_calc_sheet'] ?? 'Tên mặc định',
                'icon' => 'fas fa-calculator fa-2x',
                'link' => route('admin.employee.attendence_caculate_records'),
            ];
        }

        if (in_array('view_work_schedule', $permissions)) {
            $widgets[] = [
                'title' => $permissionTitles['view_work_schedule'] ?? 'Tên mặc định',
                'icon' => 'far fa-calendar-alt fa-2x',
                'link' => route('admin.employee-show.celender'),
            ];
        }

        if (in_array('view_salary_sheet', $permissions)) {
            $widgets[] = [
                'title' => $permissionTitles['view_salary_sheet'] ?? 'Tên mặc định',
                'icon' => 'fas fa-money-bill-wave fa-2x',
                'link' => route('admin.employee-show.salary'),
            ];
        }

        if (in_array('view_employee_schedule', $permissions)) {
            $widgets[] = [
                'title' => $permissionTitles['view_employee_schedule'] ?? 'Tên mặc định',
                'icon' => 'fas fa-user-clock fa-2x',
                'link' => route('admin.celender.home'),
            ];
        }

        if (in_array('view_daily_activities', $permissions)) {
            $widgets[] = [
                'title' => $permissionTitles['view_daily_activities'] ?? 'Tên mặc định',
                'icon' => 'fas fa-tasks fa-2x',
                'link' => route('admin.checkemployee.view-employee-todo'),
            ];
        }

        if (in_array('scan_barcode', $permissions)) {
            $widgets[] = [
                'title' => $permissionTitles['scan_barcode'] ?? 'Tên mặc định',
                'icon' => 'fas fa-barcode fa-2x',
                'link' => route('admin.barcode.scan'),
            ];
        }

        if (in_array('scan_qrcode', $permissions)) {
            $widgets[] = [
                'title' => $permissionTitles['scan_qrcode'] ?? 'Tên mặc định',
                'icon' => 'fas fa-qrcode fa-2x',
                'link' => route('admin.barcode.scanQr'),
            ];
        }

        if (in_array('view_exported_warehouse', $permissions)) {
            $widgets[] = [
                'title' => $permissionTitles['view_exported_warehouse'] ?? 'Tên mặc định',
                'icon' => 'fas fa-warehouse fa-2x',
                'link' => route('admin.storage.index'),
            ];
        }

        if (in_array('select_active_product', $permissions)) {
            $widgets[] = [
                'title' => $permissionTitles['select_active_product'] ?? 'Tên mặc định',
                'icon' => 'fas fa-box-open fa-2x',
                'link' => route('admin.employee.check-employee-todo'),
            ];
        }

        if (in_array('view_daily_import_history', $permissions)) {
            $widgets[] = [
                'title' => $permissionTitles['view_daily_import_history'] ?? 'Tên mặc định',
                'icon' => 'fas fa-truck-loading fa-2x',
                'link' => route('admin.employee-history-check'),
            ];
        }

        if (in_array('request_label_printing', $permissions)) {
            $widgets[] = [
                'title' => $permissionTitles['request_label_printing'] ?? 'Tên mặc định',
                'icon' => 'fas fa-print fa-2x',
                'link' => route('admin.send-stamp'),
            ];
        }

        if (in_array('create_carton_label', $permissions)) {
            $widgets[] = [
                'title' => $permissionTitles['create_carton_label'] ?? 'Tên mặc định',
                'icon' => 'fas fa-box fa-2x',
                'link' => route('admin.product.barcode'),
            ];
        }

        if (in_array('create_bag_label', $permissions)) {
            $widgets[] = [
                'title' => $permissionTitles['create_bag_label'] ?? 'Tên mặc định',
                'icon' => 'fas fa-shopping-bag fa-2x',
                'link' => route('admin.product.packing'),
            ];
        }

        if (in_array('view_account_info', $permissions)) {
            $widgets[] = [
                'title' => $permissionTitles['view_account_info'] ?? 'Tên mặc định',
                'icon' => 'fas fa-user-circle fa-2x',
                'link' => route('admin.product.packing'),
            ];
        }

        if (in_array('logout', $permissions)) {
            $widgets[] = [
                'title' => $permissionTitles['logout'] ?? 'Tên mặc định',
                'icon' => 'fas fa-sign-out-alt fa-2x',
                'link' => route('admin.product.packing'),
            ];
        }
    @endphp

    {{-- WIDGETS --}}
    <div class="row">
        @foreach ($widgets as $widget)
            <div class="col-12 col-md-6 col-lg-4 col-xxl-3">
                <a
                    class="card bg-body-tertiary border-transparent shadow text-decoration-none"
                    href="{{ $widget['link'] }}"
                    role="button">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col overflow-hidden">
                                <p class="fw-semibold align-items-center text-capitalize mb-1 titleWidget">
                                    {{ $widget['title'] }}
                                </p>
                                @if (! empty($widget['data']))
                                    <div class="fs-4 fw-semibold">
                                        {{ $widget['data'] }}
                                    </div>
                                @endif
                            </div>
                            <div class="col-auto">
                                <div class="avatar avatar-lg bg-body text-black">
                                    <i class="{{ $widget['icon'] ?? 'fas fa-question fa-2x' }}"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    {{-- BẢNG LƯƠNG --}}
    @if (in_array('view_total_salary', $permissions))
        <div class="row mt-4">
            <div class="col-lg-8 col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Danh sách 10 bảng lương gần nhất</h6>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0 table-hover">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tiêu đề</th>
                                        <th>Tổng (VND)</th>
                                        <th class="text-center">Ngày bắt đầu</th>
                                        <th class="text-center">Ngày kết thúc</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($salaryManagers as $salaryManager)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <a href="{{ route('admin.salary.detail', $salaryManager->id) }}">
                                                    {{ $salaryManager->title }}
                                                </a>
                                            </td>
                                            <td>{{ number_format($salaryManager->total, 2) }}</td>
                                            <td class="text-center">
                                                {{ $salaryManager->formatTimeDMY($salaryManager->start_date) }}
                                            </td>
                                            <td class="text-center">
                                                {{ $salaryManager->formatTimeDMY($salaryManager->end_date) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center pt-4">
                                                Hiện tại chưa có bảng lương nào.
                                                <br />
                                                <a href="{{ route('admin.salary.getimport') }}">Thêm bảng lương</a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- LỊCH LÀM VIỆC --}}
            @if (in_array('view_total_schedule', $permissions))
                <div class="col-lg-4 col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h6>Danh sách 10 lịch làm việc gần nhất</h6>
                        </div>
                        <div class="card-body px-0">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0 table-hover">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Tiêu đề</th>
                                            <th class="text-center">Ngày bắt đầu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($celenders as $celender)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ route('admin.celender.detail', $celender->id) }}">
                                                        {{ $celender->title }}
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    {{ $celender->formatTimeDMY($celender->date) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center pt-4">
                                                    Chưa có lịch làm việc nào.
                                                    <br />
                                                    <a href="{{ route('admin.celender.home') }}">
                                                        Đi đến danh sách lịch làm việc
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#birthdayModal').modal('show')
        })
        $(document).ready(function () {
            // Kiểm tra nếu modal tồn tại, hiển thị nó
            if ($('#cleaningDutyModal').length) {
                $('#cleaningDutyModal').modal('show')
            }
        })
    </script>
@endsection
