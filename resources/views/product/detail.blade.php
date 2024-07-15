@extends('master')
@section('content')
    <style>
        .form-control {
            border: 1px solid #d2d6da !important;
            padding-left: 10px;
        }

        .active>.page-link {
            color: white !important
        }

        .href {
            color: blue !important;
        }

        .trash {
            margin-left: 10px;
        }

        .product-tab {
            max-width: 75%;
            overflow-x: auto;
        }

        .product-tab-2 {
            max-width: 70%;
            overflow-x: auto;
        }

        @media (max-width: 768px) {
            .d-flex {
                flex-direction: column;
                align-items: stretch;
            }

            .form-control {
                width: 100%;

            }
        }
    </style>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Danh Sách Lịch Sử Cập Nhật Sản Phẩm</h4>
                    </div>
                </div>
                <div class="p-4 pb-0 d-flex">
                    <a href="{{ route('admin.product.home') }}" type="button" class='btn btn-success'>Danh Sách Sản Phẩm</a>
                    <a href="{{ route('admin.product.update-quantity-admin', $id) }}" type="button"
                        class='btn btn-warning ms-2'>Cập nhật sản lượng</a>
                    <div style="flex-grow:1; display: flex; justify-content: end;">
                        <div style="display:flex">
                            <form action="" method="get" style="display:flex">
                                @csrf
                                <select class="form-control" style="height:35px; margin-right:10px" name="month">
                                    @foreach ($listMonth as $month)
                                        <option <?= $month == $monthNearly ? 'selected' : '' ?> value="{{ $month }}">
                                            {{ $month }}
                                        </option>
                                    @endforeach
                                </select>
                                <button style="width:230px" type="submit" class='btn btn-primary'>Tìm kiếm</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="ps-4 d-flex">
                </div>
                <div class="px-0 pb-2">
                    <ul class="nav nav-tabs px-4" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link tab-vvp active" id="product-tab" data-bs-toggle="tab"
                                data-bs-target="#product" type="button" role="tab" aria-controls="product"
                                aria-selected="true">LỊCH SỬ SẢN XUẤT (100%)</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link tab-vvp" id="check-100-tab" data-bs-toggle="tab"
                                data-bs-target="#check-100" type="button" role="tab" aria-controls="check-100"
                                aria-selected="false">LỊCH SỬ HÀNG KIỂM (200%)</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link tab-vvp" id="import-200-tab" data-bs-toggle="tab"
                                data-bs-target="#import-200" type="button" role="tab" aria-controls="import-200"
                                aria-selected="false">LỊCH SỬ XUẤT HÀNG (200%)</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link tab-vvp" id="import-300-tab" data-bs-toggle="tab"
                                data-bs-target="#import-300" type="button" role="tab" aria-controls="import-300"
                                aria-selected="false">LỊCH SỬ HÀNG LỖI </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div style="margin-top:20px; margin-left:20px">
                            <label style="font-size: 18px" for="">Tên sản phẩm: {{ $product->name }}</label>
                            <label style="font-size: 18px; margin-left:20px" for="">Mã sản phẩm:
                                {{ $product->code }}</label>
                        </div>
                        <div class="tab-pane tab-vvp fade show active" id="product" role="tabpanel"
                            aria-labelledby="product-tab">
                            <div class="px-0 pb-2">
                                <div class="table-responsive p-0 d-flex">
                                    <div class="col-12">
                                        <table class="table align-items-center mb-0 table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                        rowspan="2">STT</th>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                        rowspan="2">Tên nhân viên</th>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                        rowspan="2">Mã nhân viên</th>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                        rowspan="2">Thời gian cập nhật sản lượng</th>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                        rowspan="2">Thời gian cuối cùng cập nhật</th>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                        rowspan="2">Số lượng</th>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-2"
                                                        rowspan="2">Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($dailyQuanStatus1 as $key => $daily)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ $loop->iteration }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ $daily->employee->name }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ $daily->employee->code }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ $daily->date }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ $daily->created_at }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ number_format($daily->quantity) }}
                                                            </div>
                                                        </td>
                                                        <td style="display:flex">
                                                            <button data-daily-id="{{ $daily->id }}"
                                                                data-daily-quan="{{ $daily->quantity }}"
                                                                data-product-id="{{ $id }}" data-status=1
                                                                type="button" style="margin-bottom: 0px;height: 38px"
                                                                class='btn btn-primary' data-bs-toggle="modal"
                                                                data-bs-target="#updateDetail">
                                                                Cập nhật
                                                            </button>
                                                            <form class="ms-2"
                                                                action="{{ route('admin.product.delete-update-quantity', $daily->id) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button
                                                                    onclick="return confirm('Bạn có chắc muốn xoá cập nhật sản lượng này không?');"
                                                                    class="btn btn-danger">Xoá</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @if ($total1 == 0)
                                                    <tr>
                                                        <td colspan="7" class="text-center pt-4">Hiện tại chưa có lịch
                                                            sử
                                                            sản xuất.</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div style="display: flex; justify-content: center; align-items: center; margin:20px">
                                <div>
                                    {{ $dailyQuanStatus1->appends(request()->all())->links() }}
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane tab-vvp fade" id="check-100" role="tabpanel"
                            aria-labelledby="check-100-tab">
                            <div class="px-0 pb-2">
                                <div class="table-responsive p-0 d-flex">
                                    <div class="col-12">
                                        <table class="table align-items-center mb-0 table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                        rowspan="2">STT</th>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                        rowspan="2">Tên nhân viên</th>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                        rowspan="2">Mã nhân viên</th>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                        rowspan="2">Thời gian cập nhật</th>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                        rowspan="2">Thời gian cuối cùng cập nhật</th>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                        rowspan="2">Số lượng</th>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                        rowspan="2">Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($dailyQuanStatus2 as $key => $daily)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ $loop->iteration }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ $daily->employee->name }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ $daily->employee->code }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ $daily->date }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ $daily->created_at }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ number_format($daily->quantity) }}
                                                            </div>
                                                        </td>
                                                        <td style="display:flex">
                                                            <button data-daily-id="{{ $daily->id }}"
                                                                data-daily-quan="{{ $daily->quantity }}"
                                                                data-product-id="{{ $id }}" data-status=2
                                                                type="button" style="margin-bottom: 0px;height:38px"
                                                                class='btn btn-primary' data-bs-toggle="modal"
                                                                data-bs-target="#updateDetail">
                                                                Cập nhật
                                                            </button>
                                                            <form class="ms-2"
                                                                action="{{ route('admin.product.delete-update-quantity', $daily->id) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button
                                                                    onclick="return confirm('Bạn có chắc muốn xoá cập nhật sản lượng này không?');"
                                                                    class="btn btn-danger">Xoá</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @if ($total2 == 0)
                                                    <tr>
                                                        <td colspan="7" class="text-center pt-4">Hiện tại chưa có lịch
                                                            sử hàng kiểm 200%.</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div style="display: flex; justify-content: center; align-items: center; margin:20px">
                                <div>
                                    {{ $dailyQuanStatus2->appends(request()->all())->links() }}
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane tab-vvp fade" id="import-200" role="tabpanel"
                            aria-labelledby="import-200-tab">
                            <div class="px-0 pb-2">
                                <div class="table-responsive p-0 d-flex">
                                    <div class="col-12">
                                        <table class="table align-items-center mb-0 table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                        rowspan="2">STT</th>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                        rowspan="2">Tên nhân viên</th>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                        rowspan="2">Mã nhân viên</th>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                        rowspan="2">Thời gian cập nhật</th>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                        rowspan="2">Thời gian cuối cùng cập nhật</th>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                        rowspan="2">Số lượng</th>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                        rowspan="2">Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($dailyQuanStatus3 as $key => $daily)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ $loop->iteration }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ $daily->employee->name }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ $daily->employee->code }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ $daily->date }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ $daily->created_at }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ number_format($daily->quantity) }}
                                                            </div>
                                                        </td>
                                                        <td style="display:flex">
                                                            <button data-daily-id="{{ $daily->id }}"
                                                                data-daily-quan="{{ $daily->quantity }}"
                                                                data-product-id="{{ $id }}" data-status=3
                                                                type="button" style="margin-bottom: 0px;height:38px"
                                                                class='btn btn-primary' data-bs-toggle="modal"
                                                                data-bs-target="#updateDetail">
                                                                Cập nhật
                                                            </button>
                                                            <form class="ms-2"
                                                                action="{{ route('admin.product.delete-update-quantity', $daily->id) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button
                                                                    onclick="return confirm('Bạn có chắc muốn xoá cập nhật sản lượng này không?');"
                                                                    class="btn btn-danger">Xoá</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @if ($total3 == 0)
                                                    <tr>
                                                        <td colspan="7" class="text-center pt-4">Hiện tại chưa có lịch
                                                            sử hàng xuất 200%.</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div style="display: flex; justify-content: center; align-items: center; margin:20px">
                                <div>
                                    {{ $dailyQuanStatus3->appends(request()->all())->links() }}
                                </div>
                            </div>
                        </div>
                        <!-- Tab Error-->
                        <div class="tab-pane tab-vvp fade" id="import-300" role="tabpanel"
                            aria-labelledby="import-300-tab">
                            <div class="px-0 pb-2">
                                <div class="table-responsive p-0 d-flex">
                                    <div class="col-12">
                                        <table class="table align-items-center mb-0 table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                        rowspan="2">STT</th>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                        rowspan="2">Tên nhân viên</th>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                        rowspan="2">Mã nhân viên</th>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                        rowspan="2">Thời gian cập nhật</th>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                        rowspan="2">Thời gian cuối cùng cập nhật</th>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                        rowspan="2">Số lượng</th>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1"
                                                        rowspan="2">Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($dailyQuanStatus6 as $key => $daily)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ $loop->iteration }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ $daily->employee->name }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ $daily->employee->code }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ $daily->date }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ $daily->created_at }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ number_format($daily->quantity) }}
                                                            </div>
                                                        </td>
                                                        <td style="display:flex">
                                                            <button data-daily-id="{{ $daily->id }}"
                                                                data-daily-quan="{{ $daily->quantity }}"
                                                                data-product-id="{{ $id }}" data-status=6
                                                                type="button" style="margin-bottom: 0px;height:38px"
                                                                class='btn btn-primary' data-bs-toggle="modal"
                                                                data-bs-target="#updateDetail">
                                                                Cập nhật
                                                            </button>
                                                            <form class="ms-2"
                                                                action="{{ route('admin.product.delete-update-quantity', $daily->id) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button
                                                                    onclick="return confirm('Bạn có chắc muốn xoá cập nhật sản lượng này không?');"
                                                                    class="btn btn-danger">Xoá</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @if ($total6 == 0)
                                                    <tr>
                                                        <td colspan="7" class="text-center pt-4">Hiện tại chưa có lịch
                                                            sử hàng lỗi.</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div style="display: flex; justify-content: center; align-items: center; margin:20px">
                                <div>
                                    {{ $dailyQuanStatus6->appends(request()->all())->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('product.update-history')
@endsection
