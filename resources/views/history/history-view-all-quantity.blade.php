@extends('master')

@section('content')
    <style>
        /* Căn chỉnh và khoảng cách */
        .card {
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .table th,
        .table td {
            vertical-align: middle;
            text-align: center;
        }

        .form-control-md {
            width: auto;
        }
    </style>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Lịch Sử Nhập Sản Lượng Hàng Ngày Của Nhân Viên</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="pb-2 d-flex flex-column flex-sm-row">
                        <a href="{{ route('admin.history.home') }}" class="btn btn-danger mb-2 mb-sm-0 me-sm-2">
                            <i class="fas fa-arrow-left"></i> Quay lại</a>
                        </a>
                        <form action="{{ route('admin.history.view.all.quantity') }}" method="GET"
                            class="form-inline mb-sm-0 me-sm-2">
                            <div class="form-group mb-0">
                                <label for="date" class="visually-hidden">Chọn ngày</label>
                                <input type="date" class="form-control form-control-md me-2" id="date"
                                    name="date" value="{{ request('date', now()->format('Y-m-d')) }}"
                                    onchange="this.form.submit()">
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0 table-hover">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-sm text-center">
                                        STT</th>
                                    <th class="text-uppercase text-secondary text-sm text-center">
                                        Tên Nhân Viên</th>
                                    <th class="text-uppercase text-secondary text-sm text-center">
                                        Tên Sản Phẩm</th>
                                    <th class="text-uppercase text-secondary text-sm text-center">
                                        Số Lượng</th>
                                    <th class="text-uppercase text-secondary text-sm text-center">
                                        Loại Hàng</th>
                                    <th class="text-uppercase text-secondary text-sm text-center">
                                        Ngày Nhập</th>
                                    <th class="text-uppercase text-secondary text-sm text-center">
                                        Thời Gian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dailyQuantities as $key => $dailyQuantity)
                                    <tr>
                                        <td class="text-center">
                                            <div class="text-md font-weight-bold mb-0">
                                                {{ $loop->iteration }}
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-md font-weight-bold mb-0">
                                                {{ $dailyQuantity->employee->name }}
                                            </p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-md font-weight-bold mb-0">{{ $dailyQuantity->product->name }}
                                            </p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-md font-weight-bold mb-0">
                                                {{ number_format($dailyQuantity->quantity) }}
                                            </p>
                                        </td>

                                        <td class="text-center">
                                            <p class="text-md font-weight-bold mb-0">
                                                {{ $translateStatus($dailyQuantity->status) }}
                                            </p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-md font-weight-bold mb-0">
                                                {{ $formatDate($dailyQuantity->date) }}
                                            </p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-md font-weight-bold mb-0">
                                                {{ $dailyQuantity->created_at->format('H:i:s') }}
                                            </p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($dailyQuantities->isEmpty())
                        <div class="text-center">
                            Hiện tại không có dữ liệu.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
