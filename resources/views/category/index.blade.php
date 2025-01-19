@extends('layouts.layout')

@php
    $startValue = count($categories) > 0 ? $categories->firstItem() : 0;
    $toValue = count($categories) > 0 ? $categories->lastItem() : 0;
@endphp

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Danh Sách Danh Mục</h4>
        </div>
        <div class="card-body">
            <div
                class="d-flex justify-content-between align-items-center flex-wrap"
            >
                <a
                    href="{{ route('admin.category.add') }}"
                    type="button"
                    class="btn btn-success mb-2"
                >
                    Thêm Danh Mục
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
                            <th class="text-uppercase" scope="col">
                                Tên danh mục
                            </th>
                            <th class="text-uppercase text-center" scope="col">
                                Ngày tạo
                            </th>
                            <th class="text-uppercase text-center" scope="col">
                                Ngày cập nhật
                            </th>
                            <th class="text-uppercase text-center" scope="col">
                                Chức Năng
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $key => $category)
                            <tr>
                                <td class="fw-bold text-center" scope="row">
                                    {{ $loop->iteration + $startValue - 1 }}
                                </td>
                                <td scope="row">
                                    {{ $category->name }}
                                </td>
                                <td class="text-center" scope="row">
                                    {{ $category->formatTimeDMY($category->created_at) }}
                                </td>
                                <td class="text-center" scope="row">
                                    {{ $category->formatTimeDMY($category->updated_at) }}
                                </td>
                                <td class="text-center" scope="row">
                                    <a
                                        href="{{ route('admin.category.edit', $category->id) }}"
                                        class="btn btn-primary mb-0"
                                    >
                                        Cập nhật
                                    </a>
                                    <!-- Button trigger modal delete -->
                                    <button
                                        type="button"
                                        class="btn btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalDelete-{{ $category->id }}"
                                    >
                                        Xóa
                                    </button>
                                </td>
                            </tr>
                            <!-- Modal delete -->
                            <div
                                class="modal fade"
                                id="modalDelete-{{ $category->id }}"
                                tabindex="-1"
                                aria-labelledby="modalDeleteLabel-{{ $category->id }}"
                                aria-hidden="true"
                            >
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1
                                                class="modal-title fs-5"
                                                id="modalDeleteLabel-{{ $category->id }}"
                                            >
                                                Xóa Danh Mục
                                            </h1>
                                        </div>
                                        <div class="modal-body">
                                            <p>
                                                Hành động này không thể khôi
                                                phục! Bạn có chắc muốn xóa danh
                                                mục
                                                <span class="fw-bold">
                                                    {{ $category->name }}
                                                </span>
                                                không?
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <form
                                                action="{{ route('admin.category.delete', $category->id) }}"
                                                method="delete"
                                            >
                                                @method('DELETE')
                                                @csrf
                                                <button
                                                    type="button"
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
                                                Hủy
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if ($total == 0)
                            <tr>
                                <td colspan="5" class="text-center">
                                    Hiện tại chưa có danh mục nào. Vui lòng
                                    <a
                                        class="href"
                                        href="{{ route('admin.category.add') }}"
                                    >
                                        Thêm danh mục
                                    </a>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $categories->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
