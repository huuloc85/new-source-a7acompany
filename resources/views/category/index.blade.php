@extends('layouts.'.$layout)

@php
    $startValue = count($categories) > 0 ? $categories->firstItem() : 0;
    $toValue = count($categories) > 0 ? $categories->lastItem() : 0;
@endphp

@section('content')
    <div class="card">
        <div class="card-header p-1 position-relative mt-n1 mx-1">
            <div class="border-radius-lg ps-2 pt-4 pb-3">
                <h4 class="card-title mb-0">Danh Sách Danh Mục</h4>
            </div>
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
                    <i class="fas fa-plus"></i>
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
                    <thead class="table-light">
                        <tr class="text-uppercase text-center">
                            <th scope="col">STT</th>
                            <th class="text-start" scope="col">Tên danh mục</th>
                            <th scope="col">Ngày tạo</th>
                            <th scope="col">Ngày cập nhật</th>
                            <th scope="col">Chức Năng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $key => $category)
                            <tr class="text-center">
                                <td class="fw-bold text-start" scope="row">
                                    {{ $loop->iteration + $startValue - 1 }}
                                </td>
                                <td scope="row">
                                    {{ $category->name }}
                                </td>
                                <td scope="row">
                                    {{ $category->formatTimeDMY($category->created_at) }}
                                </td>
                                <td scope="row">
                                    {{ $category->formatTimeDMY($category->updated_at) }}
                                </td>
                                <td scope="row">
                                    <a
                                        href="{{ route('admin.category.edit', $category->id) }}"
                                        class="btn btn-primary"
                                    >
                                        <i class="fas fa-edit"></i>
                                        Cập nhật
                                    </a>
                                    <!-- Button trigger modal delete -->
                                    <button
                                        type="button"
                                        class="btn btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalDelete-{{ $category->id }}"
                                    >
                                        <i class="fas fa-trash-alt"></i>
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
