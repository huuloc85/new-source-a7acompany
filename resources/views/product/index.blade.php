@extends('layouts.'.$layout)

@php
    $tabProducts = [
        'product' => ['title' => 'TỔNG QUAN', 'data' => $products, 'page' => 'product', 'status' => 1],
        'check-100' => ['title' => 'Hàng Sản Xuất', 'data' => $products, 'page' => 'produce', 'status' => 1],
        'import-200' => ['title' => 'Hàng Kiểm 200%', 'data' => $products, 'page' => 'check200', 'status' => 2],
        'import-300' => ['title' => 'Hàng Lỗi (200%)', 'data' => $products, 'page' => 'error200', 'status' => 6],
        'export-200' => ['title' => 'Xuất Hàng', 'data' => $products, 'page' => 'export', 'status' => 3],
    ];
@endphp

@section('styles')
    <style>
        .form-control {
            border: 2px solid #d2d6da !important;
            padding-left: 10px;
        }

        .active > .page-link {
            color: white !important;
        }

        .href {
            color: blue !important;
        }

        .trash {
            margin-left: 10px;
        }

        .product-tab {
            max-width: 100%;
            overflow-x: auto;
        }

        .product-tab-2 {
            max-width: 70%;
            overflow-x: auto;
        }

        .bg-2 {
            background-color: #ebebeb !important;
        }

        .bg-3 {
            background-color: #919cc9 !important;
        }

        .bg-4 {
            background-color: #d7a95f !important;
        }

        @media only screen and (max-width: 768px) {
            #button-list {
                display: block !important;
            }

            .col-3 {
                width: 80% !important;
            }

            .col-2 {
                width: 50% !important;
            }

            .col-4 {
                width: 100% !important;
            }

            .col-5 {
                width: 136% !important;
            }

            .group-filter {
                display: block !important;
                justify-content: center;
                margin-bottom: 20px !important;
            }

            #button-list {
                display: block;
                text-align: center;
            }

            .btn-group {
                display: block;
                margin-bottom: 10px;
            }

            .btn-group a {
                display: block;
                margin-bottom: 10px;
            }

            .btn-group a:last-child {
                margin-bottom: 0;
            }
        }

        #loader {
            position: fixed;
            top: 55%;
            left: 50%;
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            z-index: 9999;
            transform: translate(-50%, -50%);
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header p-4">
            <h4 class="card-title">Danh Sách Sản Phẩm</h4>
        </div>
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between gap-3">
                <div class="flex flex-wrap align-items-center gap-3">
                    <a
                        href="{{ route('admin.product.add') }}"
                        class="btn btn-primary rounded mb-2"
                        title="Thêm Sản Phẩm"
                        data-bs-toggle="tooltip"
                    >
                        <i class="fas fa-plus"></i>
                    </a>
                    <a
                        href="{{ route('admin.product.update-moq') }}"
                        class="btn btn-primary rounded mb-2"
                        title="Thêm Sản Lượng MOQ Tồn Đầu Kỳ Tồn 200%"
                        data-bs-toggle="tooltip"
                    >
                        <i class="fas fa-box"></i>
                    </a>
                    <a
                        href="{{ route('admin.product.add-quantity-admin') }}"
                        class="btn btn-primary rounded mb-2"
                        title="Thêm Sản Lượng Sản Xuất"
                        data-bs-toggle="tooltip"
                    >
                        <i class="fas fa-industry"></i>
                    </a>
                    <a
                        href="{{ route('admin.product.getTrash') }}"
                        class="btn btn-warning trash rounded mb-2"
                        title="Thùng Rác"
                        data-bs-toggle="tooltip"
                    >
                        <i class="fas fa-trash"></i>
                    </a>
                    <form
                        action="{{ route('admin.export.product') }}"
                        method="get"
                        class="d-inline"
                    >
                        @csrf
                        <input
                            type="hidden"
                            name="month"
                            value="{{ $monthNearly }}"
                        />
                        <button
                            type="submit"
                            class="btn btn-success mb-2"
                            title="Xuất Excel"
                            data-bs-toggle="tooltip"
                        >
                            <i class="fas fa-file-export me-2"></i>
                            <span>Export</span>
                        </button>
                    </form>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <form method="get" class="d-flex gap-3" id="searchForm">
                        @csrf
                        <input type="hidden" name="page" value="{{ $page }}" />
                        <select
                            class="form-select"
                            name="month"
                            id="monthSelect"
                            onchange="this.form.submit()"
                        >
                            @foreach ($listMonth as $month)
                                <option
                                    {{ $month == $monthNearly ? 'selected' : '' }}
                                    value="{{ $month }}"
                                >
                                    {{ $month }}
                                </option>
                            @endforeach
                        </select>
                        <select
                            class="form-select"
                            name="orderBy"
                            id="orderBySelect"
                            onchange="this.form.submit()"
                        >
                            <option
                                value="asc"
                                {{ request('orderBy') == 'asc' ? 'selected' : '' }}
                            >
                                Đầu Tiên
                            </option>
                            <option
                                value="desc"
                                {{ request('orderBy') == 'desc' ? 'selected' : '' }}
                            >
                                Cuối Cùng
                            </option>
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
                    @include('product.search-advance', ['href' => 'admin.product.home'])
                </div>
            </div>
            <div class="py-2 fs-5">
                Tổng Sản Phẩm:
                <strong>{{ count($products) }}</strong>
            </div>
            <section>
                <ul
                    class="nav nav-tabs flex-nowrap text-nowrap overflow-x-auto overflow-y-hidden"
                    id="myTab"
                    role="tablist"
                    data-url="{{ route('admin.product.home') }}"
                >
                    @foreach ($tabProducts as $key => $tabProduct)
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link text-uppercase {{ $page == $tabProduct['page'] ? 'active' : '' }}"
                                id="{{ $key }}-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#{{ $key }}"
                                type="button"
                                role="tab"
                                aria-controls="{{ $key }}"
                                aria-selected="{{ $page == $tabProduct['page'] ? 'true' : 'false' }}"
                            >
                                {{ $tabProduct['title'] }}
                            </button>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content" id="myTabContent">
                    @if ($page == 'product')
                        <div
                            class="tab-pane tab-vvp fade show active product"
                            id="product"
                            role="tabpanel"
                            aria-labelledby="product-tab"
                        >
                            <div class="px-0 pb-2">
                                <div class="table-responsive p-0 d-flex">
                                    <div class="col-3">
                                        <table
                                            class="table align-items-center mb-0 table-hover"
                                        >
                                            <thead>
                                                <tr>
                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder px-3"
                                                        rowspan="2"
                                                    >
                                                        &nbsp;
                                                        <br />
                                                        STT
                                                        <br />
                                                        &nbsp;
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder px-4"
                                                        rowspan="2"
                                                    >
                                                        &nbsp;
                                                        <br />
                                                        Tên linh kiện
                                                        <br />
                                                        &nbsp;
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $key => $product)
                                                    <tr>
                                                        <td>
                                                            <div
                                                                class="d-flex px-3 py-1"
                                                            >
                                                                {{ $loop->iteration }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div
                                                                class="d-flex px-3 py-1"
                                                            >
                                                                <a
                                                                    href="{{ route('admin.product.detail', $product->id) }}"
                                                                >
                                                                    {{ $product->name }}
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-10 product-tab">
                                        <table
                                            class="table align-items-center mb-0 table-hover"
                                        >
                                            <thead>
                                                <tr>
                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder text-center col-1 bg-3"
                                                        rowspan="2"
                                                    >
                                                        SẢN LƯỢNG
                                                        <br />
                                                        (MOQ)
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder text-center col-1 bg-3"
                                                        rowspan="2"
                                                    >
                                                        THUNG CATON/THANG
                                                        <br />
                                                        (MOQ)
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder text-center col-1 bg-2"
                                                        rowspan="2"
                                                    >
                                                        Dự định
                                                        <br />
                                                        Thời gian hoạt động
                                                        thiết bị
                                                        <br />
                                                        (ngày/tháng)
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder text-center col-1 bg-2"
                                                        rowspan="2"
                                                    >
                                                        Thực tế
                                                        <br />
                                                        Thời gian hoạt động
                                                        thiết bị
                                                        <br />
                                                        (ngày/tháng)
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder text-center col-1 bg-3"
                                                        rowspan="2"
                                                    >
                                                        FAPV出荷
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder text-center col-1 bg-3"
                                                        rowspan="2"
                                                    >
                                                        FASV出荷
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder text-center col-1 bg-3"
                                                        rowspan="2"
                                                    >
                                                        FAVV出荷
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder text-center col-1 bg-2"
                                                        rowspan="2"
                                                    >
                                                        TỔNG SỐ LƯỢNG
                                                        <br />
                                                        TỒN ĐẦU KỲ
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder text-center col-1 bg-2"
                                                        rowspan="2"
                                                    >
                                                        TỔNG THỰC TẾ
                                                        <br />
                                                        SẢN XUẤT(cái/tháng)
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder text-center col-1 bg-2"
                                                        rowspan="2"
                                                    >
                                                        TỔNG SỐ LƯỢNG
                                                        <br />
                                                        ĐÃ XUẤT
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder text-center col-1 bg-2"
                                                        rowspan="2"
                                                    >
                                                        SỐ LƯỢNG
                                                        <br />
                                                        ĐÃ KIỂM 200%
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder text-center col-1 bg-2"
                                                        rowspan="2"
                                                    >
                                                        SỐ LƯỢNG
                                                        <br />
                                                        HÀNG CHƯA KIỂM 200%
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder text-center col-1 bg-2"
                                                        rowspan="2"
                                                    >
                                                        TỔNG SỐ LƯỢNG
                                                        <br />
                                                        TỒN CUỐI KỲ
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder text-center col-1 bg-2"
                                                        rowspan="2"
                                                    >
                                                        SỐ NGÀY
                                                        <br />
                                                        TỒN KHO
                                                    </th>
                                                    @foreach ($listMonthExport as $monthExport)
                                                        <th
                                                            class="text-uppercase text-xxs font-weight-bolder text-center col-1 bg-3"
                                                            rowspan="2"
                                                        >
                                                            SỐ LƯỢNG
                                                            <br />
                                                            ĐÃ XUẤT THÁNG
                                                            {{ $monthExport }}
                                                        </th>
                                                    @endforeach

                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder text-center col-1"
                                                        rowspan="2"
                                                    >
                                                        THAO TÁC
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $key => $product)
                                                    <?php
                                                    $prorealityQuan = $product->TotalMonthQuantities()->where('month', $monthNearly)->where('status', 1)->value('totalQuan') ?? 0; // tổng hàng sản xuất
                                                    $importedQuan = $product->TotalMonthQuantities()->where('month', $monthNearly)->where('status', 2)->value('totalQuan') ?? 0; // tổng hàng kiểm 200%
                                                    $exportedQuan = $product->TotalMonthQuantities()->where('month', $monthNearly)->where('status', 3)->value('totalQuan') ?? 0; // tổng số lượng đã xuất
                                                    $stockQuan = $product->TotalMonthQuantities()->where('month', $monthNearly)->where('status', 4)->value('totalQuan') ?? 0; // tồn đầu kỳ
                                                    $stockQuan200 = $product->TotalMonthQuantities()->where('month', $monthNearly)->where('status', 5)->value('totalQuan') ?? 0; // tồn đầu kỳ 200%
                                                    $errorQuantity = $product->TotalMonthQuantities()->where('month', $monthNearly)->where('status', 6)->value('totalQuan') ?? 0; // hàng lỗi
                                                    $stockQuanMOQ = $product->TotalMonthQuantities()->where('month', $monthNearly)->where('status', 7)->value('totalQuan') ?? 0; // MOQ

                                                    $checked200 = $stockQuan200 + $importedQuan - $exportedQuan; // đã kiểm 200%
                                                    $stockEndQuan = $stockQuan + $prorealityQuan - $exportedQuan - $errorQuantity; // tồn cuối kỳ
                                                    $stockNoneCheck200 = $stockQuan + $prorealityQuan - $exportedQuan - $checked200 - $errorQuantity; // số lượng hàng chưa kiểm 200%
                                                    $quantityCaTon = $stockQuanMOQ / $product->quanEntityBin;
                                                    $planTime = (((($stockQuanMOQ / $product->CAV) * $product->cycle) / 3600 / 24) * 100) / 90;
                                                    $realTime = (((($exportedQuan / $product->CAV) * $product->cycle) / 3600 / 24) * 100) / 90;
                                                    ?>

                                                    <tr>
                                                        <td
                                                            class="text-center bg-3"
                                                        >
                                                            <div
                                                                class="d-flex px-3 py-1 justify-content-center"
                                                            >
                                                                {{ number_format($stockQuanMOQ) }}
                                                            </div>
                                                        </td>
                                                        <td
                                                            class="text-center bg-3"
                                                        >
                                                            <div
                                                                class="d-flex px-3 py-1 justify-content-center"
                                                            >
                                                                {{ number_format($quantityCaTon) }}
                                                            </div>
                                                        </td>
                                                        <td
                                                            class="text-center bg-2"
                                                        >
                                                            <div
                                                                class="d-flex px-3 py-1 justify-content-center"
                                                            >
                                                                {{ number_format($planTime, 1) }}
                                                            </div>
                                                        </td>
                                                        <td
                                                            class="text-center bg-2"
                                                        >
                                                            <div
                                                                class="d-flex px-3 py-1 justify-content-center"
                                                            >
                                                                {{ number_format($realTime, 1) }}
                                                            </div>
                                                        </td>
                                                        <td
                                                            class="text-center bg-3"
                                                        >
                                                            <div
                                                                class="d-flex px-3 py-1 justify-content-center"
                                                            >
                                                                {{ $product->FAPV == 1 ? 'O' : '' }}
                                                            </div>
                                                        </td>
                                                        <td
                                                            class="text-center bg-3"
                                                        >
                                                            <div
                                                                class="d-flex px-3 py-1 justify-content-center"
                                                            >
                                                                {{ $product->FASV == 1 ? 'O' : '' }}
                                                            </div>
                                                        </td>
                                                        <td
                                                            class="text-center bg-3"
                                                        >
                                                            <div
                                                                class="d-flex px-3 py-1 justify-content-center"
                                                            >
                                                                {{ $product->FAVV == 1 ? 'O' : '' }}
                                                            </div>
                                                        </td>
                                                        <td
                                                            class="text-center bg-2"
                                                        >
                                                            <div
                                                                class="d-flex px-3 py-1 justify-content-center"
                                                            >
                                                                {{ number_format($stockQuan) }}
                                                            </div>
                                                        </td>

                                                        <td
                                                            class="text-center bg-2"
                                                        >
                                                            <div
                                                                class="d-flex px-3 py-1 justify-content-center"
                                                            >
                                                                {{ number_format($prorealityQuan) }}
                                                            </div>
                                                        </td>

                                                        <td
                                                            class="text-center bg-2"
                                                        >
                                                            <div
                                                                class="d-flex px-3 py-1 justify-content-center"
                                                            >
                                                                {{ number_format($exportedQuan) }}
                                                            </div>
                                                        </td>

                                                        <td
                                                            class="text-center bg-2"
                                                        >
                                                            <div
                                                                class="d-flex px-3 py-1 justify-content-center"
                                                            >
                                                                {{ number_format($checked200) }}
                                                            </div>
                                                        </td>

                                                        <td
                                                            class="text-center bg-2"
                                                        >
                                                            <div
                                                                class="d-flex px-3 py-1 justify-content-center"
                                                            >
                                                                {{ number_format($stockNoneCheck200) }}
                                                            </div>
                                                        </td>

                                                        <td
                                                            class="text-center bg-2"
                                                        >
                                                            <div
                                                                class="d-flex px-3 py-1 justify-content-center"
                                                            >
                                                                {{ number_format($stockEndQuan) }}
                                                            </div>
                                                        </td>

                                                        <td
                                                            class="text-center bg-2"
                                                        >
                                                            <div
                                                                class="d-flex px-3 py-1 justify-content-center"
                                                            >
                                                                {{ number_format($stockQuanMOQ != 0 ? $stockEndQuan / ($stockQuanMOQ / 24) : 0, 1) }}
                                                            </div>
                                                        </td>

                                                        @foreach ($listMonthExport as $monthExport)
                                                            <td
                                                                class="text-center bg-3"
                                                            >
                                                                <div
                                                                    class="d-flex px-3 py-1 justify-content-center"
                                                                >
                                                                    <?php
                                                                    $export = $product->TotalMonthQuantities()->where('month', $monthExport)->where('status', 3)->value('totalQuan') ?? 0;
                                                                    ?>

                                                                    {{ number_format($export) }}
                                                                </div>
                                                            </td>
                                                        @endforeach

                                                        <td class="text-center">
                                                            <form
                                                                action="{{ route('admin.product.delete', $product->id) }}"
                                                                method="post"
                                                            >
                                                                @method('DELETE')
                                                                @csrf
                                                                <a
                                                                    style="
                                                                        margin-bottom: 0px;
                                                                        height: 32px;
                                                                    "
                                                                    href="{{ route('admin.product.edit', $product->id) }}"
                                                                    class="btn btn-primary"
                                                                >
                                                                    Cập nhật
                                                                </a>
                                                                <button
                                                                    style="
                                                                        margin-bottom: 0px;
                                                                        height: 32px;
                                                                    "
                                                                    onclick="return confirm('Bạn có chắc muốn đưa sản phẩm này vào thùng rác không?');"
                                                                    class="btn btn-danger"
                                                                    type="submit"
                                                                >
                                                                    Xóa
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($page == 'produce')
                        <div
                            class="tab-pane tab-vvp fade product"
                            id="check-100"
                            role="tabpanel"
                            aria-labelledby="check-100-tab"
                        >
                            <div class="px-0 pb-2">
                                <div class="table-responsive p-0 d-flex">
                                    <div class="col-4 my-4">
                                        <table
                                            class="table align-items-center mb-0 table-hover"
                                        >
                                            <thead>
                                                <tr>
                                                    <th
                                                        class="text-uppercase text-xxs fw-bold px-3 align-middle"
                                                        rowspan="2"
                                                    >
                                                        &nbsp;STT
                                                        <br />
                                                        &nbsp;
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder px-4"
                                                        rowspan="2"
                                                    >
                                                        Tên linh kiện
                                                        <br />
                                                        &nbsp;
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder text-center text-center"
                                                        rowspan="2"
                                                    >
                                                        Tổng cộng
                                                        <br />
                                                        &nbsp;
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $key => $product)
                                                    <tr>
                                                        <td>
                                                            <div
                                                                class="d-flex px-3 py-1"
                                                            >
                                                                {{ $loop->iteration }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div
                                                                class="d-flex px-3 py-1"
                                                            >
                                                                <a
                                                                    href="{{ route('admin.product.detail', $product->id) }}"
                                                                >
                                                                    {{ $product->name }}
                                                                </a>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div
                                                                class="d-flex px-3 py-1 justify-content-center"
                                                            >
                                                                {{ number_format($product->TotalMonthQuantities()->where('month', $monthNearly)->where('status', 1)->value('totalQuan') ?? 0) }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-9 product-tab">
                                        <table
                                            class="table align-items-center mb-0 table-hover"
                                        >
                                            <thead>
                                                <tr>
                                                    @foreach ($listDate as $key => $date)
                                                        <th
                                                            class="text-uppercase text-xxs font-weight-bolder text-center <?= $key % 2 == 0 ? "bg-3" : "bg-2" ?>"
                                                            colspan="2"
                                                        >
                                                            {{ $date }}
                                                        </th>
                                                    @endforeach

                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder text-center col-1"
                                                        rowspan="2"
                                                    >
                                                        THAO TÁC
                                                    </th>
                                                </tr>
                                                <tr>
                                                    @foreach ($listDate as $key => $date)
                                                        <th
                                                            class="text-uppercase text-center text-xxs <?= $key % 2 == 0 ? "bg-3" : "bg-2" ?>"
                                                        >
                                                            Ca 1
                                                        </th>
                                                        <th
                                                            class="text-uppercase text-center text-xxs <?= $key % 2 == 0 ? "bg-3" : "bg-2" ?>"
                                                        >
                                                            Ca 2
                                                        </th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            @foreach ($products as $product)
                                                <tr>
                                                    @foreach ($listDate as $key => $date)
                                                        @php
                                                            // Chuyển đổi date được cung cấp sang định dạng Carbon để so sánh
                                                            $formattedDate = Carbon\Carbon::parse(
                                                                $date,
                                                            )->startOfDay();
                                                            // Lấy tất cả các dailyQuantities cho ngày cụ thể
                                                            $dailyQuantitiesOfTheDay = $product
                                                                ->DailyQuantities()
                                                                ->where('status', 1)
                                                                ->whereDate('date', $formattedDate)
                                                                ->get();

                                                            $totalQuanDateCa1 = 0;
                                                            $totalQuanDateCa2 = 0;

                                                            // Xử lý số lượng cho mỗi ca
                                                            foreach ($dailyQuantitiesOfTheDay as $dailyQuantity) {
                                                                $created_at = Carbon\Carbon::parse(
                                                                    $dailyQuantity->created_at,
                                                                );
                                                                $nextDayEightAM = $formattedDate
                                                                    ->copy()
                                                                    ->addDay()
                                                                    ->setHour(9);

                                                                // Phân biệt ca dựa vào thời gian trong cột created_at
                                                                if ($created_at->isSameDay($formattedDate)) {
                                                                    // Ca 1 nếu created_at cùng ngày với date
                                                                    $totalQuanDateCa1 += $dailyQuantity->quantity;
                                                                } elseif ($created_at < $nextDayEightAM) {
                                                                    // Ca 2 nếu created_at trước 8 giờ sáng ngày hôm sau của date
                                                                    $totalQuanDateCa2 += $dailyQuantity->quantity;
                                                                }
                                                            }
                                                        @endphp

                                                        <td
                                                            class="{{ $key % 2 == 0 ? 'bg-3' : 'bg-2' }}"
                                                        >
                                                            <div
                                                                class="d-flex px-3 py-1 justify-content-center"
                                                            >
                                                                {{ number_format($totalQuanDateCa1) }}
                                                            </div>
                                                        </td>
                                                        <td
                                                            class="{{ $key % 2 == 0 ? 'bg-3' : 'bg-2' }}"
                                                        >
                                                            <div
                                                                class="d-flex px-3 py-1 justify-content-center"
                                                            >
                                                                {{ number_format($totalQuanDateCa2) }}
                                                            </div>
                                                        </td>
                                                    @endforeach

                                                    <td class="text-center">
                                                        <form
                                                            action="{{ route('admin.product.delete', $product->id) }}"
                                                            method="post"
                                                        >
                                                            @method('DELETE')
                                                            @csrf
                                                            <a
                                                                style="
                                                                    margin-bottom: 0px;
                                                                    height: 32px;
                                                                "
                                                                href="{{ route('admin.product.edit', $product->id) }}"
                                                                class="btn btn-primary"
                                                            >
                                                                Cập nhật
                                                            </a>
                                                            <button
                                                                style="
                                                                    margin-bottom: 0px;
                                                                    height: 32px;
                                                                "
                                                                onclick="return confirm('Bạn có chắc muốn đưa sản phẩm này vào thùng rác không?');"
                                                                class="btn btn-danger"
                                                                type="submit"
                                                            >
                                                                Xóa
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($page == 'check200')
                        <div
                            class="tab-pane tab-vvp fade product"
                            id="import-200"
                            role="tabpanel"
                            aria-labelledby="import-200-tab"
                        >
                            <div class="px-0 pb-2">
                                <div class="table-responsive p-0 d-flex">
                                    <div class="col-5">
                                        <table
                                            class="table align-items-center mb-0 table-hover"
                                        >
                                            <thead>
                                                <tr>
                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder text-center"
                                                        rowspan="2"
                                                    >
                                                        STT
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder px-4"
                                                        rowspan="2"
                                                    >
                                                        Tên Linh Kiện
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder text-center col-1"
                                                        rowspan="2"
                                                    >
                                                        Tồn đầu kỳ
                                                        <br />
                                                        hàng 200%
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder text-center col-1"
                                                        rowspan="2"
                                                    >
                                                        Phát sinh
                                                        <br />
                                                        kiểm hàng 200%
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $key => $product)
                                                    <tr>
                                                        <td>
                                                            <div
                                                                class="d-flex px-3 py-1 justify-content-center"
                                                            >
                                                                {{ $loop->iteration }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div
                                                                class="d-flex justify-content-start px-3 py-1"
                                                            >
                                                                <a
                                                                    href="{{ route('admin.product.detail', $product->id) }}"
                                                                >
                                                                    {{ $product->name }}
                                                                </a>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div
                                                                class="d-flex px-3 py-1 justify-content-center"
                                                            >
                                                                {{ number_format($product->TotalMonthQuantities()->where('month', $monthNearly)->where('status', 5)->value('totalQuan') ?? 0) }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div
                                                                class="d-flex px-3 py-1 justify-content-center"
                                                            >
                                                                {{ number_format($product->TotalMonthQuantities()->where('month', $monthNearly)->where('status', 2)->value('totalQuan') ?? 0) }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-7 product-tab-2">
                                        <table
                                            class="table align-items-center mb-0 table-hover"
                                        >
                                            <thead>
                                                <tr>
                                                    @foreach ($listDate as $key => $date)
                                                        <th
                                                            class="text-uppercase text-xxs font-weight-bolder text-center col-1 <?= $key % 2 == 0 ? "bg-3" : "bg-2" ?>"
                                                            rowspan="2"
                                                        >
                                                            {{ $date }}
                                                            <br />
                                                            &nbsp;
                                                        </th>
                                                    @endforeach

                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder text-center col-1"
                                                        rowspan="2"
                                                    >
                                                        THAO TÁC
                                                        <br />
                                                        &nbsp;
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $product)
                                                    <tr>
                                                        @foreach ($listDate as $key => $date)
                                                            <?php
                                                            $timestam = strtotime($date);
                                                            $day = date('Y-m-d', $timestam);
                                                            $totalQuanDate = $product->TotalDailyQuantities()->where('status', 2)->where('date', $day)->value('totalQuan') ?? '';
                                                            ?>

                                                            <td
                                                                class="<?= $key % 2 == 0 ? "bg-3" : "bg-2" ?>"
                                                            >
                                                                <div
                                                                    class="d-flex px-3 py-1 justify-content-center"
                                                                >
                                                                    {{ number_format((float) $totalQuanDate) }}
                                                                </div>
                                                            </td>
                                                        @endforeach

                                                        <td class="text-center">
                                                            <form
                                                                action="{{ route('admin.product.delete', $product->id) }}"
                                                                method="post"
                                                            >
                                                                @method('DELETE')
                                                                @csrf
                                                                <a
                                                                    style="
                                                                        margin-bottom: 0px;
                                                                        height: 32px;
                                                                    "
                                                                    href="{{ route('admin.product.edit', $product->id) }}"
                                                                    class="btn btn-primary"
                                                                >
                                                                    Cập nhật
                                                                </a>
                                                                <button
                                                                    style="
                                                                        margin-bottom: 0px;
                                                                        height: 32px;
                                                                    "
                                                                    onclick="return confirm('Bạn có chắc muốn đưa sản phẩm này vào thùng rác không?');"
                                                                    class="btn btn-danger"
                                                                    type="submit"
                                                                >
                                                                    Xóa
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($page == 'error200')
                        <div
                            class="tab-pane fade product"
                            id="import-300"
                            role="tabpanel"
                            aria-labelledby="import-300-tab"
                        >
                            <div class="px-0 pb-2">
                                <div class="table-responsive p-0 d-flex">
                                    <div class="col-4">
                                        <table
                                            class="table align-items-center mb-0 table-hover"
                                        >
                                            <thead style="height: 51px">
                                                <tr>
                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder px-3 align-center"
                                                        rowspan="2"
                                                    >
                                                        STT
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder px-4 align-center"
                                                        rowspan="2"
                                                    >
                                                        Tên linh kiện
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder text-center col-1 align-center"
                                                        rowspan="2"
                                                    >
                                                        Tổng cộng
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $key => $product)
                                                    <tr>
                                                        <td>
                                                            <div
                                                                class="d-flex px-3 py-1"
                                                            >
                                                                {{ $loop->iteration }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div
                                                                class="d-flex justify-content-start px-3 py-1"
                                                            >
                                                                <a
                                                                    href="{{ route('admin.product.detail', $product->id) }}"
                                                                >
                                                                    {{ $product->name }}
                                                                </a>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div
                                                                class="d-flex px-3 py-1 justify-content-center"
                                                            >
                                                                {{ number_format($product->TotalMonthQuantities()->where('month', $monthNearly)->where('status', 6)->value('totalQuan') ?? 0) }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-9 product-tab">
                                        <table
                                            class="table align-items-center mb-0 table-hover"
                                        >
                                            <thead>
                                                <tr>
                                                    @foreach ($listDate as $key => $date)
                                                        <th
                                                            class="text-uppercase text-xxs font-weight-bolder text-center col-1 <?= $key % 2 == 0 ? "bg-3" : "bg-2" ?>"
                                                            rowspan="2"
                                                        >
                                                            {{ $date }}
                                                            <br />
                                                            &nbsp;
                                                        </th>
                                                    @endforeach

                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder text-center col-1"
                                                        rowspan="2"
                                                    >
                                                        THAO TÁC
                                                        <br />
                                                        &nbsp;
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $product)
                                                    <tr>
                                                        @foreach ($listDate as $key => $date)
                                                            <?php
                                                            $timestam = strtotime($date);
                                                            $day = date('Y-m-d', $timestam);
                                                            $totalQuanDate = $product->TotalDailyQuantities()->where('status', 6)->where('date', $day)->value('totalQuan') ?? '';
                                                            ?>

                                                            <td
                                                                class="<?= $key % 2 == 0 ? "bg-3" : "bg-2" ?>"
                                                            >
                                                                <div
                                                                    class="d-flex px-3 py-1 justify-content-center"
                                                                >
                                                                    {{ number_format((float) $totalQuanDate) }}
                                                                </div>
                                                            </td>
                                                        @endforeach

                                                        <td class="text-center">
                                                            <form
                                                                action="{{ route('admin.product.delete', $product->id) }}"
                                                                method="post"
                                                            >
                                                                @method('DELETE')
                                                                @csrf
                                                                <a
                                                                    style="
                                                                        margin-bottom: 0px;
                                                                        height: 32px;
                                                                    "
                                                                    href="{{ route('admin.product.edit', $product->id) }}"
                                                                    class="btn btn-primary"
                                                                >
                                                                    Cập nhật
                                                                </a>
                                                                <button
                                                                    style="
                                                                        margin-bottom: 0px;
                                                                        height: 32px;
                                                                    "
                                                                    onclick="return confirm('Bạn có chắc muốn đưa sản phẩm này vào thùng rác không?');"
                                                                    class="btn btn-danger"
                                                                    type="submit"
                                                                >
                                                                    Xóa
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($page == 'export')
                        <div
                            class="tab-pane tab-vvp fade product"
                            id="export-200"
                            role="tabpanel"
                            aria-labelledby="export-200-tab"
                        >
                            <div class="px-0 pb-2">
                                <div class="table-responsive p-0 d-flex">
                                    <div class="col-4">
                                        <table
                                            class="table align-items-center mb-0 table-hover"
                                        >
                                            <thead style="height: 52px">
                                                <tr>
                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder px-3 align-center"
                                                        rowspan="2"
                                                    >
                                                        STT
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder px-4 align-center"
                                                        rowspan="2"
                                                    >
                                                        Tên linh kiện
                                                    </th>
                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder text-center col-1 align-center"
                                                        rowspan="2"
                                                    >
                                                        Tổng cộng
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $key => $product)
                                                    <tr>
                                                        <td>
                                                            <div
                                                                class="d-flex px-3 py-1"
                                                            >
                                                                {{ $loop->iteration }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div
                                                                class="d-flex justify-content-start px-3 py-1"
                                                            >
                                                                <a
                                                                    href="{{ route('admin.product.detail', $product->id) }}"
                                                                >
                                                                    {{ $product->name }}
                                                                </a>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div
                                                                class="d-flex px-3 py-1 justify-content-center"
                                                            >
                                                                {{ number_format($product->TotalMonthQuantities()->where('month', $monthNearly)->where('status', 3)->value('totalQuan') ?? 0) }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-9 product-tab">
                                        <table
                                            class="table align-items-center mb-0 table-hover"
                                        >
                                            <thead>
                                                <tr>
                                                    @foreach ($listDate as $key => $date)
                                                        <th
                                                            class="text-uppercase text-xxs font-weight-bolder text-center col-1 <?= $key % 2 == 0 ? "bg-3" : "bg-2" ?>"
                                                            rowspan="2"
                                                        >
                                                            {{ $date }}
                                                            <br />
                                                            &nbsp;
                                                        </th>
                                                    @endforeach

                                                    <th
                                                        class="text-uppercase text-xxs font-weight-bolder text-center col-1"
                                                        rowspan="2"
                                                    >
                                                        THAO TÁC
                                                        <br />
                                                        &nbsp;
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $product)
                                                    <tr>
                                                        @foreach ($listDate as $key => $date)
                                                            <?php
                                                            $timestam = strtotime($date);
                                                            $day = date('Y-m-d', $timestam);
                                                            $totalQuanDate = $product->TotalDailyQuantities()->where('status', 3)->where('date', $day)->value('totalQuan') ?? '';
                                                            ?>

                                                            <td
                                                                class="<?= $key % 2 == 0 ? "bg-3" : "bg-2" ?>"
                                                            >
                                                                <div
                                                                    class="d-flex px-3 py-1 justify-content-center"
                                                                >
                                                                    {{ number_format((float) $totalQuanDate) }}
                                                                </div>
                                                            </td>
                                                        @endforeach

                                                        <td class="text-center">
                                                            <form
                                                                action="{{ route('admin.product.delete', $product->id) }}"
                                                                method="post"
                                                            >
                                                                @method('DELETE')
                                                                @csrf
                                                                <a
                                                                    style="
                                                                        margin-bottom: 0px;
                                                                        height: 32px;
                                                                    "
                                                                    href="{{ route('admin.product.edit', $product->id) }}"
                                                                    class="btn btn-primary"
                                                                >
                                                                    Cập nhật
                                                                </a>
                                                                <button
                                                                    style="
                                                                        margin-bottom: 0px;
                                                                        height: 32px;
                                                                    "
                                                                    onclick="return confirm('Bạn có chắc muốn đưa sản phẩm này vào thùng rác không?');"
                                                                    class="btn btn-danger"
                                                                    type="submit"
                                                                >
                                                                    Xóa
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($total == 0)
                        <div class="text-center">
                            Hiện tại chưa có Sản phẩm nào. Vui lòng
                            <a
                                class="href"
                                href="{{ route('admin.product.add') }}"
                            >
                                Thêm sản phẩm
                            </a>
                        </div>
                    @endif
                </div>
            </section>
            <div id="loader" class="d-none"></div>
        </div>
    </div>

    @foreach ($products as $key => $product)
        {{-- Modal delete --}}
        <div
            class="modal fade"
            id="deleteProduct{{ $product->id }}"
            tabindex="-1"
            aria-labelledby="deleteProduct{{ $product->id }}"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5
                            class="modal-title"
                            id="deleteProduct{{ $product->id }}"
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
                    <div class="modal-body text-center">
                        Bạn có chắc chắn muốn xóa sản phẩm này không?
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
                            action="{{ route('admin.product.delete', $product->id) }}"
                            method="post"
                        >
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                Xóa
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(
                document.querySelectorAll('[data-bs-toggle="tooltip"]'),
            );
            var tooltipList = tooltipTriggerList.map(
                function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                },
            );
        });
        $(document).ready(function () {
            var activeHome = false;
            var activeProduce = false;
            var activeCheck200 = false;
            var activeError200 = false;
            var activeExport = false;
            var url = $('#myTab').data('url');
            var page = @json($page);
            checkLocalStorage();

            function checkLocalStorage() {
                let productTab = getLocalStorage('productTab');
                if (productTab != page) {
                    productTab = page;
                    addLocalStorage(productTab);
                }
                if (productTab && productTab != null) {
                    switch (productTab) {
                        case 'product':
                            activeHome = true;
                            resetTab();
                            handleActive('product-tab', 'product');
                            break;
                        case 'produce':
                            activeProduce = true;
                            resetTab();
                            handleActive('check-100-tab', 'check-100');
                            break;
                        case 'check200':
                            activeCheck200 = true;
                            resetTab();
                            handleActive('import-200-tab', 'import-200');
                            break;
                        case 'error200':
                            activeError200 = true;
                            resetTab();
                            handleActive('import-300-tab', 'import-300');
                            break;
                        case 'export':
                            activeExport = true;
                            resetTab();
                            handleActive('export-200-tab', 'export-200');
                            break;
                    }
                }
            }

            $('#product-tab').click(function () {
                $('#loader').show();
                if (!activeHome) {
                    eventClickTab(activeHome, 'product');
                }
            });
            $('#check-100-tab').click(function () {
                if (!activeProduce) {
                    eventClickTab(activeProduce, 'produce');
                }
            });
            $('#import-200-tab').click(function () {
                if (!activeCheck200) {
                    eventClickTab(activeCheck200, 'check200');
                }
            });
            $('#import-300-tab').click(function () {
                if (!activeError200) {
                    eventClickTab(activeError200, 'error200');
                }
            });
            $('#export-200-tab').click(function () {
                if (!activeExport) {
                    eventClickTab(activeExport, 'export');
                }
            });

            function eventClickTab(tabName, key) {
                resetClick();
                tabName = true;
                addLocalStorage(key);
                $('#loader').removeClass('d-none');
                window.location.href = url + '?page=' + key;
            }

            function resetClick() {
                activeHome = false;
                activeProduce = false;
                activeCheck200 = false;
                activeError200 = false;
                activeExport = false;
            }

            function handleActive(attribute, attribute_tab) {
                var el = document.getElementById(attribute);
                if (el) {
                    if (!el.classList.contains('active')) {
                        el.classList.add('active');
                    }
                    if (!el.classList.contains('show')) {
                        el.classList.add('show');
                    }
                }

                var elTab = document.getElementById(attribute_tab);
                if (elTab) {
                    if (!elTab.classList.contains('active')) {
                        elTab.classList.add('active');
                    }
                    if (!elTab.classList.contains('show')) {
                        elTab.classList.add('show');
                    }
                }
            }

            function resetA7A() {
                var listTab = document.getElementsByClassName('product-tab');
                if (listTab && listTab.length > 0) {
                    for (let i = 0; i < listTab.length; i++) {
                        if (listTab[i].classList.contains('show')) {
                            listTab[i].classList.remove('show');
                        }
                        if (listTab[i].classList.contains('active')) {
                            listTab[i].classList.remove('active');
                        }
                    }
                }
            }

            function addLocalStorage(key) {
                localStorage.setItem('productTab', key);
            }

            function getLocalStorage(key) {
                return localStorage.getItem(key);
            }

            function resetTab() {
                var listTab = document.getElementsByClassName('product');
                if (listTab && listTab.length > 0) {
                    for (let i = 0; i < listTab.length; i++) {
                        if (listTab[i].classList.contains('show')) {
                            listTab[i].classList.remove('show');
                        }
                        if (listTab[i].classList.contains('active')) {
                            listTab[i].classList.remove('active');
                        }
                    }
                }
            }
        });
    </script>
@endsection
