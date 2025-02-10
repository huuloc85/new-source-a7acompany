@extends('layouts.layout')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Lịch Sử Truy Cập Trang Web</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.delete.history.day') }}" method="POST"
                        class="d-flex flex-wrap align-items-center gap-3 mb-3">
                        @csrf
                        <div>
                            <input type="date" class="form-control" name="date" id="date" required />
                        </div>

                        <button onclick="return confirm('Bạn có chắc muốn xoá lịch sử ngày này không?');"
                            class="btn btn-danger" type="submit">
                            <i class="fas fa-trash-alt"></i>
                            Xóa
                        </button>
                        <a href="{{ route('admin.history.view.all.quantity') }}" class="btn btn-primary"
                            aria-label="Danh sách lịch sử nhân viên nhập sản lượng hàng ngày">
                            <i class="fas fa-history"></i>
                            Lịch sử nhập hàng ngày
                        </a>
                    </form>
                    <form action="{{ route('admin.history.home') }}" method="get" id="submitForm"
                        class="row g-3 align-items-center mb-3">
                        <div class="col-12 col-sm-auto">
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
                        <div class="col-12 col-sm-auto">
                            <select name="activity_type" id="activity-type" class="form-select"
                                onchange="document.getElementById('submitForm').submit();">
                                <option value="" {{ empty(Request::input('activity_type')) ? 'selected' : '' }}>
                                    Tất cả hoạt động
                                </option>
                                @foreach ($activityTypes as $type)
                                    <option value="{{ $type }}"
                                        {{ Request::input('activity_type') == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-auto">
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
                        Tổng số lượng truy cập: {{ $totalHistoryCurrentPage }}
                        / {{ $totalHistoryOverall }}
                    </div>
                    @if ($totalHistoryCurrentPage == 0)
                        <div class="text-center my-2">
                            Hiện tại chưa có lịch sử.
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="text-uppercase text-center align-middle bg-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Tên Nhân Viên</th>
                                    <th>Mã Nhân Viên</th>
                                    <th>Số Lần Đăng Nhập</th>
                                    <th>Thời gian hoạt động cuối cùng</th>
                                    <th>Hoạt Động</th>
                                    <th>Ngày Tháng</th>
                                    <th>Ca Làm Việc</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($loginHistory as $key => $history)
                                    <tr class="text-center align-middle">
                                        <td class="fw-bold">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ $history->employee_name }}
                                        </td>
                                        <td>
                                            {{ $history->employee_code }}
                                        </td>
                                        <td>
                                            {{ $history->login_count }}
                                        </td>
                                        <td>
                                            {{ $history->updated_at }}
                                        </td>
                                        <td class="text-start">
                                            {{ $history->description }}
                                        </td>
                                        <td>
                                            {{ $history->date }}
                                        </td>
                                        <td>
                                            @if (isset($translatedCalendarDetails[$history->employee_id]))
                                                {{ $translatedCalendarDetails[$history->employee_id] }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(
                document.querySelectorAll('[data-bs-toggle="tooltip"]'),
            );
            var tooltipList = tooltipTriggerList.map(
                function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                },
            );
        });
    </script>
@endsection
