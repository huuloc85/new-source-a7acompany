<style>
    .modal-dialog {
        max-width: 100% !important;
        margin: auto;
    }

    .iq-header-img {
        position: relative;
    }

    .img-overlay {
        position: absolute;
        top: 50%;
        left: 10px;
    }
</style>
<div class="modal fade full-width-modal" id="testModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="iq-header-img">
                <img src="{{ asset('assets/img/dashboard/top-header.png') }}" alt="header"
                    class="theme-color-default-img img-fluid w-100 h-100">
                <div class="img-overlay">
                    <h2 class="modal-title text-white" id="exampleModalLabel">Thêm PO Xuất Hàng</h2>
                </div>
            </div>
            <form action="{{ route('admin.checkpo.handle-add-po-import') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="table-responsive ps-4">
                        <div class="form-inline my-2">
                            <div class="form-group" style="display: inline-block;">
                                <select class="form-control form-control-sm" name="date" id="date"
                                    style="width: auto;">
                                    @foreach ($listDate as $date)
                                        <option value="" selected disabled>Chọn ngày cập nhật</option>
                                        <option value="{{ $date }}"
                                            {{ $date == $currentDate ? 'selected' : '' }}>
                                            {{ $date }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group ml-3" style="display: inline-block;">
                                <select class="form-control form-control-sm" name="status" id="status" required
                                    style="width: auto;" readonly>
                                    <option value="8">Purchase Order</option>
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
                                        <input type="number" class="form-control" name="quantity[]" min="0"
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
