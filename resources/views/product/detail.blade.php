@extends('layouts.layout')

@php
    $dataProductStatus = [
        'product' => ['data' => $dailyQuanStatus1, 'tabTitle' => 'LỊCH SỬ SẢN XUẤT (100%)', 'status' => 1],
        'check-100' => ['data' => $dailyQuanStatus2, 'tabTitle' => 'LỊCH SỬ HÀNG KIỂM (200%)', 'status' => 2],
        'import-200' => ['data' => $dailyQuanStatus3, 'tabTitle' => 'LỊCH SỬ XUẤT HÀNG (200%)', 'status' => 3],
        'import-300' => ['data' => $dailyQuanStatus6, 'tabTitle' => 'LỊCH SỬ HÀNG LỖI', 'status' => 6],
    ];
@endphp

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Danh Sách Lịch Sử Cập Nhật Sản Phẩm</h4>
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
                    <div class="fs-5 mb-3">
                        <div>
                            <span class="fw-bold">Tên sản phẩm:</span>
                            {{ $product->name }}
                        </div>
                        <div>
                            <span class="fw-bold">Mã sản phẩm:</span>
                            {{ $product->code }}
                        </div>
                    </div>
                    <div
                        class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3"
                    >
                        <a
                            href="{{ route('admin.product.update-quantity-admin', $id) }}"
                            type="button"
                            class="btn btn-primary"
                        >
                            Cập nhật sản lượng
                        </a>
                        <form action="" method="get">
                            @csrf
                            <select
                                class="form-control"
                                name="month"
                                onchange="this.form.submit()"
                            >
                                @foreach ($listMonth as $month)
                                    <option
                                        <?= $month == $monthNearly ? "selected" : "" ?>
                                        value="{{ $month }}"
                                    >
                                        {{ $month }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <ul
                        class="nav nav-tabs flex-nowrap text-nowrap overflow-x-auto overflow-y-hidden"
                        id="myTab"
                        role="tablist"
                    >
                        @foreach ($dataProductStatus as $key => $tab)
                            <li class="nav-item" role="presentation">
                                <button
                                    class="nav-link tab-vvp {{ $loop->first ? 'active' : '' }}"
                                    id="{{ $key }}-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#{{ $key }}"
                                    type="button"
                                    role="tab"
                                    aria-controls="{{ $key }}"
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                >
                                    {{ $tab['tabTitle'] }}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        @foreach ($dataProductStatus as $key => $tabpanel)
                            <div
                                class="tab-pane tab-vvp fade {{ $loop->first ? 'show active' : '' }}"
                                id="{{ $key }}"
                                role="tabpanel"
                                aria-labelledby="{{ $key }}-tab"
                            >
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead
                                            class="table-light text-uppercase text-center"
                                        >
                                            <tr>
                                                <th>STT</th>
                                                <th>Tên nhân viên</th>
                                                <th>Mã nhân viên</th>
                                                <th>Thời gian cập nhật</th>
                                                <th>
                                                    Thời gian cuối cùng cập nhật
                                                </th>
                                                <th>Số lượng</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center align-middle">
                                            @foreach ($tabpanel['data'] as $key => $daily)
                                                <tr>
                                                    <th>
                                                        {{ $loop->iteration }}
                                                    </th>
                                                    <td class="text-start">
                                                        {{ $daily->employee->name }}
                                                    </td>
                                                    <td>
                                                        {{ $daily->employee->code }}
                                                    </td>
                                                    <td>
                                                        {{ $daily->date }}
                                                    </td>
                                                    <td>
                                                        {{ $daily->created_at }}
                                                    </td>
                                                    <td>
                                                        {{ number_format($daily->quantity) }}
                                                    </td>
                                                    <td>
                                                        <button
                                                            data-daily-id="{{ $daily->id }}"
                                                            data-daily-quan="{{ $daily->quantity }}"
                                                            data-product-id="{{ $daily->product_id }}"
                                                            data-status="{{ $tabpanel['status'] }}"
                                                            type="button"
                                                            class="btn btn-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#updateDetail"
                                                        >
                                                            Cập nhật
                                                        </button>
                                                        <button
                                                            type="button"
                                                            class="btn btn-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalDelete-{{ $daily->id }}"
                                                        >
                                                            Xoá
                                                        </button>
                                                    </td>
                                                </tr>
                                                {{-- Modal delete --}}
                                                <div
                                                    class="modal fade"
                                                    id="modalDelete-{{ $daily->id }}"
                                                    tabindex="-1"
                                                    aria-labelledby="modalDeleteLabel-{{ $daily->id }}"
                                                    aria-hidden="true"
                                                >
                                                    <div
                                                        class="modal-dialog modal-dialog-centered"
                                                    >
                                                        <div
                                                            class="modal-content"
                                                        >
                                                            <div
                                                                class="modal-header"
                                                            >
                                                                <h1
                                                                    class="modal-title fs-5"
                                                                    id="modalDeleteLabel-{{ $daily->id }}"
                                                                >
                                                                    Xóa lịch sử
                                                                    cập nhật
                                                                </h1>
                                                                <button
                                                                    type="button"
                                                                    class="btn-close"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close"
                                                                ></button>
                                                            </div>
                                                            <div
                                                                class="modal-body"
                                                            >
                                                                <p>
                                                                    Hành động
                                                                    không thể
                                                                    khôi phục!!
                                                                    Bạn có chắc
                                                                    muốn xoá
                                                                    lịch sử cập
                                                                    <span
                                                                        class="fw-bold"
                                                                    >
                                                                        {{ '#'.$loop->iteration }}
                                                                        -
                                                                        {{ $daily->employee->name }}
                                                                        -
                                                                        {{ $daily->created_at }}
                                                                    </span>
                                                                    nhật sản
                                                                    phẩm không?
                                                                </p>
                                                            </div>
                                                            <div
                                                                class="modal-footer"
                                                            >
                                                                <form
                                                                    class="ms-2"
                                                                    action="{{ route('admin.product.delete-update-quantity', $daily->id) }}"
                                                                    method="post"
                                                                >
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button
                                                                        type="submit"
                                                                        class="btn btn-danger"
                                                                    >
                                                                        Xoá
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

                                            @if ($tabpanel['data']->isEmpty())
                                                <tr>
                                                    <td
                                                        colspan="7"
                                                        class="text-center pt-4"
                                                    >
                                                        Hiện tại chưa có lịch
                                                        sử.
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-center">
                                    {{ $tabpanel['data']->appends(request()->all())->links() }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('product.update-history')
@endsection
