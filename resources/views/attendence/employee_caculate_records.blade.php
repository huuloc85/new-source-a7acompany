@extends('layouts.'.$layout)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">
                            Bảng Tính Công Tháng
                            {{ \Carbon\Carbon::parse($currentMonth)->format('m-Y') }}
                        </h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="p-3 border rounded mb-3">
                        <div>
                            <span class="fw-bold">Tên nhân viên:</span>
                            {{ Auth()->user()->name ?? '' }}
                        </div>
                        <div>
                            <span class="fw-bold">Mã nhân viên:</span>
                            {{ Auth()->user()->code ?? '' }}
                        </div>
                        <div>
                            <span class="fw-bold">Bộ phận:</span>
                            {{ Auth()->user()->role->role_name ?? '' }}
                        </div>
                    </div>
                    <!-- Desktop Month Selection -->
                    <div class="d-none d-md-block mb-3">
                        <form method="GET" action="{{ route('admin.employee.attendence_caculate_records') }}">
                            <div class="form-group">
                                <label class="form-label" for="month">Chọn tháng:</label>
                                <input
                                    type="month"
                                    id="month"
                                    name="month"
                                    value="{{ $currentMonth }}"
                                    class="form-control"
                                    onchange="this.form.submit()" />
                            </div>
                        </form>
                    </div>
                    {{-- DESKTOP VIEW --}}
                    <div class="table-responsive d-none d-md-block">
                        @if ($records->isEmpty())
                            <p class="text-center">Hiện tại chưa có thông tin nào.</p>
                        @else
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-check form-switch ps-5">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            role="switch"
                                            id="filter_absent" />
                                        <label class="form-check-label" for="filter_absent">
                                            Hiển thị những ngày quên chấm công
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-end align-items-center">
                                        <label class="form-label me-2 mb-0">Sắp xếp theo ngày:</label>
                                        <select id="sort_date_desktop" class="form-select" style="width: auto">
                                            <option value="desc">Mới nhất</option>
                                            <option value="asc">Cũ nhất</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <table id="attendanceTable" class="table table-hover table-bordered">
                                <thead>
                                    <tr class="text-center text-uppercase">
                                        <th>STT</th>
                                        <th>Mã Nhân Viên</th>
                                        <th>Tên Nhân Viên</th>
                                        <th>Ngày Chấm</th>
                                        <th>Ngày Trong Tuần</th>
                                        <th>Giờ Vào</th>
                                        <th>Giờ Ra</th>
                                        <th>Tổng Giờ Làm Việc (H)</th>
                                        <th>Giờ Hành Chính (H)</th>
                                        <th>Giờ Tăng Ca (H)</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center align-middle">
                                    @foreach ($records as $record)
                                        <tr data-date="{{ $record->date }}">
                                            <td class="row-number">{{ $loop->iteration }}</td>
                                            <td>{{ $record->employee_code }}</td>
                                            <td>{{ $record->employee?->name ?? 'Không xác định' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($record->date)->format('d-m-Y') }}</td>
                                            <td>{{ $record->day_of_week }}</td>
                                            <td class="{{ $record->time_in ? '' : 'text-danger' }}">
                                                {{ $record->time_in ? \Carbon\Carbon::parse($record->time_in)->format('H:i:s') : 'Chưa chấm công vào' }}
                                            </td>
                                            <td class="{{ $record->time_out ? '' : 'text-danger' }}">
                                                {{ $record->time_out ? \Carbon\Carbon::parse($record->time_out)->format('H:i:s') : 'Chưa chấm công ra' }}
                                            </td>
                                            <td class="{{ $record->total_hours ? '' : 'text-danger' }}">
                                                {{ $record->total_hours ?: 'Chấm công không đủ' }}
                                            </td>
                                            <td>
                                                <strong>{{ number_format($record->administrative_hours, 2) }}</strong>
                                            </td>
                                            <td><strong>{{ number_format($record->overtime_hours, 2) }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>

                    {{-- MOBILE VIEW --}}
                    <div class="d-md-none">
                        @if ($records->isEmpty())
                            <p class="text-center">Hiện tại chưa có thông tin nào.</p>
                        @else
                            <div class="mb-3">
                                <!-- Filter Section -->
                                <div class="card shadow-sm border-0 mb-3">
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-3 text-primary">
                                            <i class="fas fa-filter me-2"></i>
                                            Bộ lọc & Sắp xếp
                                        </h6>

                                        <!-- Month Selection for Mobile -->
                                        <form
                                            method="GET"
                                            action="{{ route('admin.employee.attendence_caculate_records') }}">
                                            <div
                                                class="d-flex align-items-center justify-content-between mb-3 p-2 bg-light rounded">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar-alt text-success me-2"></i>
                                                    <span class="fw-medium text-sm">Chọn tháng</span>
                                                </div>
                                                <input
                                                    type="month"
                                                    id="month_mobile"
                                                    name="month"
                                                    value="{{ $currentMonth }}"
                                                    class="form-control form-control-sm"
                                                    style="width: 140px"
                                                    onchange="this.form.submit()" />
                                            </div>
                                        </form>

                                        <!-- Filter Switch -->
                                        <div
                                            class="d-flex align-items-center justify-content-between mb-3 p-2 bg-light rounded">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-eye-slash text-warning me-2"></i>
                                                <span class="fw-medium text-sm">Chỉ hiện ngày thiếu chấm công</span>
                                            </div>
                                            <div class="form-check form-switch mb-0">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    role="switch"
                                                    id="filter_absent_mobile" />
                                                <label class="form-check-label" for="filter_absent_mobile"></label>
                                            </div>
                                        </div>

                                        <!-- Sort Section -->
                                        <div
                                            class="d-flex align-items-center justify-content-between p-2 bg-light rounded">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-sort text-info me-2"></i>
                                                <span class="fw-medium text-sm">Sắp xếp theo ngày</span>
                                            </div>
                                            <select
                                                id="sort_date_mobile"
                                                class="form-select form-select-sm"
                                                style="width: 120px">
                                                <option value="desc">Mới nhất</option>
                                                <option value="asc">Cũ nhất</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="mobile_records_container">
                                @foreach ($records as $record)
                                    <div class="card mb-3 shadow-sm mobile-record" data-date="{{ $record->date }}">
                                        <div class="card-body p-3">
                                            <p class="mb-1">
                                                <strong>STT:</strong>
                                                <span class="mobile-row-number">{{ $loop->iteration }}</span>
                                            </p>
                                            <p class="mb-1">
                                                <strong>Mã Nhân Viên:</strong>
                                                {{ $record->employee_code }}
                                            </p>
                                            <p class="mb-1">
                                                <strong>Tên Nhân Viên:</strong>
                                                {{ $record->employee?->name ?? 'Không xác định' }}
                                            </p>
                                            <p class="mb-1">
                                                <strong>Ngày Chấm:</strong>
                                                {{ \Carbon\Carbon::parse($record->date)->format('d-m-Y') }}
                                            </p>
                                            <p class="mb-1">
                                                <strong>Ngày Trong Tuần:</strong>
                                                {{ $record->day_of_week }}
                                            </p>
                                            <p class="mb-1 {{ $record->time_in ? '' : 'text-danger' }}">
                                                <strong>Giờ Vào:</strong>
                                                {{ $record->time_in ? \Carbon\Carbon::parse($record->time_in)->format('H:i:s') : 'Chưa chấm công vào' }}
                                            </p>
                                            <p class="mb-1 {{ $record->time_out ? '' : 'text-danger' }}">
                                                <strong>Giờ Ra:</strong>
                                                {{ $record->time_out ? \Carbon\Carbon::parse($record->time_out)->format('H:i:s') : 'Chưa chấm công ra' }}
                                            </p>
                                            <p class="mb-1 {{ $record->total_hours ? '' : 'text-danger' }}">
                                                <strong>Tổng Giờ Làm Việc (H):</strong>
                                                {{ $record->total_hours ?: 'Chấm công không đủ' }}
                                            </p>
                                            <p class="mb-1">
                                                <strong>Giờ Hành Chính (H):</strong>
                                                {{ number_format($record->administrative_hours, 2) }}
                                            </p>
                                            <p class="mb-0">
                                                <strong>Giờ Tăng Ca (H):</strong>
                                                {{ number_format($record->overtime_hours, 2) }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterDesktop = document.getElementById('filter_absent')
            const filterMobile = document.getElementById('filter_absent_mobile')
            const sortDesktop = document.getElementById('sort_date_desktop')
            const sortMobile = document.getElementById('sort_date_mobile')

            const attendanceTable = document.getElementById('attendanceTable')
            const tableRows = attendanceTable ? attendanceTable.querySelectorAll('tbody tr') : []
            const mobileContainer = document.getElementById('mobile_records_container')
            const mobileCards = mobileContainer ? mobileContainer.querySelectorAll('.mobile-record') : []

            // Hàm cập nhật số thứ tự
            function updateRowNumbers() {
                // Cập nhật STT cho desktop
                const visibleDesktopRows = Array.from(tableRows).filter((row) => row.style.display !== 'none')
                visibleDesktopRows.forEach((row, index) => {
                    const sttCell = row.querySelector('.row-number')
                    if (sttCell) {
                        sttCell.textContent = index + 1
                    }
                })

                // Cập nhật STT cho mobile
                const visibleMobileCards = Array.from(mobileCards).filter((card) => card.style.display !== 'none')
                visibleMobileCards.forEach((card, index) => {
                    const sttElement = card.querySelector('.mobile-row-number')
                    if (sttElement) {
                        sttElement.textContent = index + 1
                    }
                })
            }

            // Hàm lọc records
            function filterRecords(showAbsentOnly) {
                // Xử lý bản desktop
                tableRows.forEach((row) => {
                    const timeIn = row.cells[5]?.textContent || ''
                    const timeOut = row.cells[6]?.textContent || ''
                    const isAbsent = timeIn.includes('Chưa chấm công vào') || timeOut.includes('Chưa chấm công ra')
                    row.style.display = showAbsentOnly ? (isAbsent ? '' : 'none') : ''
                })

                // Xử lý bản mobile
                mobileCards.forEach((card) => {
                    const timeInEl = Array.from(card.querySelectorAll('p')).find((p) =>
                        p.textContent.includes('Giờ Vào'),
                    )
                    const timeOutEl = Array.from(card.querySelectorAll('p')).find((p) =>
                        p.textContent.includes('Giờ Ra'),
                    )

                    const timeInText = timeInEl?.textContent || ''
                    const timeOutText = timeOutEl?.textContent || ''
                    const isAbsent =
                        timeInText.includes('Chưa chấm công vào') || timeOutText.includes('Chưa chấm công ra')
                    card.style.display = showAbsentOnly ? (isAbsent ? '' : 'none') : ''
                })

                updateRowNumbers()
            }

            // Hàm sắp xếp theo ngày
            function sortRecords(order) {
                // Sắp xếp desktop table
                if (attendanceTable && tableRows.length > 0) {
                    const tbody = attendanceTable.querySelector('tbody')
                    const rowsArray = Array.from(tableRows)

                    rowsArray.sort((a, b) => {
                        const dateA = new Date(a.getAttribute('data-date'))
                        const dateB = new Date(b.getAttribute('data-date'))
                        return order === 'asc' ? dateA - dateB : dateB - dateA
                    })

                    // Xóa tất cả rows và thêm lại theo thứ tự mới
                    tbody.innerHTML = ''
                    rowsArray.forEach((row) => tbody.appendChild(row))
                }

                // Sắp xếp mobile cards
                if (mobileContainer && mobileCards.length > 0) {
                    const cardsArray = Array.from(mobileCards)

                    cardsArray.sort((a, b) => {
                        const dateA = new Date(a.getAttribute('data-date'))
                        const dateB = new Date(b.getAttribute('data-date'))
                        return order === 'asc' ? dateA - dateB : dateB - dateA
                    })

                    // Xóa tất cả cards và thêm lại theo thứ tự mới
                    mobileContainer.innerHTML = ''
                    cardsArray.forEach((card) => mobileContainer.appendChild(card))
                }

                updateRowNumbers()
            }

            // Event listeners cho filter
            if (filterDesktop) {
                filterDesktop.addEventListener('change', () => {
                    filterRecords(filterDesktop.checked)
                })
            }

            if (filterMobile) {
                filterMobile.addEventListener('change', () => {
                    filterRecords(filterMobile.checked)
                })
            }

            // Event listeners cho sort
            if (sortDesktop) {
                sortDesktop.addEventListener('change', () => {
                    sortRecords(sortDesktop.value)
                })
            }

            if (sortMobile) {
                sortMobile.addEventListener('change', () => {
                    sortRecords(sortMobile.value)
                })
            }

            // Khởi tạo sắp xếp mặc định (mới nhất trước)
            sortRecords('desc')
        })
    </script>
@endsection
