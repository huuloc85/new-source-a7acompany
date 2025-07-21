@extends('layouts.'.$layout)

@section('content')
    <div class="container">
        <h3>Danh sách quyền</h3>
        <a href="{{ route('permissions.create') }}" class="btn btn-primary mb-3">+ Thêm quyền</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>STT</th>
                    <th>Key</th>
                    <th>Tên quyền</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($permissions as $index => $perm)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $perm->key }}</td>
                        <td>{{ $perm->name }}</td>
                        <td>
                            <a href="{{ route('permissions.edit', $perm) }}" class="btn btn-sm btn-warning">Sửa</a>
                            <form
                                action="{{ route('permissions.destroy', $perm) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Bạn có chắc muốn xóa quyền này?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Không có quyền nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
