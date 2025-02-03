@extends('layouts.layout')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Cập Nhật Sản Lượng MOQ Tồn Đầu Kỳ Tồn 200%</h4>
                </div>
                <div class="card-body">
                    <a
                        href="{{ route('admin.product.home') }}"
                        class="btn btn-link mb-3"
                    >
                        <i class="fas fa-arrow-left"></i>
                        Quay lại
                    </a>
                    <form
                        action="{{ route('admin.product.handle-update-moq') }}"
                        method="post"
                    >
                        @csrf
                        <div class="d-sm-flex align-items-center gap-3">
                            <div class="form-group">
                                <label for="month" class="form-label">
                                    Chọn tháng cập nhật:
                                </label>
                                <select
                                    class="form-select"
                                    name="month"
                                    aria-label="Chọn tháng cập nhật"
                                >
                                    @foreach ($listMonth as $month)
                                        <option
                                            value="{{ $month }}"
                                            {{ $month == $currentMonth ? 'selected' : '' }}
                                        >
                                            {{ $month }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status" class="form-label">
                                    Loại Sản Lượng:
                                </label>
                                <select
                                    class="form-select"
                                    name="status"
                                    id="status"
                                    required
                                    aria-label="Chọn loại sản lượng"
                                    onchange="showNearData()"
                                >
                                    <option value="7">MOQ</option>
                                    <option value="4">Tồn đầu kỳ</option>
                                    <option value="5">Tồn đầu kỳ 200%</option>
                                </select>
                                @error('status')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <h4 class="card-title">Sản phẩm</h4>
                            <hr />
                            @if (empty($products))
                                <div class="text-center">
                                    Không có sản phẩm nào
                                </div>
                            @else
                                @foreach ($products as $product)
                                    <div
                                        class="form-group col-6 col-sm-4 col-md-3"
                                    >
                                        <input
                                            type="hidden"
                                            name="productId[]"
                                            value="{{ $product->id }}"
                                        />
                                        <label class="form-label">
                                            {{ $product->name }} :
                                        </label>
                                        <input
                                            type="number"
                                            min="0"
                                            class="form-control quantity-input"
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
        var productNearData = @json($productNearData);

        function showNearData() {
            var select = document.getElementById('status');
            var status = select.value;

            // Lấy tất cả các ô input của sản phẩm
            var quantityInputs = document.querySelectorAll('.quantity-input');

            // Duyệt qua từng ô input để cập nhật giá trị dữ liệu gần nhất
            quantityInputs.forEach(function (input) {
                var productId = input.parentElement.querySelector(
                    "[name='productId[]']",
                ).value;
                var nearData;
                switch (status) {
                    case '7': // MOQ
                        nearData =
                            productNearData[productId]?.stockQuanMOQNearly || 0;
                        break;
                    case '4': // Tồn đầu kỳ
                        nearData =
                            productNearData[productId]?.stockQuanNearly || 0;
                        break;
                    case '5': // Tồn đầu kỳ 200%
                        nearData =
                            productNearData[productId]?.stockQuan200Nearly || 0;
                        break;
                    default:
                        nearData = '';
                        break;
                }

                // Cập nhật giá trị dữ liệu gần nhất vào ô input
                input.value = nearData;
            });
        }

        // Gọi hàm showNearData() sau khi trang được tải
        document.addEventListener('DOMContentLoaded', function () {
            showNearData();
        });
    </script>
@endsection
