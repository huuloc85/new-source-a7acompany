@extends('layouts.layout')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Thêm Sản Lượng Sản Xuất</h4>
                </div>
                <div class="card-body">
                    <a
                        href="{{ route('admin.product.home') }}"
                        type="button"
                        class="btn btn-link mb-3"
                    >
                        <i class="fas fa-arrow-left"></i>
                        Quay lại
                    </a>
                    <form
                        action="{{ route('admin.product.handle-add-quantity-admin') }}"
                        method="post"
                        enctype="multipart/form-data"
                    >
                        @csrf
                        <div class="d-sm-flex gap-3 align-items-center">
                            <div class="form-group">
                                <label for="date" class="mr-2">
                                    Ngày cập nhật:
                                </label>
                                <select
                                    class="form-control"
                                    name="date"
                                    id="date"
                                >
                                    @foreach ($listDate as $date)
                                        <option
                                            value="{{ $date }}"
                                            {{ $date == $currentDate ? 'selected' : '' }}
                                        >
                                            {{ $date }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('date')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="status" class="mr-2">
                                    Loại sản lượng:
                                </label>
                                <select
                                    class="form-control"
                                    name="status"
                                    id="status"
                                    required
                                >
                                    <option value="">
                                        Chọn loại sản lượng
                                    </option>
                                    <option value="1">Hàng 100%</option>
                                    <option value="2">Hàng 200%</option>
                                    <option value="6">Hàng lỗi</option>
                                    <option value="3">Xuất hàng</option>
                                </select>
                                @error('status')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group d-none" id="shiftBlock">
                                <label for="shift" class="mr-2">
                                    Ca làm việc:
                                </label>
                                <select
                                    class="form-control"
                                    name="shift"
                                    id="shift"
                                    required
                                >
                                    <option value="1">Ca 1</option>
                                    <option value="2">Ca 2</option>
                                </select>
                                @error('shift')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <h4 class="card-title">Sản phẩm</h4>
                            <hr />
                            @if (! empty($products))
                                @foreach ($products as $product)
                                    <div
                                        class="form-group col-6 col-sm-4 col-md-3"
                                    >
                                        <input
                                            type="hidden"
                                            name="productId[]"
                                            value="{{ $product->id }}"
                                        />
                                        <label
                                            class="form-label"
                                            for="quantity"
                                        >
                                            {{ $product->name }} :
                                        </label>
                                        <input
                                            type="number"
                                            min="0"
                                            class="form-control"
                                            name="quantity[]"
                                        />
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="submit" class="btn btn-success">
                            Cập nhật
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Lắng nghe sự kiện thay đổi trên trường "Loại Sản Lượng"
            const status = document.getElementById('status');
            const shiftBlock = document.getElementById('shiftBlock');

            status.addEventListener('change', function () {
                var selectedStatus = this.value;

                // Nếu người dùng chọn "Hàng 100%" thì hiển thị trường chọn ca làm việc
                if (selectedStatus == '1') {
                    shiftBlock.classList.remove('d-none');
                } else {
                    // Ngược lại, ẩn đi trường chọn ca làm việc
                    shiftBlock.classList.add('d-none');
                }
            });
        });
    </script>
@endsection
