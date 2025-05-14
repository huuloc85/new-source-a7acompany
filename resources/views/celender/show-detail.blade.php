@extends('layouts.'.$layout)

@section('styles')
    <style>
        .table-header-content {
            height: 4.5rem;
            min-height: 4.5rem;
            max-height: 4.5rem;
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endsection

@php
    $tabWork = [
        'VVP' => ['title' => 'Hàng Nhật - Hàng Chợ'],
        'A7A' => ['title' => 'Trực Phòng Ăn'],
        'part-time' => ['title' => 'Đổ Rác WC'],
        'women' => ['title' => 'Trực WC Nữ'],
        'men' => ['title' => 'Trực WC Nam'],
    ];

    $workLegends = [
        'N' => ['title' => 'Ca ngày', 'class' => 'text-bg-primary'],
        'D' => ['title' => 'Ca đêm', 'class' => 'text-bg-dark'],
        'X' => ['title' => 'Nghĩ', 'class' => 'text-bg-danger'],
        'TC' => ['title' => 'Tăng cường đêm', 'class' => 'text-bg-danger'],
        'LN' => ['title' => 'Làm thêm ca ngày', 'class' => 'text-bg-danger'],
        'VS' => ['title' => 'Vệ sinh', 'class' => 'text-bg-warning'],
    ];
@endphp

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">{{ $calendarTitle }}</h4>
                    </div>
                </div>
                <div class="card-body">
                    <a
                        class="btn btn-link mb-3"
                        href="{{ route('admin.celender.home') }}"
                    >
                        <i class="fas fa-arrow-left"></i>
                        Quay lại
                    </a>
                    <ul
                        class="nav nav-tabs flex-nowrap text-nowrap overflow-x-auto overflow-y-hidden"
                        id="myTab"
                        role="tablist"
                    >
                        @foreach ($tabWork as $key => $tab)
                            <li class="nav-item" role="presentation">
                                <button
                                    class="nav-link {{ $loop->first ? 'active' : '' }}"
                                    id="{{ $key }}-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#{{ $key }}"
                                    type="button"
                                    role="tab"
                                    aria-controls="{{ $key }}"
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                >
                                    {{ $tab['title'] }}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                    <div class="d-flex flex-wrap gap-3 align-items-center m-3">
                        @foreach ($workLegends as $key => $workLegend)
                            <div>
                                <span class="badge {{ $workLegend['class'] }}">
                                    {{ $key }}
                                </span>
                                <span for="">
                                    {{ $workLegend['title'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    <div class="tab-content" id="myTabContent">
                        @php
                            $VVP = $tabWork['VVP'] ?? null;
                            unset($tabWork['VVP']);
                        @endphp

                        @if (isset($VVP) && isset($categories))
                            <div
                                class="tab-pane fade show active"
                                id="VVP"
                                role="tabpanel"
                                aria-labelledby="VVP-tab"
                            >
                                @foreach ($categories as $key => $category)
                                    <div
                                        class="fw-bold bg-info p-2 text-center"
                                    >
                                        {{ $category->name }}
                                    </div>

                                    <div class="d-flex">
                                        <div class="col-4 table-responsive">
                                            <table
                                                class="table table-hover table-bordered"
                                            >
                                                <thead
                                                    class="table-light text-center uppercase align-middle"
                                                >
                                                    <tr>
                                                        <th>
                                                            <div
                                                                class="table-header-content"
                                                            >
                                                                Mã NV
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div
                                                                class="table-header-content"
                                                            >
                                                                Họ và tên
                                                            </div>
                                                        </th>
                                                    </tr>
                                                </thead>

                                                <tbody
                                                    class="text-center align-middle"
                                                >
                                                    {{-- Hàng Nhật - Hàng Chợ --}}
                                                    @if (isset($celenderDetailsHNHC))
                                                        @foreach ($celenderDetailsHNHC as $key => $celenderDetailHNHC)
                                                            @if ($celenderDetailHNHC->employee->category_celender_id == $category->id)
                                                                <tr
                                                                    style="
                                                                        height: 2.75rem;
                                                                    "
                                                                >
                                                                    <td>
                                                                        {{ $celenderDetailHNHC->employee->code }}
                                                                    </td>
                                                                    <td
                                                                        class="text-start"
                                                                    >
                                                                        {{ $celenderDetailHNHC->employee->name }}
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-8 table-responsive">
                                            <table
                                                class="table table-hover table-bordered"
                                            >
                                                <thead
                                                    class="table-light text-center uppercase align-middle"
                                                >
                                                    <tr>
                                                        @foreach ($dates as $date)
                                                            <th>
                                                                <div
                                                                    class="table-header-content"
                                                                >
                                                                    {{ $formatDate->formatTimeDate($date) }}
                                                                    <br />
                                                                    {{ $formatDate->dayOfWeek($date) }}
                                                                </div>
                                                            </th>
                                                        @endforeach
                                                    </tr>
                                                </thead>

                                                <tbody
                                                    class="text-center align-middle"
                                                >
                                                    {{-- Hàng Nhật - Hàng Chợ --}}
                                                    @if (isset($celenderDetailsHNHC))
                                                        @foreach ($celenderDetailsHNHC as $key => $celenderDetailHNHC)
                                                            @if ($celenderDetailHNHC->employee->category_celender_id == $category->id)
                                                                <tr
                                                                    style="
                                                                        height: 2.75rem;
                                                                    "
                                                                >
                                                                    @foreach ($dates as $key => $date)
                                                                        @php
                                                                            $fill = 'day'.$key + 1;
                                                                        @endphp

                                                                        <td>
                                                                            <span
                                                                                class="badge {{ $workLegends[$celenderDetailHNHC->$fill]['class'] ?? '' }}"
                                                                            >
                                                                                {{ $celenderDetailHNHC->$fill ?? '' }}
                                                                            </span>
                                                                        </td>
                                                                    @endforeach
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @foreach ($tabWork as $key => $tab)
                            @php
                                $tabCode = $key;
                            @endphp

                            <div
                                class="tab-pane fade"
                                id="{{ $tabCode }}"
                                role="tabpanel"
                                aria-labelledby="{{ $tabCode }}-tab"
                            >
                                <div class="d-flex">
                                    <div class="col-4 table-responsive">
                                        <table
                                            class="table table-hover table-bordered"
                                        >
                                            <thead
                                                class="table-light text-center uppercase align-middle"
                                            >
                                                <tr>
                                                    <th>
                                                        <div
                                                            class="table-header-content"
                                                        >
                                                            Mã NV
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div
                                                            class="table-header-content"
                                                        >
                                                            Họ và tên
                                                        </div>
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tbody
                                                class="text-center align-middle"
                                            >
                                                {{-- Hàng Nhật - Hàng Chợ --}}
                                                @if ($tabCode == 'VVP' && isset($categories))
                                                    @foreach ($categories as $key => $category)
                                                        <tr
                                                            style="
                                                                height: 2.75rem;
                                                            "
                                                        >
                                                            <td
                                                                colspan="9999"
                                                                class="fw-bold bg-info"
                                                            >
                                                                <span
                                                                    class="d-none"
                                                                >
                                                                    {{ $category->name }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        @if (isset($celenderDetailsHNHC))
                                                            @foreach ($celenderDetailsHNHC as $key => $celenderDetailHNHC)
                                                                @if ($celenderDetailHNHC->employee->category_celender_id == $category->id)
                                                                    <tr
                                                                        style="
                                                                            height: 2.75rem;
                                                                        "
                                                                    >
                                                                        <td>
                                                                            {{ $celenderDetailHNHC->employee->code }}
                                                                        </td>
                                                                        <td
                                                                            class="text-start"
                                                                        >
                                                                            {{ $celenderDetailHNHC->employee->name }}
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif

                                                {{-- Trực Phòng Ăn --}}
                                                @if ($tabCode == 'A7A' && isset($celenderDetailsEatroom))
                                                    @foreach ($celenderDetailsEatroom as $key => $celenderDetailEatroom)
                                                        <tr
                                                            style="
                                                                height: 2.75rem;
                                                            "
                                                        >
                                                            <td>
                                                                {{ $celenderDetailEatroom->employee->code }}
                                                            </td>
                                                            <td
                                                                class="text-start"
                                                            >
                                                                {{ $celenderDetailEatroom->employee->name }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif

                                                {{-- Đổ Rác WC --}}
                                                @if ($tabCode == 'part-time' && isset($celenderDetailsWC))
                                                    @foreach ($celenderDetailsWC as $key => $celenderDetailWC)
                                                        <tr
                                                            style="
                                                                height: 2.75rem;
                                                            "
                                                        >
                                                            <td>
                                                                {{ $celenderDetailWC->employee->code }}
                                                            </td>
                                                            <td
                                                                class="text-start"
                                                            >
                                                                {{ $celenderDetailWC->employee->name }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif

                                                {{-- Trực WC Nữ --}}
                                                @if ($tabCode == 'women' && isset($celenderDetailsWCCleanWomen))
                                                    @foreach ($celenderDetailsWCCleanWomen as $key => $womenWC)
                                                        <tr
                                                            style="
                                                                height: 2.75rem;
                                                            "
                                                        >
                                                            <td>
                                                                {{ $womenWC->employee->code }}
                                                            </td>
                                                            <td
                                                                class="text-start"
                                                            >
                                                                {{ $womenWC->employee->name }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif

                                                {{-- Trực WC Nam --}}
                                                @if ($tabCode == 'men' && isset($celenderDetailsWCCleanMen))
                                                    @foreach ($celenderDetailsWCCleanMen as $key => $menWC)
                                                        <tr
                                                            style="
                                                                height: 2.75rem;
                                                            "
                                                        >
                                                            <td>
                                                                {{ $menWC->employee->code }}
                                                            </td>
                                                            <td
                                                                class="text-start"
                                                            >
                                                                {{ $menWC->employee->name }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-8 table-responsive">
                                        <table
                                            class="table table-hover table-bordered"
                                        >
                                            <thead
                                                class="table-light text-center uppercase align-middle"
                                            >
                                                <tr>
                                                    @foreach ($dates as $date)
                                                        @if ($tabCode == 'part-time' && $formatDate->dayOfWeek($date) == 'T7')
                                                            <th>
                                                                <div
                                                                    class="table-header-content"
                                                                >
                                                                    {{ $formatDate->formatTimeDate($date) }}
                                                                    <br />
                                                                    {{ $formatDate->dayOfWeek($date) }}
                                                                </div>
                                                            </th>
                                                        @elseif ($tabCode != 'part-time')
                                                            <th>
                                                                <div
                                                                    class="table-header-content"
                                                                >
                                                                    {{ $formatDate->formatTimeDate($date) }}
                                                                    <br />
                                                                    {{ $formatDate->dayOfWeek($date) }}
                                                                </div>
                                                            </th>
                                                        @endif
                                                    @endforeach
                                                </tr>
                                            </thead>

                                            <tbody
                                                class="text-center align-middle"
                                            >
                                                {{-- Hàng Nhật - Hàng Chợ --}}
                                                @if ($tabCode == 'VVP' && isset($categories))
                                                    @foreach ($categories as $key => $category)
                                                        <tr
                                                            style="
                                                                height: 2.75rem;
                                                            "
                                                        >
                                                            <td
                                                                colspan="9999"
                                                                class="fw-bold bg-info"
                                                            >
                                                                {{ $category->name }}
                                                            </td>
                                                        </tr>
                                                        @if (isset($celenderDetailsHNHC))
                                                            @foreach ($celenderDetailsHNHC as $key => $celenderDetailHNHC)
                                                                @if ($celenderDetailHNHC->employee->category_celender_id == $category->id)
                                                                    <tr
                                                                        style="
                                                                            height: 2.75rem;
                                                                        "
                                                                    >
                                                                        @foreach ($dates as $key => $date)
                                                                            @php
                                                                                $fill = 'day'.$key + 1;
                                                                            @endphp

                                                                            <td>
                                                                                <span
                                                                                    class="badge {{ $workLegends[$celenderDetailHNHC->$fill]['class'] ?? '' }}"
                                                                                >
                                                                                    {{ $celenderDetailHNHC->$fill ?? '' }}
                                                                                </span>
                                                                            </td>
                                                                        @endforeach
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif

                                                {{-- Trực Phòng Ăn --}}
                                                @if ($tabCode == 'A7A' && isset($celenderDetailsEatroom))
                                                    @foreach ($celenderDetailsEatroom as $key => $celenderDetailEatroom)
                                                        <tr
                                                            style="
                                                                height: 2.75rem;
                                                            "
                                                        >
                                                            @foreach ($dates as $key => $date)
                                                                @php
                                                                    $fill = 'day'.$key + 1;
                                                                @endphp

                                                                <td>
                                                                    @if ($celenderDetailEatroom->$fill)
                                                                        <span
                                                                            class="badge {{ $workLegends['VS']['class'] ?? '' }}"
                                                                        >
                                                                            VS
                                                                        </span>
                                                                    @endif
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                @endif

                                                {{-- Đổ Rác WC --}}
                                                @if ($tabCode == 'part-time' && isset($celenderDetailsWC))
                                                    @foreach ($celenderDetailsWC as $key => $celenderDetailWC)
                                                        <tr
                                                            style="
                                                                height: 2.75rem;
                                                            "
                                                        >
                                                            @php
                                                                $keyDate = 0;
                                                            @endphp

                                                            @foreach ($dates as $key => $date)
                                                                @if ($formatDate->dayOfWeek($date) == 'T7')
                                                                    @php
                                                                        $fill = 'day'.$keyDate + 1;
                                                                        $keyDate += 1;
                                                                    @endphp

                                                                    <td>
                                                                        @if ($celenderDetailWC->$fill)
                                                                            <span
                                                                                class="badge {{ $workLegends['VS']['class'] ?? '' }}"
                                                                            >
                                                                                VS
                                                                            </span>
                                                                        @endif
                                                                    </td>
                                                                @endif
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                @endif

                                                {{-- Trực WC Nữ --}}
                                                @if ($tabCode == 'women' && isset($celenderDetailsWCCleanWomen))
                                                    @foreach ($celenderDetailsWCCleanWomen as $key => $womenWC)
                                                        <tr
                                                            style="
                                                                height: 2.75rem;
                                                            "
                                                        >
                                                            @foreach ($dates as $key => $date)
                                                                @php
                                                                    $fill = 'day'.$key + 1;
                                                                @endphp

                                                                <td>
                                                                    @if ($womenWC->$fill)
                                                                        <span
                                                                            class="badge {{ $workLegends['VS']['class'] ?? '' }}"
                                                                        >
                                                                            VS
                                                                        </span>
                                                                    @endif
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                @endif

                                                {{-- Trực WC Nam --}}
                                                @if ($tabCode == 'men' && isset($celenderDetailsWCCleanMen))
                                                    @foreach ($celenderDetailsWCCleanMen as $key => $menWC)
                                                        <tr
                                                            style="
                                                                height: 2.75rem;
                                                            "
                                                        >
                                                            @foreach ($dates as $key => $date)
                                                                @php
                                                                    $fill = 'day'.$key + 1;
                                                                @endphp

                                                                <td>
                                                                    @if ($menWC->$fill)
                                                                        <span
                                                                            class="badge {{ $workLegends['VS']['class'] ?? '' }}"
                                                                        >
                                                                            VS
                                                                        </span>
                                                                    @endif
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <a
                        class="btn btn-link mt-3"
                        href="{{ route('admin.celender.home') }}"
                    >
                        <i class="fas fa-arrow-left"></i>
                        Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            const listClass = ['N', 'D', 'X', 'TC', 'LN'];
            $('input').on('keyup', function () {
                $(this).val($(this).val().toUpperCase());
                for (let i = 0; i < listClass.length; i++) {
                    if ($(this).hasClass(listClass[i])) {
                        $(this).removeClass(listClass[i]);
                    }
                }
                $(this).addClass($(this).val());
            });
        });
        $(document).ready(function () {
            // Add search input after workLegends div
            $('.d-flex.flex-wrap.gap-3.align-items-center.m-3').after(`
        <div class="mb-3">
            <input type="text"
                   id="employeeSearch"
                   class="form-control w-100 w-md-25"
                   placeholder="Tìm kiếm nhân viên..."
            >
        </div>
    `);

            // Handle the search functionality
            $('#employeeSearch').on('input', function () {
                const searchValue = $(this).val().toLowerCase().trim();

                // Get the active tab's ID
                const activeTabId = $('.tab-pane.active').attr('id');

                // Find both tables in the active tab
                const $leftTable = $(`#${activeTabId} .col-4 table`);
                const $rightTable = $(`#${activeTabId} .col-8 table`);

                // Process rows in pairs
                const $leftRows = $leftTable.find('tbody tr');
                const $rightRows = $rightTable.find('tbody tr');

                $leftRows.each(function (index) {
                    const $leftRow = $(this);
                    const $rightRow = $rightRows.eq(index);

                    // Skip category header rows (rows with bg-info class)
                    if ($leftRow.find('.bg-info').length > 0) {
                        return;
                    }

                    // Get employee code and name from the left table
                    const code = $leftRow
                        .find('td:first-child')
                        .text()
                        .toLowerCase()
                        .trim();
                    const name = $leftRow
                        .find('td:nth-child(2)')
                        .text()
                        .toLowerCase()
                        .trim();

                    // Show/hide rows based on search match
                    if (
                        code.includes(searchValue) ||
                        name.includes(searchValue)
                    ) {
                        $leftRow.show();
                        $rightRow.show();

                        // If this row matches, also show its category header if it exists
                        let $currentLeftRow = $leftRow;
                        let $currentRightRow = $rightRow;

                        while ($currentLeftRow.prev().length > 0) {
                            $currentLeftRow = $currentLeftRow.prev();
                            $currentRightRow = $currentRightRow.prev();

                            if ($currentLeftRow.find('.bg-info').length > 0) {
                                $currentLeftRow.show();
                                $currentRightRow.show();
                                break;
                            }
                        }
                    } else {
                        $leftRow.hide();
                        $rightRow.hide();
                    }
                });

                // Hide category headers if no rows in that category are visible
                $(`#${activeTabId} .col-4 .bg-info`)
                    .closest('tr')
                    .each(function (index) {
                        const $leftHeader = $(this);
                        const $rightHeader = $(
                            `#${activeTabId} .col-8 .bg-info`,
                        )
                            .closest('tr')
                            .eq(index);

                        let hasVisibleRows = false;
                        let $nextLeftRow = $leftHeader.next();
                        let $nextRightRow = $rightHeader.next();

                        while (
                            $nextLeftRow.length &&
                            !$nextLeftRow.find('.bg-info').length
                        ) {
                            if ($nextLeftRow.is(':visible')) {
                                hasVisibleRows = true;
                                break;
                            }
                            $nextLeftRow = $nextLeftRow.next();
                            $nextRightRow = $nextRightRow.next();
                        }

                        if (!hasVisibleRows) {
                            $leftHeader.hide();
                            $rightHeader.hide();
                        }
                    });
            });

            // Clear search when changing tabs
            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function () {
                const $searchInput = $('#employeeSearch');
                if ($searchInput.val()) {
                    $searchInput.val('').trigger('input');
                }
            });

            // Keep the existing input class change functionality
            const listClass = ['N', 'D', 'X', 'TC', 'LN'];
            $('input').on('keyup', function () {
                if (
                    !$(this).attr('id') ||
                    $(this).attr('id') !== 'employeeSearch'
                ) {
                    $(this).val($(this).val().toUpperCase());
                    for (let i = 0; i < listClass.length; i++) {
                        if ($(this).hasClass(listClass[i])) {
                            $(this).removeClass(listClass[i]);
                        }
                    }
                    $(this).addClass($(this).val());
                }
            });
        });
    </script>
@endsection
