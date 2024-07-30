@extends('master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Nhập Thông Tin Chi Tiết Kế Hoạch Sản Phẩm</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.product-plan.store') }}" method="POST">
                            @csrf

                            <div class="form-grid">
                                <!-- Phần 1 -->
                                <div class="form-group">
                                    <label for="products" id="products-label">Chọn Sản Phẩm</label>
                                    <select id="products" name="products[]" class="form-control" multiple required
                                        onchange="updateLabel('products')">
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="material_name" id="material-name-label">Tên Nguyên Vật Liệu</label>
                                    <select id="material_name" name="material_name[]" class="form-control" multiple required
                                        onchange="updateLabel('material_name')">
                                        @foreach ($materials as $material)
                                            <option value="{{ $material }}">{{ $material }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="production_plan">Kế Hoạch Sản Xuất (PCS)</label>
                                    <input type="number" id="production_plan" name="production_plan" class="form-control"
                                        required>
                                </div>

                                <!-- Phần 2 -->
                                {{-- <div class="form-group">
                                    <label for="planned_material">Dự Định Vật Liệu (KG)</label>
                                    <input type="number" id="planned_material" name="planned_material" class="form-control"
                                        required>
                                </div> --}}

                                <div class="form-group">
                                    <label for="packaging_type">Loại Bao Bì</label>
                                    <input type="text" id="packaging_type" name="packaging_type" class="form-control"
                                        required>
                                </div>

                                <div class="form-group">
                                    <label for="packaging_count_per_box">Số Bao Bì/Thùng</label>
                                    <input type="number" id="packaging_count_per_box" name="packaging_count_per_box"
                                        class="form-control" required>
                                </div>

                                {{-- <div class="form-group">
                                    <label for="total_packaging">Tổng Bao Bì</label>
                                    <input type="number" id="total_packaging" name="total_packaging" class="form-control"
                                        required>
                                </div> --}}

                                <!-- Phần 3 -->
                                <div class="form-group">
                                    <label for="box_type">Loại Thùng</label>
                                    <input type="text" id="box_type" name="box_type" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="products_per_box">Sản Phẩm/Thùng</label>
                                    <input type="number" id="products_per_box" name="products_per_box" class="form-control"
                                        required>
                                </div>

                                {{-- <div class="form-group">
                                    <label for="box_quantity">Số Lượng Thùng</label>
                                    <input type="number" id="box_quantity" name="box_quantity" class="form-control"
                                        required>
                                </div> --}}

                                <div class="form-group">
                                    <label for="product_density">Tỷ Trọng Sản Phẩm (G)</label>
                                    <input type="number" id="product_density" name="product_density" class="form-control"
                                        required>
                                </div>

                                {{-- <div class="form-group">
                                    <label for="daily_production_plan">Kế Hoạch SX/Ngày</label>
                                    <input type="number" id="daily_production_plan" name="daily_production_plan"
                                        class="form-control" required>
                                </div> --}}

                                <div class="form-group">
                                    <label for="cavity_count">Số Cavity</label>
                                    <input type="number" id="cavity_count" name="cavity_count" class="form-control"
                                        required>
                                </div>

                                <div class="form-group">
                                    <label for="cycle">Chu Kỳ</label>
                                    <input type="number" id="cycle" name="cycle" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="ton">Tấn</label>
                                    <input type="number" id="ton" name="ton" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="machine">Máy</label>
                                    <input type="text" id="machine" name="machine" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="machine_run_days">Số Ngày Chạy Máy</label>
                                    <input type="number" id="machine_run_days" name="machine_run_days" class="form-control"
                                        required>
                                </div>

                                <div class="form-group">
                                    <label for="remaining_production_days">Số Ngày Còn SX (Ngày)</label>
                                    <input type="number" id="remaining_production_days" name="remaining_production_days"
                                        class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="remaining_production_quantity">Số Lượng Còn SX (PCS)</label>
                                    <input type="number" id="remaining_production_quantity"
                                        name="remaining_production_quantity" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="produced_quantity">Sản Lượng Đã Sản Xuất</label>
                                    <input type="number" id="produced_quantity" name="produced_quantity"
                                        class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary">Lưu Kế Hoạch</button>
                                <a href="{{ route('admin.product-plan.add') }}" class="btn btn-secondary">Hủy</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    .form-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
    }

    .form-control {
        width: 100%;
    }
</style>
