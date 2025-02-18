@extends('layouts.'.$layout)

@php
    $isManager =
        Auth()->user()->role->role_name == 'admin' ||
        Auth()->user()->role->role_name == 'manager' ||
        Auth()->user()->role->role_name == 'accountant';
@endphp

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-link" href="{{ route('admin.home') }}">
                        <i class="fas fa-arrow-left"></i>
                        <span>Quay lại</span>
                    </a>
                    <div class="text-center">
                        <div class="mb-3">
                            <img
                                src="{{ asset('img/default-avatar.jpg') }}"
                                {{--
    //FIXME: fix display avatar
    @if (Auth()->user()->photo)
    src="{{ asset('storage/admin/'.Auth()->user()->photo) }}"
    @else
    src="{{ asset('storage/employee/'.Auth()->user()->photo) }}"
    @endif
--}}
                                alt="avatar"
                                class="avatar avatar-xl rounded-circle shadow object-fit-cover"
                            />
                        </div>
                        <h5 class="mb-1">
                            {{ Auth()->user()->name }}
                        </h5>
                        <p class="mb-0 font-weight-normal text-sm">
                            Chức vụ:
                            {{ Auth()->user()->role->role_name }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="text-center">Thay đổi thông tin</h5>
                </div>
                <div class="card-body">
                    @if ($isManager)
                        <form
                            action="{{ route('admin.change-profile') }}"
                            method="post"
                            enctype="multipart/form-data"
                        >
                            @csrf
                            <div class="mb-3">
                                <div class="text-center">
                                    <img
                                        id="blah1"
                                        src="{{ asset('img/default-avatar.jpg') }}"
                                        class="avatar avatar-xxl rounded-circle shadow object-fit-cover"
                                    />
                                </div>
                                <label class="form-label" for="photo">
                                    Ảnh
                                </label>
                                <input
                                    accept="image/*"
                                    type="file"
                                    id="inputFile"
                                    name="photo"
                                    id="photo"
                                    class="form-control"
                                />
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label" for="">
                                    Tên
                                </label>
                                <input
                                    type="text"
                                    value="{{ old('name') ?? (Auth()->user()->name ?? '') }}"
                                    name="name"
                                    id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Tên"
                                    disabled
                                />
                                @error('name')
                                    <div class="text text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label" for="">
                                    Tên đăng nhập
                                </label>
                                <input
                                    type="text"
                                    value="{{ old('phone') ?? (Auth()->user()->phone ?? '') }}"
                                    name="phone"
                                    id="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    placeholder="Tên đăng nhập"
                                    disabled
                                />
                                @error('phone')
                                    <div class="text text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">
                                Cập nhật thông tin
                            </button>
                            <a
                                href="{{ route('logout') }}"
                                class="btn btn-danger"
                            >
                                Đăng xuất
                            </a>
                        </form>
                    @else
                        <form
                            action="{{ route('admin.change-info') }}"
                            method="post"
                        >
                            @csrf
                            <div class="form-floating mb-3">
                                <input
                                    type="text"
                                    value="{{ Auth()->user()->code ?? '' }}"
                                    name="code"
                                    id="code"
                                    class="form-control"
                                    placeholder="Mã nhân viên"
                                />
                                <label for="code">Mã nhân viên</label>
                                @error('code')
                                    <div class="text text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-floating mb-3">
                                <input
                                    type="text"
                                    value="{{ Auth()->user()->name ?? '' }}"
                                    name="name"
                                    id="name"
                                    class="form-control"
                                    placeholder="Tên"
                                />
                                <label for="name">Tên</label>
                                @error('name')
                                    <div class="text text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-floating mb-3">
                                <input
                                    type="text"
                                    value="{{ Auth()->user()->phone ?? '' }}"
                                    name="phone"
                                    id="phone"
                                    class="form-control"
                                    placeholder="Số điện thoại"
                                />
                                <label for="phone">Số điện thoại</label>
                                @error('phone')
                                    <div class="text text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-floating mb-3">
                                <input
                                    type="text"
                                    value="{{ Auth()->user()->email ?? '' }}"
                                    name="email"
                                    id="email"
                                    class="form-control"
                                    placeholder="Email"
                                />
                                <label for="email">Email</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input
                                    type="date"
                                    value="{{ Auth()->user()->birthday ?? '' }}"
                                    name="birthday"
                                    id="birthday"
                                    class="form-control"
                                    placeholder="Ngày tháng năm sinh"
                                />
                                <label for="birthday">
                                    Ngày tháng năm sinh
                                </label>
                                @error('birthday')
                                    <div class="text text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-floating mb-3">
                                <input
                                    type="date"
                                    value="{{ Auth()->user()->date_joining ?? '' }}"
                                    name="date_joining"
                                    id="date_joining"
                                    class="form-control"
                                    placeholder="Ngày vào công ty"
                                />
                                <label for="date_joining">
                                    Ngày vào công ty
                                </label>
                                @error('date_joining')
                                    <div class="text text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <textarea
                                    name="address"
                                    id="address"
                                    class="form-control"
                                    style="height: 6rem"
                                    placeholder="Tạm trú"
                                >
{{ Auth()->user()->address ?? '' }}</textarea
                                >
                                <label for="address">Tạm trú</label>
                                @error('address')
                                    <div class="text text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-floating mb-3">
                                <textarea
                                    name="home_town"
                                    id="home_town"
                                    class="form-control"
                                    style="height: 6rem"
                                    placeholder="Quê quán"
                                >
{{ Auth()->user()->home_town ?? '' }}</textarea
                                >
                                <label for="home_town">Quê quán</label>
                                @error('home_town')
                                    <div class="text text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-floating mb-3">
                                <input
                                    type="text"
                                    value="{{ Auth()->user()->CCCD ?? '' }}"
                                    name="CCCD"
                                    id="CCCD"
                                    class="form-control"
                                    placeholder="Số CCCD"
                                />
                                <label for="CCCD">Số CCCD</label>
                                @error('CCCD')
                                    <div class="text text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-floating mb-3">
                                <input
                                    type="text"
                                    value="{{ Auth()->user()->gender ?? '' }}"
                                    name="gender"
                                    id="gender"
                                    class="form-control"
                                    placeholder="Giới tính"
                                />
                                <label for="gender">Giới tính</label>
                                @error('gender')
                                    <div class="text text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-floating mb-3">
                                <input
                                    type="text"
                                    value="{{ Auth()->user()->marital_status ?? '' }}"
                                    name="marital_status"
                                    id="marital_status"
                                    class="form-control"
                                    placeholder="Tình trạng hôn nhân"
                                />
                                <label for="marital_status">
                                    Tình trạng hôn nhân
                                </label>
                                @error('marital_status')
                                    <div class="text text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="">Ảnh</label>
                                <div>
                                    <img
                                        type="hidden"
                                        width="66px"
                                        height="66px"
                                        id="blah1"
                                        src="{{ asset('img/default-image.png') }}"
                                        {{-- src="{{ asset('storage/employee/'.Auth()->user()->photo) }}" --}}
                                        alt=""
                                    />
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="">Ảnh thẻ</label>
                                <div>
                                    <img
                                        type="hidden"
                                        width="66px"
                                        height="66px"
                                        id="blah1"
                                        src="{{ asset('img/default-image.png') }}"
                                        {{-- src="{{ asset('storage/employee/card/'.Auth()->user()->card_photo) }}" --}}
                                        alt=""
                                    />
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                Cập nhật
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="text-center">Thay đổi mật khẩu</h5>
                </div>
                <div class="card-body">
                    <form
                        action="{{ route('admin.change-password') }}"
                        method="post"
                    >
                        @csrf
                        <div class="form-floating mb-3">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Mật khẩu cũ"
                                required
                            />
                            <label for="password">Mật khẩu cũ</label>
                            @error('password')
                                <div class="text text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input
                                type="password"
                                id="newpassword"
                                name="newpassword"
                                class="form-control @error('newpassword') is-invalid @enderror"
                                placeholder="Mật khẩu mới"
                                required
                            />
                            <label for="newpassword">Mật khẩu mới</label>
                            @error('newpassword')
                                <div class="text text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input
                                type="password"
                                id="renewpassword"
                                name="renewpassword"
                                class="form-control @error('renewpassword') is-invalid @enderror"
                                placeholder="Nhập lại mật khẩu mới"
                                required
                            />
                            <label for="renewpassword">
                                Nhập lại mật khẩu mới
                            </label>
                            @error('renewpassword')
                                <div class="text text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <!-- Button reset password -->
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-toggle="modal"
                            data-bs-target="#resetPassword"
                        >
                            Khôi phục mật khẩu
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Cập nhật
                        </button>
                    </form>

                    <!-- Modal reset password -->
                    <div
                        class="modal fade"
                        id="resetPassword"
                        tabindex="-1"
                        aria-labelledby="resetPasswordLabel"
                        aria-hidden="true"
                    >
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1
                                        class="modal-title fs-5"
                                        id="resetPasswordLabel"
                                    >
                                        Khôi phục mật khẩu
                                    </h1>
                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal"
                                        aria-label="Close"
                                    ></button>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        Bạn có chắc chắn muốn khôi phục lại mật
                                        khẩu?
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <form
                                        action="{{ route('admin.reset-password', Auth()->user()->id) }}"
                                        method="post"
                                    >
                                        @csrf
                                        <button
                                            type="submit"
                                            class="btn btn-primary"
                                        >
                                            Reset
                                        </button>
                                    </form>
                                    <button
                                        type="button"
                                        class="btn btn-secondary"
                                        data-bs-dismiss="modal"
                                    >
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        jQuery(document).ready(function () {
            if ($('#blah').hide()) {
                $('#blah').hide();
            }
            jQuery('#inputFile').change(function () {
                $('#blah').show();
                const file = jQuery(this)[0].files;
                if (file[0]) {
                    jQuery('#blah').attr('src', URL.createObjectURL(file[0]));
                    jQuery('#blah1').attr('src', URL.createObjectURL(file[0]));
                }
            });
        });
    </script>
@endsection
