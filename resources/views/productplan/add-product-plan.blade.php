@extends('master')

@section('content')
<style>
    .container {
        max-width: 100%;
        padding: 0 1rem;
        /* display: flex;
                                                                    justify-content: center; */
    }

    .grid-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-group label {
        margin-bottom: 0.5rem;
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .form-group input,
    .form-group select {
        width: 100%;
    }

    .form-buttons {
        grid-column: span 3;
        display: flex;
        justify-content: center;
        gap: 1rem;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                {{-- <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Nhập Thông Tin Chi Tiết Kế Hoạch Sản Phẩm</h4>
                    </div> --}}
                <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Nhập Thông Tin Chi Tiết Kế Hoạch Sản Phẩm</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.product-plan.store') }}" method="POST">
                        @csrf
                        <div class="grid-container">
                            <!-- Row 1 -->
                            <div class="form-group">
                                <label for="product_select">Chọn Sản Phẩm</label>
                                <select class="form-control" id="product_select" name="product_id">
                                    <option value="">Tất Cả Sản Phẩm</option>
                                    @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-bin-code="{{ $product->binCode }}"
                                        data-quan-entity-bin="{{ $product->quanEntityBin }}">
                                        {{ $product->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="material_name">Chọn Nguyên Liệu</label>
                                <select class="form-control" id="material_name" name="material_name">
                                    <option value="">Tất Cả Nguyên Liệu</option>
                                    @foreach ($materials as $material)
                                    <option>{{ $material }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="material_color">Màu Sắc</label>
                                <select class="form-control" id="material_color" name="material_color">
                                    <option value="">Tất Cả Nguyên Liệu</option>
                                    @foreach ($materialcolor as $color)
                                    <option>{{ $color }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="production_plan">Kế Hoạch Sản Xuất (PCS)</label>
                                <input type="number" id="production_plan" name="production_plan" class="form-control">
                            </div>

                            <!-- Row 2 -->
                            <div class="form-group">
                                <label for="packaging_type">Loại Bao Bì</label>
                                <select id="packaging_type" name="packaging_type" class="form-control" required>
                                    <option value="">Tất Cả Bao Bì</option>
                                    @foreach ($packagingTypes as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="packaging_count_per_box">Số Bao Bì/Thùng</label>
                                <input type="number" id="packaging_count_per_box" name="packaging_count_per_box"
                                    class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="box_type">Loại Thùng</label>
                                <input type="text" id="box_type" name="box_type" class="form-control" required>
                            </div>

                            <!-- Row 3 -->
                            <div class="form-group">
                                <label for="products_per_box">Sản Phẩm/Thùng</label>
                                <input type="number" id="products_per_box" name="products_per_box" class="form-control"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="product_density">Tỷ Trọng Sản Phẩm (G)</label>
                                <input id="product_density" name="product_density" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="cavity_count">Số Cavity</label>
                                <input type="number" id="cavity_count" name="cavity_count" class="form-control"
                                    required>
                            </div>

                            <!-- Row 4 -->
                            <div class="form-group">
                                <label for="cycle">Chu Kỳ</label>
                                <input id="cycle" name="cycle" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="ton">Tấn</label>
                                <input type="number" id="ton" name="ton" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="machine">Máy</label>
                                <input type="text" id="machine" name="machine" class="form-control" required>
                            </div>

                            <!-- Submit and Cancel buttons -->
                            <div class="form-buttons">
                                <button type="submit" class="btn btn-primary">Lưu Kế Hoạch</button>
                                <a href="{{ route('admin.product-plan.index') }}" class="btn btn-secondary">Hủy</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('product_select').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var binCode = selectedOption.getAttribute('data-bin-code');
        var quanEntityBin = selectedOption.getAttribute('data-quan-entity-bin');

        document.getElementById('box_type').value = binCode || '';
        document.getElementById('products_per_box').value = quanEntityBin || '';
    });
</script>
@endsection