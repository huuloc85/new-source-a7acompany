@extends('master')
@section('content')
<style>
    .form-control {
        border: 1px solid #d2d6da !important;
        padding-left: 10px;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                <div class="border-radius-lg ps-2 pt-4 pb-3">
                    <h4 class="card-title mb-0">Thêm Lịch Làm Việc</h4>
                </div>
            </div>
            <div class="px-0 pb-2">
                <div class="table-responsive p-4">
                    <form action="{{ route('admin.celender.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Tiêu đề</label>
                                    <input type="text" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="Tiêu đề" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                    <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="date" class="form-label">Ngày bắt đầu</label>
                                    <input type="date" id="date" class="form-control @error('date') is-invalid @enderror" placeholder="Ngày bắt đầu" name="date" value="{{ old('date') }}" required>
                                    @error('date')
                                    <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @foreach ($roles as $key => $role)
                            <div class="col-12">
                                <div class="mb-3">
                                    <h3 class="form-label">{{ $role->role_name }}</h3>
                                    <div class="d-flex">
                                        @foreach ($weeks as $keyw => $week)
                                        <li data-id="{{ $key }}-{{ $week }}" class="list-group-item ms-6 title">
                                            <input id="{{ $key }}-{{ $week }}" name="celender[]" type="checkbox" value="{{ $role->role_name }}_{{ $week }}" />
                                            <label for="{{ $key }}-{{ $week }}">{{ $keyw }}</label>
                                        </li>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @if (count($roles) == 0)
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Hiện tại chưa có chức vụ nào. Vui lòng <a href="{{ route('admin.role.add') }}">Thêm chức vụ</a>.</label>
                                </div>
                            </div>
                            @endif
                            @error('celender')
                            <div class="text text-danger mb-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success">Thêm mới</button>
                        <a href="{{ route('admin.celender.home') }}" type="button" class="btn btn-danger">Quay lại</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection