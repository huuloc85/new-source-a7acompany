@extends('master')

@section('content')
<style>
    .form-control {
        border: 1px solid #d2d6da !important;
        padding-left: 10px;
    }

    .active>.page-link {
        color: white !important;
    }

    .href {
        color: blue !important;
    }

    .search-role {
        height: 37px;
    }

    .table td,
    .table th {
        white-space: normal;
    }

    /* Custom styles for tabs */
    .nav-tabs .nav-item.show .nav-link,
    .nav-tabs .nav-link.active {
        color: #495057;
        background-color: #ffffff;
        border-color: #dee2e6 #dee2e6 #ffffff;
    }

    .nav-tabs .nav-link {
        color: blue;
        /* Unselected tab color */
        border: 1px solid transparent;
        border-top-left-radius: .25rem;
        border-top-right-radius: .25rem;
    }

    .nav-tabs {
        border-bottom: 1px solid #dee2e6;
    }

    .tab-content {
        border: 1px solid #dee2e6;
        border-top: none;
        padding: 1rem;
        background-color: #ffffff;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                <div class="border-radius-lg ps-2 pt-4 pb-3">
                    <h4 class="card-title mb-0">Bảng Kiểm Tra Nhân Viên Nhập Sản Lượng</h4>
                </div>
                <form action="{{ route('admin.daily.productivity.history') }}" method="GET"
                    class="form-inline mb-sm-0 me-sm-2 my-3">
                    <div class="form-group">
                        <label for="date" class="visually-hidden">Chọn ngày</label>
                        <input type="date" class="form-control btn-sm-on-small" id="date" name="date"
                            value="{{ request('date', now()->format('Y-m-d')) }}" class="form-control"
                            onchange="this.form.submit()">
                    </div>
                </form>
                {{-- <form method="GET" action="{{ route('admin.employee-history-check') }}"
                class="form-inline mb-sm-0 me-sm-2 my-3">
                <div class="form-group">
                    <label for="filter_date" class="visually-hidden">Chọn ngày</label>
                    <input type="date" class="form-control btn-sm-on-small" name="filter_date" id="filter_date"
                        value="{{ request('filter_date', now()->format('Y-m-d')) }}" onchange="this.form.submit()">
                </div>
                </form> --}}
            </div>

            <div class="tab-content my-2">
                <ul class="nav nav-tabs nav-tabs-primary">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#productivity-logs">Nhân viên đã nhập sản
                            lượng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#employees-without-logs">Nhân viên chưa nhập sản
                            lượng</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <!-- Bảng Nhân Viên Đã Nhập Sản Lượng -->
                    <div class="tab-pane fade show active" id="productivity-logs">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">STT</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tên Nhân Viên</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tên Sản Phẩm</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Số Lượng</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Thời gian</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ngày Nhập</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Đã Nhập Sản Lượng</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ca Làm Việc</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Trạng Thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($productivityLogs as $log)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $log['employee']->name }}</td>
                                        <td class="text-center">{{ $log['product']->name }}</td>
                                        <td class="text-center">{{ number_format($log['total_quantity']) }}</td>
                                        <td class="text-center">{{ $log['created_at'] }}</td>
                                        <td class="text-center">{{ $log['date'] }}</td>
                                        <td class="text-center">{{ $log['status_label'] }}</td>
                                        <td class="text-center">{{ $log['shift'] }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-success text-font-white">Đã Nhập Sản Lượng</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Hiện tại chưa có lịch sử nhập sản
                                            lượng.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Bảng Nhân Viên Chưa Nhập Sản Lượng -->
                    <div class="tab-pane fade" id="employees-without-logs">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">STT</th>
                                        <th class="text-center">Tên Nhân Viên</th>
                                        <th class="text-center">Mã Nhân Viên</th>
                                        <th class="text-center">Tên Sản Phẩm</th>
                                        <th class="text-center">Ngày Nhập</th>
                                        <th class="text-center">Thời Gian</th>
                                        <th class="text-center">Trạng Thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($employeesWithoutLogs as $log)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $log['employee']->name }}</td>
                                        <td class="text-center">{{ $log['employee']->code }}</td>
                                        <td class="text-center">{{ $log['product']->name }}</td>
                                        <td class="text-center">{{ $log['date'] }}</td>
                                        <td class="text-center">{{ $log['created_at'] }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-danger text-font-white">Chưa Nhập Sản Lượng</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Hiện tại không có nhân viên chưa nhập
                                            sản lượng.</td>
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