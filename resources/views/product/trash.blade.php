@extends('layouts.'.$layout)

@php
    $startValue = count($products) > 0 ? $products->firstItem() : 0;
    $toValue = count($products) > 0 ? $products->lastItem() : 0;
@endphp

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Thùng Rác Sản Phẩm</h4>
                    </div>
                </div>
                <div class="card-body">
                    <a
                        href="{{ route('admin.product.home') }}"
                        type="button"
                        class="btn btn-link mb-3"
                    >
                        <i class="fas fa-arrow-left"></i>
                        Danh Sách Sản Phẩm
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
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead
                                class="table-light text-uppercase text-center align-middle"
                            >
                                <tr>
                                    <th>STT</th>
                                    <th>Mã Linh Kiện</th>
                                    <th>Tên Linh Kiện</th>
                                    <th>Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $key => $product)
                                    <tr class="text-center align-middle">
                                        <th>
                                            {{ $loop->iteration }}
                                        </th>
                                        <td>
                                            {{ $product->code }}
                                        </td>
                                        <td>
                                            {{ $product->name }}
                                        </td>
                                        <td>
                                            <button
                                                type="submit"
                                                class="btn btn-warning"
                                                data-bs-toggle="modal"
                                                data-bs-target="#restoreModal-{{ $product->id }}"
                                            >
                                                <i
                                                    class="fas fa-rotate-left"
                                                ></i>
                                                Khôi phục
                                            </button>
                                        </td>
                                    </tr>
                                    {{-- Modal restore --}}
                                    <div
                                        class="modal fade"
                                        id="restoreModal-{{ $product->id }}"
                                        tabindex="-1"
                                        aria-labelledby="restoreModalLabel-{{ $product->id }}"
                                        aria-hidden="true"
                                    >
                                        <div
                                            class="modal-dialog modal-dialog-centered"
                                        >
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5
                                                        class="modal-title"
                                                        id="restoreModalLabel-{{ $product->id }}"
                                                    >
                                                        Khôi Phục Sản Phẩm
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
                                                        Bạn có chắc muốn khôi
                                                        phục sản phẩm
                                                        <span class="fw-bold">
                                                            {{ $product->id.'-'.$product->name }}
                                                        </span>
                                                        không?
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form
                                                        action="{{ route('admin.product.restore', $product->id) }}"
                                                        method="PUT"
                                                    >
                                                        @method('PUT')
                                                        @csrf
                                                        <button
                                                            class="btn btn-warning"
                                                            type="submit"
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
                                            colspan="9999"
                                            class="text-center pt-4"
                                        >
                                            Hiện tại chưa có sản phẩm nào trong
                                            thùng rác.
                                            <a
                                                class="href"
                                                href="{{ route('admin.product.home') }}"
                                            >
                                                Quay lại danh sách sản phẩm
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center">
                            {{ $products->appends(request()->all())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('product.search-advance', ['href' => 'admin.product.getTrash'])
@endsection
