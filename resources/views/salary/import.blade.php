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
                    <h4 class="card-title">Import Bảng Lương</h4>
                </div>
            </div>
            <div class="px-0 pb-2">
                <div class="table-responsive p-4">
                    <form action="{{ route('admin.salary.import') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="">Tiêu đề</label>
                                    <input type="text" value="{{ old('title') }}" name="title" id="title" placeholder="Tiêu đề" class="form-control @error('role_name') is-invalid @enderror" required>
                                    @error('title')
                                    <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <h5 class="text-center">VVP</h5>
                                <div class="mb-3">
                                    <label class="form-label" for="quantity">Ngày bắt đầu</label>
                                    <input type="date" value="{{ old('start_date') }}" name="start_date" placeholder="dd/mm/yyyy" class="form-control start_date @error('start_date') is-invalid @enderror" required>
                                    @error('start_date')
                                    <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="">Ngày kết thúc</label>
                                    <input type="date" value="{{ old('end_date') }}" name="end_date" placeholder="dd/mm/yyyy" class="form-control end_date @error('end_date') is-invalid @enderror" required>
                                    @error('end_date')
                                    <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="">Chọn file VVP</label>
                                    <input class="form-control  @error('file_vvp') is-invalid @enderror" type="file" name="file_vvp" required>
                                    @error('file_vvp')
                                    <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <h5 class="text-center mt-4">A7A</h5>
                                <div class="mb-3">
                                    <label class="form-label" for="">Chọn file A7A</label>
                                    <input class="form-control  @error('file_a7a') is-invalid @enderror" type="file" name="file_a7a" required>
                                    @error('file_a7a')
                                    <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="col-6">
                                <h5 class="text-center">Thời vụ</h5>
                                <div class="mb-3">
                                    <label class="form-label" for="quantity">Ngày bắt đầu</label>
                                    <input value="{{ old('start_date_parttime') }}" type="date" name="start_date_parttime" placeholder="dd/mm/yyyy" class="form-control start_date @error('start_date_parttime') is-invalid @enderror">
                            @error('start_date_parttime')
                            <div class="text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="">Ngày kết thúc</label>
                            <input value="{{ old('end_date_parttime') }}" type="date" name="end_date_parttime" placeholder="dd/mm/yyyy" class="form-control end_date @error('end_date_parttime') is-invalid @enderror">
                            @error('end_date_parttime')
                            <div class="text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="">Chọn file thời vụ</label>
                            <input class="form-control  @error('file_parttime') is-invalid @enderror" type="file" name="file_parttime">
                            @error('file_parttime')
                            <div class="text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                </div> --}}
            </div>
            <button type="submit" class="btn btn-success">Import bảng lương</button>
            <a href="{{ route('admin.salary.home') }}" type="button" class="btn btn-danger">Quay lại</a>
            </form>
        </div>
    </div>
</div>
</div>
</div>
@endsection