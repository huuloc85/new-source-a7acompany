@extends('master')

@section('content')
    <style>
        @media (max-width: 768px) {
            .table-wrapper {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            thead {
                display: none;
            }

            tr {
                display: block;
                margin-bottom: 10px;
                border: 1px solid #ddd;
            }

            td {
                display: block;
                text-align: right;
                font-size: 14px;
                padding: 10px;
                position: relative;
                border-bottom: 1px solid #ddd;
                min-width: 150px;
                word-wrap: break-word;
                word-break: break-all;

            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 10px;
                white-space: nowrap;
                font-weight: bold;
                text-align: left;
            }

            table th:nth-child(1),
            table td:nth-child(1) {
                display: none;
            }

            input[type="checkbox"] {
                display: none;
            }

            label[for="filter_absent"] {
                display: inline-block;
                padding: 10px 15px;
                background-color: #007bff;
                color: white;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s;
            }

            label[for="filter_absent"]:hover {
                background-color: #0056b3;
            }

            input[type="checkbox"]:checked+label[for="filter_absent"] {
                background-color: #28a745;
            }
        }

        @media (max-width: 400px) {
            td {
                font-size: 12px;
                padding: 5px;
                overflow-wrap: break-word;
                white-space: normal;
                word-wrap: break-word;
            }

            td::before {
                font-size: 10px;
                padding-left: 5px;
            }

            input[type="checkbox"] {
                display: none;
            }

            label[for="filter_absent"] {
                display: inline-block;
                padding: 8px 12px;
                background-color: #007bff;
                color: white;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s;
                font-size: 12px;
            }

            label[for="filter_absent"]:hover {
                background-color: #0056b3;
            }

            input[type="checkbox"]:checked+label[for="filter_absent"] {
                background-color: #28a745;
            }
        }

        @media (min-width: 769px) {
            .form-label {
                font-size: 14px;
            }

            .form-control {
                font-size: 14px;
            }

            table {
                font-size: 12px;
            }

            th,
            td {
                padding: 8px;
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
                <div class="card-body">
                    <div class="row">
                        <div>
                            <label class="form-label fw-bold">Tên nhân viên:
                                <span>{{ Auth()->user()->name ?? '' }}</span></label>
                        </div>
                        <div>
                            <label class="form-label fw-bold">Mã nhân viên:
                                <span>{{ Auth()->user()->code ?? '' }}</span></label>
                        </div>
                        <div>
                            <label class="form-label fw-bold">Bộ phận:
                                <span>{{ Auth()->user()->role->role_name ?? '' }}</span></label>
                        </div>
                    </div>
                    <form method="GET" action="{{ route('admin.employee.attendence_caculate_records') }}">
                        <div class="form-group">
                            <label for="month">Chọn tháng:</label>
                            <input type="month" id="month" name="month" value="{{ $currentMonth }}"
                                class="form-control" onchange="this.form.submit()">
                        </div>

                    </form>

                    <div class="table-wrapper">
                        @if ($records->isEmpty())
                            <p class="text-center">Hiện tại chưa có thông tin nào.</p>
                        @else
                            <input type="checkbox" id="filter_absent" name="filter_absent" value="1"
                                {{ request('filter_absent') ? 'checked' : '' }}>
                            <label for="filter_absent">Chỉ hiển thị những ngày quên chấm công</label>
                            <table id="attendanceTable" class="table table-hover mb-4">
                                <thead>
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
                                            Tổng Giờ Làm Việc (H)</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                            Giờ Hành Chính (H)</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                            Giờ Tăng Ca (H)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($records as $record)
                                        <tr class="text-center">
                                            <td data-label="STT">{{ $loop->iteration }}</td>
                                            <td data-label="Mã Nhân Viên">{{ $record->employee_code }}</td>
                                            <td data-label="Tên Nhân Viên">
                                                {{ $record->employee ? $record->employee->name : 'Không xác định' }}</td>
                                            <td data-label="Ngày Chấm">
                                                {{ \Carbon\Carbon::parse($record->date)->format('d-m-Y') }}</td>
                                            <td data-label="Ngày Trong Tuần">{{ $record->day_of_week }}</td>

                                            <td data-label="Giờ Vào" class="{{ $record->time_in ? '' : 'text-danger' }}">
                                                {{ $record->time_in ? \Carbon\Carbon::parse($record->time_in)->format('H:i:s') : 'Chưa chấm công vào' }}
                                            </td>
                                            <td data-label="Giờ Ra" class="{{ $record->time_out ? '' : 'text-danger' }}">
                                                {{ $record->time_out ? \Carbon\Carbon::parse($record->time_out)->format('H:i:s') : 'Chưa chấm công ra' }}
                                            </td>
                                            <td data-label="Tổng Giờ Làm Việc (H)"
                                                class="{{ $record->total_hours ? '' : 'text-danger' }}">
                                                {{ $record->total_hours ? $record->total_hours : 'Chấm công không đủ' }}
                                            </td>
                                            <td data-label="Giờ Hành Chính (H)">
                                                <strong>{{ $record->administrative_hours > 0 ? number_format($record->administrative_hours, 2) : '0' }}</strong>
                                            </td>
                                            <td data-label="Giờ Tăng Ca (H)">
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterCheckbox = document.getElementById('filter_absent');
            const attendanceTable = document.getElementById('attendanceTable');
            const rows = attendanceTable.querySelectorAll('tbody tr');

            filterCheckbox.addEventListener('change', function() {
                const showAbsentOnly = filterCheckbox.checked;

                rows.forEach(row => {
                    const timeInCell = row.cells[5]; // Giờ Vào
                    const timeOutCell = row.cells[6]; // Giờ Ra

                    const isAbsent = timeInCell.textContent.includes('Chưa chấm công vào') ||
                        timeOutCell.textContent.includes('Chưa chấm công ra');
                    if (showAbsentOnly) {
                        row.style.display = isAbsent ? '' : 'none';
                    } else {
                        row.style.display = '';
                    }
                });
            });
        });
    </script>
@endsection
