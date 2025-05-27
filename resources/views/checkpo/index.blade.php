@extends('layouts.'.$layout)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Danh Sách PO</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                        {{-- Thêm Sản Lượng --}}
                        <button
                            type="button"
                            class="btn btn-primary tooltip-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#import_Modal"
                            data-toggle="tooltip"
                            title="Thêm Sản Lượng">
                            <i class="fas fa-plus"></i>
                        </button>
                        @include('checkpo.add-po-import', ['href' => 'admin.checkpo.index'])

                        {{-- Thêm PO Xuất Hàng --}}
                        <button
                            type="button"
                            class="btn btn-primary tooltip-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#export_Modal"
                            data-toggle="tooltip"
                            title="Thêm PO Xuất Hàng">
                            <i class="fas fa-truck"></i>
                        </button>
                        @include('checkpo.add-po-export', ['href' => 'admin.checkpo.index'])

                        {{-- Thêm Tồn Đầu Kỳ --}}
                        <button
                            type="button"
                            class="btn btn-primary tooltip-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#stockQuantityModal"
                            data-toggle="tooltip"
                            title="Thêm Tồn Đầu Kỳ">
                            <i class="fas fa-warehouse"></i>
                        </button>
                        @include('checkpo.add-stock-quantity-inventory', [
                            'href' => 'admin.checkpo.index',
                        ])

                        {{-- Lịch Sử Nhập PO --}}
                        <a
                            href="{{ route('admin.history-import-quantity') }}"
                            class="btn btn-primary tooltip-btn"
                            data-toggle="tooltip"
                            title="Lịch Sử Nhập PO">
                            <i class="fas fa-clock-rotate-left"></i>
                        </a>

                        {{-- Export --}}
                        <form action="{{ route('admin.checkpo.export') }}" method="GET">
                            <button
                                class="btn btn-success tooltip-btn"
                                type="submit"
                                data-toggle="tooltip"
                                title="Export">
                                <i class="fas fa-file-export"></i>
                            </button>
                            <input name="monthExport" id="monthExport" type="hidden" value="{{ $selectedMonth }}" />
                        </form>

                        {{-- Chọn tháng --}}
                        <form action="{{ route('admin.checkpo.index') }}" method="GET">
                            <select
                                name="monthFilter"
                                id="monthFilter"
                                onchange="this.form.submit()"
                                class="form-control mt-0"
                                data-toggle="tooltip"
                                title="Chọn tháng">
                                @foreach ($totalMonthQuantities as $month)
                                    <option value="{{ $month }}" {{ $month == $selectedMonth ? 'selected' : '' }}>
                                        {{ Carbon\Carbon::createFromFormat('m-Y', $month)->format('m-Y') }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    {{-- tab --}}
                    <div>
                        <ul
                            class="nav nav-tabs flex-nowrap text-nowrap overflow-x-auto overflow-y-hidden"
                            id="myTab"
                            role="tablist">
                            @foreach ($months as $i => $weekArray)
                                <li class="nav-item" role="presentation">
                                    <button
                                        class="nav-link tab-vvp po-tab {{ $i == 0 ? 'active' : '' }}"
                                        id="week-{{ $i }}-tab-btn"
                                        data-bs-toggle="tab"
                                        data-bs-target="#week-{{ $i }}"
                                        type="button"
                                        role="tab"
                                        aria-controls="week-{{ $i }}"
                                        aria-selected="false">
                                        Tuần {{ $i + 1 }}
                                    </button>
                                </li>
                            @endforeach

                            <li class="nav-item" role="presentation">
                                <button
                                    class="nav-link tab-vvp po-tab"
                                    id="daily-tab-btn"
                                    data-bs-toggle="tab"
                                    data-bs-target="#daily-tab"
                                    type="button"
                                    role="tab"
                                    aria-controls="daily-tab"
                                    aria-selected="false">
                                    Hằng Ngày
                                </button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button
                                    class="nav-link tab-vvp po-tab"
                                    id="error-tab-btn"
                                    data-bs-toggle="tab"
                                    data-bs-target="#error-tab"
                                    type="button"
                                    role="tab"
                                    aria-controls="error-tab"
                                    aria-selected="false">
                                    Hàng Lỗi
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            {{-- Tab Tuần --}}
                            @foreach ($months as $index => $weekArray)
                                <div
                                    class="tab-pane po-tab {{ $index == 0 ? 'active show' : 'fade' }}"
                                    id="week-{{ $index }}"
                                    role="tabpanel"
                                    aria-labelledby="week-{{ $index }}-tab">
                                    <div class="text-center text-uppercase font-weight-bolder text-lg my-2">
                                        Tuần từ {{ $weekArray['startOfWeek'] }} đến
                                        {{ $weekArray['endOfWeek'] }}
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="table-light text-uppercase text-center">
                                                <tr>
                                                    <th>STT</th>
                                                    <th>Tên linh kiện</th>
                                                    <th>Tổng số lượng tồn hiện tại</th>
                                                    <th>Còn lại trong tuần</th>
                                                    <th>Đã xuất trong tuần</th>
                                                    <th>Tồn đầu tuần</th>
                                                    @foreach ($weekArray['weekDays'] as $date)
                                                        <th>
                                                            {{ \Carbon\Carbon::createFromFormat('d/m/Y', $date)->format('d-m') }}
                                                        </th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $product)
                                                    <tr class="text-center align-middle">
                                                        <th>
                                                            {{ $loop->iteration }}
                                                        </th>
                                                        <td>
                                                            {{ $product->name }}
                                                        </td>
                                                        <td>
                                                            {{ number_format($weekArray['products'][$product->id]['total']) }}
                                                        </td>
                                                        <td>
                                                            {{ number_format($weekArray['products'][$product->id]['totalReamingOfWeek']) }}
                                                        </td>
                                                        <td>
                                                            {{ number_format($weekArray['products'][$product->id]['quanExport']) }}
                                                        </td>
                                                        <td>
                                                            {{ number_format($weekArray['products'][$product->id]['beginningOfWeek']) }}
                                                        </td>
                                                        @foreach ($weekArray['weekDays'] as $date)
                                                            <td class="text-center bg-secondary-subtle">
                                                                {{ number_format($weekArray[$date]['quanExport'][$product->id] ?? 0) }}
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach

                            {{-- Tab Hàng Ngày --}}
                            <div
                                class="tab-pane tab-vvp fade po-tab"
                                id="daily-tab"
                                role="tabpanel"
                                aria-labelledby="daily-tab-btn">
                                <div class="text-center text-uppercase font-weight-bolder text-lg my-2">
                                    BẢNG SẢN LƯỢNG SẢN XUẤT HẰNG NGÀY TRONG THÁNG {{ $selectedMonth }}
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light text-uppercase text-center align-middle">
                                            <tr>
                                                <th rowspan="2">STT</th>
                                                <th rowspan="2">Tên linh kiện</th>
                                                <th rowspan="2">Tổng cộng</th>
                                                @foreach ($listDate as $key => $date)
                                                    <th colspan="2" title="{{ $date }}">
                                                        {{ \Carbon\Carbon::parse($date)->format('d-m') }}
                                                    </th>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                @foreach ($listDate as $key => $date)
                                                    <th
                                                        class="<?= $key % 2 == 0 ? 'bg-info-subtle' : 'bg-secondary-subtle' ?>">
                                                        Ca 1
                                                    </th>
                                                    <th
                                                        class="<?= $key % 2 == 0 ? 'bg-info-subtle' : 'bg-secondary-subtle' ?>">
                                                        Ca 2
                                                    </th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody class="text-center align-middle">
                                            @foreach ($products as $product)
                                                <tr>
                                                    <th>
                                                        {{ $loop->iteration }}
                                                    </th>
                                                    <td>
                                                        <a href="{{ route('admin.product.detail', $product->id) }}">
                                                            {{ $product->name }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{ $product->total }}
                                                    </td>
                                                    @foreach ($listDate as $key => $date)
                                                        <td
                                                            class="<?= $key % 2 == 0 ? 'bg-info-subtle' : 'bg-secondary-subtle' ?>">
                                                            {{ number_format($product->totalQuanDateCa1[$date]) }}
                                                        </td>
                                                        <td
                                                            class="<?= $key % 2 == 0 ? 'bg-info-subtle' : 'bg-secondary-subtle' ?>">
                                                            {{ number_format($product->totalQuanDateCa2[$date]) }}
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Tab Hàng Lỗi --}}
                            <div
                                class="tab-pane tab-vvp fade po-tab"
                                id="error-tab"
                                role="tabpanel"
                                aria-labelledby="error-tab">
                                <div class="text-center text-uppercase font-weight-bolder text-lg my-2">
                                    BẢNG NHẬP HÀNG LỖI HÀNG NGÀY TRONG THÁNG
                                    {{ $selectedMonth }}
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light text-uppercase text-center">
                                            <tr>
                                                <th>STT</th>
                                                <th>Tên linh kiện</th>
                                                <th>Tổng cộng</th>
                                                @foreach ($listDate as $key => $date)
                                                    <th
                                                        class="<?= $key % 2 == 0 ? 'bg-info-subtle' : 'bg-secondary-subtle' ?>">
                                                        {{ \Carbon\Carbon::parse($date)->format('d-m') }}
                                                    </th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody class="text-center align-middle">
                                            @foreach ($products as $key => $product)
                                                <tr>
                                                    <th>
                                                        {{ $loop->iteration }}
                                                    </th>
                                                    <td>
                                                        <a href="{{ route('admin.product.detail', $product->id) }}">
                                                            {{ $product->name }}
                                                        </a>
                                                    </td>

                                                    <td>
                                                        {{ $product->totalEror }}
                                                    </td>
                                                    @foreach ($listDate as $key => $date)
                                                        <td
                                                            class="<?= $key % 2 == 0 ? 'bg-info-subtle' : 'bg-secondary-subtle' ?>">
                                                            {{ number_format((float) $product->totalQuanDateError[$date]) }}
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
@endsection

