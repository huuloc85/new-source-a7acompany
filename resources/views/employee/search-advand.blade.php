<div class="modal fade" id="searchModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                <label class="form-label" for="">Chức vụ</label>
                                <select class="form-control" name="role_id" id="role_id" style="width: 470px">
                                    <option style="text-align: center" value="">----- Chọn chức vụ -----</option>
                                    @foreach ($roles as $role)
                                        <option {{ request()->role_id == $role->id ? 'selected' : '' }}
                                            value="{{ $role->id }}">{{ $role->role_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label" for="">Danh mục lịch làm việc</label>
                                <select class="form-control" name="category_celender_id" id="category_celender_id"
                                    style="width: 470px">
                                    <option style="text-align: center" value="">----- Chọn danh mục -----</option>
                                    @foreach ($categories as $category)
                                        <option
                                            <?= request()->category_celender_id == $category->id ? 'selected' : '' ?>
                                            value="{{ $category->id }}">{{ $category->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label" for="">Mã nhân viên</label>
                                <input type="text" class="form-control" name="code" value="{{ request()->code }}"
                                    placeholder="Mã nhân viên">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label" for="">Tên nhân viên</label>
                                <input type="text" class="form-control" name="name" value="{{ request()->name }}"
                                    placeholder="Tên nhân viên">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label" for="">Tạm trú</label>
                                <input type="text" value="{{ request()->address }}" name="address"
                                    class="form-control" placeholder="Tạm trú">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label" for="">Quê quán</label>
                                <input type="text" value="{{ request()->home_town }}" name="home_town"
                                    class="form-control" placeholder="Quê quán">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label" for="">Số điện thoại
                                </label>
                                <input type="text" class="form-control" value="{{ request()->phone }}" name="phone"
                                    id="phone" placeholder="Số điện thoại">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-2">
                                <label class="form-label" for="">Số CCCD</label>
                                <input type="text" class="form-control" name="CCCD" value="{{ request()->CCCD }}"
                                    placeholder="Số CCCD">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label" for="">Giới tính:</label><br>
                                <input type="radio" <?= request()->gender == 'Nam' ? 'checked' : '' ?> name="gender"
                                    value="Nam">
                                <label for="html">Nam </label>&nbsp&nbsp&nbsp
                                <input type="radio" <?= request()->gender == 'Nữ' ? 'checked' : '' ?> name="gender"
                                    value="Nữ">
                                <label for="css">Nữ</label>&nbsp&nbsp&nbsp
                                <input type="radio" <?= request()->gender == 'Khác' ? 'checked' : '' ?>
                                    name="gender" value="Khác">
                                <label for="css">Khác</label><br>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route($href) }}" style="float: left" type="submit" class="btn btn-warning">Đặt
                        lại</a>
                    <button type="submit" class="btn btn-success">Tìm kiếm</button>
                </div>
            </div>
        </form>
    </div>
</div>
