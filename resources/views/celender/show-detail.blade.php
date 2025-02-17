@extends('layouts.layout')

@section('styles')
    <style>
        .date-list {
            margin-left: 0px !important;
            padding: 0px !important;
        }

        .mw-input {
            max-width: 22px;
            font-size: 12px;
            text-align: center;
        }

        .mw-input-wc {
            max-width: 70px;
            font-size: 12px;
            text-align: center;
        }

        .N {
            color: blue;
            font-weight: bolder;
        }

        .D {
            color: black;
            font-weight: bolder;
        }

        .X {
            color: red;
            font-weight: bolder;
        }

        .TC {
            color: red;
            font-weight: bolder;
        }

        .LN {
            color: red;
            font-weight: bolder;
        }

        input {
            font-weight: bolder;
        }

        /* .keywork {
                                                                    border: 1px solid black;
                                                                    width: 25px;
                                                                    text-align: center;
                                                                } */

        .bg-yellow {
            background-color: yellow;
        }

        .table-container {
            overflow-x: auto;
            /* Cho phép cuộn ngang */
            max-width: 100%;
            /* Đảm bảo bảng không vượt quá chiều rộng của phần tử cha */
        }

        .table {
            min-width: 1000px;
            /* Thiết lập chiều rộng tối thiểu cho bảng để có thể cuộn */
            border-collapse: collapse;
        }

        .sticky-column {
            position: sticky;
            left: 0;
            z-index: 1;
            background-color: white;
            box-shadow: 2px 0px 5px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            white-space: nowrap;
            /* Ngăn chặn xuống dòng trong các cột */
        }

        th.sticky-column {
            background-color: #f8f9fa;
            /* Màu nền cho header cố định */
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
                        @foreach ($tabWork as $key => $tab)
                            <div
                                class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                id="{{ $key }}"
                                role="tabpanel"
                                aria-labelledby="{{ $key }}-tab"
                            >
                                <div class="table-responsive">
                                    <table
                                        class="table table-hover table-bordered"
                                    >
                                        <thead
                                            class="table-light text-center uppercase align-middle"
                                        >
                                            <tr>
                                                <th class="sticky-column">
                                                    Mã NV
                                                </th>
                                                <th class="sticky-column">
                                                    Họ và tên
                                                </th>
                                                @foreach ($dates as $date)
                                                    @if ($key == 'part-time' && $formatDate->dayOfWeek($date) == 'T7')
                                                        <th>
                                                            {{ $formatDate->formatTimeDate($date) }}
                                                            <br />
                                                            {{ $formatDate->dayOfWeek($date) }}
                                                        </th>
                                                    @elseif ($key != 'part-time')
                                                        <th>
                                                            {{ $formatDate->formatTimeDate($date) }}
                                                            <br />
                                                            {{ $formatDate->dayOfWeek($date) }}
                                                        </th>
                                                    @endif
                                                @endforeach
                                            </tr>
                                        </thead>

                                        <tbody class="text-center align-middle">
                                            {{-- Hàng Nhật - Hàng Chợ --}}
                                            @if ($key == 'VVP' && isset($categories))
                                                @foreach ($categories as $key => $category)
                                                    <tr>
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
                                                                <tr>
                                                                    <td
                                                                        class="sticky-column"
                                                                    >
                                                                        {{ $celenderDetailHNHC->employee->code }}
                                                                    </td>
                                                                    <td
                                                                        class="sticky-column"
                                                                    >
                                                                        {{ $celenderDetailHNHC->employee->name }}
                                                                    </td>
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
                                            @if ($key == 'A7A' && isset($celenderDetailsEatroom))
                                                @foreach ($celenderDetailsEatroom as $key => $celenderDetailEatroom)
                                                    <tr>
                                                        <td>
                                                            {{ $celenderDetailEatroom->employee->code }}
                                                        </td>
                                                        <td class="text-start">
                                                            {{ $celenderDetailEatroom->employee->name }}
                                                        </td>
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
                                            @if ($key == 'part-time' && isset($celenderDetailsWC))
                                                @foreach ($celenderDetailsWC as $key => $celenderDetailWC)
                                                    <tr>
                                                        <td>
                                                            {{ $celenderDetailWC->employee->code }}
                                                        </td>
                                                        <td class="text-start">
                                                            {{ $celenderDetailWC->employee->name }}
                                                        </td>

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
                                            @if ($key == 'women' && isset($celenderDetailsWCCleanWomen))
                                                @foreach ($celenderDetailsWCCleanWomen as $key => $womenWC)
                                                    <tr>
                                                        <td>
                                                            {{ $womenWC->employee->code }}
                                                        </td>
                                                        <td class="text-start">
                                                            {{ $womenWC->employee->name }}
                                                        </td>
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
                                            @if ($key == 'men' && isset($celenderDetailsWCCleanMen))
                                                @foreach ($celenderDetailsWCCleanMen as $key => $menWC)
                                                    <tr>
                                                        <td>
                                                            {{ $menWC->employee->code }}
                                                        </td>
                                                        <td class="text-start">
                                                            {{ $menWC->employee->name }}
                                                        </td>
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
        // Select all table elements
        const tables = document.querySelectorAll('.table');

        // Define shift types
        const SHIFT_TYPES = {
            N: 'Ca ngày',
            D: 'Ca đêm',
            X: 'Nghỉ',
            TC: 'Tăng cường đêm',
            LN: 'Làm thêm ca ngày',
            VS: 'Vệ sinh',
        };

        // Create filter container
        function createFilterContainer() {
            const filterContainer = document.createElement('div');
            filterContainer.className =
                'filter-container d-flex flex-wrap gap-3 mb-3';

            // Employee name filter (input field)
            const employeeFilter = document.createElement('div');
            employeeFilter.className = 'form-group';
            employeeFilter.innerHTML = `
        <label for="employeeFilter" class="form-label">Tên Nhân Viên:</label>
        <input type="text" id="employeeFilter" class="form-control form-control-sm" placeholder="Nhập tên nhân viên" />
    `;

            // Shift type filter
            const shiftFilter = document.createElement('div');
            shiftFilter.className = 'form-group';
            shiftFilter.innerHTML = `
        <label for="shiftFilter" class="form-label">Ca làm việc:</label>
        <select id="shiftFilter" class="form-select form-select-sm">
            <option value="">Tất cả</option>
            ${Object.entries(SHIFT_TYPES)
                .map(
                    ([key, value]) =>
                        `<option value="${key}">${value} (${key})</option>`,
                )
                .join('')}
        </select>
    `;

            filterContainer.appendChild(employeeFilter);
            filterContainer.appendChild(shiftFilter);

            return filterContainer;
        }

        // Initialize filters
        function initializeFilters() {
            const tabContent = document.getElementById('myTabContent');
            const filterContainer = createFilterContainer();
            tabContent.insertBefore(filterContainer, tabContent.firstChild);

            // Add event listeners
            const filters = {
                shift: document.getElementById('shiftFilter'),
                employee: document.getElementById('employeeFilter'),
            };

            Object.values(filters).forEach((filter) => {
                filter.addEventListener('input', () => applyFilters(filters));
                filter.addEventListener('change', () => applyFilters(filters));
            });
        }

        // Apply filters
        function applyFilters(filters) {
            const activeTab = document.querySelector('.tab-pane.active');
            if (!activeTab) return;

            const rows = activeTab.querySelectorAll('tbody tr');
            rows.forEach((row) => {
                if (row.cells.length < 2) {
                    // Show category headers always
                    row.style.display = '';
                    return;
                }

                const shiftMatch = filters.shift.value
                    ? Array.from(row.cells)
                          .slice(2)
                          .some(
                              (cell) =>
                                  cell.textContent.trim() ===
                                  filters.shift.value,
                          )
                    : true;

                const employeeMatch = filters.employee.value
                    ? row.cells[1]?.textContent
                          .trim()
                          .toLowerCase()
                          .includes(filters.employee.value.toLowerCase())
                    : true;

                row.style.display = shiftMatch && employeeMatch ? '' : 'none';
            });

            // Show category headers if any child rows are visible
            const categories = activeTab.querySelectorAll('tr.fw-bold.bg-info');
            categories.forEach((category) => {
                let nextRow = category.nextElementSibling;
                let hasVisibleChildren = false;

                while (nextRow && !nextRow.classList.contains('fw-bold')) {
                    if (nextRow.style.display !== 'none') {
                        hasVisibleChildren = true;
                        break;
                    }
                    nextRow = nextRow.nextElementSibling;
                }

                category.style.display = hasVisibleChildren ? '' : 'none';
            });
        }

        // Add tab change handler
        function handleTabChange() {
            const tabButtons = document.querySelectorAll(
                '[data-bs-toggle="tab"]',
            );
            tabButtons.forEach((button) => {
                button.addEventListener('shown.bs.tab', () => {
                    const filters = {
                        shift: document.getElementById('shiftFilter'),
                        employee: document.getElementById('employeeFilter'),
                    };
                    applyFilters(filters);
                });
            });
        }

        // Add clear filters button
        function addClearFiltersButton() {
            const filterContainer = document.querySelector('.filter-container');
            const clearButton = document.createElement('div');
            clearButton.className = 'form-group d-flex align-items-end';
            clearButton.innerHTML = `
        <button class="btn btn-outline-secondary btn-sm" id="clearFilters">
            Xóa bộ lọc
        </button>
    `;

            clearButton
                .querySelector('#clearFilters')
                .addEventListener('click', () => {
                    document.getElementById('shiftFilter').value = '';
                    document.getElementById('employeeFilter').value = '';

                    const filters = {
                        shift: document.getElementById('shiftFilter'),
                        employee: document.getElementById('employeeFilter'),
                    };
                    applyFilters(filters);
                });

            filterContainer.appendChild(clearButton);
        }

        // Initialize everything
        document.addEventListener('DOMContentLoaded', () => {
            initializeFilters();
            handleTabChange();
            addClearFiltersButton();
        });
    </script>
@endsection
