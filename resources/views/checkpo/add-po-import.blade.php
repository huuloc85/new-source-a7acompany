<div
    class="modal fade"
    id="import_Modal"
    tabindex="-1"
    aria-labelledby="import_ModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form
            action="{{ route('admin.checkpo.handle-add-po-export') }}"
            method="post"
            enctype="multipart/form-data"
            class="modal-content"
        >
            @csrf
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="import_ModalLabel">
                    Thêm Sản Lượng
                </h1>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="date" class="form-label">Ngày cập nhật</label>
                    <select class="form-control" name="date" id="date" required>
                        <option value="">Chọn ngày cập nhật</option>
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
                    <label for="status" class="form-label">
                        Loại sản lượng
                    </label>
                    <select
                        class="form-control"
                        name="status"
                        id="status"
                        required
                    >
                        <option value="">Chọn loại sản lượng</option>
                        <option value="1">Hàng 100%</option>
                        <option value="6">Hàng lỗi</option>
                    </select>
                    @error('status')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="shift" class="form-label">Ca làm việc</label>
                    <select
                        class="form-control"
                        name="shift"
                        id="shift"
                        disabled
                    >
                        <option value="">Chọn ca làm việc</option>
                        <option value="1">Ca 1</option>
                        <option value="2">Ca 2</option>
                    </select>
                    @error('shift')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
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
    document.addEventListener('DOMContentLoaded', function () {
        const status = document.getElementById('status');
        const shift = document.getElementById('shift');

        status.addEventListener('change', function () {
            // If user choose "Hàng 100%" (value = 1) then enable shift select
            // Otherwise, disable shift select
            if (status.value == 1) {
                shift.removeAttribute('disabled');
                shift.setAttribute('required', 'required');
            } else {
                shift.removeAttribute('required');
                shift.setAttribute('disabled', 'disabled');
            }
        });
    });
</script>
