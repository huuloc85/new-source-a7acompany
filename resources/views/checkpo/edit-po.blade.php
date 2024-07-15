<div class="modal fade" id="updatePO" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.checkpo.update') }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cập nhật sản lượng PO</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label" for="">Số lượng cập nhật</label>
                                <input id="quantity" type="number" class="form-control" value="" name="quantity"
                                    placeholder="Số lượng cập nhật" required>
                                <input id="dailyId" type="text" value="" class="form-control" name="dailyId"
                                    hidden>
                                <input id="status" type="text" value="" class="form-control" name="status"
                                    hidden>
                                <input id="product_id" type="text" value="" class="form-control"
                                    name="product_id" hidden>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $('#updateDetail').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var dailyId = button.data('daily-id');
        var dailyQuan = button.data('daily-quan');
        var status = button.data('status');
        var productId = button.data('product-id');
        document.getElementById('dailyId').value = dailyId;
        document.getElementById('quantity').value = dailyQuan;
        document.getElementById('status').value = status;
        document.getElementById('product_id').value = productId;
    });
</script>
