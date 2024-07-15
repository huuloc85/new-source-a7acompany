@extends('master')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Lịch Sử Cập Nhật Sản Lượng</h4>
                    </div>
                </div>
                <div class="p-4 pb-0 d-flex">
                    <a class="btn btn-danger" href="{{ route('admin.product.update-quantity') }}">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
                <div class="px-4 pb-2">
                    <h5 class="text-center">Thông tin cập nhật</h5>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div>
                                <label class="form-label fw-bold" for="">Tên nhân viên:
                                    <label>{{ Auth()->user()->name ?? '' }}</label></label>
                            </div>
                            <div>
                                <label class="form-label fw-bold" for="">Mã nhân viên:
                                    <label>{{ Auth()->user()->code ?? '' }}</label></label>
                            </div>
                            <div>
                                <form action="" id="searchForm">
                                    <div style="display:flex">
                                        <select class="form-control" style="height:35px; margin-right:10px" name="month"
                                            id="monthSelect" onchange="document.getElementById('searchForm').submit();">
                                            @foreach ($listMonth as $month)
                                                <option {{ $month == $monthNearly ? 'selected' : '' }}
                                                    value="{{ $month }}">
                                                    {{ $month }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <select class="form-control" style="height:35px; margin-right:10px"
                                            name="product_id" id="productSelect"
                                            onchange="document.getElementById('searchForm').submit();">
                                            <option {{ $product_id == '' ? 'selected' : '' }} value="">Chọn sản phẩm
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
                            </div>
                            <div class=" d-flex justify-content-between">
                                <div class="text-start pt-1">
                                    <h6 class="mb-0">Ngày</h6>
                                </div>
                                <div class="text-end pt-1">
                                    <h6 class="mb-0">Số lượng</h6>
                                </div>
                            </div>
                            @foreach ($datas as $data)
                                <div class=" d-flex justify-content-between">
                                    <div class="text-start pt-1">
                                        <label class="form-label fw-bold" for="">{{ $data->date }}</label>
                                    </div>
                                    <div class="text-end pt-1">
                                        <label>{{ $data->quantity }}</label>
                                    </div>
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
    <script>
        document.getElementById('monthSelect').addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });
        document.getElementById('productSelect').addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });
    </script>
@endsection
