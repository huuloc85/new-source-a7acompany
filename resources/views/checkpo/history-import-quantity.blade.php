@extends('master')
@section('content')
<style>
    .btn-edit,
    .btn-delete {
        display: inline-block;
        margin-right: 5px;
        border: none;
        color: #fff;
        padding: 5px 10px;
        text-align: center;
        text-decoration: none;
        font-size: 14px;
        border-radius: 4px;
    }

    .btn-edit {
        background-color: #17a2b8;
    }

    .btn-delete {
        background-color: #dc3545;
    }

    .btn-edit:hover {
        background-color: #138496;
    }

    .btn-delete:hover {
        background-color: #c82333;
    }

    .btn-group-custom {
        display: flex;
        justify-content: center;
    }

    .btn-group-custom form {
        margin: 0;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                <div class="border-radius-lg ps-2 pt-4 pb-3">
                    <h4 class="card-title mb-0">Danh Sách Lịch Sử Cập Nhật Sản Phẩm</h4>
                </div>
            </div>
            <div class="p-4 pb-0 d-flex">
                <a href="{{ route('admin.checkpo.index') }}" type="button" class="btn btn-success">Danh Sách PO</a>
                <div style="flex-grow:1; display: flex; justify-content: end;">
                    <div style="display:flex">
                        <form action="{{ route('admin.history-import-quantity') }}" method="get" id="searchForm"
                            style="display:flex">
                            @csrf
                            <input type="month" name="month" class="form-control"
                                style="height:35px; margin-right:10px" onchange="this.form.submit()"
                                value="{{ isset($month) ? $month->format('Y-m') : Carbon::now()->format('Y-m') }}">

                            <select class="form-control" style="height:35px; margin-right:10px" name="product_id"
                                onchange="this.form.submit()">
                                <option value="">Tất Cả Sản Phẩm</option>
                                @foreach ($products as $product)
                                <option value="{{ $product->id }}"
                                    {{ $productId == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
            </div>
            <div class="px-0 pb-2">
                <ul class="nav nav-tabs px-4" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link tab-vvp active" id="product-tab" data-bs-toggle="tab"
                            data-bs-target="#product" type="button" role="tab" aria-controls="product"
                            aria-selected="true">LỊCH SỬ NHẬP PO</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    @if ($dailyQuantities->isEmpty())
                    <div class="text-center mt-3">Hiện tại không có lịch sử nào.
                        <a href="{{ route('admin.checkpo.index') }}">Quay lại danh
                            sách PO</a>.
                    </div>
                    @else
                    <table class="table align-items-center mb-0 table-hover">
                        <thead>
                            <tr>
                                <th class="text-center text-uppercase text-md font-weight-bolder">STT</th>
                                <th class="text-center text-uppercase text-md font-weight-bolder">Tên sản phẩm</th>
                                <th class="text-center text-uppercase text-md font-weight-bolder">Số lượng</th>
                                <th class="text-center text-uppercase text-md font-weight-bolder">Ngày nhập sản
                                    lượng
                                    xuất hàng</th>
                                <th class="text-center text-uppercase text-md font-weight-bolder">Thời gian cập nhật
                                </th>
                                <th class="text-center text-uppercase text-md font-weight-bolder">Người nhập</th>
                                <th class="text-center text-uppercase text-md font-weight-bolder">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dailyQuantities as $key => $dailyQuantity)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td class="text-center">{{ $dailyQuantity->product->name }}</td>
                                <td class="text-center">{{ number_format($dailyQuantity->quantity) }}</td>
                                <td class="text-center">{{ $dailyQuantity->date }}</td>
                                <td class="text-center">{{ $dailyQuantity->created_at }}</td>
                                <td class="text-center">{{ $dailyQuantity->employee->name }}</td>
                                <td class="text-center btn-group-custom">
                                    <div class="btn-group" role="group" aria-label="Button Group">
                                        <button type="button" class="btn btn-primary me-2 rounded"
                                            data-daily-id="{{ $dailyQuantity->id }}"
                                            data-daily-quan="{{ $dailyQuantity->quantity }}"
                                            data-product-id="{{ $dailyQuantity->product->id }}" data-status="8"
                                            data-bs-toggle="modal" data-bs-target="#updatePO" title="Chỉnh sửa"
                                            data-bs-placement="top">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <form action="{{ route('admin.checkpo.delete', $dailyQuantity->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa không?')"
                                                title="Xóa" data-bs-toggle="tooltip" data-bs-placement="top">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@include('checkpo.edit-po')
@endsection