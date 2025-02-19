@extends('layouts.'.$layout)

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
                        @foreach ($tabWork as $key => $tab)
                            @php
                                $tabCode = $key;
                            @endphp

                            <div
                                class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
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
                                                style="height: 4.5rem"
                                            >
                                                <tr>
                                                    <th>Mã NV</th>
                                                    <th>Họ và tên</th>
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
                                                style="height: 4.5rem"
                                            >
                                                <tr>
                                                    @foreach ($dates as $date)
                                                        @if ($tabCode == 'part-time' && $formatDate->dayOfWeek($date) == 'T7')
                                                            <th>
                                                                {{ $formatDate->formatTimeDate($date) }}
                                                                <br />
                                                                {{ $formatDate->dayOfWeek($date) }}
                                                            </th>
                                                        @elseif ($tabCode != 'part-time')
                                                            <th>
                                                                {{ $formatDate->formatTimeDate($date) }}
                                                                <br />
                                                                {{ $formatDate->dayOfWeek($date) }}
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
        document.addEventListener('DOMContentLoaded', function () {
            // Create search input
            const searchContainer = document.createElement('div');
            searchContainer.className = 'mb-3';
            searchContainer.innerHTML = `
        <div class="input-group">
            <span class="input-group-text">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" class="form-control" id="tableSearch"
                   placeholder="Tìm kiếm theo mã nhân viên hoặc tên...">
        </div>
    `;

            // Insert search input at the correct location
            const tabContent = document.querySelector('#myTabContent');
            tabContent.parentNode.insertBefore(searchContainer, tabContent);

            const searchInput = document.getElementById('tableSearch');

            searchInput.addEventListener('input', function () {
                const searchTerm = this.value.toLowerCase().trim();

                // Loop through all active tab panes
                document
                    .querySelectorAll('.tab-pane.active')
                    .forEach((tabPane) => {
                        // Get both tables in the current tab
                        const leftTable =
                            tabPane.querySelector('.col-4 table tbody');
                        const rightTable =
                            tabPane.querySelector('.col-8 table tbody');

                        if (!leftTable || !rightTable) return;

                        // Get all rows from left table
                        const leftRows = leftTable.querySelectorAll('tr');
                        const rightRows = rightTable.querySelectorAll('tr');

                        leftRows.forEach((leftRow, index) => {
                            const rightRow = rightRows[index];
                            if (!rightRow) return;

                            // Check if it's a category header
                            if (leftRow.querySelector('.bg-info')) {
                                // Always show category headers initially
                                leftRow.style.display = '';
                                rightRow.style.display = '';
                                return;
                            }

                            // Get searchable content
                            const code =
                                leftRow
                                    .querySelector('td:first-child')
                                    ?.textContent.trim()
                                    .toLowerCase() || '';
                            const name =
                                leftRow
                                    .querySelector('td:nth-child(2)')
                                    ?.textContent.trim()
                                    .toLowerCase() || '';

                            // Check if row matches search
                            const matches =
                                code.includes(searchTerm) ||
                                name.includes(searchTerm);

                            // Show/hide both rows
                            leftRow.style.display = matches ? '' : 'none';
                            rightRow.style.display = matches ? '' : 'none';
                        });

                        // Handle category headers visibility
                        const categories =
                            leftTable.querySelectorAll('tr:has(.bg-info)');
                        categories.forEach((categoryRow) => {
                            const categoryIndex =
                                Array.from(leftRows).indexOf(categoryRow);
                            let hasVisibleRows = false;

                            // Check next rows until next category
                            let currentIndex = categoryIndex + 1;
                            while (
                                currentIndex < leftRows.length &&
                                !leftRows[currentIndex].querySelector(
                                    '.bg-info',
                                )
                            ) {
                                if (
                                    leftRows[currentIndex].style.display !==
                                    'none'
                                ) {
                                    hasVisibleRows = true;
                                    break;
                                }
                                currentIndex++;
                            }

                            // Show/hide category header based on visible rows
                            categoryRow.style.display =
                                hasVisibleRows || searchTerm === ''
                                    ? ''
                                    : 'none';
                            rightRows[categoryIndex].style.display =
                                categoryRow.style.display;
                        });
                    });
            });
        });
    </script>
@endsection
