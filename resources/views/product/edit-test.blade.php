@extends('master')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header p-1">
                <h4 class="card-title">Chỉnh sửa sản phẩm</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.product.updateTest', $product->id) }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="name">Tên Sản Phẩm</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}"
                            required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="code">Mã Sản Phẩm</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code', $product->code) }}"
                            required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="material">Vật Liệu</label>
                        <input type="text" name="material" class="form-control"
                            value="{{ old('material', $product->material) }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="color">Màu Sắc</label>
                        <input type="text" name="color" class="form-control"
                            value="{{ old('color', $product->color) }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="quantity_per_package">Số Lượng Trên Bao Bì</label>
                        <input type="number" name="quantity_per_package" class="form-control"
                            value="{{ old('quantity_per_package', $product->quantity_per_package) }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="quanEntityBin">Số Lượng Trên Thùng</label>
                        <input type="number" name="quanEntityBin" class="form-control"
                            value="{{ old('quanEntityBin', $product->quanEntityBin) }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
@endsection
