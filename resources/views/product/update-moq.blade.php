@extends('master')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Cập Nhật Sản Lượng MOQ Tồn Đầu Kỳ Tồn 200%</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.product.handle-update-moq') }}" method="post">
                        @csrf
                        <div class="form-inline">
                            <div class="form-group" style="display: inline-block;">
                                <label for="month" class="form-label">Chọn tháng cập nhật:</label>
                                <select class="form-select w-auto" name="month" aria-label="Chọn tháng cập nhật">
                                    @foreach ($listMonth as $month)
                                        <option value="{{ $month }}" {{ $month == $currentMonth ? 'selected' : '' }}>
                                            {{ $month }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group ml-3" style="display: inline-block;">
                                <label for="status" class="form-label">Loại Sản Lượng:</label>
                                <select class="form-select w-auto" name="status" id="status" required
                                    aria-label="Chọn loại sản lượng" onchange="showNearData()">
                                    <option value="7">MOQ</option>
                                    <option value="4">Tồn đầu kỳ</option>
                                    <option value="5">Tồn đầu kỳ 200%</option>
                                </select>
                                @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 30px;" class="my-4">
                            @if (!empty($products))
                                @foreach ($products as $product)
                                    <div style="display: flex; justify-content: space-between;">
                                        <div>
                                            <input type="hidden" style="margin-top:-3px" name="productId[]"
                                                value='{{ $product->id }}'>
                                            <label class="me-2" style="margin-top:3px" for="html">{{ $product->name }}
                                                :</label>
                                        </div>
                                        <input type="number"
                                            style="height:27px; width:150px; margin-right:100px; text-align:center"
                                            min="0" class="form-control quantity-input" name="quantity[]">
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <button type="submit" class="btn btn-success">Cập nhật</button>
                        <a href="{{ route('admin.product.home') }}" class="btn btn-danger">Quay lại</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        var productNearData = @json($productNearData);

        function showNearData() {
            var select = document.getElementById("status");
            var status = select.value;

            // Lấy tất cả các ô input của sản phẩm
            var quantityInputs = document.querySelectorAll(".quantity-input");

            // Duyệt qua từng ô input để cập nhật giá trị dữ liệu gần nhất
            quantityInputs.forEach(function(input) {
                var productId = input.parentElement.querySelector("[name='productId[]']").value;
                var nearData;
                switch (status) {
                    case "7": // MOQ
                        nearData = productNearData[productId]?.stockQuanMOQNearly || 0;
                        break;
                    case "4": // Tồn đầu kỳ
                        nearData = productNearData[productId]?.stockQuanNearly || 0;
                        break;
                    case "5": // Tồn đầu kỳ 200%
                        nearData = productNearData[productId]?.stockQuan200Nearly || 0;
                        break;
                    default:
                        nearData = "";
                        break;
                }

                // Cập nhật giá trị dữ liệu gần nhất vào ô input
                input.value = nearData;
            });
        }

        // Gọi hàm showNearData() sau khi trang được tải
        document.addEventListener("DOMContentLoaded", function() {
            showNearData();
        });
    </script>
@endsection