@section('scriptsx')
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
                    case 'week-0':
                        activeWeek = true;
                        resetTab();
                        handleActive('week-0-tab-btn', 'week-0');
                        break;
                    case 'week-1':
                        activeWeek = true;
                        resetTab();
                        handleActive('week-1-tab-btn', 'week-1');
                        break;
                    case 'week-2':
                        activeWeek = true;
                        resetTab();
                        handleActive('week-2-tab-btn', 'week-2');
                        break;
                    case 'week-3':
                        activeWeek = true;
                        resetTab();
                        handleActive('week-3-tab-btn', 'week-3');
                        break;
                    case 'week-4':
                        activeWeek = true;
                        resetTab();
                        handleActive('week-4-tab-btn', 'week-4');
                        break;
                    case 'daily':
                        activeDaily = true;
                        resetTab();
                        handleActive('daily-tab-btn', 'daily-tab');
                        break;
                    case 'error':
                        activeError = true;
                        resetTab();
                        handleActive('error-tab-btn', 'error-tab');
                        break;
                }
            }
        }

        // Xử lý sự kiện click cho các tab tuần
        $('#daily-tab-btn').click(function () {
            resetClick();
            activeDaily = true;
            addLocalStorage('daily');
        });
        $('#week-0-tab-btn').click(function () {
            resetClick();
            activeWeek0 = true;
            addLocalStorage('week-0');
        });
        $('#week-1-tab-btn').click(function () {
            resetClick();
            activeWeek1 = true;
            addLocalStorage('week-1');
        });
        $('#week-2-tab-btn').click(function () {
            resetClick();
            activeWeek2 = true;
            addLocalStorage('week-2');
        });
        $('#week-3-tab-btn').click(function () {
            resetClick();
            activeWeek3 = true;
            addLocalStorage('week-3');
        });
        $('#week-4-tab-btn').click(function () {
            resetClick();
            activeWeek4 = true;
            addLocalStorage('week-4');
        });
        $('#error-tab-btn').click(function () {
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
        }

        function addLocalStorage(key) {
            localStorage.setItem('poTab', key);
        }

        function getLocalStorage(key) {
            return localStorage.getItem(key);
        }

        $(function () {
            $('[data-toggle="tooltip"]').tooltip();

            $('.tooltip-btn').on('click', function () {
                $(this).tooltip('hide');
            });
        });
    </script>
@endsection
