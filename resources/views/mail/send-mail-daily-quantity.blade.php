<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Báo Cáo Sản Lượng Hàng Ngày</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-container .table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }

        .table-container th,
        .table-container td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
            box-sizing: border-box;

        }

        .table-container th:nth-child(1),
        .table-container td:nth-child(1) {
            width: 5%;
        }

        .table-container th:nth-child(2),
        .table-container td:nth-child(2) {
            width: 35%;
        }

        .table-container th:nth-child(3),
        .table-container td:nth-child(3) {
            width: 10%;
        }

        .table-container th:nth-child(4),
        .table-container td:nth-child(4) {
            width: 20%;
        }

        .table-container th:nth-child(5),
        .table-container td:nth-child(5) {
            width: 15%;
        }

        .table-container th:nth-child(6),
        .table-container td:nth-child(6) {
            width: 15%;
        }

        .h2 {
            text-align: center;
            text-transform: uppercase;
        }

        .h1 {
            text-align: center;
            text-transform: uppercase;
        }

        .footer {
            text-align: center;
        }

        @media screen and (max-width: 700px) {
            .table-container .table {
                width: 100%;
                table-layout: auto;
            }
        }
    </style>
</head>

<div class="container">
    <div class="row">
        <div class="col">
            <div class="alert alert-primary text-uppercase text-center" role="alert">
                <h1>Báo Cáo Sản Xuất Ngày {{ $selectedDate->format('d-m-Y') }}</h1>
            </div>
        </div>
    </div>
    <div class="tab-content">
        <div class="tab-pane fade show active">
            <h2 class="text-center text-lg">Sản Xuất</h2>
            <div class="px-0 pb-2">
                <div class="table-responsive table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên linh kiện</th>
                                <th>Số Lượng</th>
                                <th>Người Nhập</th>
                                <th>Ca Làm Việc</th>
                                <th>Ngày Nhập</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @foreach ($productivityLogsQuery as $log)
                                @if ($log->status == 1)
                                    <tr>
                                        <td>{{ $counter }}</td>
                                        <td>{{ $log->product->name }}</td>
                                        <td>{{ $log->quantity }}</td>
                                        <td>{{ $log->employee->name }}</td>
                                        <td>{{ $translatedCalendarDetails[$log->employee_id] ?? 'Nhân Viên Đã Nghỉ Việc' }}
                                        </td>
                                        </td>
                                        <td>{{ $selectedDate->format('d-m') }}</td>
                                    </tr>
                                    @php $counter++; @endphp
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade">
            <h2 class="text-center text-lg">Kiểm 200%</h2>
            <div class="px-0 pb-2">
                <div class="table-responsive table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên linh kiện</th>
                                <th>Số Lượng</th>
                                <th>Người Nhập</th>
                                <th>Ca Làm Việc</th>
                                <th>Ngày Nhập</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @foreach ($productivityLogsQuery as $log)
                                @if ($log->status == 2)
                                    <tr>
                                        <td>{{ $counter }}</td>
                                        <td>{{ $log->product->name }}</td>
                                        <td>{{ $log->quantity }}</td>
                                        <td>{{ $log->employee->name }}</td>
                                        <td>{{ $translatedCalendarDetails[$log->employee_id] ?? 'Nhân Viên Đã Nghỉ Việc' }}
                                        </td>
                                        </td>
                                        <td>{{ $selectedDate->format('d-m') }}</td>
                                    </tr>
                                    @php $counter++; @endphp
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade">
            <h2 class="text-center text-lg">Hàng Lỗi</h2>
            <div class="px-0 pb-2">
                <div class="table-responsive table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên linh kiện</th>
                                <th>Số Lượng</th>
                                <th>Người Nhập</th>
                                <th>Ca Làm Việc</th>
                                <th>Ngày Nhập</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @foreach ($productivityLogsQuery as $log)
                                @if ($log->status == 6)
                                    <tr>
                                        <td>{{ $counter }}</td>
                                        <td>{{ $log->product->name }}</td>
                                        <td>{{ $log->quantity }}</td>
                                        <td>{{ $log->employee->name }}</td>
                                        <td>{{ $translatedCalendarDetails[$log->employee_id] ?? 'Nhân Viên Đã Nghỉ Việc' }}
                                        </td>
                                        </td>
                                        <td>{{ $selectedDate->format('d-m') }}</td>
                                    </tr>
                                    @php $counter++; @endphp
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade">
            <h2 class="text-center text-lg">Danh sách nhân viên chưa nhập sản lượng</h2>
            <div class="px-0 pb-2">
                <div class="table-responsive table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center text-secondary fw-bold">STT</th>
                                <th class="text-center text-secondary fw-bold">Tên Nhân Viên</th>
                                <th class="text-center text-secondary fw-bold">Ca Làm Việc</th>
                                <th class="text-center text-secondary fw-bold">Trạng Thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employeesWithoutProductivity as $employee)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $employee->name }}</td>
                                    <td class="text-center">
                                        {{ $translatedCalendarDetails[$employee->id] ?? 'Nhân Viên Đã Nghỉ Việc' }}
                                    </td>
                                    <td class="text-center text-danger">Chưa nhập sản lượng ngày
                                        {{ $selectedDate->format('d-m') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Chi tiết vui lòng xem tại: A7ACOMPANY.COM</p>
        <p>CTY TNHH MTV VINH VINH PHÁT</p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>

</html>
