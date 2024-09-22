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
            <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                <div class="border-radius-lg ps-2 pt-4 pb-3">
                    <h4 class="card-title mb-0">Thêm Sản Lượng Sản Xuất</h4>
                </div>
            </div>
            <div class="px-0 pb-2">
                <div class="table-responsive ps-4">
                    <form action="{{ route('admin.product.handle-add-quantity-admin') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-inline my-2">
                            <div class="form-group" style="display: inline-block;">
                                <label for="date" class="mr-2">Chọn ngày cập nhật:</label>
                                <select class="form-control form-control-sm" name="date" id="date" style="width: auto;">
                                    @foreach ($listDate as $date)
                                    <option value="{{ $date }}" {{ $date == $currentDate ? 'selected' : '' }}>
                                        {{ $date }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('date')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group ml-3" style="display: none;">
                                <label for="shift" class="mr-2">Ca làm việc:</label>
                                <select class="form-control form-control-sm" name="shift" id="shift" required style="width: auto;">
                                    <option value="1">Ca 1</option>
                                    <option value="2">Ca 2</option>
                                </select>
                                @error('shift')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group ml-3" style="display: inline-block;">
                                <select class="form-control form-control-sm" name="status" id="status" required style="width: auto;" readonly>
                                    <option value="" selected disabled>Chọn loại sản lượng</option>
                                    <option value="1">Hàng 100%</option>
                                    <option value="2">Hàng 200%</option>
                                    <option value="6">Hàng lỗi</option>
                                    <option value="3">Xuất hàng</option>
                                </select>
                                @error('status')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 30px;" class="mb-3">
                            @if (!empty($products))
                            @foreach ($products as $product)
                            <div style="display: flex; justify-content: space-between;">
                                <div>
                                    <input type="hidden" style="margin-top:-3px" name="productId[]" value='{{ $product->id }}'>
                                    <label class="me-2" style="margin-top:3px" for="html">{{ $product->name }} :</label>
                                </div>
                                <input type="number" style="height:27px; width:100px; margin-right:50px" min="0" class="form-control" name="quantity[]">
                            </div>
                            @endforeach
                            @endif
                        </div>
                        <button type="submit" class="btn btn-success">Cập nhật</button>
                        <a href="{{ route('admin.product.home') }}" type="button" class="btn btn-danger">Quay lại</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Lắng nghe sự kiện thay đổi trên trường "Loại Sản Lượng"
        document.getElementById('status').addEventListener('change', function() {
            var selectedStatus = this.value;
            var shiftDiv = document.querySelector('.form-group.ml-3');

            // Nếu người dùng chọn "Hàng 100%" thì hiển thị trường chọn ca làm việc
            if (selectedStatus == '1') {
                shiftDiv.style.display = 'inline-block';
            } else {
                // Ngược lại, ẩn đi trường chọn ca làm việc
                shiftDiv.style.display = 'none';
            }
        });
    });
</script>