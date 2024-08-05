@extends('master')
@section('content')
    <style>
        .form-control {
            border: 1px solid #d2d6da !important;
            padding-left: 10px;
        }

        .info {
            border: 1px solid;
            border-radius: 5px;
            padding: 20px;
        }

        .wrapped-input {
            white-space: normal !important;
            word-wrap: break-word !important;
            overflow: auto !important;
        }
    </style>
    <div class="container-fluid px-2 px-md-4 mt-5">
        <div class="card card-body mx-3 mx-md-4 mt-n6">
            <div class="row gx-4 mb-2">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        @if (Auth()->user()->role->role_name == 'admin' ||
                                Auth()->user()->role->role_name == 'manager' ||
                                Auth()->user()->role->role_name == 'accountant')
                            <img src="{{ asset('storage/admin/' . Auth()->user()->photo) }}" alt="profile_image"
                                class="w-100 border-radius-lg shadow-sm">
                        @else
                            <img src="{{ asset('storage/employee/' . Auth()->user()->photo) }}" alt="profile_image"
                                class="w-100 border-radius-lg shadow-sm">
                        @endif
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            Tên:
                            {{ Auth()->user()->name }}
                        </h5>
                        <p class="mb-0 font-weight-normal text-sm">
                            Chức vụ:
                            {{ Auth()->user()->role->role_name }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 col-lg-6">
                    <div class="info mb-4">
                        @if (Auth()->user()->role->role_name == 'admin' ||
                                Auth()->user()->role->role_name == 'manager' ||
                                Auth()->user()->role->role_name == 'accountant')
                            <h5 class="text-center">Thay đổi thông tin</h5>
                            <form action="{{ route('admin.change-profile') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="">Tên</label>
                                            <input type="text" value="{{ old('name') ?? (Auth()->user()->name ?? '') }}"
                                                name="name" class="form-control @error('name') is-invalid @enderror"
                                                placeholder="Tên" disabled>
                                            @error('name')
                                                <div class="text text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="">Tên đăng nhập</label>
                                            <input type="text" value="{{ old('phone') ?? (Auth()->user()->phone ?? '') }}"
                                                name="phone" class="form-control @error('phone') is-invalid @enderror"
                                                placeholder="Tên đăng nhập" disabled>
                                            @error('phone')
                                                <div class="text text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="">Ảnh</label>
                                            <input accept="image/*" type='file' id="inputFile" name="photo"
                                                class="form-control">
                                            <img type="hidden" width="66px" height="66px" id="blah1"
                                                src="{{ asset('storage/admin/' . Auth()->user()->photo) }}"
                                                alt="" />
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Cập nhật thông tin</button>
                                <a href="{{ route('logout') }}" class="btn btn-danger">Đăng xuất</a>
                            </form>
                        @else
                            <h5 class="text-center">Thông tin tài khoản</h5>
                            <div class="row">
                                <form action="{{ route('admin.change-info') }}" method="post">
                                    @csrf
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="">Tên</label>
                                            <input type="text" value="{{ Auth()->user()->name ?? '' }}" name="name"
                                                class="form-control">
                                            @error('name')
                                                <div class="text text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="">Số điện thoại</label>
                                            <input type="text" value="{{ Auth()->user()->phone ?? '' }}" name="phone"
                                                class="form-control">
                                            @error('phone')
                                                <div class="text text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="">Email</label>
                                            <input type="text" value="{{ Auth()->user()->email ?? '' }}" name="email"
                                                class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="">Mã nhân viên</label>
                                            <input type="text" value="{{ Auth()->user()->code ?? '' }}" name="code"
                                                class="form-control">
                                            @error('code')
                                                <div class="text text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="">Ngày tháng năm sinh</label>
                                            <input type="date" value="{{ Auth()->user()->birthday ?? '' }}"
                                                name="birthday" class="form-control">
                                            @error('birthday')
                                                <div class="text text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="">Ngày vào công ty</label>
                                            <input type="date" value="{{ Auth()->user()->date_joining ?? '' }}"
                                                name="date_joining" class="form-control">
                                            @error('date_joining')
                                                <div class="text text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="">Ảnh</label><br>
                                            <img type="hidden" width="66px" height="66px" id="blah1"
                                                src="{{ asset('storage/employee/' . Auth()->user()->photo) }}"
                                                alt="" />
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="">Tạm trú</label>
                                            <textarea name="address" class="form-control" id="" cols="30" rows="3">{{ Auth()->user()->address ?? '' }}</textarea>
                                            @error('address')
                                                <div class="text text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="">Quê quán</label>
                                            <textarea name="home_town" class="form-control" id="" cols="30" rows="3">{{ Auth()->user()->home_town ?? '' }}</textarea>
                                            @error('home_town')
                                                <div class="text text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="">Số CCCD</label>
                                            <input type="text" value="{{ Auth()->user()->CCCD ?? '' }}"
                                                name="CCCD" class="form-control">
                                            @error('CCCD')
                                                <div class="text text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="">Giới tính</label>
                                            <input type="text" value="{{ Auth()->user()->gender ?? '' }}"
                                                name="gender" class="form-control">
                                            @error('gender')
                                                <div class="text text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="">Tình trạng hôn nhân</label>
                                            <input type="text" value="{{ Auth()->user()->marital_status ?? '' }}"
                                                name="marital_status" class="form-control">
                                            @error('marital_status')
                                                <div class="text text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="">Ảnh thẻ</label><br>
                                            <img type="hidden" width="66px" height="66px" id="blah1"
                                                src="{{ asset('storage/employee/card/' . Auth()->user()->card_photo) }}"
                                                alt="" />
                                        </div>
                                    </div>
                                    <div class="row d-flex bd-highlight">
                                        <div class="col-12 col-sm-6 bd-highlight">
                                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="info">
                        <h5 class="text-center">Thay đổi mật khẩu</h5>
                        <form action="{{ route('admin.change-password') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="">Mật khẩu mới</label>
                                        <input type="password" value="" name="newpassword"
                                            class="form-control @error('newpassword') is-invalid @enderror"
                                            placeholder="Mật khẩu mới" required>
                                        @error('newpassword')
                                            <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="">Nhập lại mật khẩu mới</label>
                                        <input type="password" value="" name="renewpassword"
                                            class="form-control @error('renewpassword') is-invalid @enderror"
                                            placeholder="Nhập lại mật khẩu mới" required>
                                        @error('renewpassword')
                                            <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="">Mật khẩu cũ</label>
                                        <input type="password" value="" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Mật khẩu cũ" required>
                                        @error('password')
                                            <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex bd-highlight">
                                <div class="col-12 col-sm-6 bd-highlight">
                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                                </div>
                            </div>
                        </form>
                        <div class="col-12 col-sm-6 flex-shrink-1 bd-highlight">
                            <form action="{{ route('admin.reset-password', Auth()->user()->id) }}" method="post">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('Bạn có chắc chắn muốn khôi phục lại mật khẩu?');"
                                    class="btn btn-danger">Khôi phục mật khẩu</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 mt-4">
                    <a class="btn btn-danger" href="{{ route('admin.home') }}">Quay lại</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        jQuery(document).ready(function() {
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
        });
    </script>
@endsection
