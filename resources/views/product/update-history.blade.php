<div
    class="modal fade"
    id="updateDetail"
    aria-labelledby="updateModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <form
            action="{{ route('admin.product.update.detail') }}"
            method="post"
            class="modal-content"
        >
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fs-5" id="updateModalLabel">
                    Cập nhật sản lượng
                </h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label" for="">Số lượng cập nhật</label>
                    <input
                        id="quantity"
                        type="number"
                        class="form-control"
                        value=""
                        name="quantity"
                        placeholder="Số lượng cập nhật"
                        required
                    />
                    <input
                        id="dailyId"
                        type="text"
                        value=""
                        class="form-control"
                        name="dailyId"
                        hidden
                    />
                    <input
                        id="status"
                        type="text"
                        value=""
                        class="form-control"
                        name="status"
                        hidden
                    />
                    <input
                        id="product_id"
                        type="text"
                        value=""
                        class="form-control"
                        name="product_id"
                        hidden
                    />
                    <input
                        id="oldQuan"
                        type="text"
                        value=""
                        class="form-control"
                        name="oldQuan"
                        hidden
                    />
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </div>
        </form>
    </div>
</div>
<script>
    const updateDetailModal = document.getElementById('updateDetail');
    updateDetailModal.addEventListener('show.bs.modal', (event) => {
        const button = event.relatedTarget; // Nút đã kích hoạt modal
        const dailyId = button.getAttribute('data-daily-id');
        const dailyQuantity = button.getAttribute('data-daily-quan');
        const productId = button.getAttribute('data-product-id');
        const status = button.getAttribute('data-status');
        const quantityInput = document.getElementById('quantity');
        const dailyIdInput = document.getElementById('dailyId');
        const productIdInput = document.getElementById('product_id');
        const statusInput = document.getElementById('status');
        const oldQuanInput = document.getElementById('oldQuan');

        quantityInput.value = dailyQuantity;
        dailyIdInput.value = dailyId;
        productIdInput.value = productId;
        statusInput.value = status;
        oldQuanInput.value = dailyQuantity;
    });
</script>
