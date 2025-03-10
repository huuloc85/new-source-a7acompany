<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Báo Cáo Sản Lượng Hàng Ngày</title>
        <style>
            /* Reset cơ bản cho email */
            body {
                margin: 0;
                padding: 0;
                font-family: 'Helvetica Neue', Arial, sans-serif;
                line-height: 1.6;
                background-color: #f5f7fa;
            }

            .container {
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
                background-color: #ffffff;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            }

            /* Header */
            .header {
                background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
                padding: 20px;
                border-radius: 8px 8px 0 0;
                color: white;
                text-align: center;
            }

            .header h1 {
                margin: 0;
                font-size: 22px;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            /* Table styling */
            .table-container {
                margin: 20px 0;
                overflow-x: auto;
            }

            .table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
                background-color: #fff;
            }

            .table th {
                background-color: #34495e;
                color: white;
                padding: 10px;
                font-weight: 600;
                text-transform: uppercase;
                font-size: 12px;
                border-bottom: 2px solid #2c3e50;
                text-align: center;
            }

            .table td {
                padding: 10px;
                border-bottom: 1px solid #eee;
                vertical-align: middle;
                text-align: center;
                font-size: 13px;
            }

            /* Style đặc biệt cho cột STT */
            .table th.stt-col,
            .table td.stt-col {
                width: 50px;
                /* Độ rộng cố định cho cột STT */
                background-color: #ecf0f1;
                /* Màu nền nhạt để nổi bật */
                font-weight: bold;
                color: #2c3e50;
            }

            .table th.stt-col {
                background-color: #34495e;
                /* Giữ màu header cho th */
                color: white;
            }

            .table tr:nth-child(even) {
                background-color: #f8f9fa;
            }

            .table tr:hover {
                background-color: #f1f3f5;
                transition: background-color 0.3s ease;
            }

            /* Section titles */
            h2 {
                color: #2c3e50;
                text-align: center;
                margin: 20px 0 15px;
                padding-bottom: 8px;
                border-bottom: 2px solid #3498db;
                text-transform: uppercase;
                font-size: 18px;
                letter-spacing: 0.5px;
            }

            /* Footer */
            .footer {
                text-align: center;
                padding: 15px 0;
                border-top: 1px solid #eee;
                color: #666;
                font-size: 11px;
                margin-top: 20px;
            }

            .footer a {
                color: #3498db;
                text-decoration: none;
            }

            .footer a:hover {
                text-decoration: underline;
            }

            /* Responsive */
            @media screen and (max-width: 600px) {
                .container {
                    padding: 10px;
                    width: 100%;
                    box-sizing: border-box;
                }

                .header {
                    padding: 15px;
                }

                .header h1 {
                    font-size: 16px;
                }

                h2 {
                    font-size: 14px;
                    margin: 15px 0 10px;
                }

                .table-container {
                    margin: 10px 0;
                }

                .table th,
                .table td {
                    font-size: 11px;
                    padding: 6px;
                    word-break: break-word;
                }

                .table th.stt-col,
                .table td.stt-col {
                    width: 40px;
                    /* Giảm độ rộng STT trên mobile */
                }

                .table {
                    min-width: 100%;
                }

                .footer {
                    font-size: 10px;
                    padding: 10px 0;
                }
            }

            /* Status specific styling */
            .text-danger {
                color: #e74c3c !important;
                font-weight: 500;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="header">
                <h1>
                    Báo Cáo Sản Xuất Ngày
                    {{ $selectedDate->format('d-m-Y') }}
                </h1>
            </div>

            <div class="content">
                <!-- Sản Xuất -->
                <h2>Sản Xuất</h2>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="stt-col">STT</th>
                                <th>Tên linh kiện</th>
                                <th>Số Lượng</th>
                                <th>Người Nhập</th>
                                <th>Ca Làm Việc</th>
                                <th>Ngày Nhập</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = 1;
                            @endphp

                            @foreach ($productivityLogsQuery as $log)
                                @if ($log->status == 1)
                                    <tr>
                                        <td>{{ $counter }}</td>
                                        <td>{{ $log->product->name }}</td>
                                        <td>{{ $log->quantity }}</td>
                                        <td>{{ $log->employee->name }}</td>
                                        <td>
                                            {{ $translatedCalendarDetails[$log->employee_id] ?? 'Nhân Viên Đã Nghỉ Việc' }}
                                        </td>
                                        <td>
                                            {{ $selectedDate->format('d-m') }}
                                        </td>
                                    </tr>
                                    @php
                                        $counter++;
                                    @endphp
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Kiểm 200% -->
                <h2>Kiểm 200%</h2>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="stt-col">STT</th>
                                <th>Tên linh kiện</th>
                                <th>Số Lượng</th>
                                <th>Người Nhập</th>
                                <th>Ca Làm Việc</th>
                                <th>Ngày Nhập</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = 1;
                            @endphp

                            @foreach ($productivityLogsQuery as $log)
                                @if ($log->status == 2)
                                    <tr>
                                        <td>{{ $counter }}</td>
                                        <td>{{ $log->product->name }}</td>
                                        <td>{{ $log->quantity }}</td>
                                        <td>{{ $log->employee->name }}</td>
                                        <td>
                                            {{ $translatedCalendarDetails[$log->employee_id] ?? 'Nhân Viên Đã Nghỉ Việc' }}
                                        </td>
                                        <td>
                                            {{ $selectedDate->format('d-m') }}
                                        </td>
                                    </tr>
                                    @php
                                        $counter++;
                                    @endphp
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Hàng Lỗi -->
                <h2>Hàng Lỗi</h2>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="stt-col">STT</th>
                                <th>Tên linh kiện</th>
                                <th>Số Lượng</th>
                                <th>Người Nhập</th>
                                <th>Ca Làm Việc</th>
                                <th>Ngày Nhập</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = 1;
                            @endphp

                            @foreach ($productivityLogsQuery as $log)
                                @if ($log->status == 6)
                                    <tr>
                                        <td>{{ $counter }}</td>
                                        <td>{{ $log->product->name }}</td>
                                        <td>{{ $log->quantity }}</td>
                                        <td>{{ $log->employee->name }}</td>
                                        <td>
                                            {{ $translatedCalendarDetails[$log->employee_id] ?? 'Nhân Viên Đã Nghỉ Việc' }}
                                        </td>
                                        <td>
                                            {{ $selectedDate->format('d-m') }}
                                        </td>
                                    </tr>
                                    @php
                                        $counter++;
                                    @endphp
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Nhân viên chưa nhập -->
                <h2>Danh sách nhân viên chưa nhập sản lượng</h2>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="stt-col">STT</th>
                                <th>Tên Nhân Viên</th>
                                <th>Ca Làm Việc</th>
                                <th>Trạng Thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = 1;
                            @endphp

                            @foreach ($employeesWithoutProductivity as $employee)
                                @php
                                    $calendarDetail = $translatedCalendarDetails[$employee->id] ?? '';
                                @endphp

                                @if ($calendarDetail !== '' && $calendarDetail !== 'Nghỉ')
                                    <tr>
                                        <td>{{ $counter }}</td>
                                        <td>{{ $employee->name }}</td>
                                        <td>{{ $calendarDetail }}</td>
                                        <td class="text-danger">
                                            Chưa nhập sản lượng ngày
                                            {{ $selectedDate->format('d-m') }}
                                        </td>
                                    </tr>
                                    @php
                                        $counter++;
                                    @endphp
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="footer">
                <p>
                    Chi tiết vui lòng xem tại:
                    <a href="http://a7acompany.com">A7ACOMPANY.COM</a>
                </p>
                <p>CTY TNHH MTV VINH VINH PHÁT</p>
            </div>
        </div>
    </body>
</html>
