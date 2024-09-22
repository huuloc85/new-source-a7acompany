@extends('master')
@section('content')
    <style>
        .form-control {
            border: 2px solid #d2d6da !important;
            padding-left: 10px;
        }

        .active>.page-link {
            color: white !important
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
            background-color: #ebebeb !important
        }

        .bg-3 {
            background-color: #919cc9 !important
        }

        .bg-4 {
            background-color: #d7a95f !important
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
    </style>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Danh Sách Sản Phẩm</h4>
                    </div>
                </div>
                <div id="button-list" class="p-4 pb-0 d-flex">
                    <div class="btn-group">
                        <a href="{{ route('admin.product.add') }}" class="btn btn-primary me-2 rounded" title="Thêm Sản Phẩm"
                            data-bs-toggle="tooltip">
                            <i class="fas fa-plus"></i>
                        </a>
                        <a href="{{ route('admin.product.update-moq') }}" class="btn btn-primary me-2 rounded"
                            title="Thêm Sản Lượng MOQ Tồn Đầu Kỳ Tồn 200%" data-bs-toggle="tooltip">
                            <i class="fas fa-box"></i>
                        </a>
                        <a href="{{ route('admin.product.add-quantity-admin') }}" class="btn btn-primary me-2 rounded"
                            title="Thêm Sản Lượng Sản Xuất" data-bs-toggle="tooltip">
                            <i class="fas fa-industry"></i>
                        </a>
                        <a href="{{ route('admin.product.getTrash') }}" class="btn btn-warning trash me-2 rounded"
                            title="Thùng Rác" data-bs-toggle="tooltip">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                    <div style="flex-grow:2; display: flex; justify-content: end;">
                        <div style="display:flex">
                            <form action="{{ route('admin.export.product') }}" method="get" style="display:flex">
                                @csrf
                                <select class="form-control" style=" margin-right:10px" name="month">
                                    @foreach ($listMonth as $month)
                                        <option <?= $month == $monthNearly ? 'selected' : '' ?> value="{{ $month }}">
                                            {{ $month }}
                                        </option>
                                    @endforeach
                                </select>
                                <button style="margin-right: 10px; width:155px" type="submit" class='btn btn-success'
                                    title="Xuất Excel" data-bs-toggle="tooltip">
                                    <i class="fas fa-file-export"></i>
                                </button>
                            </form>
                            <button type="button" class='btn btn-primary' data-bs-toggle="modal"
                                data-bs-target="#searchModal" title="Tìm kiếm" data-bs-toggle="tooltip">
                                <i class="fas fa-search"></i>
                            </button>
                            @include('product.search-advand', ['href' => 'admin.product.home'])
                        </div>
                    </div>
                </div>

                <div class="p-4 pb-0 pt-2 d-flex group-filter">
                    <div class="ps-2 d-flex mt-0">
                        Tổng Sản Phẩm: {{ count($products) }}
                    </div>
                    <div class="d-flex justify-content-end" style="flex-grow:1;">
                        <div class="d-flex">
                            <form action="" method="get" class="d-flex" id="searchForm">
                                @csrf
                                <div class="form-group me-2">
                                    <select class="form-select" name="month" id="monthSelect"
                                        onchange="this.form.submit()">
                                        @foreach ($listMonth as $month)
                                            <option {{ $month == $monthNearly ? 'selected' : '' }}
                                                value="{{ $month }}">
                                                {{ $month }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-select" name="orderBy" id="orderBySelect"
                                        onchange="this.form.submit()">
                                        <option value="asc" {{ request('orderBy') == 'asc' ? 'selected' : '' }}>Đầu Tiên
                                        </option>
                                        <option value="desc" {{ request('orderBy') == 'desc' ? 'selected' : '' }}>Cuối
                                            Cùng</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="px-0 pb-2">
                    <ul class="nav nav-tabs px-4" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link tab-vvp product active show" id="product-tab" data-bs-toggle="tab"
                                data-bs-target="#product" type="button" role="tab" aria-controls="product"
                                aria-selected="true">TỔNG QUAN</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link tab-vvp product" id="check-100-tab" data-bs-toggle="tab"
                                data-bs-target="#check-100" type="button" role="tab" aria-controls="check-100"
                                aria-selected="false">HÀNG SẢN XUẤT</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link tab-vvp product" id="import-200-tab" data-bs-toggle="tab"
                                data-bs-target="#import-200" type="button" role="tab" aria-controls="import-200"
                                aria-selected="false">HÀNG KIỂM 200%</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link tab-vvp product" id="import-300-tab" data-bs-toggle="tab"
                                data-bs-target="#import-300" type="button" role="tab" aria-controls="import-300"
                                aria-selected="false">HÀNG LỖI (200%)</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link tab-vvp product" id="export-200-tab" data-bs-toggle="tab"
                                data-bs-target="#export-200" type="button" role="tab" aria-controls="export-200"
                                aria-selected="false">XUẤT HÀNG</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">

                        <div class="tab-pane tab-vvp fade show active product" id="product" role="tabpanel"
                            aria-labelledby="product-tab">
                            <div class="px-0 pb-2">
                                <div class="table-responsive p-0 d-flex">
                                    <div class="col-3">
                                        <table class="table align-items-center mb-0 table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-3"
                                                        rowspan="2">&nbsp;<br>STT<br>&nbsp;</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-4"
                                                        rowspan="2">&nbsp;<br>Tên linh kiện<br>&nbsp;</th>
                                                    {{-- <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                                    rowspan="2">&nbsp;<br>Tên linh kiện<br>&nbsp;</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $key => $product)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ $loop->iteration }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                <a
                                                                    href="{{ route('admin.product.detail', $product->id) }}">{{ $product->name }}</a>
                                                            </div>
                                                        </td>
                                                        {{-- <td>
                                                        <div class="d-flex px-3 py-1">
                                                            {{ $product->name }}
                                </div>
                                </td> --}}
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-10 product-tab">
                                        <table class="table align-items-center mb-0 table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1 bg-3"
                                                        rowspan="2">SẢN LƯỢNG<br>(MOQ)</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1 bg-3"
                                                        rowspan="2">THUNG CATON/THANG<br>(MOQ)</th>
                                                    {{-- <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1 bg-4"
                                                    rowspan="2">Kích thước<br> khuôn</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1 bg-4"
                                                    rowspan="2">Số CAV<br>(cái/ shot)</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1 bg-4"
                                                    rowspan="2">Chu kì<br>(s/shot)<br></th> --}}
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1 bg-2"
                                                        rowspan="2">Dự định <br>Thời gian hoạt động thiết
                                                        bị<br>(ngày/tháng)</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1 bg-2"
                                                        rowspan="2">Thực tế <br>Thời gian hoạt động thiết
                                                        bị<br>(ngày/tháng)</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1 bg-3"
                                                        rowspan="2">FAPV出荷</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1 bg-3"
                                                        rowspan="2">FASV出荷</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1 bg-3"
                                                        rowspan="2">FAVV出荷</th>
                                                    {{-- @foreach ($models as $key => $model)
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1 bg-4"
                                                        rowspan="2">{{ $model }}</th>
                                            @endforeach --}}
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1 bg-2"
                                                        rowspan="2">TỔNG SỐ LƯỢNG<br>TỒN ĐẦU KỲ</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1 bg-2"
                                                        rowspan="2">TỔNG THỰC TẾ<br>SẢN XUẤT(cái/tháng)</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1 bg-2"
                                                        rowspan="2">TỔNG SỐ LƯỢNG<br>ĐÃ XUẤT</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1 bg-2"
                                                        rowspan="2">SỐ LƯỢNG<br>ĐÃ KIỂM 200%</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1 bg-2"
                                                        rowspan="2">SỐ LƯỢNG<br>HÀNG CHƯA KIỂM 200%</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1 bg-2"
                                                        rowspan="2">TỔNG SỐ LƯỢNG<br>TỒN CUỐI KỲ</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1 bg-2"
                                                        rowspan="2">SỐ NGÀY<br>TỒN KHO</th>
                                                    @foreach ($listMonthExport as $monthExport)
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1 bg-3"
                                                            rowspan="2">SỐ LƯỢNG<br>ĐÃ XUẤT THÁNG
                                                            {{ $monthExport }}
                                                        </th>
                                                    @endforeach
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1"
                                                        rowspan="2">THAO TÁC</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $key => $product)
                                                    <?php
                                                    $prorealityQuan = $product->TotalMonthQuantities()->where('month', $monthNearly)->where('status', 1)->value('totalQuan') ?? 0; //tổng hàng sản xuất
                                                    $importedQuan = $product->TotalMonthQuantities()->where('month', $monthNearly)->where('status', 2)->value('totalQuan') ?? 0; //tổng hàng kiểm 200%
                                                    $exportedQuan = $product->TotalMonthQuantities()->where('month', $monthNearly)->where('status', 3)->value('totalQuan') ?? 0; //tổng số lượng đã xuất
                                                    $stockQuan = $product->TotalMonthQuantities()->where('month', $monthNearly)->where('status', 4)->value('totalQuan') ?? 0; //tồn đầu kỳ
                                                    $stockQuan200 = $product->TotalMonthQuantities()->where('month', $monthNearly)->where('status', 5)->value('totalQuan') ?? 0; //tồn đầu kỳ 200%
                                                    $errorQuantity = $product->TotalMonthQuantities()->where('month', $monthNearly)->where('status', 6)->value('totalQuan') ?? 0; //hàng lỗi
                                                    $stockQuanMOQ = $product->TotalMonthQuantities()->where('month', $monthNearly)->where('status', 7)->value('totalQuan') ?? 0; // MOQ
                                                    
                                                    $checked200 = $stockQuan200 + $importedQuan - $exportedQuan; //đã kiểm 200%
                                                    $stockEndQuan = $stockQuan + $prorealityQuan - $exportedQuan - $errorQuantity; //tồn cuối kỳ
                                                    $stockNoneCheck200 = $stockQuan + $prorealityQuan - $exportedQuan - $checked200 - $errorQuantity; //số lượng hàng chưa kiểm 200%
                                                    $quantityCaTon = $stockQuanMOQ / $product->quanEntityBin;
                                                    $planTime = (((($stockQuanMOQ / $product->CAV) * $product->cycle) / 3600 / 24) * 100) / 90;
                                                    $realTime = (((($exportedQuan / $product->CAV) * $product->cycle) / 3600 / 24) * 100) / 90;
                                                    ?>
                                                    <tr>
                                                        <td class="text-center bg-3">
                                                            <div class="d-flex px-3 py-1 justify-content-center">
                                                                {{ number_format($stockQuanMOQ) }}
                                                            </div>
                                                        </td>
                                                        <td class="text-center bg-3">
                                                            <div class="d-flex px-3 py-1 justify-content-center">
                                                                {{ number_format($quantityCaTon) }}
                                                            </div>
                                                        </td>
                                                        {{-- <td class="text-center bg-4">
                                                        <div class="d-flex px-3 py-1 justify-content-center">
                                                            {{ $product->moldSize }}
                            </div>
                            </td>
                            <td class="text-center bg-4">
                                <div class="d-flex px-3 py-1 justify-content-center">
                                    {{ $product->CAV }}
                                </div>
                            </td>
                            <td class="text-center bg-4">
                                <div class="d-flex px-3 py-1 justify-content-center">
                                    {{ $product->cycle }}
                                </div>
                            </td> --}}
                                                        <td class="text-center bg-2">
                                                            <div class="d-flex px-3 py-1 justify-content-center">
                                                                {{ number_format($planTime, 1) }}
                                                            </div>
                                                        </td>
                                                        <td class="text-center bg-2">
                                                            <div class="d-flex px-3 py-1 justify-content-center">
                                                                {{ number_format($realTime, 1) }}
                                                            </div>
                                                        </td>
                                                        <td class="text-center bg-3">
                                                            <div class="d-flex px-3 py-1 justify-content-center">
                                                                {{ $product->FAPV == 1 ? 'O' : '' }}
                                                            </div>
                                                        </td>
                                                        <td class="text-center bg-3">
                                                            <div class="d-flex px-3 py-1 justify-content-center">
                                                                {{ $product->FASV == 1 ? 'O' : '' }}
                                                            </div>
                                                        </td>
                                                        <td class="text-center bg-3">
                                                            <div class="d-flex px-3 py-1 justify-content-center">
                                                                {{ $product->FAVV == 1 ? 'O' : '' }}
                                                            </div>
                                                        </td>
                                                        {{-- @foreach ($models as $key => $model)
                                                        <td class="text-center bg-4">
                                                            <div class="d-flex px-3 py-1 justify-content-center">
                                                                {{ $product->binCode == $model ? $product->quanEntityBin : '' }}
                        </div>
                        </td>
                        @endforeach --}}
                                                        <td class="text-center bg-2">
                                                            <div class="d-flex px-3 py-1 justify-content-center">
                                                                {{ number_format($stockQuan) }}
                                                            </div>
                                                        </td>

                                                        <td class="text-center bg-2">
                                                            <div class="d-flex px-3 py-1 justify-content-center">
                                                                {{ number_format($prorealityQuan) }}
                                                            </div>
                                                        </td>

                                                        <td class="text-center bg-2">
                                                            <div class="d-flex px-3 py-1 justify-content-center">
                                                                {{ number_format($exportedQuan) }}
                                                            </div>
                                                        </td>

                                                        <td class="text-center bg-2">
                                                            <div class="d-flex px-3 py-1 justify-content-center">
                                                                {{ number_format($checked200) }}
                                                            </div>
                                                        </td>

                                                        <td class="text-center bg-2">
                                                            <div class="d-flex px-3 py-1 justify-content-center">
                                                                {{ number_format($stockNoneCheck200) }}
                                                            </div>
                                                        </td>

                                                        <td class="text-center bg-2">
                                                            <div class="d-flex px-3 py-1 justify-content-center">
                                                                {{ number_format($stockEndQuan) }}
                                                            </div>
                                                        </td>

                                                        <td class="text-center bg-2">
                                                            <div class="d-flex px-3 py-1 justify-content-center">
                                                                {{ number_format($stockQuanMOQ != 0 ? $stockEndQuan / ($stockQuanMOQ / 24) : 0, 1) }}
                                                            </div>
                                                        </td>

                                                        @foreach ($listMonthExport as $monthExport)
                                                            <td class="text-center bg-3">
                                                                <div class="d-flex px-3 py-1 justify-content-center">
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
                                                                method="post">
                                                                @method('DELETE')
                                                                @csrf
                                                                <a style="margin-bottom: 0px; height:32px"
                                                                    href="{{ route('admin.product.edit', $product->id) }}"
                                                                    class="btn btn-primary">Cập nhật</a>
                                                                <button style="margin-bottom: 0px; height:32px"
                                                                    onclick="return confirm('Bạn có chắc muốn đưa sản phẩm này vào thùng rác không?');"
                                                                    class='btn btn-danger' type="submit">Xóa</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            {{-- <div style="display: flex; justify-content: center; align-items: center; margin:20px">
                            <div>
                                {{ $products->appends(request()->all())->links() }}
        </div>
    </div> --}}
                        </div>

                        <div class="tab-pane tab-vvp fade product" id="check-100" role="tabpanel"
                            aria-labelledby="check-100-tab">
                            <div class="px-0 pb-2">
                                <div class="table-responsive p-0 d-flex">
                                    <div class="col-4 my-4">
                                        <table class="table align-items-center mb-0 table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xxs fw-bold opacity-7 px-3 align-middle"
                                                        rowspan="2">&nbsp;STT<br>&nbsp;</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-4"
                                                        rowspan="2">Tên linh kiện<br>&nbsp;</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center text-center"
                                                        rowspan="2">Tổng cộng<br>&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $key => $product)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ $loop->iteration }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                <a
                                                                    href="{{ route('admin.product.detail', $product->id) }}">{{ $product->name }}</a>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex px-3 py-1 justify-content-center">
                                                                {{ number_format($product->TotalMonthQuantities()->where('month', $monthNearly)->where('status', 1)->value('totalQuan') ?? 0) }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-9 product-tab">
                                        <table class="table align-items-center mb-0 table-hover">
                                            <thead>
                                                <tr>
                                                    @foreach ($listDate as $key => $date)
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center <?= $key % 2 == 0 ? 'bg-3' : 'bg-2' ?>"
                                                            colspan="2">{{ $date }}</th>
                                                    @endforeach
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1"
                                                        rowspan="2">THAO TÁC</th>
                                                </tr>
                                                <tr>
                                                    @foreach ($listDate as $key => $date)
                                                        <th
                                                            class="text-uppercase text-center text-xxs <?= $key % 2 == 0 ? 'bg-3' : 'bg-2' ?>">
                                                            Ca 1</th>
                                                        <th
                                                            class=" text-uppercase text-center text-xxs <?= $key % 2 == 0 ? 'bg-3' : 'bg-2' ?>">
                                                            Ca 2</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            @foreach ($products as $product)
                                                <tr>
                                                    @foreach ($listDate as $key => $date)
                                                        @php
                                                            // Chuyển đổi date được cung cấp sang định dạng Carbon để so sánh
                                                            $formattedDate = Carbon\Carbon::parse($date)->startOfDay();
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
                                                        <td class="{{ $key % 2 == 0 ? 'bg-3' : 'bg-2' }}">
                                                            <div class="d-flex px-3 py-1 justify-content-center">
                                                                {{ number_format($totalQuanDateCa1) }}
                                                            </div>
                                                        </td>
                                                        <td class="{{ $key % 2 == 0 ? 'bg-3' : 'bg-2' }}">
                                                            <div class="d-flex px-3 py-1 justify-content-center">
                                                                {{ number_format($totalQuanDateCa2) }}
                                                            </div>
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach

                                        </table>
                                    </div>
                                </div>
                            </div>
                            {{-- <div style="display: flex; justify-content: center; align-items: center; margin:20px">
                            <div>
                                {{ $products->appends(request()->all())->links() }}
</div>
</div> --}}
                        </div>

                        <div class="tab-pane tab-vvp fade product" id="import-200" role="tabpanel"
                            aria-labelledby="import-200-tab">
                            <div class="px-0 pb-2">
                                <div class="table-responsive p-0 d-flex">
                                    <div class="col-5">
                                        <table class="table align-items-center mb-0 table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                                        rowspan="2">STT</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-4"
                                                        rowspan="2">Tên Linh Kiện</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1"
                                                        rowspan="2">Tồn đầu kỳ<br>hàng 200%</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1"
                                                        rowspan="2">Phát sinh<br>kiểm hàng 200%</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $key => $product)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex px-3 py-1 justify-content-center">
                                                                {{ $loop->iteration }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex justify-content-start px-3 py-1">
                                                                <a
                                                                    href="{{ route('admin.product.detail', $product->id) }}">{{ $product->name }}</a>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class="d-flex px-3 py-1 justify-content-center">
                                                                {{ number_format($product->TotalMonthQuantities()->where('month', $monthNearly)->where('status', 5)->value('totalQuan') ?? 0) }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex px-3 py-1 justify-content-center">
                                                                {{ number_format($product->TotalMonthQuantities()->where('month', $monthNearly)->where('status', 2)->value('totalQuan') ?? 0) }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-7 product-tab-2">
                                        <table class="table align-items-center mb-0 table-hover">
                                            <thead>
                                                <tr>
                                                    @foreach ($listDate as $key => $date)
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1 <?= $key % 2 == 0 ? 'bg-3' : 'bg-2' ?>"
                                                            rowspan="2">{{ $date }}<br>&nbsp;</th>
                                                    @endforeach
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1"
                                                        rowspan="2">THAO TÁC<br>&nbsp;</th>
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
                                                            <td class="<?= $key % 2 == 0 ? 'bg-3' : 'bg-2' ?>">
                                                                <div class="d-flex px-3 py-1 justify-content-center">
                                                                    {{ number_format((float) $totalQuanDate) }}
                                                                </div>
                                                            </td>
                                                        @endforeach
                                                        <td class="text-center">
                                                            <form
                                                                action="{{ route('admin.product.delete', $product->id) }}"
                                                                method="post">
                                                                @method('DELETE')
                                                                @csrf
                                                                <a style="margin-bottom: 0px; height:32px"
                                                                    href="{{ route('admin.product.edit', $product->id) }}"
                                                                    class="btn btn-primary">Cập nhật</a>
                                                                <button style="margin-bottom: 0px; height:32px"
                                                                    onclick="return confirm('Bạn có chắc muốn đưa sản phẩm này vào thùng rác không?');"
                                                                    class='btn btn-danger' type="submit">Xóa</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            {{-- <div style="display: flex; justify-content: center; align-items: center; margin:20px">
                            <div>
                                {{ $products->appends(request()->all())->links() }}
</div>
</div> --}}
                        </div>

                        <div class="tab-pane fade product" id="import-300" role="tabpanel"
                            aria-labelledby="import-300-tab">
                            <div class="px-0 pb-2">
                                <div class="table-responsive p-0 d-flex">
                                    <div class="col-4">
                                        <table class="table align-items-center mb-0 table-hover">
                                            <thead style="height: 51px">
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-3 align-center"
                                                        rowspan="2">STT</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-4 align-center"
                                                        rowspan="2">Tên linh kiện</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1 align-center"
                                                        rowspan="2">Tổng cộng</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $key => $product)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ $loop->iteration }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex justify-content-start px-3 py-1">
                                                                <a
                                                                    href="{{ route('admin.product.detail', $product->id) }}">{{ $product->name }}</a>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class="d-flex px-3 py-1 justify-content-center">
                                                                {{ number_format($product->TotalMonthQuantities()->where('month', $monthNearly)->where('status', 6)->value('totalQuan') ?? 0) }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-9 product-tab">
                                        <table class="table align-items-center mb-0 table-hover">
                                            <thead>
                                                <tr>
                                                    @foreach ($listDate as $key => $date)
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1 <?= $key % 2 == 0 ? 'bg-3' : 'bg-2' ?>"
                                                            rowspan="2">{{ $date }}<br>&nbsp;</th>
                                                    @endforeach
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1"
                                                        rowspan="2">THAO TÁC<br>&nbsp;</th>
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
                                                            <td class="<?= $key % 2 == 0 ? 'bg-3' : 'bg-2' ?>">
                                                                <div class="d-flex px-3 py-1 justify-content-center">
                                                                    {{ number_format((float) $totalQuanDate) }}
                                                                </div>
                                                            </td>
                                                        @endforeach
                                                        <td class="text-center">
                                                            <form
                                                                action="{{ route('admin.product.delete', $product->id) }}"
                                                                method="post">
                                                                @method('DELETE')
                                                                @csrf
                                                                <a style="margin-bottom: 0px; height:32px"
                                                                    href="{{ route('admin.product.edit', $product->id) }}"
                                                                    class="btn btn-primary">Cập nhật</a>
                                                                <button style="margin-bottom: 0px; height:32px"
                                                                    onclick="return confirm('Bạn có chắc muốn đưa sản phẩm này vào thùng rác không?');"
                                                                    class='btn btn-danger' type="submit">Xóa</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            {{-- <div style="display: flex; justify-content: center; align-items: center; margin:20px">
                            <div>
                                {{ $products->appends(request()->all())->links() }}
</div>
</div> --}}
                        </div>

                        <div class="tab-pane tab-vvp fade product" id="export-200" role="tabpanel"
                            aria-labelledby="export-200-tab">
                            <div class="px-0 pb-2">
                                <div class="table-responsive p-0 d-flex">
                                    <div class="col-4">
                                        <table class="table align-items-center mb-0 table-hover">
                                            <thead style="height: 52px">
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-3 align-center"
                                                        rowspan="2">STT</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-4 align-center"
                                                        rowspan="2">Tên linh kiện</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1 align-center"
                                                        rowspan="2">Tổng cộng</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $key => $product)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex px-3 py-1">
                                                                {{ $loop->iteration }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex justify-content-start px-3 py-1">
                                                                <a
                                                                    href="{{ route('admin.product.detail', $product->id) }}">{{ $product->name }}</a>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class="d-flex px-3 py-1 justify-content-center">
                                                                {{ number_format($product->TotalMonthQuantities()->where('month', $monthNearly)->where('status', 3)->value('totalQuan') ?? 0) }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-9 product-tab">
                                        <table class="table align-items-center mb-0 table-hover">
                                            <thead>
                                                <tr>
                                                    @foreach ($listDate as $key => $date)
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1 <?= $key % 2 == 0 ? 'bg-3' : 'bg-2' ?>"
                                                            rowspan="2">{{ $date }}<br>&nbsp;</th>
                                                    @endforeach
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center col-1"
                                                        rowspan="2">THAO TÁC<br>&nbsp;</th>
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
                                                            <td class="<?= $key % 2 == 0 ? 'bg-3' : 'bg-2' ?>">
                                                                <div class="d-flex px-3 py-1 justify-content-center">
                                                                    {{ number_format((float) $totalQuanDate) }}
                                                                </div>
                                                            </td>
                                                        @endforeach
                                                        <td class="text-center">
                                                            <form
                                                                action="{{ route('admin.product.delete', $product->id) }}"
                                                                method="post">
                                                                @method('DELETE')
                                                                @csrf
                                                                <a style="margin-bottom: 0px; height:32px"
                                                                    href="{{ route('admin.product.edit', $product->id) }}"
                                                                    class="btn btn-primary">Cập nhật</a>
                                                                <button style="margin-bottom: 0px; height:32px"
                                                                    onclick="return confirm('Bạn có chắc muốn đưa sản phẩm này vào thùng rác không?');"
                                                                    class='btn btn-danger' type="submit">Xóa</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            {{-- <div style="display: flex; justify-content: center; align-items: center; margin:20px">
                            <div>
                                {{ $products->appends(request()->all())->links() }}
</div>
</div> --}}
                        </div>

                        @if ($total == 0)
                            <div class="text-center">
                                Hiện tại chưa có Sản phẩm nào. Vui lòng
                                <a class="href" href="{{ route('admin.product.add') }}">Thêm sản phẩm</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var activeHome = false;
        var activeProduce = false;
        var activeCheck200 = false;
        var activeExport = false;
        checkLocalStorage();

        function checkLocalStorage() {
            let productTab = getLocalStorage('productTab');
            if (productTab && productTab != null) {
                switch (productTab) {
                    case "product":
                        activeHome = true;
                        resetTab();
                        handleActive('product-tab', 'product');
                        break;
                    case "produce":
                        activeProduce = true;
                        resetTab();
                        handleActive('check-100-tab', 'check-100');
                        break;
                    case "check200":
                        activeCheck200 = true;
                        resetTab();
                        handleActive('import-200-tab', 'import-200');
                        break;
                    case "export":
                        activeExport = true;
                        resetTab();
                        handleActive('export-200-tab', 'export-200');
                        break;
                }
            }
        }

        $('#product-tab').click(function() {
            resetClick();
            activeHome = true;
            addLocalStorage('product');
            console.log(1);
        });
        $('#check-100-tab').click(function() {
            resetClick();
            activeProduce = true;
            addLocalStorage('produce');
        });
        $('#import-200-tab').click(function() {
            resetClick();
            activeCheck200 = true;
            addLocalStorage('check200');
        });
        $('#export-200-tab').click(function() {
            resetClick();
            activeExport = true;
            addLocalStorage('export');
        });

        function resetClick() {
            activeHome = false;
            activeProduce = false;
            activeCheck200 = false;
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
        };

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
        };

        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endsection
