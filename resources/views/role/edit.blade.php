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
                    <h4 class="card-title mb-0">Cập Nhật Chức Vụ</h4>
                </div>
            </div>
            <div class="px-0 pb-2">
                <div class="table-responsive p-4">
                    <form action="{{ route('admin.role.update', $role->id) }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Tên chức vụ</label>
                                    <input type="text" class="form-control @error('role_name') is-invalid @enderror" placeholder="Tên chức vụ" name="role_name" value="{{ old('role_name') ?? $role->role_name }}" required>
                                    @error('role_name')
                                    <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Cập nhật</button>
                        <a href="{{route('admin.role.home')}}" type="button" class="btn btn-danger">Quay lại</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection