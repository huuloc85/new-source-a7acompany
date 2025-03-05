@extends('layouts.'.$layout)

@section('content')
    <style>
        .form-control {
            border: 1px solid #d2d6da !important;
            padding-left: 10px;
        }

        .active > .page-link {
            color: white !important;
        }

        .href {
            color: blue !important;
        }

        .search-role {
            height: 37px;
        }

        /* Fix tabs alignment and appearance */
        .nav-tabs {
            display: flex;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 0;
            padding-left: 0;
            list-style: none;
        }

        .nav-tabs .nav-item {
            margin-bottom: -1px;
        }

        .nav-tabs .nav-link {
            border: 1px solid transparent;
            border-top-left-radius: 0.25rem;
            border-top-right-radius: 0.25rem;
            color: blue;
            padding: 0.5rem 1rem;
            font-weight: 500;
            display: block;
        }

        .nav-tabs .nav-item.show .nav-link,
        .nav-tabs .nav-link.active {
            color: #495057;
            background-color: #ffffff;
            border-color: #dee2e6 #dee2e6 #ffffff;
        }

        .tab-content > .tab-pane {
            display: none;
        }

        .tab-content > .active {
            display: block;
        }

        .tab-content {
            border: 1px solid #dee2e6;
            border-top: none;
            padding: 1rem;
            background-color: #ffffff;
        }

        /* Table styling - uniform appearance */
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            border-bottom: 1px solid #dee2e6;
        }

        /* Table alignment - desktop */
        .table th {
            font-weight: 600;
            text-align: left !important;
            vertical-align: middle !important;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }

        .table td {
            text-align: right !important;
            vertical-align: middle !important;
        }

        /* Center specific columns like STT */
        .table td.stt,
        .table th.stt {
            text-align: center !important;
        }

        /* Uniform cell height */
        .table tr {
            height: 50px;
        }

        /* Responsive table styles */
        @media (max-width: 767.98px) {
            /* Improve tab display on mobile */
            .nav-tabs {
                display: flex;
                justify-content: space-between;
                width: 100%;
            }

            .nav-tabs .nav-item {
                flex: 1;
                text-align: center;
                margin-bottom: 0;
            }

            .nav-tabs .nav-link {
                padding: 0.75rem 0.5rem;
                margin: 0;
                border-radius: 0;
                font-size: 0.9rem;
            }

            /* Card-based table for mobile */
            .table-responsive-card .table {
                border: 0;
            }

            .table-responsive-card .table thead {
                display: none;
            }

            .table-responsive-card .table tr {
                margin-bottom: 20px;
                display: block;
                border: 1px solid #ddd;
                border-radius: 8px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
                background-color: #fff;
                height: auto;
            }

            .table-responsive-card .table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                text-align: right !important;
                padding: 12px 15px;
                border-bottom: 1px solid #eee;
                min-height: 50px;
                font-size: 1rem;
            }

            .table-responsive-card .table td:last-child {
                border-bottom: 0;
            }

            .table-responsive-card .table td:before {
                content: attr(data-label);
                font-weight: bold;
                font-size: 14px;
                color: #000000;
                text-align: left;
                flex: 1;
                padding-right: 15px;
            }

            .table-responsive-card .table td span.value {
                flex: 2;
                text-align: right;
            }

            .table-responsive-card .badge {
                display: inline-block;
                width: auto;
            }
        }

        /* Fix for date input on mobile */
        @media (max-width: 576px) {
            .form-control {
                font-size: 14px;
                /* Prevents zoom on iOS */
            }

            .btn-sm-on-small {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div
                    class="card-header p-1 position-relative mt-n1 mx-1 no-print"
                >
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">
                            Bảng Kiểm Tra Nhân Viên Nhập Sản Lượng
                        </h4>
                    </div>
                    <div>
                        <a
                            class="btn btn-link"
                            href="{{ route('admin.home') }}"
                        >
                            <i class="fas fa-arrow-left"></i>
                            Quay lại
                        </a>
                    </div>
                    <form
                        action="{{ route('admin.daily.productivity.history') }}"
                        method="GET"
                        class="form-inline mb-sm-0 me-sm-2 my-3"
                    >
                        <div class="form-group">
                            <label for="date" class="mb-2">Chọn ngày</label>
                            <input
                                type="date"
                                class="form-control btn-sm-on-small"
                                id="date"
                                name="date"
                                value="{{ request('date', now()->format('Y-m-d')) }}"
                                class="form-control"
                                onchange="this.form.submit()"
                            />
                        </div>
                    </form>
                </div>

                <div class="card-body p-0">
                    <ul class="nav nav-tabs nav-tabs-primary">
                        <li class="nav-item">
                            <a
                                class="nav-link active"
                                data-bs-toggle="tab"
                                href="#productivity-logs"
                            >
                                Nhân viên đã nhập sản lượng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                class="nav-link"
                                data-bs-toggle="tab"
                                href="#employees-without-logs"
                            >
                                Nhân viên chưa nhập sản lượng
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <!-- Bảng Nhân Viên Đã Nhập Sản Lượng -->
                        <div
                            class="tab-pane fade show active"
                            id="productivity-logs"
                        >
                            <div class="table-responsive-card">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th
                                                class="stt text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                            >
                                                STT
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                            >
                                                Tên Nhân Viên
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                            >
                                                Tên Sản Phẩm
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                            >
                                                Số Lượng
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                            >
                                                Thời gian
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                            >
                                                Ngày Nhập
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                            >
                                                Đã Nhập Sản Lượng
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                            >
                                                Ca Làm Việc
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                            >
                                                Trạng Thái
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($productivityLogs as $log)
                                            <tr>
                                                <td
                                                    class="stt"
                                                    data-label="STT"
                                                >
                                                    <span class="value">
                                                        {{ $loop->iteration }}
                                                    </span>
                                                </td>
                                                <td data-label="Tên Nhân Viên">
                                                    <span class="value">
                                                        {{ $log['employee']->name }}
                                                    </span>
                                                </td>
                                                <td data-label="Tên Sản Phẩm">
                                                    <span class="value">
                                                        {{ $log['product']->name }}
                                                    </span>
                                                </td>
                                                <td data-label="Số Lượng">
                                                    <span class="value">
                                                        {{ number_format($log['total_quantity']) }}
                                                    </span>
                                                </td>
                                                <td data-label="Thời gian">
                                                    <span class="value">
                                                        {{ $log['created_at'] }}
                                                    </span>
                                                </td>
                                                <td data-label="Ngày Nhập">
                                                    <span class="value">
                                                        {{ $log['date'] }}
                                                    </span>
                                                </td>
                                                <td
                                                    data-label="Đã Nhập Sản Lượng"
                                                >
                                                    <span class="value">
                                                        {{ $log['status_label'] }}
                                                    </span>
                                                </td>
                                                <td data-label="Ca Làm Việc">
                                                    <span class="value">
                                                        {{ $log['shift'] }}
                                                    </span>
                                                </td>
                                                <td data-label="Trạng Thái">
                                                    <span class="value">
                                                        <span
                                                            class="badge bg-success text-white"
                                                        >
                                                            Đã Nhập Sản Lượng
                                                        </span>
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td
                                                    colspan="9"
                                                    class="text-center"
                                                    style="font-size: 11px"
                                                >
                                                    Hiện tại chưa có lịch sử
                                                    nhập sản lượng.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Bảng Nhân Viên Chưa Nhập Sản Lượng -->
                        <div class="tab-pane fade" id="employees-without-logs">
                            <div class="table-responsive-card">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th
                                                class="stt text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                            >
                                                STT
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                            >
                                                Tên Nhân Viên
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                            >
                                                Mã Nhân Viên
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                            >
                                                Tên Sản Phẩm
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                            >
                                                Ngày Nhập
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                            >
                                                Thời Gian
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                            >
                                                Trạng Thái
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($employeesWithoutLogs as $log)
                                            <tr>
                                                <td
                                                    class="stt"
                                                    data-label="STT"
                                                >
                                                    <span class="value">
                                                        {{ $loop->iteration }}
                                                    </span>
                                                </td>
                                                <td data-label="Tên Nhân Viên">
                                                    <span class="value">
                                                        {{ $log['employee']->name }}
                                                    </span>
                                                </td>
                                                <td data-label="Mã Nhân Viên">
                                                    <span class="value">
                                                        {{ $log['employee']->code }}
                                                    </span>
                                                </td>
                                                <td data-label="Tên Sản Phẩm">
                                                    <span class="value">
                                                        {{ $log['product']->name }}
                                                    </span>
                                                </td>
                                                <td data-label="Ngày Nhập">
                                                    <span class="value">
                                                        {{ $log['date'] }}
                                                    </span>
                                                </td>
                                                <td data-label="Thời Gian">
                                                    <span class="value">
                                                        {{ $log['created_at'] }}
                                                    </span>
                                                </td>
                                                <td data-label="Trạng Thái">
                                                    <span class="value">
                                                        <span
                                                            class="badge bg-danger text-white"
                                                        >
                                                            Chưa Nhập Sản Lượng
                                                        </span>
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td
                                                    colspan="7"
                                                    class="text-center"
                                                    style="font-size: 11px"
                                                >
                                                    Hiện tại không có nhân viên
                                                    chưa nhập sản lượng.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
