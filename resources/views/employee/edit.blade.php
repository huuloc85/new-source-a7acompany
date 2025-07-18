@extends('layouts.'.$layout)
@section('styles')
    <style>
        .required {
            color: red;
        }
    </style>
@endsection

@php
    $defaultImage = asset('img/default-avatar.jpg');
    $gender = old('gender') ?? $employee->gender;
    $marital_status = old('marital_status') ?? $employee->marital_status;
    $company = old('company') ?? $employee->company;
    $role_id = old('role_id') ?? $employee->role_id;
    $calendar_category_id = old('calendar_category_id') ?? $employee->calendarCategory_id;
@endphp

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Cập Nhật Nhân Sự</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                        <a href="{{ route('admin.employee.home') }}" type="button" class="btn btn-link">
                            <i class="fas fa-arrow-left"></i>
                            Quay lại
                        </a>
                        <button
                            type="button"
                            class="btn btn-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#resetPasswordModal">
                            Khôi phục mật khẩu
                        </button>
                    </div>

                    {{-- Modal Reset password --}}
                    <div
                        class="modal fade"
                        id="resetPasswordModal"
                        tabindex="-1"
                        aria-labelledby="resetPasswordModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="resetPasswordModalLabel">Khôi phục mật khẩu</h5>
                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        Bạn có chắc chắn muốn khôi phục lại mật khẩu cho nhân viên
                                        <span class="fw-bold">
                                            {{ $employee->name }}
                                        </span>
                                        ?
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <form action="{{ route('admin.reset-password', $employee->id) }}" method="post">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Khôi phục mật khẩu</button>
                                    </form>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form
                        action="{{ route('admin.employee.update', $employee->id) }}"
                        method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-4 col-xxl-3">
                                <div class="form-group">
                                    <label class="form-label">
                                        Họ và tên
                                        <span class="required">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Họ và tên"
                                        name="name"
                                        value="{{ old('name') ?? $employee->name }}"
                                        required />
                                    @error('name')
                                        <div class="text text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 col-xxl-3">
                                <div class="form-group">
                                    <label class="form-label">
                                        Mã nhân viên
                                        <span class="required">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control @error('code') is-invalid @enderror"
                                        placeholder="Mã nhân viên"
                                        name="code"
                                        value="{{ old('code') ?? $employee->id }}"
                                        required />
                                    @error('code')
                                        <div class="text text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 col-xxl-3">
                                <div class="form-group">
                                    <label class="form-label">
                                        Số điện thoại
                                        <span class="required">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        placeholder="Số điện thoại"
                                        name="phone"
                                        value="{{ old('phone') ?? $employee->phone }}"
                                        required />
                                    @error('phone')
                                        <div class="text text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 col-xxl-3">
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input
                                        type="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="Email"
                                        name="email"
                                        value="{{ old('email') ?? $employee->email }}" />
                                    @error('email')
                                        <div class="text text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-4 col-xxl-3">
                                <div class="form-group">
                                    <label class="form-label">
                                        Số CCCD
                                        <span class="required">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control @error('CCCD') is-invalid @enderror"
                                        placeholder="Số CCCD"
                                        name="CCCD"
                                        value="{{ old('CCCD') ?? $employee->CCCD }}"
                                        required />
                                    @error('CCCD')
                                        <div class="text text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 col-xxl-3">
                                <div class="form-group">
                                    <label class="form-label">
                                        Địa chỉ
                                        <span class="required">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control @error('address') is-invalid @enderror"
                                        placeholder="Địa chỉ"
                                        name="address"
                                        value="{{ old('address') ?? $employee->address }}"
                                        required />
                                    @error('address')
                                        <div class="text text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 col-xxl-3">
                                <div class="form-group">
                                    <label class="form-label">
                                        Quê quán
                                        <span class="required">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control @error('home_town') is-invalid @enderror"
                                        placeholder="Quê quán"
                                        name="home_town"
                                        value="{{ old('home_town') ?? $employee->home_town }}"
                                        required />
                                    @error('home_town')
                                        <div class="text text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 col-xxl-3">
                                <div class="form-group">
                                    <label class="form-label">
                                        Ngày tháng năm sinh
                                        <span class="required">*</span>
                                    </label>
                                    <input
                                        type="date"
                                        class="form-control @error('birthday') is-invalid @enderror"
                                        name="birthday"
                                        value="{{ old('birthday') ?? $employee->birthday }}"
                                        required />
                                    @error('birthday')
                                        <div class="text text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 col-xxl-3">
                                <div class="form-group">
                                    <div>
                                        <label class="form-label">
                                            Giới tính
                                            <span class="required">*</span>
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline p-1">
                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            name="gender"
                                            value="Nam"
                                            id="gender1"
                                            <?= $gender == "Nam" ? "checked" : "" ?> />
                                        <label class="form-check-label" for="gender1">Nam</label>
                                    </div>
                                    <div class="form-check form-check-inline p-1">
                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            name="gender"
                                            value="Nữ"
                                            id="gender2"
                                            <?= $gender == "Nữ" ? "checked" : "" ?> />
                                        <label class="form-check-label" for="gender2">Nữ</label>
                                    </div>
                                    <div class="form-check form-check-inline p-1">
                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            name="gender"
                                            value="Khác"
                                            id="gender3"
                                            <?= $gender == "Khác" ? "checked" : "" ?> />
                                        <label class="form-check-label" for="gender3">Khác</label>
                                    </div>

                                    @error('gender')
                                        <div class="text text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 col-xxl-3">
                                <div class="form-group">
                                    <label class="form-label">
                                        Tình trạng hôn nhân
                                        <span class="required">*</span>
                                    </label>
                                    <select
                                        class="form-control @error('marital_status') is-invalid @enderror"
                                        name="marital_status"
                                        required>
                                        <option value="">Chọn tình trạng hôn nhân</option>
                                        <option value="Độc thân" {{ $marital_status == 'Độc thân' ? 'selected' : '' }}>
                                            Độc thân
                                        </option>
                                        <option
                                            value="Đã kết hôn"
                                            {{ $marital_status == 'Đã kết hôn' ? 'selected' : '' }}>
                                            Đã kết hôn
                                        </option>
                                        <option value="Ly hôn" {{ $marital_status == 'Ly hôn' ? 'selected' : '' }}>
                                            Ly hôn
                                        </option>
                                        <option value="Góa" {{ $marital_status == 'Góa' ? 'selected' : '' }}>
                                            Góa
                                        </option>
                                    </select>
                                    @error('marital_status')
                                        <div class="text text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 col-xxl-3">
                                <div class="form-group">
                                    <label class="form-label">
                                        Công ty hoạt động
                                        <span class="required">*</span>
                                    </label>
                                    <select
                                        class="form-control @error('company') is-invalid @enderror"
                                        name="company"
                                        required>
                                        <option value="">Chọn công ty</option>
                                        <option
                                            value="Vinh Vinh Phát"
                                            {{ $company == 'Vinh Vinh Phát' ? 'selected' : '' }}>
                                            Vinh Vinh Phát
                                        </option>
                                        <option value="A7A" {{ $company == 'A7A' ? 'selected' : '' }}>A7A</option>
                                    </select>
                                    @error('company')
                                        <div class="text text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 col-xxl-3">
                                <div class="form-group">
                                    <label class="form-label">
                                        Ngày vào công ty
                                        <span class="required">*</span>
                                    </label>
                                    <input
                                        type="date"
                                        class="form-control @error('date_joining') is-invalid @enderror"
                                        name="date_joining"
                                        value="{{ old('date_joining') ?? $employee->date_joining }}"
                                        required />
                                    @error('date_joining')
                                        <div class="text text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 col-xxl-3">
                                <div class="form-group">
                                    <label class="form-label">
                                        Chức vụ
                                        <span class="required">*</span>
                                    </label>
                                    <select
                                        class="form-control @error('role_id') is-invalid @enderror"
                                        name="role_id"
                                        required>
                                        <option class="text-center" value="">----- Chọn chức vụ -----</option>
                                        @foreach ($roles as $role)
                                            <option
                                                <?= $role_id == $role->id ? "selected" : "" ?>
                                                value="{{ $role->id }}">
                                                {{ $role->role_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                        <div class="text text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 col-xxl-3">
                                <div class="form-group">
                                    <label class="form-label">
                                        Danh mục lịch làm việc
                                        <span class="required">*</span>
                                    </label>
                                    <select
                                        class="form-control @error('calendar_category_id') is-invalid @enderror"
                                        name="calendar_category_id"
                                        required>
                                        <option class="text-center" value="">----- Chọn danh mục -----</option>
                                        @foreach ($categories as $category)
                                            <option
                                                <?= $calendar_category_id == $category->id ? "selected" : "" ?>
                                                value="{{ $category->id }}">
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('calendar_category_id')
                                        <div class="text text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-4 col-xxl-3">
                                <div class="form-group">
                                    <label class="form-label">Ảnh</label>
                                    <input
                                        accept="image/*"
                                        type="file"
                                        id="inputFile"
                                        name="photo"
                                        class="form-control @error('photo') is-invalid @enderror" />
                                    @error('photo')
                                        <div class="text text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <img
                                        class="mt-2 object-fit-cover"
                                        width="120px"
                                        height="120px"
                                        id="blah"
                                        src="{{ asset('storage/employee/'.$employee->photo) }}"
                                        alt=""
                                        onerror="this.src='{{ $defaultImage }}'" />
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 col-xxl-3">
                                <div class="form-group">
                                    <label class="form-label">Ảnh thẻ</label>
                                    <input
                                        accept="image/*"
                                        type="file"
                                        id="inputFile3"
                                        name="card_photo"
                                        class="form-control @error('card_photo') is-invalid @enderror" />
                                    @error('card_photo')
                                        <div class="text text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <img
                                        class="mt-2 object-fit-cover"
                                        width="120px"
                                        height="120px"
                                        id="blah3"
                                        src="{{ asset('storage/employee/card/'.$employee->card_photo) }}"
                                        alt=""
                                        onerror="this.src='{{ $defaultImage }}'" />
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Thêm mới</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        jQuery(document).ready(function () {
            //ảnh
            jQuery('#inputFile').change(function () {
                $('#blah').show()
                const file = jQuery(this)[0].files
                if (file[0]) {
                    jQuery('#blah').attr('src', URL.createObjectURL(file[0]))
                    jQuery('#blah1').attr('src', URL.createObjectURL(file[0]))
                }
            })

            //ảnh thẻ
            jQuery('#inputFile3').change(function () {
                $('#blah3').show()
                const file = jQuery(this)[0].files
                if (file[0]) {
                    jQuery('#blah3').attr('src', URL.createObjectURL(file[0]))
                    jQuery('#blah4').attr('src', URL.createObjectURL(file[0]))
                }
            })
        })
    </script>
@endsection
