@extends('layouts.'.$layout)

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
            border-radius: 5px;
            overflow: hidden;
        }

        td {
            display: block;
            text-align: right;
            font-size: 14px;
            padding: 10px;
            position: relative;
            border-bottom: 1px solid #ddd;
            word-wrap: break-word;
            word-break: break-word;
        }

        td::before {
            content: attr(data-label);
            position: absolute;
            top: 10px;
            left: 10px;
            font-weight: bold;
            white-space: nowrap;
        }

        td:last-child {
            border-bottom: none;
        }

        table th:nth-child(1),
        table td:nth-child(1) {
            display: none;
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
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }
    }
</style>

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
                    <form
                        method="GET"
                        action="{{ route('admin.employee.attendence_caculate_records') }}"
                    >
                        <div class="form-group">
                            <label class="form-label" for="month">
                                Chọn tháng:
                            </label>
                            <input
                                type="month"
                                id="month"
                                name="month"
                                value="{{ $currentMonth }}"
                                class="form-control"
                                onchange="this.form.submit()"
                            />
                        </div>
                    </form>
                    <div class="table-responsive">
                        @if ($records->isEmpty())
                            <p class="text-center">
                                Hiện tại chưa có thông tin nào.
                            </p>
                        @else
                            <div class="form-check form-switch ps-5">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    role="switch"
                                    id="filter_absent"
                                />
                                <label
                                    class="form-check-label"
                                    for="filter_absent"
                                >
                                    Hiển thị những ngày quên chấm công
                                </label>
                            </div>
                            <table
                                id="attendanceTable"
                                class="table table-hover table-bordered"
                            >
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-uppercase">STT</th>
                                        <th class="text-uppercase">
                                            Mã Nhân Viên
                                        </th>
                                        <th class="text-uppercase">
                                            Tên Nhân Viên
                                        </th>
                                        <th class="text-uppercase">
                                            Ngày Chấm
                                        </th>
                                        <th class="text-uppercase">
                                            Ngày Trong Tuần
                                        </th>

                                        <th class="text-uppercase">Giờ Vào</th>
                                        <th class="text-uppercase">Giờ Ra</th>
                                        <th class="text-uppercase">
                                            Tổng Giờ Làm Việc (H)
                                        </th>
                                        <th class="text-uppercase">
                                            Giờ Hành Chính (H)
                                        </th>
                                        <th class="text-uppercase">
                                            Giờ Tăng Ca (H)
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($records as $record)
                                        <tr class="text-center">
                                            <td data-label="STT">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td data-label="Mã Nhân Viên">
                                                {{ $record->employee_code }}
                                            </td>
                                            <td data-label="Tên Nhân Viên">
                                                {{ $record->employee ? $record->employee->name : 'Không xác định' }}
                                            </td>
                                            <td data-label="Ngày Chấm">
                                                {{ \Carbon\Carbon::parse($record->date)->format('d-m-Y') }}
                                            </td>
                                            <td data-label="Ngày Trong Tuần">
                                                {{ $record->day_of_week }}
                                            </td>

                                            <td
                                                data-label="Giờ Vào"
                                                class="{{ $record->time_in ? '' : 'text-danger' }}"
                                            >
                                                {{ $record->time_in ? \Carbon\Carbon::parse($record->time_in)->format('H:i:s') : 'Chưa chấm công vào' }}
                                            </td>
                                            <td
                                                data-label="Giờ Ra"
                                                class="{{ $record->time_out ? '' : 'text-danger' }}"
                                            >
                                                {{ $record->time_out ? \Carbon\Carbon::parse($record->time_out)->format('H:i:s') : 'Chưa chấm công ra' }}
                                            </td>
                                            <td
                                                data-label="Tổng Giờ Làm Việc (H)"
                                                class="{{ $record->total_hours ? '' : 'text-danger' }}"
                                            >
                                                {{ $record->total_hours ? $record->total_hours : 'Chấm công không đủ' }}
                                            </td>
                                            <td data-label="Giờ Hành Chính (H)">
                                                <strong>
                                                    {{ $record->administrative_hours > 0 ? number_format($record->administrative_hours, 2) : '0' }}
                                                </strong>
                                            </td>
                                            <td data-label="Giờ Tăng Ca (H)">
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
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterCheckbox = document.getElementById('filter_absent');
            const attendanceTable = document.getElementById('attendanceTable');
            const rows = attendanceTable.querySelectorAll('tbody tr');

            filterCheckbox.addEventListener('change', function () {
                const showAbsentOnly = filterCheckbox.checked;

                rows.forEach((row) => {
                    const timeInCell = row.cells[5]; // Giờ Vào
                    const timeOutCell = row.cells[6]; // Giờ Ra

                    const isAbsent =
                        timeInCell.textContent.includes('Chưa chấm công vào') ||
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
