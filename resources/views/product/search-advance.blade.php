<div
    class="modal fade"
    id="searchModal"
    tabindex="-1"
    aria-labelledby="searchModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <form method="get">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchModalLabel">
                        Tìm kiếm nâng cao
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="">
                                    Kích thước khuôn
                                </label>
                                <select
                                    class="form-control"
                                    name="moldSize"
                                    id="moldSize"
                                >
                                    <option class="text-center" value="">
                                        ----- Chọn kích thước khuôn -----
                                    </option>
                                    @foreach ($modelSizes as $modelSize)
                                        <option
                                            <?= request()->moldSize == $modelSize ? "selected" : "" ?>
                                            value="{{ $modelSize }}"
                                        >
                                            {{ $modelSize }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label" for="">
                                    Mã thùng
                                </label>
                                <select
                                    class="form-control"
                                    name="binCode"
                                    id="binCode"
                                >
                                    <option class="text-center" value="">
                                        ----- Chọn mã thùng -----
                                    </option>
                                    @foreach ($models as $model)
                                        <option
                                            <?= request()->binCode == $model ? "selected" : "" ?>
                                            value="{{ $model }}"
                                        >
                                            {{ $model }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="">
                                    Mã linh kiện
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="code"
                                    value="{{ request()->code }}"
                                    placeholder="Mã linh kiện"
                                />
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="">
                                    Tên linh kiện
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="name"
                                    value="{{ request()->name }}"
                                    placeholder="Tên linh kiện"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a
                        href="{{ route($href) }}"
                        type="submit"
                        class="btn btn-secondary"
                    >
                        Đặt lại
                    </a>
                    <button type="submit" class="btn btn-success">
                        Tìm kiếm
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
