@extends('master')

@section('content')
    <style>
        .form-control,
        .form-select {
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }

        .form-control-search {
            padding-left: 2.5rem;
            border-radius: 25px;
            border: 1px solid #ced4da;
            font-size: 0.875rem;
            width: 100%
        }

        .position-relative {
            position: relative;
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1rem;
            color: #6c757d;
        }

        .form-control::placeholder {
            color: #6c757d;
        }

        input#search:focus::placeholder {
            color: transparent;
        }

        @media (max-width: 768px) {

            .mb-2,
            .btn {
                width: 100%;
            }

            .btn {
                margin-bottom: 0.5rem;
            }

            .form-control-search {

                width: 100%
            }
        }

        .position-relative {
            position: relative;
        }

        #loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            /* Tạo hiệu ứng mờ nền */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
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
                <div class="d-flex flex-wrap align-items-center my-2 ps-2 pe-2">
                    <div class="mb-2 d-flex align-items-center ps-2">
                        <button type="button" class="btn btn-primary btn-sm rounded-pill shadow-sm me-2"
                            data-bs-toggle="modal" data-bs-target="#filterModal">
                            Lọc Thông Tin
                        </button>
                        <a href="{{ route('admin.attendence.index') }}"
                            class="btn btn-success btn-sm rounded-pill shadow-sm">
                            Bảng Lịch Sử Chấm Công
                        </a>
                    </div>
                    <div class="mb-2 me-2 flex-grow-1 position-relative ps-2">
                        <input type="text" id="search" class="form-control form-control-search"
                            placeholder="Tìm kiếm theo tên nhân viên hoặc ngày chấm công">
                        <i class="search-icon fas fa-search"></i>
                    </div>
                    <div class="mb-2 me-2 flex-grow-1 position-relative ps-2">
                        {{-- <!-- Overlay Loading Spinner -->
                        <div id="loading-overlay" style="display: none;">
                            <div class="spinner"></div>
                        </div> --}}

                        <!-- Form Export -->
                        <form id="export-form" class="d-flex align-items-center">
                            <div class="me-2 d-flex align-items-center">
                                <label for="start_date" class="form-label mb-0 me-1">Ngày Bắt Đầu:</label>
                                <input type="date" id="start_date" name="start_date" class="form-control" required>
                            </div>
                            <div class="me-2 d-flex align-items-center">
                                <label for="end_date" class="form-label mb-0 me-1">Ngày Kết Thúc:</label>
                                <input type="date" id="end_date" name="end_date" class="form-control" required>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" id="export-button">Export</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive position-relative">
                        <div id="loading-overlay"
                            style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 9999; align-items: center; justify-content: center; flex-direction: column;">
                            <div class="spinner"></div>
                            <p style="color: white; margin-top: 10px;">Đang xuất dữ liệu...</p>
                        </div>
                        @if ($records->isEmpty())
                            <p class="text-center">Hiện tại chưa có thông tin nào.</p>
                        @else
                            <input type="checkbox" id="filter_absent" name="filter_absent" value="1"
                                {{ request('filter_absent') ? 'checked' : '' }}>
                            Chỉ hiển thị những người quên chấm công
                            <table id="attendanceTable" class="table table-hover mb-4">
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
                                        {{-- <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                            style="{{ is_null(request('time_filter')) || request('time_filter') === 'none' || in_array(request('time_filter'), ['qc_day', 'working_hours']) ? 'display: none;' : '' }}">
                                            Ca Làm Việc
                                        </th> --}}
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
                                            <td>{{ $record->employee ? $record->employee->name : 'Không xác định' }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($record->date)->format('d-m-Y') }}</td>
                                            <td>{{ $record->day_of_week }}</td>
                                            <td class="{{ $record->time_in ? '' : 'text-danger' }}">
                                                {{ $record->time_in ? \Carbon\Carbon::parse($record->time_in)->format('H:i:s') : 'Chưa chấm công vào' }}
                                            </td>
                                            <td class="{{ $record->time_out ? '' : 'text-danger' }}">
                                                {{ $record->time_out ? \Carbon\Carbon::parse($record->time_out)->format('H:i:s') : 'Chưa chấm công ra' }}
                                            </td>
                                            {{-- <td
                                                style="{{ request('time_filter') === null || request('time_filter') === 'none' || in_array(request('time_filter'), ['qc_day', 'working_hours']) ? 'display: none;' : '' }}">
                                                <strong>{{ $record->shift === 'Đổi lịch đi làm' ? $record->shift : '' }}</strong>
                                                {{ $record->shift !== 'Đổi lịch đi làm' ? $record->shift : '' }}
                                            </td> --}}
                                            <td>{{ $record->shift }}</td>
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
    </div>

    <!-- Modal tìm kiếm -->
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
                            <div class="col-md-12 mb-3">
                                <label for="employee_name" class="form-label">Tên Nhân Viên:</label>
                                <input type="text" id="employee_name" name="employee_name" class="form-control"
                                    value="{{ request('employee_name') }}">
                            </div>
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
                                <label for="time_filter" class="form-label">Danh mục làm việc:</label>
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
            const filterCheckbox = document.getElementById('filter_absent');
            const attendanceTable = document.getElementById('attendanceTable');
            const rows = attendanceTable.querySelectorAll('tbody tr');

            function filterRows() {
                const searchTerm = searchInput.value.toLowerCase();
                const showAbsentOnly = filterCheckbox.checked;

                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    let found = false;

                    // Kiểm tra từ khóa tìm kiếm
                    cells.forEach(cell => {
                        if (cell.textContent.toLowerCase().includes(searchTerm)) {
                            found = true;
                        }
                    });

                    const timeInCell = row.cells[5]; // Giờ Vào
                    const timeOutCell = row.cells[6]; // Giờ Ra
                    const isAbsent = timeInCell.textContent.includes('Chưa chấm công vào') ||
                        timeOutCell.textContent.includes('Chưa chấm công ra');

                    // Điều kiện hiển thị hàng
                    if ((searchTerm === '' || found) && (!showAbsentOnly || isAbsent)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            // Lắng nghe sự kiện nhập trên ô tìm kiếm
            searchInput.addEventListener('input', filterRows);

            // Lắng nghe sự kiện thay đổi trên checkbox
            filterCheckbox.addEventListener('change', filterRows);
        });
        document.getElementById('export-button').addEventListener('click', function() {
            // Hiển thị overlay loading
            document.getElementById('loading-overlay').style.display = 'flex';

            // Lấy giá trị ngày bắt đầu và kết thúc
            const startDateInput = document.getElementById('start_date').value;
            const endDateInput = document.getElementById('end_date').value;

            // Chuyển đổi sang định dạng d-m-Y
            const formatDate = (dateString) => {
                const date = new Date(dateString);
                const day = String(date.getDate()).padStart(2, '0'); // Lấy ngày và thêm số 0 nếu cần
                const month = String(date.getMonth() + 1).padStart(2, '0'); // Tháng bắt đầu từ 0
                const year = date.getFullYear();
                return `${day}-${month}-${year}`; // Định dạng d-m-Y
            };

            const formattedStartDate = formatDate(startDateInput);
            const formattedEndDate = formatDate(endDateInput);

            // Thực hiện yêu cầu AJAX
            fetch("{{ route('admin.attendance.export') }}?start_date=" + startDateInput + "&end_date=" +
                    endDateInput)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.blob(); // Chuyển đổi phản hồi thành blob
                })
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = url;
                    // Tạo tên tệp dựa trên formattedStartDate và formattedEndDate
                    const fileName = 'Bảng Tính Công Từ ' + formattedStartDate + ' Đến ' + formattedEndDate +
                        '.xlsx';
                    link.setAttribute('download', fileName); // Tên tệp đã chỉnh sửa
                    document.body.appendChild(link);
                    link.click(); // Tự động tải xuống
                    document.body.removeChild(link); // Xóa link sau khi tải xong

                    // Ẩn overlay loading
                    document.getElementById('loading-overlay').style.display = 'none';
                })
                .catch(error => {
                    document.getElementById('loading-overlay').style.display = 'none';
                    alert('Có lỗi xảy ra khi xuất dữ liệu');
                    console.error(error);
                });
        });
    </script>
@endsection
