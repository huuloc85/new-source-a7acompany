@extends('master')
@section('content')
    <style>
        .form-control {
            border: 1px solid #d2d6da !important;
            padding-left: 10px;
        }

        .active>.page-link {
            color: white !important
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

        #searchModal .modal-content {
            border-radius: 15px;
        }

        #testModal .modal-content {
            border-radius: 15px;
        }

        #stockquantityModal .modal-content {
            border-radius: 15px;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: hidden;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
            padding: 8px;
            border: 1px solid #ddd;
        }

        .table th {
            background-color: #f4f4f4;
            text-align: center;
        }

        .table td {
            text-align: center;
        }

        .table td[title] {
            position: relative;
        }

        .table td[title]::after {
            content: attr(title);
            position: absolute;
            left: 0;
            bottom: 100%;
            background-color: #333;
            color: #fff;
            padding: 5px;
            border-radius: 4px;
            white-space: nowrap;
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 12px;
            z-index: 1000;
            transform: translateY(-5px);
        }

        .table td[title]:hover::after {
            visibility: visible;
            opacity: 1;
        }

        @media screen and (max-width: 768px) {
            .tab-pane .container-fluid {
                width: 100%;
                padding: 0;
                /* Loại bỏ padding nếu có */
            }

            .tab-pane .col-10,
            .tab-pane .col-2,
            .tab-pane .col-4,
            .tab-pane .col-8 {
                width: 100%;
                /* Đặt cột chiếm toàn bộ chiều rộng */
                flex: 1 1 auto;
                /* Cột sẽ điều chỉnh kích thước tự động */
            }

            .tab-pane .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .tab-pane .table-responsive .table {
                min-width: 100%;
                /* Điều chỉnh để phù hợp với chiều rộng của bảng */
            }

            .tab-pane .table-responsive .col-2 {
                width: 150px;
                /* Điều chỉnh kích thước cột */
            }

            .tab-pane .col-4,
            .tab-pane .col-8 {
                width: 100%;
                /* Đảm bảo các cột có kích thước hợp lý trên thiết bị nhỏ */
            }

            .tab-pane table th,
            .tab-pane table td {
                width: auto;
                /* Đảm bảo ô bảng tự động điều chỉnh kích thước */
            }

            .table-wrapper {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                /* Đảm bảo cuộn mượt trên thiết bị cảm ứng */
                padding-bottom: 1rem;
                /* Tùy chọn để thêm khoảng cách */
            }

            .table {
                min-width: 1000px;
                /* Điều chỉnh tùy theo nội dung bảng */
            }



        }
    </style>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Danh Sách PO</h4>
                    </div>
                </div>
                <div style="display: flex; align-items: center; padding-left: 10px; margin-top: 20px;" class="my-3">
                    {{-- Thêm Sản Lượng --}}
                    <div style="margin-right: 10px;">
                        <button type="button" class='btn btn-primary tooltip-btn' data-bs-toggle="modal"
                            data-bs-target="#searchModal" data-toggle="tooltip" title="Thêm Sản Lượng">
                            <i class="fas fa-plus"></i>
                        </button>
                        @include('checkpo.add-po-export', ['href' => 'admin.checkpo.index'])
                    </div>

                    {{-- Thêm PO Xuất Hàng --}}
                    <div style="margin-right: 10px;">
                        <button type="button" class='btn btn-primary tooltip-btn' data-bs-toggle="modal"
                            data-bs-target="#testModal" data-toggle="tooltip" title="Thêm PO Xuất Hàng">
                            <i class="fas fa-truck"></i>
                        </button>
                        @include('checkpo.add-po-import', ['href' => 'admin.checkpo.index'])
                    </div>

                    {{-- Thêm Tồn Đầu Kỳ --}}
                    <div style="margin-right: 10px;">
                        <button type="button" class='btn btn-primary tooltip-btn' data-bs-toggle="modal"
                            data-bs-target="#stockquantityModal" data-toggle="tooltip" title="Thêm Tồn Đầu Kỳ">
                            <i class="fas fa-warehouse"></i>
                        </button>
                        @include('checkpo.add-stock-quantity-inventory', [
                            'href' => 'admin.checkpo.index',
                        ])
                    </div>

                    {{-- Export --}}
                    <form action="{{ route('admin.checkpo.export') }}" method="GET" style="margin-right: 10px;">
                        <button class='btn btn-success tooltip-btn' type="submit" data-toggle="tooltip" title="Export">
                            <i class="fas fa-file-export"></i>
                        </button>
                        <input name="monthExport" id="monthExport" type="hidden" value="{{ $selectedMonth }}">
                    </form>

                    <div style="margin-right: 10px;">
                        <a href="{{ route('admin.history-import-quantity') }}" class="btn btn-primary tooltip-btn"
                            data-toggle="tooltip" title="Lịch Sử Nhập PO">
                            <i class="fas fa-truck"></i>
                        </a>
                    </div>
                    {{-- Chọn tháng --}}
                    <form action="{{ route('admin.checkpo.index') }}" method="GET"
                        style="flex-grow: 1; display: flex; align-items: center; margin-right: 10px;">
                        <select name="monthFilter" id="monthFilter" onchange="this.form.submit()" class="form-control mt-0"
                            style="width: auto;" data-toggle="tooltip" title="Chọn tháng">
                            @foreach ($totalMonthQuantities as $month)
                                <option value="{{ $month }}" {{ $month == $selectedMonth ? 'selected' : '' }}>
                                    {{ Carbon\Carbon::createFromFormat('m-Y', $month)->format('m-Y') }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="row">
                    <div class="col-12 mb-3">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            @foreach ($months as $i => $weekArray)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link tab-vvp po-tab {{ $i == 0 ? 'active' : '' }}"
                                        id="week-{{ $i }}-tab-btn" data-bs-toggle="tab"
                                        data-bs-target="#week-{{ $i }}" type="button" role="tab"
                                        aria-controls="week-{{ $i }}" aria-selected="false">Tuần
                                        {{ $i + 1 }}</button>
                                </li>
                            @endforeach

                            <li class="nav-item" role="presentation">
                                <button class="nav-link tab-vvp po-tab" id="daily-tab-btn" data-bs-toggle="tab"
                                    data-bs-target="#daily-tab" type="button" role="tab" aria-controls="daily-tab"
                                    aria-selected="false">Hằng Ngày</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link tab-vvp po-tab" id="error-tab-btn" data-bs-toggle="tab"
                                    data-bs-target="#error-tab" type="button" role="tab" aria-controls="error-tab"
                                    aria-selected="false">Hàng Lỗi</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            {{-- Tab Tuần --}}
                            @foreach ($months as $index => $weekArray)
                                @php
                                    $weekDays = array_keys($weekArray);
                                    $startOfWeek = $weekDays[0] ?? '';
                                    $endOfWeek = end($weekDays) ?? '';
                                @endphp
                                <div class="tab-pane po-tab {{ $index == 0 ? 'active show' : 'fade' }}"
                                    id="week-{{ $index }}" role="tabpanel"
                                    aria-labelledby="week-{{ $index }}-tab">
                                    <div class="container-fluid">
                                        <div class="text-center text-uppercase font-weight-bolder text-lg">
                                            Tuần từ {{ $startOfWeek }} đến {{ $endOfWeek }}
                                        </div>
                                        <div class="table-wrapper">
                                            <table class="table align-items-center mb-0 table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                                            title="STT">STT</th>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                                            title="Tên linh kiện">Tên linh kiện</th>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                                            title="Tổng số lượng tồn hiện tại">Tổng số lượng tồn hiện tại
                                                        </th>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                                            title="Còn lại trong tuần">Còn lại trong tuần</th>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                                            title="Đã xuất trong tuần">Đã xuất trong tuần</th>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                                            title="Tồn đầu tuần">Tồn đầu tuần</th>
                                                        @foreach ($weekDays as $date)
                                                            @php
                                                                // Chỉ định định dạng của chuỗi ngày
                                                                $carbonDate = \Carbon\Carbon::createFromFormat(
                                                                    'd/m/Y',
                                                                    $date,
                                                                );
                                                            @endphp
                                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center text-center"
                                                                title="{{ $carbonDate->toDateString() }}">
                                                                {{ $carbonDate->format('d-m') }}
                                                            </th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($products as $product)
                                                        @php
                                                            $quan100 = 0;
                                                            $quanExport = 0;
                                                            $reamingOfWeek = 0;
                                                            $beginningOfWeek = 0;
                                                            $errorQuantity = 0;
                                                            $previousReamingOfWeekValue = 0;

                                                            if ($index === 0) {
                                                                $totalMonthQuantities = $product
                                                                    ->TotalMonthQuantities()
                                                                    ->where('status', 4)
                                                                    ->where('month', $selectedMonth)
                                                                    ->first();

                                                                if ($totalMonthQuantities) {
                                                                    $beginningOfWeek = $totalMonthQuantities->totalQuan;
                                                                }
                                                            } else {
                                                                $previousReamingOfWeekValue =
                                                                    $previousReamingOfWeek[$product->id] ?? 0;
                                                                $beginningOfWeek = $previousReamingOfWeekValue;
                                                            }

                                                            foreach ($weekArray as $date => $quantities) {
                                                                $quan100 += $quantities['quan100'][$product->id] ?? 0;
                                                                $quanExport +=
                                                                    $quantities['quanExport'][$product->id] ?? 0;
                                                            }

                                                            $reamingOfWeek = $quan100 - $quanExport + $beginningOfWeek;

                                                            if ($index > 0) {
                                                                $previousReamingOfWeek[$product->id] = $reamingOfWeek;
                                                            } else {
                                                                $previousReamingOfWeek[$product->id] = $reamingOfWeek;
                                                            }

                                                            $errorQuantity = $product
                                                                ->TotalMonthQuantities()
                                                                ->where('status', 6)
                                                                ->where('month', $selectedMonth)
                                                                ->sum('totalQuan');

                                                            $total = $quan100 + $beginningOfWeek;
                                                            $totalReamingOfWeek = $reamingOfWeek - $errorQuantity;

                                                            session()->put("$index.$product->id.total", $total);
                                                            session()->put(
                                                                "$index.$product->id.totalReamingOfWeek",
                                                                $totalReamingOfWeek,
                                                            );
                                                            session()->put(
                                                                "$index.$product->id.quanExport",
                                                                $quanExport,
                                                            );
                                                            session()->put(
                                                                "$index.$product->id.beginningOfWeek",
                                                                $beginningOfWeek,
                                                            );
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td title="{{ $product->name }}">
                                                                {{ $product->name }}
                                                            </td>
                                                            <td>{{ number_format($total) }}</td>
                                                            <td>{{ number_format($totalReamingOfWeek) }}</td>
                                                            <td>{{ number_format($quanExport) }}</td>
                                                            <td>{{ number_format($beginningOfWeek) }}</td>
                                                            @foreach ($weekDays as $date)
                                                                @php
                                                                    $quanExport =
                                                                        $weekArray[$date]['quanExport'][$product->id] ??
                                                                        0;
                                                                @endphp
                                                                <td class="text-center bg-2">
                                                                    {{ number_format($quanExport) }}
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            {{-- Tab Hàng Ngày --}}
                            <div class="tab-pane tab-vvp fade po-tab" id="daily-tab" role="tabpanel"
                                aria-labelledby="daily-tab-btn">
                                <div class="text-center font-weight-bolder text-lg my-2">
                                    BẢNG SẢN LƯỢNG SẢN XUẤT HẰNG NGÀY TRONG THÁNG {{ $selectedMonth }}
                                </div>
                                <div class="px-0 pb-2">
                                    <div class="table-responsive p-0 d-flex">
                                        <div class="col-4">
                                            <table class="table align-items-center mb-0 table-hover">
                                                <thead style="height: 82px">
                                                    <tr>
                                                        <th
                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                                            STT</th>
                                                        <th
                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                                            Tên linh
                                                            kiện</th>
                                                        <th
                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                                            Tổng cộng
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($products as $product)
                                                        <tr>
                                                            <td>
                                                                <div class="px-3 py-1 text-center">
                                                                    {{ $loop->iteration }}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="justify-content-start align-items-center ps-4">
                                                                    <a
                                                                        href="{{ route('admin.product.detail', $product->id) }}">{{ $product->name }}</a>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex px-3 py-1 justify-content-center">
                                                                    {{ number_format($product->TotalMonthQuantities()->where('month', $selectedMonth)->where('status', 1)->value('totalQuan') ?? 0) }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-8 product-tab">
                                            <table class="table align-items-center mb-0 table-hover">
                                                <thead>
                                                    <tr>
                                                        @foreach ($listDate as $key => $date)
                                                            @php
                                                                $formattedDate = \Carbon\Carbon::parse($date)->format(
                                                                    'd-m',
                                                                ); // Định dạng ngày tháng
                                                            @endphp
                                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center<?= $key % 2 == 0 ? 'bg-3' : 'bg-2' ?>"
                                                                colspan="2" title="{{ $date }}">
                                                                {{ $formattedDate }}
                                                            </th>
                                                        @endforeach
                                                    </tr>
                                                    <tr>
                                                        @foreach ($listDate as $key => $date)
                                                            <th
                                                                class="text-uppercase text-center text-md <?= $key % 2 == 0 ? 'bg-3' : 'bg-2' ?>">
                                                                Ca 1</th>
                                                            <th
                                                                class=" text-uppercase text-center text-md <?= $key % 2 == 0 ? 'bg-3' : 'bg-2' ?>">
                                                                Ca 2</th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>
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
                                                                    foreach (
                                                                        $dailyQuantitiesOfTheDay
                                                                        as $dailyQuantity
                                                                    ) {
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
                                                                            $totalQuanDateCa1 +=
                                                                                $dailyQuantity->quantity;
                                                                        } elseif ($created_at < $nextDayEightAM) {
                                                                            // Ca 2 nếu created_at trước 8 giờ sáng ngày hôm sau của date
                                                                            $totalQuanDateCa2 +=
                                                                                $dailyQuantity->quantity;
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
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Tab Hàng Lỗi --}}
                            <div class="tab-pane tab-vvp fade po-tab" id="error-tab" role="tabpanel"
                                aria-labelledby="error-tab">
                                <div class="text-center font-weight-bolder text-lg my-2">
                                    BẢNG NHẬP HÀNG LỖI HÀNG NGÀY TRONG THÁNG {{ $selectedMonth }}
                                </div>
                                <div class="px-0 pb-2">
                                    <div class="table-responsive p-0 d-flex">
                                        <div class="col-4 my-2">
                                            <table class="table align-items-center mb-0 table-hover">
                                                <thead>
                                                    <tr>
                                                        <th
                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center text-center">
                                                            STT</th>
                                                        <th
                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                                            Tên linh kiện</th>
                                                        <th
                                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                                            Tổng cộng</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($products as $key => $product)
                                                        <tr>
                                                            <td>
                                                                <div class="text-center px-3 py-1 ">
                                                                    {{ $loop->iteration }}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="justify-content-start align-items-center ps-4">
                                                                    <a
                                                                        href="{{ route('admin.product.detail', $product->id) }}">{{ $product->name }}</a>
                                                                </div>
                                                            </td>

                                                            <td>
                                                                <div class="ps-0 px-3 py-1 text-center">
                                                                    {{ number_format($product->TotalMonthQuantities()->where('month', $selectedMonth)->where('status', 6)->value('totalQuan') ?? 0) }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-8 product-tab my-2">
                                            <table class="table align-items-center mb-0 table-hover">
                                                <thead>
                                                    <tr>
                                                        @foreach ($listDate as $key => $date)
                                                            @php
                                                                // Sử dụng Carbon để định dạng ngày tháng theo 'd-m'
                                                                $formattedDate = \Carbon\Carbon::parse($date)->format(
                                                                    'd-m',
                                                                );
                                                            @endphp
                                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-centercol-3 <?= $key % 2 == 0 ? 'bg-3' : 'bg-2' ?>"
                                                                rowspan="2" title="{{ $formattedDate }}">
                                                                {{ $formattedDate }}
                                                            </th>
                                                        @endforeach

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
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var activeWeek0 = false;
        var activeWeek1 = false;
        var activeWeek2 = false;
        var activeWeek3 = false;
        var activeWeek4 = false;
        var activeDaily = false;
        var activeError = false;
        checkLocalStorage();

        function checkLocalStorage() {
            let productTab = getLocalStorage('poTab');
            if (productTab && productTab != null) {
                switch (productTab) {
                    case "week-0":
                        activeWeek = true;
                        resetTab();
                        handleActive('week-0-tab-btn', 'week-0');
                        break;
                    case "week-1":
                        activeWeek = true;
                        resetTab();
                        handleActive('week-1-tab-btn', 'week-1');
                        break;
                    case "week-2":
                        activeWeek = true;
                        resetTab();
                        handleActive('week-2-tab-btn', 'week-2');
                        break;
                    case "week-3":
                        activeWeek = true;
                        resetTab();
                        handleActive('week-3-tab-btn', 'week-3');
                        break;
                    case "week-4":
                        activeWeek = true;
                        resetTab();
                        handleActive('week-4-tab-btn', 'week-4');
                        break;
                    case "daily":
                        activeDaily = true;
                        resetTab();
                        handleActive('daily-tab-btn', 'daily-tab');
                        break;
                    case "error":
                        activeError = true;
                        resetTab();
                        handleActive('error-tab-btn', 'error-tab');
                        break;
                }
            }
        }

        // Xử lý sự kiện click cho các tab tuần
        $('#daily-tab-btn').click(function() {
            resetClick();
            activeDaily = true;
            addLocalStorage('daily');
        });
        $('#week-0-tab-btn').click(function() {
            resetClick();
            activeWeek0 = true;
            addLocalStorage('week-0');
        });
        $('#week-1-tab-btn').click(function() {
            resetClick();
            activeWeek1 = true;
            addLocalStorage('week-1');
        });
        $('#week-2-tab-btn').click(function() {
            resetClick();
            activeWeek2 = true;
            addLocalStorage('week-2');
        });
        $('#week-3-tab-btn').click(function() {
            resetClick();
            activeWeek3 = true;
            addLocalStorage('week-3');
        });
        $('#week-4-tab-btn').click(function() {
            resetClick();
            activeWeek4 = true;
            addLocalStorage('week-4');
        });
        $('#error-tab-btn').click(function() {
            resetClick();
            activeError = true;
            addLocalStorage('error');
        });


        function resetClick() {
            activeDaily = false;
            activeError = false;
            activeWeek0 = false;
            activeWeek1 = false;
            activeWeek2 = false;
            activeWeek3 = false;
            activeWeek4 = false;
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

        function resetTab() {
            var listTab = document.getElementsByClassName('po-tab');
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
            localStorage.setItem('poTab', key);
        }

        function getLocalStorage(key) {
            return localStorage.getItem(key);
        }

        $(function() {
            $('[data-toggle="tooltip"]').tooltip();

            $('.tooltip-btn').on('click', function() {
                $(this).tooltip(
                    'hide');
            });
        });
    </script>
@endsection
