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

    .table td,
    .table th {
        white-space: normal;
    }

    .card-body {
        display: flex;
        flex-direction: column;
        align-items: start;
    }

    .card-body>* {
        margin-bottom: 5px;
    }

    .card-body form {
        display: flex;
        align-items: center;
    }

    .card-body form .form-group {
        margin-right: 10px;
    }
</style>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                <div class="border-radius-lg ps-2 pt-4 pb-3">
                    <h4 class="card-title mb-0">Lịch Sử Truy Cập Trang Web</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <form action="{{ route('admin.delete.history.day') }}" method="POST"
                        class="d-flex align-items-center">
                        @csrf
                        <div class="form-group me-2 mb-0">
                            <input type="date" class="form-control" name="date" id="date" required>
                        </div>
                        <button onclick="return confirm('Bạn có chắc muốn xoá lịch sử ngày này không?');"
                            class='btn btn-danger' type="submit">
                            <i class="fas fa-trash-alt"></i> Xóa
                        </button>
                    </form>
                    <a href="{{ route('admin.history.view.all.quantity') }}" class="btn btn-primary mx-2"
                        aria-label="Danh sách lịch sử nhân viên nhập sản lượng hàng ngày">
                        <i class="fas fa-history"></i> Lịch sử nhập hàng ngày
                    </a>

                </div>

                <form action="{{ route('admin.history.home') }}" method="get" id="submitForm"
                    class="row gx-3 gy-2 align-items-center mb-2">
                    <div class="col-auto">
                        <select name="date" id="date-select" class="form-select"
                            onchange="document.getElementById('submitForm').submit();">
                            <option disabled selected>Chọn Ngày</option>
                            @foreach ($days as $day)
                            <option value="{{ $day }}" {{ $day == $selectedDate ? 'selected' : '' }}>
                                {{ Carbon\Carbon::parse($day)->format('d-m') }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <select name="activity_type" id="activity-type" class="form-select"
                            onchange="document.getElementById('submitForm').submit();">
                            <option value="" {{ empty(Request::input('activity_type')) ? 'selected' : '' }}>Tất cả
                                hoạt động</option>
                            @foreach ($activityTypes as $type)
                            <option value="{{ $type }}"
                                {{ Request::input('activity_type') == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <select name="month" id="month-select" class="form-select"
                            onchange="document.getElementById('submitForm').submit();">
                            <option disabled selected>Chọn Tháng</option>
                            @foreach ($months as $month)
                            <option value="{{ $month }}"
                                {{ $month == $selectedMonthYear ? 'selected' : '' }}>
                                {{ Carbon\Carbon::createFromFormat('m-Y', $month)->format('m-Y') }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </form>
                <div>
                    Tổng số lượng truy cập: {{ $totalHistoryCurrentPage }} / {{ $totalHistoryOverall }}
                </div>
            </div>
            <div class="px-2 pb-2">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0 table-hover">
                        <thead>
                            <tr>
                                <th
                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                    STT</th>
                                <th
                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                    Tên Nhân Viên</th>
                                <th
                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                    Mã Nhân Viên</th>
                                <th
                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                    Số Lần Đăng Nhập</th>
                                <th
                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                    Thời gian hoạt động cuối cùng</th>
                                <th
                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                    Hoạt Động</th>
                                <th
                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                    Ngày Tháng</th>
                                <th
                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                    Ca Làm Việc</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($loginHistory as $key => $history)
                            <tr>
                                <td class="text-center">
                                    <div class="d-flex px-3 py-1 justify-content-center">
                                        {{ $loop->iteration }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $history->employee_name }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $history->employee_code }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $history->login_count }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $history->updated_at }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $history->description }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $history->date }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">
                                        @if (isset($translatedCalendarDetails[$history->employee_id]))
                                        {{ $translatedCalendarDetails[$history->employee_id] }}
                                        @endif
                                    </p>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($totalHistoryCurrentPage == 0)
                <div class="text-center">
                    Hiện tại chưa có lịch sử.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endsection