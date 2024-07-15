@extends('master')
@section('content')
    <style>
        .form-control {
            border: 1px solid #d2d6da !important;
            padding-left: 10px;
        }

        .required {
            color: red;
        }
    </style>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Cập Nhật Sản Lượng</h4>
                </div>
            </div>
                <div class="px-0 pb-2">
                    <div class="table-responsive p-4">
                        <form action="{{ route('admin.handle-update') }}" method="post">
                            @csrf
                            <input type="hidden" value="{{ $product->id }}" name="product_id">
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Mã liên kiện</label>
                                        <input type="text" class="form-control" name="code"
                                            value="{{ $product->code }}" disabled>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tên linh kiện</label>
                                        <input type="text" class="form-control" name="code"
                                            value="{{ $product->name }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label class="form-label">Ngày cập nhật<span class="required">*</span></label>
                                        <input type="date" min="{{ date('Y-m-01') }}" max="{{ date('Y-m-t') }}"
                                            class="form-control @error('name') is-invalid @enderror"
                                            placeholder="Ngày cập nhật" name="date" value="{{ old('date') }}" required>
                                        @error('date')
                                            <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label class="form-label">Số lượng<span class="required">*</span></label>
                                        <input type="number" placeholder="Số lượng"
                                            class="form-control @error('quantity') is-invalid @enderror" name="quantity"
                                            value="{{ old('quantity') }}" required>
                                        @error('quantity')
                                            <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label class="form-label">Chọn danh mục<span class="required">*</span></label>
                                        <select class="form-control" name="status">
                                            <option value="1">Sản xuất (100%)</option>
                                            <option value="2">Hàng kiểm (200%)</option>
                                            <option value="3">Xuất hàng (200%)</option>
                                            <option value="6">Hàng Lỗi</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">Cập Nhật</button>
                            <a href="{{ route('admin.product.detail', $product->id) }}" type="button"
                                class="btn btn-danger">Quay lại</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
