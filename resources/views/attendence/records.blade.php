@extends('master')

@section('content')
    <style>
        .form-control-search {
            padding-left: 2.5rem;
            /* Khoảng cách cho biểu tượng tìm kiếm */
            border-radius: 25px;
            /* Bo tròn góc */
            border: 1px solid #ced4da;
            /* Màu viền */
            font-size: 0.875rem;
            /* Kích thước chữ */
            max-width: 400px;
            /* Kích thước tối đa của input */
            width: 100%;
            /* Đảm bảo chiếm toàn bộ không gian khả dụng trong container */
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            /* Khoảng cách từ viền trái */
            top: 50%;
            transform: translateY(-50%);
            /* Căn giữa theo chiều dọc */
            font-size: 1rem;
            /* Kích thước biểu tượng */
            color: #6c757d;
            /* Màu sắc biểu tượng */
        }

        .form-control::placeholder {
            color: #6c757d;
            /* Màu sắc placeholder */
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                    <div class="border-radius-lg ps-2 pt-4 pb-3 d-flex align-items-center justify-content-between">
                        <h4 class="card-title mb-0">Bảng Tính Công Tháng
                            {{ \Carbon\Carbon::parse($currentMonth)->format('m-Y') }}</h4>
                    </div>
                </div>
                <div class="d-flex align-items-center my-2 ps-2">
                    <button type="button" class="btn btn-primary btn-sm rounded-pill shadow-sm me-2" data-bs-toggle="modal"
                        data-bs-target="#filterModal">
                        Lọc Thông Tin
                    </button>
                    <div class="position-relative flex-grow-1 ms-2">
                        <input type="text" id="search" class="form-control form-control-search"
                            placeholder="Tìm kiếm theo tên nhân viên hoặc ngày chấm công">
                        <i class="search-icon fas fa-search"></i>
                    </div>
                </div>
                <div class="card-body">
                    @if ($records->isEmpty())
                        <p class="text-center">Hiện tại chưa có thông tin nào.</p>
                    @else
                        <table class="table table-hover mb-4">
                            <thead class="text-center">
                                <tr>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        STT</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Mã Nhân Viên</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Tên Nhân Viên</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Ngày Chấm</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Ngày Trong Tuần</th>

                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Giờ Vào</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Giờ Ra</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Tổng Giờ Làm Việc(H)</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Giờ Hành Chính(H) </th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Giờ Tăng Ca(H)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($records as $record)
                                    <tr class="text-center">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $record->employee_code }}</td>
                                        <td>{{ $record->employee ? $record->employee->name : 'Không xác định' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($record->date)->format('d-m-Y') }}</td>
                                        <td>{{ $record->day_of_week }}</td>
                                        <td class="{{ $record->time_in ? '' : 'text-danger' }}">
                                            {{ $record->time_in ? \Carbon\Carbon::parse($record->time_in)->format('H:i:s') : 'Chưa chấm công vào' }}
                                        </td>
                                        <td class="{{ $record->time_out ? '' : 'text-danger' }}">
                                            {{ $record->time_out ? \Carbon\Carbon::parse($record->time_out)->format('H:i:s') : 'Chưa chấm công về' }}
                                        </td>
                                        <td class="{{ $record->total_hours ? '' : 'text-danger' }}">
                                            @if ($record->total_hours)
                                                <strong>{{ $record->total_hours }}</strong>
                                            @else
                                                {{ 'Chấm công không đủ' }}
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $record->administrative_hours > 0 ? number_format($record->administrative_hours, 2) : '0' }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $record->overtime_hours > 0 ? number_format($record->overtime_hours, 2) : '0' }}</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for search filters -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Tìm Kiếm Thông Tin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="GET" action="{{ route('admin.attendence.records') }}">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="month" class="form-label">Tháng:</label>
                                <input type="month" name="month" id="month" class="form-control"
                                    placeholder="Chọn tháng" value="{{ request('month', $currentMonth) }}">
                            </div>
                            {{-- <div class="col-md-12 mb-3">
                                <label for="employee_name" class="form-label">Tên Nhân Viên:</label>
                                <input type="text" id="employee_name" name="employee_name" class="form-control"
                                    value="{{ request('employee_name') }}">
                            </div> --}}
                            <div class="col-md-12 mb-3">
                                <label for="start_date" class="form-label">Từ ngày:</label>
                                <input type="date" id="start_date" name="start_date" class="form-control"
                                    value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="end_date" class="form-label">Đến ngày:</label>
                                <input type="date" id="end_date" name="end_date" class="form-control"
                                    value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="time_filter" class="form-label">Thời Gian:</label>
                                <select id="time_filter" name="time_filter" class="form-select">
                                    @foreach (config('a7a.list_category') as $key => $record)
                                        <option value="{{ $key }}"
                                            {{ request('time_filter') === $key ? 'selected' : '' }}>
                                            {{ $record }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const table = document.querySelector('.table');
            const rows = table.querySelectorAll('tbody tr');

            searchInput.addEventListener('input', function() {
                const searchTerm = searchInput.value.toLowerCase();

                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    let found = false;

                    cells.forEach(cell => {
                        if (cell.textContent.toLowerCase().includes(searchTerm)) {
                            found = true;
                        }
                    });

                    if (searchTerm === '' || found) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection
