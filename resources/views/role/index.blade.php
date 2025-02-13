@extends('layouts.layout')
@php
    $startValue = count($roles) > 0 ? $roles->firstItem() : 0;
    $toValue = count($roles) > 0 ? $roles->lastItem() : 0;
@endphp

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Danh Sách Chức Vụ</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                        <a href="{{ route('admin.role.add') }}" type="button" class="btn btn-success">
                            <i class="fas fa-plus"></i>
                            Thêm Chức Vụ
                        </a>
                        <form action="">
                            <div class="input-group">
                                <input name="key" value="{{ request()->key }}" type="text" class="form-control"
                                    placeholder="Nhận từ khóa..." />
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="mb-2">
                        <span class="fw-bold">
                            {{ $startValue }}
                        </span>
                        -
                        <span class="fw-bold">
                            {{ $toValue }}
                        </span>
                        của
                        <span class="fw-bold">
                            {{ $total }}
                        </span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light text-uppercase">
                                <tr>
                                    <th class="text-center">STT</th>
                                    <th>Tên chức vụ</th>
                                    <th>Ngày tạo</th>
                                    <th>Ngày cập nhật</th>
                                    <th class="text-center">Chức Năng</th>
                                </tr>
                            </thead>
                            <tbody class="align-middle">
                                @foreach ($roles as $key => $role)
                                    <tr>
                                        <td class="text-center fw-bold">
                                            {{ $loop->iteration + $roles->firstItem() - 1 }}
                                        </td>
                                        <td>
                                            {{ $role->role_name }}
                                        </td>
                                        <td>
                                            {{ $role->formatTimeDMY($role->created_at) }}
                                        </td>
                                        <td>
                                            {{ $role->formatTimeDMY($role->updated_at) }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.role.edit', $role->id) }}" class="btn btn-primary">
                                                Cập nhật
                                            </a>
                                            {{-- Button delete --}}
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal-{{ $role->id }}">
                                                Xóa
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- Modal Delete --}}
                                    <div class="modal fade" id="deleteModal-{{ $role->id }}" tabindex="-1"
                                        aria-labelledby="deleteModalLabel-{{ $role->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="deleteModalLabel-{{ $role->id }}">
                                                        Xóa chức vụ
                                                    </h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>
                                                        Hành động này không thể
                                                        khôi phục! Bạn có chắc
                                                        muốn xóa chức vụ
                                                        <span class="fw-bold">
                                                            "{{ $role->role_name }}"
                                                        </span>
                                                        không?
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('admin.role.delete', $role->id) }}"
                                                        method="post">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">
                                                            Xóa
                                                        </button>

                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
                                                            Hủy
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                @if ($total == 0)
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            Hiện tại chưa có Chức vụ nào. Vui
                                            lòng
                                            <a class="href" href="{{ route('admin.role.add') }}">
                                                Thêm chức vụ
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $roles->appends(request()->all())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
