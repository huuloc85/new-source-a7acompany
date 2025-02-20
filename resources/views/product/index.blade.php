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
                    @foreach ($tabProducts as $keyTab => $tabProduct)
                        @if ($page == 'product' && $keyTab == 'product')
                            <div
                                class="tab-pane fade {{ $page == $tabProduct['page'] ? 'show active' : '' }}"
                                id="{{ $keyTab }}"
                                role="tabpanel"
                                aria-labelledby="{{ $keyTab }}-tab"
                                tabindex="0"
                            >
                                <div class="d-flex">
                                    <div class="col-4 table-responsive">
                                        <table class="table table-hover">
                                            <thead
                                                class="table-light text-center align-middle text-uppercase"
                                                style="height: 5.75rem"
                                            >
                                                <tr>
                                                    <th>STT</th>
                                                    <th>Tên linh kiện</th>
                                                </tr>
                                            </thead>
                                            <tbody
                                                class="text-center align-middle"
                                            >
                                                @foreach ($products as $key => $product)
                                                    <tr style="height: 3.5rem">
                                                        <th>
                                                            {{ $loop->iteration }}
                                                        </th>
                                                        <td class="text-start">
                                                            <a
                                                                href="{{ route('admin.product.detail', $product->id) }}"
                                                            >
                                                                {{ $product->name }}
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-8 table-responsive">
                                        <table class="table table-hover">
                                            <thead
                                                class="table-light text-center align-middle text-uppercase"
                                                style="height: 5.75rem"
                                            >
                                                <tr>
                                                    <th
                                                        class="bg-body-secondary"
                                                    >
                                                        SẢN LƯỢNG
                                                        <br />
                                                        (MOQ)
                                                    </th>
                                                    <th
                                                        class="bg-body-secondary"
                                                    >
                                                        THUNG CATON/THANG
                                                        <br />
                                                        (MOQ)
                                                    </th>
                                                    <th>
                                                        Dự định
                                                        <br />
                                                        Thời gian hoạt động
                                                        thiết bị
                                                        <br />
                                                        (ngày/tháng)
                                                    </th>
                                                    <th>
                                                        Thực tế
                                                        <br />
                                                        Thời gian hoạt động
                                                        thiết bị
                                                        <br />
                                                        (ngày/tháng)
                                                    </th>
                                                    <th
                                                        class="bg-body-secondary"
                                                    >
                                                        FAPV出荷
                                                    </th>
                                                    <th
                                                        class="bg-body-secondary"
                                                    >
                                                        FASV出荷
                                                    </th>
                                                    <th
                                                        class="bg-body-secondary"
                                                    >
                                                        FAVV出荷
                                                    </th>
                                                    <th>
                                                        TỔNG SỐ LƯỢNG
                                                        <br />
                                                        TỒN ĐẦU KỲ
                                                    </th>
                                                    <th>
                                                        TỔNG THỰC TẾ
                                                        <br />
                                                        SẢN XUẤT(cái/tháng)
                                                    </th>
                                                    <th>
                                                        TỔNG SỐ LƯỢNG
                                                        <br />
                                                        ĐÃ XUẤT
                                                    </th>
                                                    <th>
                                                        SỐ LƯỢNG
                                                        <br />
                                                        ĐÃ KIỂM 200%
                                                    </th>
                                                    <th>
                                                        SỐ LƯỢNG
                                                        <br />
                                                        HÀNG CHƯA KIỂM 200%
                                                    </th>
                                                    <th>
                                                        TỔNG SỐ LƯỢNG
                                                        <br />
                                                        TỒN CUỐI KỲ
                                                    </th>
                                                    <th>
                                                        SỐ NGÀY
                                                        <br />
                                                        TỒN KHO
                                                    </th>
                                                    @foreach ($listMonthExport as $monthExport)
                                                        <th
                                                            class="bg-body-secondary"
                                                        >
                                                            SỐ LƯỢNG
                                                            <br />
                                                            ĐÃ XUẤT THÁNG
                                                            {{ $monthExport }}
                                                        </th>
                                                    @endforeach

                                                    <th>THAO TÁC</th>
                                                </tr>
                                            </thead>
                                            <tbody
                                                class="text-center align-middle"
                                            >
                                                @foreach ($products as $key => $product)
                                                    @php
                                                        $prorealityQuan =
                                                            $product
                                                                ->TotalMonthQuantities()
                                                                ->where('month', $monthNearly)
                                                                ->where('status', 1)
                                                                ->value('totalQuan') ?? 0; // tổng hàng sản xuất
                                                        $importedQuan =
                                                            $product
                                                                ->TotalMonthQuantities()
                                                                ->where('month', $monthNearly)
                                                                ->where('status', 2)
                                                                ->value('totalQuan') ?? 0; // tổng hàng kiểm 200%
                                                        $exportedQuan =
                                                            $product
                                                                ->TotalMonthQuantities()
                                                                ->where('month', $monthNearly)
                                                                ->where('status', 3)
                                                                ->value('totalQuan') ?? 0; // tổng số lượng đã xuất
                                                        $stockQuan =
                                                            $product
                                                                ->TotalMonthQuantities()
                                                                ->where('month', $monthNearly)
                                                                ->where('status', 4)
                                                                ->value('totalQuan') ?? 0; // tồn đầu kỳ
                                                        $stockQuan200 =
                                                            $product
                                                                ->TotalMonthQuantities()
                                                                ->where('month', $monthNearly)
                                                                ->where('status', 5)
                                                                ->value('totalQuan') ?? 0; // tồn đầu kỳ 200%
                                                        $errorQuantity =
                                                            $product
                                                                ->TotalMonthQuantities()
                                                                ->where('month', $monthNearly)
                                                                ->where('status', 6)
                                                                ->value('totalQuan') ?? 0; // hàng lỗi
                                                        $stockQuanMOQ =
                                                            $product
                                                                ->TotalMonthQuantities()
                                                                ->where('month', $monthNearly)
                                                                ->where('status', 7)
                                                                ->value('totalQuan') ?? 0; // MOQ

                                                        $checked200 = $stockQuan200 + $importedQuan - $exportedQuan; // đã kiểm 200%
                                                        $stockEndQuan =
                                                            $stockQuan + $prorealityQuan - $exportedQuan - $errorQuantity; // tồn cuối kỳ
                                                        $stockNoneCheck200 =
                                                            $stockQuan +
                                                            $prorealityQuan -
                                                            $exportedQuan -
                                                            $checked200 -
                                                            $errorQuantity; // số lượng hàng chưa kiểm 200%
                                                        $quantityCaTon = $stockQuanMOQ / $product->quanEntityBin;
                                                        $planTime =
                                                            (((($stockQuanMOQ / $product->CAV) * $product->cycle) /
                                                                3600 /
                                                                24) *
                                                                100) /
                                                            90;
                                                        $realTime =
                                                            (((($exportedQuan / $product->CAV) * $product->cycle) /
                                                                3600 /
                                                                24) *
                                                                100) /
                                                            90;
                                                    @endphp

                                                    <tr style="height: 3.5rem">
                                                        <td
                                                            class="bg-body-secondary"
                                                        >
                                                            {{ number_format($stockQuanMOQ) }}
                                                        </td>
                                                        <td
                                                            class="bg-body-secondary"
                                                        >
                                                            {{ number_format($quantityCaTon) }}
                                                        </td>
                                                        <td>
                                                            {{ number_format($planTime, 1) }}
                                                        </td>
                                                        <td>
                                                            {{ number_format($realTime, 1) }}
                                                        </td>
                                                        <td
                                                            class="bg-body-secondary"
                                                        >
                                                            {{ $product->FAPV == 1 ? 'O' : '' }}
                                                        </td>
                                                        <td
                                                            class="bg-body-secondary"
                                                        >
                                                            {{ $product->FASV == 1 ? 'O' : '' }}
                                                        </td>
                                                        <td
                                                            class="bg-body-secondary"
                                                        >
                                                            {{ $product->FAVV == 1 ? 'O' : '' }}
                                                        </td>
                                                        <td>
                                                            {{ number_format($stockQuan) }}
                                                        </td>
                                                        <td>
                                                            {{ number_format($prorealityQuan) }}
                                                        </td>
                                                        <td>
                                                            {{ number_format($exportedQuan) }}
                                                        </td>
                                                        <td>
                                                            {{ number_format($checked200) }}
                                                        </td>
                                                        <td>
                                                            {{ number_format($stockNoneCheck200) }}
                                                        </td>
                                                        <td>
                                                            {{ number_format($stockEndQuan) }}
                                                        </td>
                                                        <td>
                                                            {{ number_format($stockQuanMOQ != 0 ? $stockEndQuan / ($stockQuanMOQ / 24) : 0, 1) }}
                                                        </td>
                                                        @foreach ($listMonthExport as $monthExport)
                                                            <td
                                                                class="bg-body-secondary"
                                                            >
                                                                <?php
                                                                $export = $product->TotalMonthQuantities()->where('month', $monthExport)->where('status', 3)->value('totalQuan') ?? 0;
                                                                ?>

                                                                {{ number_format($export) }}
                                                            </td>
                                                        @endforeach

                                                        <td>
                                                            <a
                                                                href="{{ route('admin.product.edit', $product->id) }}"
                                                                class="btn btn-primary"
                                                            >
                                                                <i
                                                                    class="fas fa-edit"
                                                                ></i>
                                                                Cập nhật
                                                            </a>
                                                            <button
                                                                type="submit"
                                                                class="btn btn-danger"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#deleteProduct{{ $product->id }}"
                                                            >
                                                                <i
                                                                    class="fas fa-trash-alt"
                                                                ></i>
                                                                Xóa
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($page == 'produce' && $keyTab == 'check-100')
                            <div
                                class="tab-pane fade {{ $page == $tabProduct['page'] ? 'show active' : '' }}"
                                id="{{ $keyTab }}"
                                role="tabpanel"
                                aria-labelledby="{{ $keyTab }}-tab"
                                tabindex="0"
                            >
                                <div class="d-flex">
                                    <div class="col-4 table-responsive">
                                        <table class="table table-hover">
                                            <thead
                                                class="table-light text-uppercase text-center align-middle"
                                                style="height: 5.75rem"
                                            >
                                                <tr>
                                                    <th>STT</th>
                                                    <th>Tên linh kiện</th>
                                                    <th>
                                                        Tổng cộng
                                                        <br />
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody
                                                class="text-center align-middle"
                                            >
                                                @foreach ($products as $key => $product)
                                                    <tr style="height: 3.5rem">
                                                        <th>
                                                            {{ $loop->iteration }}
                                                        </th>
                                                        <td class="text-start">
                                                            <a
                                                                href="{{ route('admin.product.detail', $product->id) }}"
                                                            >
                                                                {{ $product->name }}
                                                            </a>
                                                        </td>
                                                        <td>
                                                            {{ number_format($product->TotalMonthQuantities()->where('month', $monthNearly)->where('status', 1)->value('totalQuan') ?? 0) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-8 table-responsive">
                                        <table class="table table-hover">
                                            <thead
                                                class="table-light text-uppercase text-center align-middle"
                                                style="height: 5.75rem"
                                            >
                                                <tr>
                                                    @foreach ($listDate as $key => $date)
                                                        <th
                                                            class="<?= $key % 2 == 0 ? "bg-body-secondary" : "" ?>"
                                                            colspan="2"
                                                        >
                                                            {{ $date }}
                                                        </th>
                                                    @endforeach

                                                    <th rowspan="2">
                                                        THAO TÁC
                                                    </th>
                                                </tr>
                                                <tr>
                                                    @foreach ($listDate as $key => $date)
                                                        <th
                                                            class="<?= $key % 2 == 0 ? "bg-body-secondary" : "" ?>"
                                                        >
                                                            Ca 1
                                                        </th>
                                                        <th
                                                            class="<?= $key % 2 == 0 ? "bg-body-secondary" : "" ?>"
                                                        >
                                                            Ca 2
                                                        </th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody
                                                class="text-center align-middle"
                                            >
                                                @foreach ($products as $key => $product)
                                                    <tr style="height: 3.5rem">
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

                                                            <td
                                                                class="{{ $key % 2 == 0 ? 'bg-body-secondary' : '' }}"
                                                            >
                                                                {{ number_format($totalQuanDateCa1) }}
                                                            </td>
                                                            <td
                                                                class="{{ $key % 2 == 0 ? 'bg-body-secondary' : '' }}"
                                                            >
                                                                {{ number_format($totalQuanDateCa2) }}
                                                            </td>
                                                        @endforeach

                                                        <td>
                                                            <a
                                                                href="{{ route('admin.product.edit', $product->id) }}"
                                                                class="btn btn-primary"
                                                            >
                                                                <i
                                                                    class="fas fa-edit"
                                                                ></i>
                                                                Cập nhật
                                                            </a>
                                                            <button
                                                                type="submit"
                                                                class="btn btn-danger"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#deleteProduct{{ $product->id }}"
                                                            >
                                                                <i
                                                                    class="fas fa-trash-alt"
                                                                ></i>
                                                                Xóa
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($page == $tabProduct['page']
                             && ($keyTab == 'import-200'
                             || $keyTab == 'import-300'
                             || $keyTab == 'export-200'))
                            <div
                                class="tab-pane fade {{ $page == $tabProduct['page'] ? 'show active' : '' }}"
                                id="{{ $keyTab }}"
                                role="tabpanel"
                                aria-labelledby="{{ $keyTab }}-tab"
                                tabindex="0"
                            >
                                <div class="d-flex">
                                    <div class="col-4 table-responsive">
                                        <table class="table table-hover">
                                            <thead
                                                class="table-light text-uppercase text-center align-middle"
                                                style="height: 4.5rem"
                                            >
                                                <tr>
                                                    <th>STT</th>
                                                    <th>Tên linh kiện</th>
                                                    @if ($keyTab == 'import-200')
                                                        <th>
                                                            Tồn đầu kỳ
                                                            <br />
                                                            hàng 200%
                                                        </th>
                                                        <th>
                                                            Phát sinh
                                                            <br />
                                                            kiểm hàng 200%
                                                        </th>
                                                    @else
                                                        <th>Tổng cộng</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody
                                                class="text-center align-middle"
                                            >
                                                @foreach ($products as $product)
                                                    <tr style="height: 3.5rem">
                                                        <th>
                                                            {{ $loop->iteration }}
                                                        </th>
                                                        <td class="text-start">
                                                            <a
                                                                href="{{ route('admin.product.detail', $product->id) }}"
                                                            >
                                                                {{ $product->name }}
                                                            </a>
                                                        </td>

                                                        @if ($keyTab == 'import-200')
                                                            <td>
                                                                {{ number_format($product->TotalMonthQuantities()->where('month', $monthNearly)->where('status', 5)->value('totalQuan') ?? 0) }}
                                                            </td>
                                                            <td>
                                                                {{ number_format($product->TotalMonthQuantities()->where('month', $monthNearly)->where('status', $tabProduct['status'])->value('totalQuan') ?? 0) }}
                                                            </td>
                                                        @else
                                                            <td>
                                                                {{ number_format($product->TotalMonthQuantities()->where('month', $monthNearly)->where('status', $tabProduct['status'])->value('totalQuan') ?? 0) }}
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-8 table-responsive">
                                        <table class="table table-hover">
                                            <thead
                                                class="table-light text-uppercase text-center align-middle"
                                                style="height: 4.5rem"
                                            >
                                                <tr>
                                                    @foreach ($listDate as $key => $date)
                                                        <th
                                                            class="<?= $key % 2 == 0 ? "bg-body-secondary" : "" ?>"
                                                        >
                                                            {{ $date }}
                                                        </th>
                                                    @endforeach

                                                    <th>THAO TÁC</th>
                                                </tr>
                                            </thead>
                                            <tbody
                                                class="text-center align-middle"
                                            >
                                                @foreach ($products as $product)
                                                    <tr style="height: 3.5rem">
                                                        @foreach ($listDate as $key => $date)
                                                            @php
                                                                $timestam = strtotime($date);
                                                                $day = date('Y-m-d', $timestam);
                                                                $totalQuanDate =
                                                                    $product
                                                                        ->TotalDailyQuantities()
                                                                        ->where('status', $tabProduct['status'])
                                                                        ->where('date', $day)
                                                                        ->value('totalQuan') ?? '';
                                                            @endphp

                                                            <td
                                                                class="<?= $key % 2 == 0 ? "bg-body-secondary" : "" ?>"
                                                            >
                                                                {{ number_format((float) $totalQuanDate) }}
                                                            </td>
                                                        @endforeach

                                                        <td>
                                                            <a
                                                                href="{{ route('admin.product.edit', $product->id) }}"
                                                                class="btn btn-primary"
                                                            >
                                                                <i
                                                                    class="fas fa-edit"
                                                                ></i>
                                                                Cập nhật
                                                            </a>
                                                            <button
                                                                type="submit"
                                                                class="btn btn-danger"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#deleteProduct{{ $product->id }}"
                                                            >
                                                                <i
                                                                    class="fas fa-trash-alt"
                                                                ></i>
                                                                Xóa
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    @if ($total == 0)
                        <div class="text-center">
                            Hiện tại chưa có Sản phẩm nào.
                            <a
                                class="href"
                                href="{{ route('admin.product.add') }}"
                            >
                                Vui lòng thêm sản phẩm
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

{{--
    disable script
    -- remove letter "X" to enable script
--}}
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
