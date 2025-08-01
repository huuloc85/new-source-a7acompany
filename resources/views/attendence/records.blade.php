@extends('layouts.'.$layout)

@section('content')
    {{--
        <style>
        .form-control,
        .form-select {
        border-radius: 0.375rem;
        font-size: 0.875rem;
        }
        
        .position-relative {
        position: relative;
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
        width: 100%;
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
        
        .employee-list {
        max-height: 500px;
        overflow-y: auto;
        }
        
        .employee-card {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        text-align: center;
        background-color: #f9f9f9;
        margin-bottom: 10px;
        min-height: 50px;
        }
        
        .modal-lg {
        max-width: 80%;
        }
        
        .search-box {
        position: relative;
        }
        
        .search-box .search-icon {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        font-size: 14px;
        color: #6c757d;
        pointer-events: none;
        }
        
        input.form-control-search {
        padding-right: 30px;
        }
        
        /* btn Lọc thông tin (Primary) */
        .btn-outline-primary:hover {
        color: #fff;
        background-color: #0d6efd;
        /* Màu chuẩn của Bootstrap cho Primary */
        border-color: #0d6efd;
        }
        
        .btn-outline-primary:active {
        color: #0d6efd;
        background-color: transparent;
        border-color: #0d6efd;
        }
        
        .btn-outline-primary:focus {
        box-shadow: none;
        background-color: transparent;
        border-color: #0d6efd;
        }
        
        /* btn Lịch sử (Success) */
        .btn-outline-success:hover {
        color: #fff;
        background-color: #198754;
        /* Màu chuẩn của Bootstrap cho Success */
        border-color: #198754;
        }
        
        .btn-outline-success:active {
        color: #198754;
        background-color: transparent;
        border-color: #198754;
        }
        
        /*
        .btn-outline-success:focus {
        box-shadow: none;
        background-color: transparent;
        border-color: #198754;
        } */
        
        /* btn Danh sách (Info) */
        .btn-outline-info:hover {
        color: #fff;
        background-color: #0dcaf0;
        /* Màu chuẩn của Bootstrap cho Info */
        border-color: #0dcaf0;
        }
        
        .btn-outline-info:active {
        color: #0dcaf0;
        background-color: transparent;
        border-color: #0dcaf0;
        }
        
        .btn-outline-info:focus {
        box-shadow: none;
        background-color: transparent;
        border-color: #0dcaf0;
        }
        </style>
    --}}

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">
                            Bảng Tính Công
                            @if (request('start_date') && request('end_date'))
                                từ
                                {{ \Carbon\Carbon::parse(request('start_date'))->format('d-m-Y') }}
                                đến
                                {{ \Carbon\Carbon::parse(request('end_date'))->format('d-m-Y') }}
                            @else
                                Tháng
                                {{ \Carbon\Carbon::parse($currentMonth)->format('m-Y') }}
                            @endif
                        </h4>
                    </div>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <!-- Nút Lọc Thông Tin và Lịch Sử Chấm Công -->
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                            <button
                                type="button"
                                class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#filterModal">
                                <i class="fas fa-filter"></i>
                                Lọc Thông Tin
                            </button>
                            <a href="{{ route('admin.attendence.index') }}" class="btn btn-success">
                                <i class="fas fa-history"></i>
                                Lịch Sử Chấm Công
                            </a>
                            <a href="#" id="attendance-search-button" class="btn btn-secondary">
                                <i class="fas fa-search"></i>
                                Tìm Chấm Công Thực Tế
                            </a>
                            <button
                                type="button"
                                class="btn btn-info"
                                data-bs-toggle="modal"
                                data-bs-target="#todayEmployeesModal">
                                <i class="fas fa-users"></i>
                                Danh Sách Nhân Viên Làm Việc Hằng Ngày
                            </button>
                        </div>
                        <!-- Danh Sách Nhân Viên Làm Việc Hằng Ngày -->

                        <!-- Form Export -->
                        <form id="export-form" class="d-flex flex-wrap align-items-center gap-2 mb-2">
                            <div class="row g-1 align-items-center">
                                <div class="col-auto">
                                    <label for="start_date" class="col-form-label">Từ:</label>
                                </div>
                                <div class="col-auto">
                                    <input
                                        type="date"
                                        id="start_date"
                                        name="start_date"
                                        class="form-control"
                                        required />
                                </div>
                            </div>
                            <div class="row g-1 align-items-center">
                                <div class="col-auto">
                                    <label for="end_date" class="col-form-label">Đến:</label>
                                </div>
                                <div class="col-auto">
                                    <input type="date" id="end_date" name="end_date" class="form-control" required />
                                </div>
                            </div>
                            <button type="button" class="btn btn-warning" id="export-button">
                                <i class="fas fa-file-export"></i>
                                Export
                            </button>
                        </form>
                        <!-- Ô Tìm Kiếm -->
                        <input
                            type="text"
                            id="search"
                            class="form-control"
                            placeholder="Tìm kiếm nhân viên hoặc ngày chấm công"
                            style="max-width: 25rem" />
                    </div>
                    <!-- Checkbox Lọc -->
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="filter_absent" name="filter_absent" />
                        <label class="form-check-label" for="filter_absent">
                            Chỉ hiển thị những người quên chấm công
                        </label>
                    </div>
                    <div class="table-responsive">
                        <!-- Loading Overlay -->
                        <div
                            id="loading-overlay"
                            style="
                                display: none;
                                position: fixed;
                                top: 0;
                                left: 0;
                                right: 0;
                                bottom: 0;
                                background: rgba(0, 0, 0, 0.5);
                                z-index: 9999;
                                align-items: center;
                                justify-content: center;
                                flex-direction: column;
                            ">
                            <div class="spinner-border" role="status"></div>
                            <p style="color: white; margin-top: 10px">Đang xuất dữ liệu...</p>
                        </div>

                        @if ($records->isEmpty())
                            <p class="text-center text-danger">Hiện tại chưa có thông tin nào.</p>
                        @else
                            <table id="attendanceTable" class="table table-hover mb-4">
                                <thead class="text-uppercase text-center">
                                    <tr>
                                        <th>STT</th>
                                        <th>Mã Nhân Viên</th>
                                        <th>Tên Nhân Viên</th>
                                        <th>Ngày Chấm</th>
                                        <th>Ngày Trong Tuần</th>
                                        <th>Giờ Vào</th>
                                        <th>Giờ Ra</th>
                                        <th>Ca Làm Việc</th>
                                        <th>Tổng Giờ Làm Việc(H)</th>
                                        <th>Giờ Hành Chính(H)</th>
                                        <th>Giờ Tăng Ca(H)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($records as $record)
                                        <tr class="text-center">
                                            <td class="fw-bold">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ $record->employee_code }}
                                            </td>
                                            <td>
                                                {{ $record->employee ? $record->employee->name : 'Không xác định' }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($record->date)->format('d-m-Y') }}
                                            </td>
                                            <td>{{ $record->day_of_week }}</td>
                                            <td class="{{ $record->time_in ? '' : 'text-danger' }}">
                                                {{ $record->time_in ? \Carbon\Carbon::parse($record->time_in)->format('H:i:s') : 'Chưa chấm công vào' }}
                                            </td>
                                            <td class="{{ $record->time_out ? '' : 'text-danger' }}">
                                                {{ $record->time_out ? \Carbon\Carbon::parse($record->time_out)->format('H:i:s') : 'Chưa chấm công ra' }}
                                            </td>
                                            <td>{{ $record->shift }}</td>
                                            <td class="{{ $record->total_hours ? '' : 'text-danger' }}">
                                                @if ($record->total_hours)
                                                    <strong>
                                                        {{ $record->total_hours }}
                                                    </strong>
                                                @else
                                                    {{ 'Chấm công không đủ' }}
                                                @endif
                                            </td>
                                            <td>
                                                <strong>
                                                    {{ $record->administrative_hours > 0 ? number_format($record->administrative_hours, 2) : '0' }}
                                                </strong>
                                            </td>
                                            <td>
                                                <strong>
                                                    {{ $record->overtime_hours > 0 ? number_format($record->overtime_hours, 2) : '0' }}
                                                </strong>
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
        <div class="modal-dialog modal-dialog-centered">
            <form method="GET" action="{{ route('admin.attendence.records') }}" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Tìm Kiếm Thông Tin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="month" class="form-label">Tháng:</label>
                            <input
                                type="month"
                                name="month"
                                id="month"
                                class="form-control"
                                placeholder="Chọn tháng"
                                value="{{ request('month', $currentMonth) }}" />
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="employee_name" class="form-label">Tên Nhân Viên:</label>
                            <input
                                type="text"
                                id="employee_name"
                                name="employee_name"
                                class="form-control"
                                value="{{ request('employee_name') }}" />
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="start_date" class="form-label">Từ ngày:</label>
                            <input
                                type="date"
                                id="start_date"
                                name="start_date"
                                class="form-control"
                                value="{{ request('start_date') }}" />
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="end_date" class="form-label">Đến ngày:</label>
                            <input
                                type="date"
                                id="end_date"
                                name="end_date"
                                class="form-control"
                                value="{{ request('end_date') }}" />
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="time_filter" class="form-label">Danh mục làm việc:</label>
                            <select id="time_filter" name="time_filter" class="form-select">
                                @foreach (config('a7a.list_category') as $key => $record)
                                    <option
                                        value="{{ $key }}"
                                        {{ request('time_filter') === $key ? 'selected' : '' }}>
                                        {{ $record }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal tìm kiếm -->
    <div
        class="modal fade"
        id="todayEmployeesModal"
        tabindex="-1"
        aria-labelledby="todayEmployeesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="mt-4">
                        Danh sách nhân viên làm việc ngày
                        @if ($day)
                            {{ \Carbon\Carbon::createFromFormat('d', $day)->format('d/m/Y') }}
                        @else
                            {{ $today->format('d/m/Y') }}
                        @endif
                        ({{ $employeesTodayCount }} nhân viên)
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form Lọc Ngày -->
                    <div class="form-group">
                        <label for="day">Chọn ngày:</label>
                        <select name="day" id="day" class="form-control" required>
                            <option value="">Chọn ngày</option>
                            @for ($i = 1; $i <= 31; $i++)
                                <option value="{{ $i }}" {{ $day == $i ? 'selected' : '' }}>Ngày {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <!-- Hiển thị danh sách nhân viên -->
                    @if ($employeesToday->isEmpty())
                        <p>Không có nhân viên nào làm việc trong ngày này.</p>
                    @else
                        <div class="employee-list row">
                            @foreach ($employeesToday as $detail)
                                <div class="col-3 employee-card">
                                    <p>{{ $detail->employee->name }}</p>
                                    <p>
                                        <strong>Ca làm việc:</strong>
                                        {{ $detail->shift }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('attendance-search-button').addEventListener('click', function (e) {
            e.preventDefault() // Ngăn không cho tự động chuyển hướng
            Swal.fire({
                title: 'Bạn đã đổi qua mạng Vinh Vinh Phát chưa?',
                text: 'Vui lòng kiểm tra và xác nhận trước khi tiếp tục.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đã đổi mạng',
                cancelButtonText: 'Chưa đổi mạng',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open('http://192.168.1.200/doc/index.html#/eventSearch?t=1731739764211', '_blank')
                }
            })
        })
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search')
            const filterCheckbox = document.getElementById('filter_absent')
            const attendanceTable = document.getElementById('attendanceTable')
            const rows = attendanceTable.querySelectorAll('tbody tr')

            function filterRows() {
                const searchTerm = searchInput.value.toLowerCase()
                const showAbsentOnly = filterCheckbox.checked

                rows.forEach((row) => {
                    const cells = row.querySelectorAll('td')
                    let found = false

                    // Kiểm tra từ khóa tìm kiếm
                    cells.forEach((cell) => {
                        if (cell.textContent.toLowerCase().includes(searchTerm)) {
                            found = true
                        }
                    })

                    const timeInCell = row.cells[5] // Giờ Vào
                    const timeOutCell = row.cells[6] // Giờ Ra
                    const isAbsent =
                        timeInCell.textContent.includes('Chưa chấm công vào') ||
                        timeOutCell.textContent.includes('Chưa chấm công ra')

                    // Điều kiện hiển thị hàng
                    if ((searchTerm === '' || found) && (!showAbsentOnly || isAbsent)) {
                        row.style.display = ''
                    } else {
                        row.style.display = 'none'
                    }
                })
            }

            // Lắng nghe sự kiện nhập trên ô tìm kiếm
            searchInput.addEventListener('input', filterRows)

            // Lắng nghe sự kiện thay đổi trên checkbox
            filterCheckbox.addEventListener('change', filterRows)
        })
        document.getElementById('export-button').addEventListener('click', function () {
            // Hiển thị overlay loading
            document.getElementById('loading-overlay').style.display = 'flex'

            // Lấy giá trị ngày bắt đầu và kết thúc
            const startDateInput = document.getElementById('start_date').value
            const endDateInput = document.getElementById('end_date').value

            // Chuyển đổi sang định dạng d-m-Y
            const formatDate = (dateString) => {
                const date = new Date(dateString)
                const day = String(date.getDate()).padStart(2, '0') // Lấy ngày và thêm số 0 nếu cần
                const month = String(date.getMonth() + 1).padStart(2, '0') // Tháng bắt đầu từ 0
                const year = date.getFullYear()
                return `${day}-${month}-${year}` // Định dạng d-m-Y
            }

            const formattedStartDate = formatDate(startDateInput)
            const formattedEndDate = formatDate(endDateInput)

            // Thực hiện yêu cầu AJAX
            fetch('{{ route('admin.attendance.export') }}?start_date=' + startDateInput + '&end_date=' + endDateInput)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok')
                    }
                    return response.blob() // Chuyển đổi phản hồi thành blob
                })
                .then((blob) => {
                    const url = window.URL.createObjectURL(blob)
                    const link = document.createElement('a')
                    link.href = url
                    // Tạo tên tệp dựa trên formattedStartDate và formattedEndDate
                    const fileName = 'Bảng Tính Công Từ ' + formattedStartDate + ' Đến ' + formattedEndDate + '.xlsx'
                    link.setAttribute('download', fileName) // Tên tệp đã chỉnh sửa
                    document.body.appendChild(link)
                    link.click() // Tự động tải xuống
                    document.body.removeChild(link) // Xóa link sau khi tải xong

                    // Ẩn overlay loading
                    document.getElementById('loading-overlay').style.display = 'none'
                })
                .catch((error) => {
                    document.getElementById('loading-overlay').style.display = 'none'
                    alert('Có lỗi xảy ra khi xuất dữ liệu')
                    console.error(error)
                })
        })
        document.addEventListener('DOMContentLoaded', () => {
            const daySelect = document.getElementById('day')
            const urlParams = new URLSearchParams(window.location.search)
            const currentDay = {{ $currentDay }} // Ngày hiện tại từ server-side
            const selectedDay = parseInt(urlParams.get('day'))
            const showModal = urlParams.get('showModal') === 'true'

            // Xử lý sự kiện khi người dùng chọn ngày
            daySelect?.addEventListener('change', (e) => {
                const selectedValue = parseInt(e.target.value)
                const params = new URLSearchParams()

                // Chỉ cập nhật URL khi ngày được chọn khác với ngày hiện tại
                if (selectedValue && selectedValue !== currentDay) {
                    params.set('day', selectedValue)
                    params.set('showModal', 'true')
                }

                // Điều hướng đến URL mới với các tham số
                window.location.search = params.toString()
            })

            // Tự động mở modal nếu ngày được chọn khác ngày hiện tại và có showModal
            if (showModal && selectedDay !== currentDay) {
                new bootstrap.Modal(document.getElementById('todayEmployeesModal')).show()
            }
        })
    </script>
@endsection
