@extends('layouts.layout')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Import Bảng Lương</h4>
                </div>
                <div class="card-body">
                    <a
                        href="{{ route('admin.salary.home') }}"
                        type="button"
                        class="btn btn-link"
                    >
                        <i class="fas fa-arrow-left"></i>
                        Quay lại
                    </a>
                    <form
                        action="{{ route('admin.salary.import') }}"
                        method="post"
                        enctype="multipart/form-data"
                    >
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="title">
                                        Tiêu đề
                                    </label>
                                    <input
                                        type="text"
                                        value="{{ old('title') }}"
                                        name="title"
                                        id="title"
                                        placeholder="Tiêu đề"
                                        class="form-control @error('role_name') is-invalid @enderror"
                                        required
                                    />
                                    @error('title')
                                        <div class="text text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <h5 class="text-center">VVP</h5>
                                <div class="form-group">
                                    <label class="form-label" for="start_date">
                                        Ngày bắt đầu
                                    </label>
                                    <input
                                        type="date"
                                        value="{{ old('start_date') }}"
                                        name="start_date"
                                        placeholder="dd/mm/yyyy"
                                        class="form-control start_date @error('start_date') is-invalid @enderror"
                                        required
                                    />
                                    @error('start_date')
                                        <div class="text text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="end_date">
                                        Ngày kết thúc
                                    </label>
                                    <input
                                        type="date"
                                        value="{{ old('end_date') }}"
                                        name="end_date"
                                        placeholder="dd/mm/yyyy"
                                        class="form-control end_date @error('end_date') is-invalid @enderror"
                                        required
                                    />
                                    @error('end_date')
                                        <div class="text text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="file_vvp">
                                        Chọn file VVP
                                    </label>
                                    <input
                                        class="form-control @error('file_vvp') is-invalid @enderror"
                                        type="file"
                                        name="file_vvp"
                                        required
                                    />
                                    @error('file_vvp')
                                        <div class="text text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <h5 class="text-center mt-4">A7A</h5>
                                <div class="form-group">
                                    <label class="form-label" for="file_a7a">
                                        Chọn file A7A
                                    </label>
                                    <input
                                        class="form-control @error('file_a7a') is-invalid @enderror"
                                        type="file"
                                        name="file_a7a"
                                        required
                                    />
                                    @error('file_a7a')
                                        <div class="text text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">
                            Import bảng lương
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
