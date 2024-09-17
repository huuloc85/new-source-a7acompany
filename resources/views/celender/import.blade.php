<style>
    .importance {
        color: red;
        font-weight: bold;
    }
    .note {
        color: red;
    }
    .required {
        color: red;
    }
</style>
<div class="modal fade" id="importCelender" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.celender.add') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Import Lịch làm việc</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="import-title" class="form-label">Tiêu đề<span class="required">*</span></label>
                                <input type="text" id="import-title" value="{{ request()->title }}" name="title" class="form-control" placeholder="Tiêu đề" required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="import-date" class="form-label">Chọn ngày (<span class="importance">*</span>) <span class="note">Chọn ngày đầu tiên của tháng!!!</span></label>
                                <input type="date" id="import-date" value="{{ request()->date }}" name="date" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="import-file" class="form-label">Chọn file<span class="required">*</span></label>
                                <input type="file" id="import-file" name="fileImport" class="form-control" placeholder="Chọn file" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Import</button>
                </div>
            </div>
        </form>
    </div>
</div>
