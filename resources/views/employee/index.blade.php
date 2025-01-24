@extends('layouts.layout')

@php
    $defaultAvatar = asset('img/default-avatar.jpg');

    $startValue = count($employees) > 0 ? $employees->firstItem() : 0;
    $toValue = count($employees) > 0 ? $employees->lastItem() : 0;
@endphp

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Danh Sách Nhân Viên</h4>
                </div>
                <div class="card-body">
                    <div
                        class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3"
                    >
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <a
                                href="{{ route('admin.employee.add') }}"
                                type="button"
                                class="btn btn-success"
                                title="Thêm Nhân Sự"
                                data-bs-toggle="tooltip"
                            >
                                <i class="fas fa-user-plus"></i>
                            </a>
                            <a
                                href="{{ route('admin.employee.getTrash') }}"
                                type="button"
                                class="btn btn-warning"
                                title="Thùng Rác"
                                data-bs-toggle="tooltip"
                            >
                                <i class="fas fa-trash"></i>
                            </a>
                            <a
                                href="http://192.168.1.2/doc/index.html#/portal/login"
                                type="button"
                                class="btn btn-dark"
                                title="Thêm Nhân Sự Vào Máy Chấm Công"
                                data-bs-toggle="tooltip"
                            >
                                <i class="fas fa-fingerprint"></i>
                            </a>
                        </div>

                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <form
                                action="{{ route('admin.employee.home') }}"
                                method="GET"
                            >
                                <select
                                    name="company"
                                    id="company"
                                    class="form-control"
                                    onchange="this.form.submit()"
                                >
                                    <option value="">Tất cả công ty</option>
                                    @foreach ($companies as $company)
                                        <option
                                            value="{{ $company }}"
                                            {{ request('company') == $company ? 'selected' : '' }}
                                        >
                                            {{ $company }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>

                            <button
                                type="button"
                                class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#searchModal"
                                title="Tìm kiếm nâng cao"
                            >
                                <i class="fas fa-filter"></i>
                            </button>
                            @include('employee.search-advand', ['href' => 'admin.employee.home'])
                        </div>
                    </div>
                    <div
                        class="d-flex flex-wrap-reverse align-items-center justify-content-between gap-3 mb-3"
                    >
                        <div>
                            From
                            <span class="fw-bold">
                                {{ $startValue }}
                            </span>
                            to
                            <span class="fw-bold">
                                {{ $toValue }}
                            </span>
                            of
                            <span class="fw-bold">
                                {{ $total }}
                            </span>
                            entires
                        </div>
                        <div class="d-flex align-items-center border rounded">
                            <i class="fas fa-search ps-2"></i>
                            <input
                                type="text"
                                id="search"
                                class="form-control border-0"
                                placeholder="Tìm kiếm"
                            />
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="datatable">
                            <thead>
                                <tr class="text-uppercase text-center">
                                    <th>STT</th>
                                    <th>Tên/Điện thoại</th>
                                    <th>Chức vụ</th>
                                    <th>Công ty đang hoạt động</th>
                                    <th>Mã nhân viên</th>
                                    <th>Danh mục lịch làm việc</th>
                                    <th>Chức năng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($total == 0)
                                    <tr>
                                        <td
                                            colspan="7"
                                            class="text-center pt-4"
                                        >
                                            Hiện tại chưa có Nhân viên nào. Vui
                                            lòng
                                            <a
                                                class="href"
                                                href="{{ route('admin.employee.add') }}"
                                            >
                                                Thêm nhân viên
                                            </a>
                                        </td>
                                    </tr>
                                @endif

                                @foreach ($employees as $key => $employee)
                                    <tr class="text-center align-middle">
                                        <td class="fw-bold">
                                            {{ $loop->iteration + $startValue - 1 }}
                                        </td>
                                        <td class="text-start">
                                            <div
                                                class="d-flex align-items-center gap-3"
                                            >
                                                <img
                                                    src="{{ asset('storage/employee/'.$employee->photo) }}"
                                                    class="avatar avatar-sm border-radius-lg shadow"
                                                    alt="avatar"
                                                    onerror="this.src='{{ $defaultAvatar }}';"
                                                />
                                                <div>
                                                    <h6>
                                                        {{ $employee->name }}
                                                    </h6>
                                                    <div
                                                        class="text-xs text-secondary"
                                                    >
                                                        {{ $employee->phone }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $employee->role->role_name }}
                                        </td>
                                        <td>
                                            {{ $employee->company ?? 'Chưa có công ty' }}
                                        </td>
                                        <td>
                                            {{ $employee->code }}
                                        </td>
                                        <td>
                                            {{ $employee->category_celender->name }}
                                        </td>
                                        <td>
                                            <a
                                                href="{{ route('admin.employee.edit', $employee->id) }}"
                                                class="btn btn-primary"
                                            >
                                                <i class="fas fa-edit"></i>
                                                Sửa
                                            </a>
                                            <button
                                                type="button"
                                                class="btn btn-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal-{{ $employee->id }}"
                                            >
                                                <i class="fas fa-trash-alt"></i>
                                                Xóa
                                            </button>
                                        </td>
                                    </tr>
                                    {{-- Modal delete --}}
                                    <div
                                        class="modal fade"
                                        id="deleteModal-{{ $employee->id }}"
                                        tabindex="-1"
                                        aria-labelledby="deleteModalLabel-{{ $employee->id }}"
                                        aria-hidden="true"
                                    >
                                        <div
                                            class="modal-dialog modal-dialog-centered"
                                        >
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5
                                                        class="modal-title"
                                                        id="deleteModalLabel-{{ $employee->id }}"
                                                    >
                                                        Xác nhận xóa
                                                    </h5>
                                                    <button
                                                        type="button"
                                                        class="btn-close"
                                                        data-bs-dismiss="modal"
                                                        aria-label="Close"
                                                    ></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>
                                                        Bạn có chắc chắn muốn
                                                        xóa nhân viên "
                                                        <span class="fw-bold">
                                                            {{ $employee->code.' - '.$employee->name }}
                                                        </span>
                                                        " không?
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button
                                                        type="button"
                                                        class="btn btn-secondary"
                                                        data-bs-dismiss="modal"
                                                    >
                                                        Hủy
                                                    </button>
                                                    <form
                                                        action="{{ route('admin.employee.delete', $employee->id) }}"
                                                        method="post"
                                                        class="mb-0"
                                                    >
                                                        @method('DELETE')
                                                        @csrf
                                                        <button
                                                            type="submit"
                                                            class="btn btn-danger"
                                                        >
                                                            Xóa
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $employees->appends(request()->all())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });

        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search');
            const table = document.querySelector('#datatable');
            const rows = table.querySelectorAll('tbody tr');

            searchInput.addEventListener('input', function () {
                const searchTerm = searchInput.value.toLowerCase();

                rows.forEach((row) => {
                    const cells = row.querySelectorAll('td');
                    let found = false;

                    cells.forEach((cell) => {
                        if (
                            cell.textContent.toLowerCase().includes(searchTerm)
                        ) {
                            found = true;
                        }
                    });

                    row.style.display = found ? '' : 'none';
                });
            });
        });
    </script>
@endsection
