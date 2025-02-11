@extends('layouts.'.$layout)

@php
    $defaultAvatar = asset('img/default-avatar.jpg');

    $startValue = count($employees) == 0 ? 0 : $employees->firstItem();
    $toValue = count($employees) == 0 ? 0 : $employees->lastItem();
@endphp

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">
                            Danh Sách Nhân Viên Đã Nghỉ Việc
                        </h4>
                    </div>
                </div>
                <div class="card-body">
                    <a
                        href="{{ route('admin.employee.home') }}"
                        type="button"
                        class="btn btn-link mb-3"
                    >
                        <i class="fas fa-arrow-left"></i>
                        Quay lại
                    </a>
                    <div
                        class="d-flex flex-wrap-reverse justify-content-between align-items-center gap-3 mb-3"
                    >
                        <div>
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
                        <button
                            type="button"
                            class="btn btn-dark"
                            data-bs-toggle="modal"
                            data-bs-target="#searchModal"
                        >
                            <i class="fas fa-filter"></i>
                        </button>
                        @include('employee.search-advand', ['href' => 'admin.employee.getTrash'])
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr
                                    class="text-uppercase table-light text-center"
                                >
                                    <th>STT</th>
                                    <th>Tên/Điện thoại</th>
                                    <th>Chức vụ</th>
                                    <th>Mã nhân viên</th>
                                    <th>Danh mục lịch làm việc</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $key => $employee)
                                    <tr class="align-middle text-center">
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
                                            {{ $employee->code }}
                                        </td>
                                        <td>
                                            {{ $employee->category_celender->name }}
                                        </td>
                                        <td>
                                            <button
                                                type="submit"
                                                class="btn btn-warning"
                                                data-bs-toggle="modal"
                                                data-bs-target="#revertModal-{{ $employee->id }}"
                                            >
                                                <i
                                                    class="fas fa-rotate-left"
                                                ></i>
                                                Khôi Phục
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- Modal revert --}}
                                    <div
                                        class="modal fade"
                                        id="revertModal-{{ $employee->id }}"
                                        tabindex="-1"
                                        aria-labelledby="revertModalLabel-{{ $employee->id }}"
                                        aria-hidden="true"
                                    >
                                        <div
                                            class="modal-dialog modal-dialog-centered"
                                        >
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5
                                                        class="modal-title"
                                                        id="revertModalLabel-{{ $employee->id }}"
                                                    >
                                                        Khôi phục nhân viên
                                                    </h5>
                                                    <button
                                                        type="button"
                                                        class="btn-close"
                                                        data-bs-dismiss="modal"
                                                        aria-label="Close"
                                                    ></button>
                                                </div>
                                                <div
                                                    class="modal-body text-start"
                                                >
                                                    <p>
                                                        Bạn có chắc muốn khôi
                                                        phục nhân viên
                                                        <span class="fw-bold">
                                                            {{ $employee->code.' - '.$employee->name }}
                                                        </span>
                                                        không?
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form
                                                        action="{{ route('admin.employee.restore', $employee->id) }}"
                                                        method="put"
                                                    >
                                                        @method('PUT')
                                                        @csrf
                                                        <button
                                                            type="submit"
                                                            class="btn btn-warning"
                                                        >
                                                            Khôi Phục
                                                        </button>
                                                    </form>
                                                    <button
                                                        type="button"
                                                        class="btn btn-secondary"
                                                        data-bs-dismiss="modal"
                                                    >
                                                        Đóng
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                @if ($total == 0)
                                    <tr>
                                        <td
                                            colspan="6"
                                            class="text-center pt-4"
                                        >
                                            Hiện tại chưa có Nhân viên nào trong
                                            thùng rác.
                                            <a
                                                class="href"
                                                href="{{ route('admin.employee.home') }}"
                                            >
                                                Danh sách nhân viên
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center">
                            {{ $employees->appends(request()->all())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
