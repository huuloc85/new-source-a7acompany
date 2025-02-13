@extends('layouts.layout')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Cập Nhật Chức Vụ</h4>
                    </div>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.role.home') }}" type="button" class="btn btn-link">
                        <i class="fas fa-arrow-left"></i>
                        Quay lại
                    </a>
                    <form action="{{ route('admin.role.update', $role->id) }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label class="form-label">
                                        Tên chức vụ
                                    </label>
                                    <input type="text" class="form-control @error('role_name') is-invalid @enderror"
                                        placeholder="Tên chức vụ" name="role_name"
                                        value="{{ old('role_name') ?? $role->role_name }}" required />
                                    @error('role_name')
                                        <div class="text text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">
                            Cập nhật
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
