<div class="modal fade" id="searchModal" aria-labelledby="exampleModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <form method="get">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tìm kiếm nâng cao</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label" for="">Kích thước khuôn</label>
                                <select class="form-control" name="moldSize" id="moldSize" style="width: 470px">
                                    <option style="text-align: center" value="">----- Chọn kích thước khuôn -----</option>
                                    @foreach ($modelSizes as $modelSize)
                                        <option <?= request()->moldSize == $modelSize ? 'selected' : '' ?> value="{{ $modelSize }}">{{ $modelSize }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label" for="">Mã thùng</label>
                                <select class="form-control" name="binCode" id="binCode" style="width: 470px">
                                    <option style="text-align: center" value="">----- Chọn mã thùng -----</option>
                                    @foreach ($models as $model)
                                        <option <?= request()->binCode == $model ? 'selected' : '' ?> value="{{ $model }}">{{ $model }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label" for="">Mã linh kiện</label>
                                <input type="text" class="form-control" name="code" value="{{ request()->code }}" placeholder="Mã linh kiện">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label" for="">Tên linh kiện</label>
                                <input type="text"  class="form-control" name="name" value="{{ request()->name }}" placeholder="Tên linh kiện">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{route($href)}}" style="float: left" type="submit" class="btn btn-warning">Đặt lại</a>
                    <button type="submit" class="btn btn-success">Tìm kiếm</button>
                </div>
            </div>
        </form>
    </div>
</div>
