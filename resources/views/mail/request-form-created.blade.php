<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Thông Báo Đơn Yêu Mới</title>
        <style>
            body {
                font-family: 'Segoe UI', Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
                color: #333333;
            }

            .email-container {
                max-width: 600px;
                margin: 30px auto;
                background-color: #ffffff;
                border: 1px solid #d0d0d0;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .email-header {
                background-color: #ffffff;
                color: #333333;
                padding: 20px 30px;
                border-bottom: 2px solid #d0d0d0;
            }

            .email-header h1 {
                font-size: 18px;
                margin: 0;
                font-weight: 600;
                color: #333333;
            }

            .email-header p {
                font-size: 13px;
                margin: 5px 0 0 0;
                color: #666666;
            }

            .email-body {
                padding: 30px;
            }

            .greeting {
                font-size: 14px;
                color: #333333;
                margin-bottom: 20px;
                line-height: 1.6;
            }

            .info-table {
                width: 100%;
                border-collapse: collapse;
                margin: 25px 0;
                background-color: transparent;
                border: 1px solid #e0e0e0;
            }

            .info-table tr {
                border-bottom: 1px solid #e0e0e0;
            }

            .info-table tr:last-child {
                border-bottom: none;
            }

            .info-table td {
                padding: 12px 15px;
                font-size: 14px;
            }

            .info-table td:first-child {
                font-weight: 600;
                color: #555555;
                width: 35%;
                background-color: transparent;
            }

            .info-table td:last-child {
                color: #333333;
            }

            .status-badge {
                display: inline-block;
                padding: 5px 14px;
                background-color: #fff3cd;
                color: #856404;
                font-size: 13px;
                font-weight: 600;
                border: 1px solid #ffc107;
            }

            .cta-section {
                text-align: center;
                margin: 30px 0;
            }

            .btn-primary {
                display: inline-block;
                background-color: #ffffff;
                color: #333333;
                padding: 12px 40px;
                text-decoration: none;
                font-size: 14px;
                font-weight: 600;
                border: 2px solid #333333;
            }

            .btn-primary:hover {
                background-color: #f4f4f4;
                border-color: #333333;
            }

            .divider {
                height: 1px;
                background-color: #d0d0d0;
                margin: 25px 0;
            }

            .email-footer {
                background-color: transparent;
                text-align: center;
                padding: 20px;
                border-top: 1px solid #d0d0d0;
            }

            .email-footer p {
                font-size: 12px;
                color: #666666;
                margin: 5px 0;
            }

            .link-text {
                font-size: 12px;
                color: #666666;
                text-align: center;
                margin-top: 20px;
            }

            .link-text a {
                color: #333333;
                word-break: break-all;
            }

            @media only screen and (max-width: 600px) {
                .email-body {
                    padding: 20px;
                }

                .info-table td {
                    display: block;
                    width: 100%;
                }

                .info-table td:first-child {
                    background-color: transparent;
                    font-weight: 600;
                    padding-bottom: 5px;
                }

                .info-table td:last-child {
                    padding-top: 0;
                }
            }
        </style>
    </head>

    <body>
        <div class="email-container">
            <!-- Header -->
            <div class="email-header">
                <h1>Thông báo đơn yêu cầu mới</h1>
            </div>

            <!-- Body -->
            <div class="email-body">
                <div class="greeting">
                    <p>
                        Kính gửi:
                        <strong>{{ $supervisor->name }}</strong>
                    </p>
                    <p>
                        Bạn có một đơn yêu cầu mới cần phê duyệt từ nhân viên
                        <strong>{{ $requestForm->employee->name }}</strong>
                        .
                    </p>
                </div>

                <div class="divider"></div>

                <!-- Thông tin đơn -->
                <table class="info-table">
                    <tr>
                        <td>Loại đơn</td>
                        <td>{{ $requestFormTypeName }}</td>
                    </tr>
                    <tr>
                        <td>Nhân viên</td>
                        <td>
                            <strong>{{ $requestForm->employee->name }}</strong>
                            @if ($requestForm->employee->email)
                                <br />
                                <span style="color: #666666; font-size: 13px">
                                    {{ $requestForm->employee->email }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Ngày gửi</td>
                        <td>{{ $requestForm->submitted_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Tiêu đề</td>
                        <td><strong>{{ $requestForm->title }}</strong></td>
                    </tr>
                    <tr>
                        <td>Trạng thái</td>
                        <td><span class="status-badge">Chờ duyệt</span></td>
                    </tr>
                </table>

                <!-- Nút hành động -->
                <div class="cta-section">
                    <a href="{{ $approvalUrl }}" class="btn-primary">Xem chi tiết & Phê duyệt</a>
                </div>

                <div class="divider"></div>

                <div class="link-text">
                    Nếu nút trên không hoạt động, vui lòng sao chép link sau:
                    <br />
                    <a href="{{ $approvalUrl }}">{{ $approvalUrl }}</a>
                </div>
            </div>

            <!-- Footer -->
            <div class="email-footer">
                <p><strong>{{ config('app.name') }}</strong></p>
                <p>Email tự động - Vui lòng không trả lời</p>
                <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>
        </div>
    </body>
</html>
