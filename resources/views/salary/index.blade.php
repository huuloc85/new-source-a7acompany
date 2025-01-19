@extends('layouts.layout')

@php
    $startValue = count($salaryManagers) > 0 ? $salaryManagers->firstItem() : 0;
    $toValue = count($salaryManagers) > 0 ? $salaryManagers->lastItem() : 0;
@endphp

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Danh Sách Bảng Lương</h4>
        </div>
        <div class="card-body">
            <div
                class="d-flex justify-content-between align-items-center flex-wrap"
            >
                <a
                    class="btn btn-success mb-2"
                    href="{{ route('admin.salary.getimport') }}"
                >
                    Import Bảng Lương
                </a>
                <form action="" class="mb-2">
                    <div class="input-group input-group-outline">
                        <input
                            name="key"
                            value="{{ request()->key }}"
                            type="text"
                            class="form-control"
                            placeholder="Nhập từ khóa..."
                        />
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                            <span hidden>Search</span>
                        </button>
                    </div>
                </form>
            </div>
            <div class="mb-2">
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
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase text-center" scope="col">
                                STT
                            </th>
                            <th class="text-uppercase" scope="col">Tiêu đề</th>
                            <th class="text-uppercase" scope="col">
                                Tổng (VND)
                            </th>
                            <th class="text-uppercase text-center" scope="col">
                                Ngày bắt đầu
                            </th>
                            <th class="text-uppercase text-center" scope="col">
                                Ngày kết thúc
                            </th>
                            <th class="text-uppercase text-center" scope="col">
                                Chức Năng
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salaryManagers as $key => $salaryManager)
                            <tr>
                                <td class="fw-bold text-center" scope="row">
                                    {{ $loop->iteration + $startValue - 1 }}
                                </td>
                                <td scope="row">
                                    {{ $salaryManager->title }}
                                </td>
                                <td scope="row">
                                    {{ number_format($salaryManager->total, 2) }}
                                </td>
                                <td class="text-center" scope="row">
                                    {{ $salaryManager->formatTimeDMY($salaryManager->start_date) }}
                                </td>
                                <td class="text-center" scope="row">
                                    {{ $salaryManager->formatTimeDMY($salaryManager->end_date) }}
                                </td>
                                <td class="text-center" scope="row">
                                    <a
                                        href="{{ route('admin.salary.detail', $salaryManager->id) }}"
                                        class="btn btn-primary"
                                    >
                                        Chi tiết
                                    </a>

                                    <!-- Button trigger modal delete -->
                                    <button
                                        type="button"
                                        class="btn btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalDelete-{{ $salaryManager->id }}"
                                    >
                                        Xóa
                                    </button>
                                </td>
                            </tr>
                            <!-- Modal delete -->
                            <div
                                class="modal fade"
                                id="modalDelete-{{ $salaryManager->id }}"
                                tabindex="-1"
                                aria-labelledby="modalDeleteLabel-{{ $salaryManager->id }}"
                                aria-hidden="true"
                            >
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1
                                                class="modal-title fs-5"
                                                id="modalDeleteLabel-{{ $salaryManager->id }}"
                                            >
                                                Xóa bảng lương
                                            </h1>
                                        </div>
                                        <div class="modal-body">
                                            <p>
                                                Hành động không thể khôi phục!!
                                                Bạn có chắc xoá bảng lương
                                                <span class="fw-bold">
                                                    {{ $salaryManager->title }}
                                                </span>
                                                không?
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <form
                                                action="{{ route('admin.salary.delete', $salaryManager->id) }}"
                                                method="post"
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
                                            <button
                                                type="button"
                                                class="btn btn-secondary"
                                                data-bs-dismiss="modal"
                                            >
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if ($total == 0)
                            <tr>
                                <td colspan="6" class="text-center">
                                    Hiện tại chưa có bảng lương nào. Vui lòng
                                    <a
                                        href="{{ route('admin.salary.getimport') }}"
                                    >
                                        Thêm bảng lương
                                    </a>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $salaryManagers->appends(request()->all())->links() }}
            </div>
        </div>
    </div>
@endsection
