<div
    class="modal fade"
    id="export_Modal"
    tabindex="-1"
    aria-labelledby="export_ModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form
            action="{{ route('admin.checkpo.handle-add-po-import') }}"
            method="post"
            enctype="multipart/form-data"
            class="modal-content"
        >
            @csrf
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="export_ModalLabel">
                    Thêm PO Xuất Hàng
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
                <div class="form-group d-none">
                    <select
                        class="form-control"
                        name="status"
                        id="status"
                        required
                        readonly
                    >
                        <option value="8">Purchase Order</option>
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
