@extends('master')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Bảng Chấm Công Tháng {{ $currentMonth }}</h4>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Phần này để lọc dữ liệu -->
                    <form method="GET" action="{{ route('admin.attendence.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="employee_name" class="form-label">Tên Nhân Viên:</label>
                                <input type="text" id="employee_name" name="employee_name" class="form-control"
                                    value="{{ request('employee_name') }}" onchange="this.form.submit()">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="start_date" class="form-label">Từ ngày:</label>
                                <input type="date" id="start_date" name="start_date" class="form-control"
                                    value="{{ request('start_date') }}" onchange="this.form.submit()">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="end_date" class="form-label">Đến ngày:</label>
                                <input type="date" id="end_date" name="end_date" class="form-control"
                                    value="{{ request('end_date') }}" onchange="this.form.submit()">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="time_filter" class="form-label">Thời Gian:</label>
                                <select id="time_filter" name="time_filter" class="form-select"
                                    onchange="this.form.submit()">
                                    <option value="working_hours"
                                        {{ request('time_filter', 'working_hours') === 'working_hours' ? 'selected' : '' }}>
                                        Nhân viên hành chính (07:30 - 17:00)
                                    </option>
                                    <option value="qc_day" {{ request('time_filter') === 'qc_day' ? 'selected' : '' }}>
                                        Nhân viên QC Ca Ngày (07:30 - 19:30)
                                    </option>
                                    <option value="nhan_vien_san_xuat_shift_1"
                                        {{ request('time_filter') === 'nhan_vien_san_xuat_shift_1' ? 'selected' : '' }}>
                                        Nhân Viên Sản Xuất Ca 1 (07:30 - 19:30)
                                    </option>
                                    <option value="nhan_vien_san_xuat_shift_2"
                                        {{ request('time_filter') === 'nhan_vien_san_xuat_shift_2' ? 'selected' : '' }}>
                                        Nhân Viên Sản Xuất Ca 2 (19:30 - 07:30)
                                    </option>
                                </select>
                            </div>
                        </div>
                    </form>


                    <table class="table table-hover mb-4">
                        <thead class="text-center">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">STT</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Mã Nhân Viên</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Tên Nhân Viên</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Ngày Chấm</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Ngày Trong Tuần</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Giờ Vào</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Giờ Ra</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records as $record)
                                <tr class="text-center">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $record->employee_code }}</td>
                                    <td>{{ $record->employee ? $record->employee->name : 'Không xác định' }}</td>
                                    <td>{{ $record->formatted_date }}</td>
                                    <td>{{ $record->day_of_week }}</td>
                                    <td class="{{ $record->time_in ? '' : 'text-danger' }}">
                                        {{ $record->time_in ?: 'Chưa chấm công' }}
                                    </td>
                                    <td class="{{ $record->time_out ? '' : 'text-danger' }}">
                                        {{ $record->time_out ?: 'Chưa chấm công' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
