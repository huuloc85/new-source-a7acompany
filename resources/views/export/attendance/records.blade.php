<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Bảng Chấm Công</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: center;
            padding: 8px;
        }

        th {
            background-color: orange;
            color: white;
            position: relative;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #d1e7fd;
        }

        .text-danger {
            color: red;
            font-weight: bold;
        }

        strong {
            display: block;
        }
    </style>
</head>

<body>
    <h1>Bảng Chấm Công</h1>
    <table id="attendanceTable" class="table table-hover mb-4">
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
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                    style="{{ is_null(request('time_filter')) || request('time_filter') === 'none' || in_array(request('time_filter'), ['qc_day', 'working_hours']) ? 'display: none;' : '' }}">
                    Ca Làm Việc
                </th>
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
                        {{ $record->time_out ? \Carbon\Carbon::parse($record->time_out)->format('H:i:s') : 'Chưa chấm công ra' }}
                    </td>
                    <td
                        style="{{ request('time_filter') === null || request('time_filter') === 'none' || in_array(request('time_filter'), ['qc_day', 'working_hours']) ? 'display: none;' : '' }}">
                        <strong>{{ $record->shift === 'Đổi lịch đi làm' ? $record->shift : '' }}</strong>
                        {{ $record->shift !== 'Đổi lịch đi làm' ? $record->shift : '' }}
                    </td>

                    <td class="{{ $record->total_hours ? '' : 'text-danger' }}">
                        @if ($record->total_hours)
                            <strong>{{ round($record->total_hours, 2) }}</strong>
                            <!-- Làm tròn đến 2 chữ số thập phân -->
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
</body>






</html>
