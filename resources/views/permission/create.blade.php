@extends('layouts.'.$layout)

@section('content')
    <div class="container">
        <h3>Tạo quyền mới</h3>

        <form method="POST" action="{{ route('permissions.store') }}">
            @csrf

            <div class="mb-3">
                <label for="key" class="form-label">Key</label>
                <input type="text" name="key" id="key" class="form-control" value="{{ old('key') }}" required />
                @error('key')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Tên quyền</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required />
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-success">Lưu</button>
            <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection
