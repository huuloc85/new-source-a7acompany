<style>
    .importance {
        color: red;
        font-weight: bold
    }
    .note {
        color: red;
    }
    .required{
        color: red;
    }
</style>
<div class="modal fade" id="importCelender" aria-labelledby="exampleModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <form action="{{route('admin.celender.add')}}" method="post" enctype="multipart/form-data">
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
                                <label class="form-label" for="">Tiêu đề<span class="required">*</span></label>
                                <input type="text" value="{{ request()->title }}" name="title" class="form-control" placeholder="Tiêu đề" required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label" for="">Chọn ngày(<label class="importance" for="">*</label> )<label class="note">Chọn ngày đầu tiên của tháng!!!</label></label>
                                <input type="date" value="{{ request()->date }}" name="date" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label" for="">Chọn file<span class="required">*</span></label>
                                <input type="file" name="fileImport" class="form-control" placeholder="Chọn file" required>
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