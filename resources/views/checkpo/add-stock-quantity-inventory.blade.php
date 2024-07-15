<style>
    .modal-dialog {
        max-width: 50% !important;
        margin: 1.75rem auto;
    }
</style>
<div class="modal fade full-width-modal" id="stockquantityModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="iq-header-img">
                <img src="{{ asset('assets/img/dashboard/top-header.png') }}" alt="header"
                    class="theme-color-default-img img-fluid w-100 h-100">
                <div class="img-overlay">
                    <h2 class="modal-title text-white" id="exampleModalLabel">Thêm Tồn Đầu Kỳ Của Tháng</h2>
                </div>
            </div>
            <form action="{{ route('admin.checkpo.handle-add-quantity-inventory') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="table-responsive ps-4">
                        <div class="form-inline my-2">
                            <div class="form-group" style="display: inline-block;">
                                <select class="form-control form-control-sm" name="month" id="month">
                                    @foreach ($totalMonthQuantities as $month)
                                        <option value="{{ $month }}"
                                            {{ $month == $selectedMonth ? 'selected' : '' }}>
                                            {{ Carbon\Carbon::createFromFormat('m-Y', $month)->format('m-Y') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group ml-3" style="display: inline-block;">
                                <select class="form-control form-control-sm" name="status" id="status" required
                                    readonly>
                                    <option value="4">Tồn Đầu Kỳ</option>
                                </select>
                            </div>
                        </div>
                        <div
                            style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px;">
                            @if (!empty($products))
                                @foreach ($products as $product)
                                    <div class="d-flex justify-content-between align-items-center">
                                        <input type="hidden" name="productId[]" value='{{ $product->id }}'>
                                        <label for="html">{{ $product->name }}:</label>
                                        <input type="number" class="form-control" min="0" name="quantity[]"
                                            value="{{ $productNearData[$product->id]['stockQuanNearly'] ?? '' }}"
                                            style="width: 100px;">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    var productNearData = {!! json_encode($productNearData) !!};

    document.addEventListener("DOMContentLoaded", function() {
        showNearData();
    });

    function showNearData() {
        var quantityInputs = document.querySelectorAll(".quantity-input");

        quantityInputs.forEach(function(input) {
            var productId = input.parentElement.querySelector("[name='productId[]']").value;
            var nearData = productNearData[productId] ? productNearData[productId]['stockQuanNearly'] : '';
            input.value = nearData;
        });
    }
</script>
