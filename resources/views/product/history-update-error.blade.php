@extends('layouts.layout')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Lịch Sử Nhập Hàng Lỗi</h4>
                    </div>
                </div>
                <div class="card-body">
                    <a class="btn btn-primary" href="{{ route('admin.product.update-quantity') }}">
                        <i class="fas fa-arrow-left"></i>
                        Quay lại
                    </a>
                    <h5 class="text-center">Thông tin cập nhật</h5>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div>
                                <span class="fw-bold">Tên nhân viên:</span>
                                {{ Auth()->user()->name ?? '' }}
                            </div>
                            <div>
                                <span class="fw-bold">Mã nhân viên:</span>
                                {{ Auth()->user()->code ?? '' }}
                            </div>
                            <form action="" id="searchForm" class="my-3">
                                <div class="d-flex flex-column flex-sm-row gap-3">
                                    <select class="form-control" name="month" id="monthSelect">
                                        @foreach ($listMonth as $month)
                                            <option {{ $month == $monthNearly ? 'selected' : '' }}
                                                value="{{ $month }}">
                                                {{ $month }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <select class="form-control" name="product_id" id="productSelect">
                                        <option {{ $product_id == '' ? 'selected' : '' }} value="">
                                            Chọn sản phẩm
                                        </option>
                                        @foreach ($listProduct as $product)
                                            <option {{ $product_id == $product->id ? 'selected' : '' }}
                                                value="{{ $product->id }}">
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                            <div class="d-flex justify-content-between">
                                <h6>Ngày</h6>
                                <h6>Số lượng</h6>
                            </div>
                            @foreach ($datas as $data)
                                <div class="d-flex justify-content-between pb-1">
                                    <span>
                                        {{ $data->date }}
                                    </span>
                                    <span>
                                        {{ $data->quantity }}
                                    </span>
                                </div>
                            @endforeach

                            @if (count($datas) == 0)
                                <div class="text-center mt-3">
                                    Chưa có thông tin cập nhật
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document
            .getElementById('monthSelect')
            .addEventListener('change', function() {
                document.getElementById('searchForm').submit();
            });
        document
            .getElementById('productSelect')
            .addEventListener('change', function() {
                document.getElementById('searchForm').submit();
            });
    </script>
@endsection
