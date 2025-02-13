<div
    class="modal fade"
    id="stockQuantityModal"
    tabindex="-1"
    aria-labelledby="stockQuantityModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form
            action="{{ route('admin.checkpo.handle-add-quantity-inventory') }}"
            method="post"
            enctype="multipart/form-data"
            class="modal-content"
        >
            @csrf

            <div class="modal-header">
                <h2 class="modal-title fs-5" id="stockQuantityModalLabel">
                    Thêm Tồn Đầu Kỳ Của Tháng
                </h2>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="month" class="form-label">Tháng</label>
                    <select class="form-control" name="month" id="month">
                        @foreach ($totalMonthQuantities as $month)
                            <option
                                value="{{ $month }}"
                                {{ $month == $selectedMonth ? 'selected' : '' }}
                            >
                                {{ Carbon\Carbon::createFromFormat('m-Y', $month)->format('m-Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group d-none">
                    <select
                        class="form-control form-control-sm"
                        name="status"
                        id="status"
                        required
                        readonly
                    >
                        <option value="4">Tồn Đầu Kỳ</option>
                    </select>
                </div>
                <div class="row">
                    @if (! empty($products))
                        @foreach ($products as $product)
                            <div class="col-6 col-sm-4">
                                <input
                                    type="hidden"
                                    name="productId[]"
                                    value="{{ $product->id }}"
                                />
                                <label class="form-label">
                                    {{ $product->name }}:
                                </label>
                                <input
                                    type="number"
                                    class="form-control"
                                    name="quantity[]"
                                    min="0"
                                />
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Cập nhật</button>
            </div>
        </form>
    </div>
</div>
<script>
    var productNearData = {!! json_encode($productNearData) !!};

    document.addEventListener('DOMContentLoaded', function () {
        showNearData();
    });

    function showNearData() {
        var quantityInputs = document.querySelectorAll('.quantity-input');

        quantityInputs.forEach(function (input) {
            var productId = input.parentElement.querySelector(
                "[name='productId[]']",
            ).value;
            var nearData = productNearData[productId]
                ? productNearData[productId]['stockQuanNearly']
                : '';
            input.value = nearData;
        });
    }
</script>
