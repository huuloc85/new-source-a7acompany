@extends('master')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                    <div class="border-radius-lg ps-2 pt-4 pb-3 d-flex align-items-center justify-content-between">
                        <h4 class="card-title mb-0">Bảng Tính Công Tháng
                            {{ \Carbon\Carbon::parse($currentMonth)->format('m-Y') }}</h4>
                    </div>
                </div>
                <div class="border-radius-lg ps-2 pt-4 pb-3 d-flex align-items-center justify-content-between">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#filterModal">
                        Tìm Kiếm
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-hover mb-4">
                        <thead class="text-center">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                    STT</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                    Mã Nhân Viên</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                    Tên Nhân Viên</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                    Ngày Chấm</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                    Ngày Trong Tuần</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                    Giờ Vào</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                    Giờ Ra</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                    Tổng Giờ Làm Việc(H)</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                    Giờ Hành Chính(H) </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
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
                                        {{ $record->total_hours ? $record->total_hours : 'Chấm công không đủ' }}
                                    </td>
                                    <td>
                                        @if ($record->administrative_hours > 0)
                                            <strong>{{ number_format($record->administrative_hours, 2) }}</strong>
                                        @else
                                            {{ '0' }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($record->overtime_hours > 0)
                                            <strong>{{ number_format($record->overtime_hours, 2) }}</strong>
                                        @else
                                            {{ '0' }}
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
                                <label for="time_filter" class="form-label">Thời Gian:</label>
                                <select id="time_filter" name="time_filter" class="form-select">
                                    <option value="working_hours"
                                        {{ request('time_filter', 'working_hours') === 'working_hours' ? 'selected' : '' }}>
                                        Nhân viên hành chính (07:30 - 17:00)
                                    </option>
                                    <option value="qc_day" {{ request('time_filter') === 'qc_day' ? 'selected' : '' }}>
                                        Nhân viên QC Ca Ngày (07:30 - 19:30)
                                    </option>
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
@endsection
