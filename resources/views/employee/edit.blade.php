@extends('master')
@section('content')
    <style>
        .form-control {
            border: 1px solid #d2d6da !important;
            padding-left: 10px;
        }

        .required {
            color: red;
        }
    </style>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Cập Nhật Nhân Sự</h4>
                    </div>
                </div>
                <div class="px-0 pb-2">
                    <div class="table-responsive p-4">
                        <form action="{{ route('admin.reset-password', $employee->id) }}" method="post">
                            @csrf
                            <button type="submit"
                                onclick="return confirm('Bạn có chắc chắn muốn khôi phục lại mật khẩu cho nhân viên {{ $employee->name }}?');"
                                class="btn btn-danger">Khôi phục mật khẩu</button>
                        </form>
                        <form action="{{ route('admin.employee.update', $employee->id) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label class="form-label">Họ và tên<span class="required">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            placeholder="Họ và tên" name="name"
                                            value="{{ old('name') ?? $employee->name }}" required>
                                        @error('name')
                                            <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Số điện thoại<span class="required">*</span></label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                            placeholder="Số điện thoại" name="phone"
                                            value="{{ old('phone') ?? $employee->phone }}" required>
                                        @error('phone')
                                            <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            placeholder="Email" name="email"
                                            value="{{ old('email') ?? $employee->email }}">
                                        @error('email')
                                            <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tình trạng hôn nhân<span class="required">*</span></label>
                                        <input type="text"
                                            class="form-control @error('marital_status') is-invalid @enderror"
                                            placeholder="Tình trạng hôn nhân" name="marital_status"
                                            value="{{ old('marital_status') ?? $employee->marital_status }}">
                                        @error('marital_status')
                                            <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Ảnh</label>
                                        <input accept="image/*" type='file' id="inputFile" name="photo"
                                            class="form-control @error('photo') is-invalid @enderror">
                                        @error('photo')
                                            <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                        <br>
                                        <img type="hidden" width="120px" height="120px" id="blah1"
                                            src="{{ asset('storage/employee/' . $employee->photo) }}" alt="" />
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label class="form-label">Địa chỉ<span class="required">*</span></label>
                                        <input type="text" class="form-control @error('address') is-invalid @enderror"
                                            placeholder="Địa chỉ" name="address"
                                            value="{{ old('address') ?? $employee->address }}" required>
                                        @error('address')
                                            <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Quê quán<span class="required">*</span></label>
                                        <input type="text" class="form-control @error('home_town') is-invalid @enderror"
                                            placeholder="Quê quán" name="home_town"
                                            value="{{ old('home_town') ?? $employee->home_town }}" required>
                                        @error('home_town')
                                            <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Ngày tháng năm sinh<span class="required">*</span></label>
                                        <input type="date" class="form-control @error('birthday') is-invalid @enderror"
                                            name="birthday" value="{{ old('birthday') ?? $employee->birthday }}" required>
                                        @error('birthday')
                                            <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Ngày vào công ty<span class="required">*</span></label>
                                        <input type="date"
                                            class="form-control @error('date_joining') is-invalid @enderror"
                                            name="date_joining"
                                            value="{{ old('date_joining') ?? $employee->date_joining }}" required>
                                        @error('date_joining')
                                            <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Ảnh thẻ</label>
                                        <input accept="image/*" type='file' id="inputFile3" name="card_photo"
                                            class="form-control @error('card_photo') is-invalid @enderror">
                                        @error('card_photo')
                                            <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                        <br>
                                        <img type="hidden" width="120px" height="120px" id="blah4"
                                            src="{{ asset('storage/employee/card/' . $employee->card_photo) }}"
                                            alt="" />
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label class="form-label">Số CCCD<span class="required">*</span></label>
                                        <input type="text" class="form-control @error('CCCD') is-invalid @enderror"
                                            placeholder="Số CCCD" name="CCCD"
                                            value="{{ old('CCCD') ?? $employee->CCCD }}" required>
                                        @error('CCCD')
                                            <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Mã nhân viên<span class="required">*</span></label>
                                        <input type="text" class="form-control @error('code') is-invalid @enderror"
                                            placeholder="Mã nhân viên" name="code"
                                            value="{{ old('code') ?? $employee->code }}" required>
                                        @error('code')
                                            <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Chức vụ<span class="required">*</span></label>
                                        <select class="form-control @error('role_id') is-invalid @enderror"
                                            name="role_id" required>
                                            <option style="text-align: center" value="">----- Chọn chức vụ -----
                                            </option>
                                            @foreach ($roles as $role)
                                                <option
                                                    <?= old('role_id') ?? $employee->role_id == $role->id ? 'selected' : '' ?>
                                                    value="{{ $role->id }}">{{ $role->role_name }} </option>
                                            @endforeach
                                        </select>
                                        @error('role_id')
                                            <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Danh mục lịch làm việc<span
                                                class="required">*</span></label>
                                        <select class="form-control @error('category_celender_id') is-invalid @enderror"
                                            name="category_celender_id" required>
                                            <option style="text-align: center" value="">----- Chọn danh mục -----
                                            </option>
                                            @foreach ($categories as $category)
                                                <option
                                                    <?= old('category_celender_id') ?? $employee->category_celender_id == $category->id ? 'selected' : '' ?>
                                                    value="{{ $category->id }}">{{ $category->name }} </option>
                                            @endforeach
                                        </select>
                                        @error('category_celender_id')
                                            <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Giới tính<span class="required">*</span></label><br>
                                        <input type="radio"
                                            <?= old('gender') ?? $employee->gender == 'Nam' ? 'checked' : (old('gender') ? '' : 'checked') ?>
                                            name="gender" value="Nam">
                                        <label for="html">Nam </label>&nbsp&nbsp&nbsp
                                        <input type="radio"
                                            <?= old('gender') ?? $employee->gender == 'Nữ' ? 'checked' : '' ?>
                                            name="gender" value="Nữ">
                                        <label for="css">Nữ</label>&nbsp&nbsp&nbsp
                                        <input type="radio"
                                            <?= old('gender') ?? $employee->gender == 'Khác' ? 'checked' : '' ?>
                                            name="gender" value="Khác">
                                        <label for="css">Khác</label><br>
                                        @error('gender')
                                            <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">Cập nhật</button>
                            <a href="{{ route('admin.employee.home') }}" type="button" class="btn btn-danger">Quay
                                lại</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        jQuery(document).ready(function() {
            //ảnh
            if ($('#blah').hide()) {
                $('#blah').hide();
            }
            jQuery('#inputFile').change(function() {
                $('#blah').show();
                const file = jQuery(this)[0].files;
                if (file[0]) {
                    jQuery('#blah').attr('src', URL.createObjectURL(file[0]));
                    jQuery('#blah1').attr('src', URL.createObjectURL(file[0]));
                }
            });

            //ảnh thẻ
            if ($('#blah3').hide()) {
                $('#blah3').hide();
            }
            jQuery('#inputFile3').change(function() {
                $('#blah3').show();
                const file = jQuery(this)[0].files;
                if (file[0]) {
                    jQuery('#blah3').attr('src', URL.createObjectURL(file[0]));
                    jQuery('#blah4').attr('src', URL.createObjectURL(file[0]));
                }
            });
        });
    </script>
@endsection
