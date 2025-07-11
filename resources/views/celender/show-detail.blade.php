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
                    <a class="btn btn-link mb-3" href="{{ route('admin.celender.home') }}">
                        <i class="fas fa-arrow-left"></i>
                        Quay lại
                    </a>
                    <ul
                        class="nav nav-tabs flex-nowrap text-nowrap overflow-x-auto overflow-y-hidden"
                        id="myTab"
                        role="tablist">
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
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
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
                            <div class="tab-pane fade show active" id="VVP" role="tabpanel" aria-labelledby="VVP-tab">
                                @foreach ($categories as $key => $category)
                                    <div class="fw-bold bg-info p-2 text-center">
                                        {{ $category->name }}
                                    </div>
                                    <div class="table-responsive d-flex">
                                        {{-- Bảng Nhân Viên --}}
                                        <div class="col-2 table-responsive" style="flex-basis: 19%">
                                            <table class="table table-hover table-bordered">
                                                <thead class="table-light text-center uppercase align-middle">
                                                    <tr>
                                                        <th>
                                                            <div class="table-header-content">Mã NV</div>
                                                        </th>
                                                        <th>
                                                            <div class="table-header-content">Họ và tên</div>
                                                        </th>
                                                    </tr>
                                                </thead>

                                                <tbody class="text-center align-middle">
                                                    {{-- Hàng Nhật - Hàng Chợ --}}
                                                    @if (isset($celenderDetailsHNHC))
                                                        @foreach ($celenderDetailsHNHC as $key => $celenderDetailHNHC)
                                                            @if ($celenderDetailHNHC->employee->category_celender_id == $category->id)
                                                                <tr style="height: 2.75rem">
                                                                    <td>
                                                                        {{ $celenderDetailHNHC->employee->code }}
                                                                    </td>
                                                                    <td class="text-start">
                                                                        {{ $celenderDetailHNHC->employee->name }}
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-10 table-responsive">
                                            <table class="table table-hover table-bordered">
                                                <thead class="table-light text-center uppercase align-middle">
                                                    <tr>
                                                        @foreach ($dates as $date)
                                                            <th>
                                                                <div class="table-header-content">
                                                                    {{ $formatDate->formatTimeDate($date) }}
                                                                    <br />
                                                                    {{ $formatDate->dayOfWeek($date) }}
                                                                </div>
                                                            </th>
                                                        @endforeach

                                                        <th>
                                                            <div class="table-header-content">thao tác</div>
                                                        </th>
                                                    </tr>
                                                </thead>

                                                <tbody class="text-center align-middle">
                                                    {{-- Hàng Nhật - Hàng Chợ --}}
                                                    @if (isset($celenderDetailsHNHC))
                                                        @foreach ($celenderDetailsHNHC as $key => $celenderDetailHNHC)
                                                            @if ($celenderDetailHNHC->employee->category_celender_id == $category->id)
                                                                @php
                                                                    $rowId = 'row-'.$celenderDetailHNHC->employee_id;
                                                                @endphp

                                                                <tr id="{{ $rowId }}">
                                                                    <form
                                                                        method="POST"
                                                                        action="{{ route('admin.celender.update-detail', $id) }}">
                                                                        @csrf
                                                                        <input
                                                                            type="hidden"
                                                                            name="employee_id"
                                                                            value="{{ $celenderDetailHNHC->employee_id }}" />

                                                                        @foreach ($dates as $dateKey => $date)
                                                                            @php
                                                                                $fill = 'day'.($dateKey + 1);
                                                                                $cellValue =
                                                                                    $celenderDetailHNHC->$fill ?? '';
                                                                            @endphp

                                                                            <td>
                                                                                <span
                                                                                    id="{{ $rowId }}-view-{{ $fill }}">
                                                                                    <span
                                                                                        class="badge {{ $workLegends[$cellValue]['class'] ?? '' }}">
                                                                                        {{ $cellValue }}
                                                                                    </span>
                                                                                </span>

                                                                                <select
                                                                                    name="days[{{ $fill }}]"
                                                                                    id="{{ $rowId }}-edit-{{ $fill }}"
                                                                                    class="form-select form-select-sm d-none"
                                                                                    style="min-width: 70px">
                                                                                    {{-- Nếu giá trị hiện tại không có trong $workLegends (fallback) --}}
                                                                                    @if (! isset($workLegends[$cellValue]))
                                                                                        <option
                                                                                            value="{{ $cellValue }}"
                                                                                            selected>
                                                                                            {{ $cellValue }}
                                                                                        </option>
                                                                                    @endif

                                                                                    @foreach ($workLegends as $code => $legend)
                                                                                        <option
                                                                                            value="{{ $code }}"
                                                                                            {{ $cellValue == $code ? 'selected' : '' }}>
                                                                                            {{ $code }}
                                                                                        </option>
                                                                                    @endforeach

                                                                                    {{-- Cho phép chọn lịch rỗng (xoá dữ liệu) --}}
                                                                                    <option value=""></option>
                                                                                </select>
                                                                            </td>
                                                                        @endforeach

                                                                        @php
                                                                            $hiddenRoles = [14, 18, 19];
                                                                        @endphp

                                                                        @if (! in_array(Auth::user()->role_id, $hiddenRoles))
                                                                            <td>
                                                                                {{-- Nút Cập nhật chọn (hiển thị mặc định) --}}
                                                                                <button
                                                                                    type="button"
                                                                                    id="{{ $rowId }}-edit-btn"
                                                                                    class="btn btn-sm btn-warning"
                                                                                    onclick="toggleEdit('{{ $rowId }}')">
                                                                                    Cập nhật lịch
                                                                                </button>

                                                                                {{-- Nút Huỷ (ẩn ban đầu) --}}
                                                                                <button
                                                                                    type="button"
                                                                                    id="{{ $rowId }}-cancel-btn"
                                                                                    class="btn btn-sm btn-danger d-none"
                                                                                    onclick="cancelEdit('{{ $rowId }}')">
                                                                                    Huỷ
                                                                                </button>

                                                                                {{-- Nút Lưu (ẩn ban đầu) --}}
                                                                                <button
                                                                                    type="submit"
                                                                                    id="{{ $rowId }}-save-btn"
                                                                                    class="btn btn-sm btn-success d-none">
                                                                                    Lưu
                                                                                </button>
                                                                            </td>
                                                                        @endif
                                                                    </form>
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
                                aria-labelledby="{{ $tabCode }}-tab">
                                <div class="d-flex">
                                    <div class="col-4 table-responsive">
                                        <table class="table table-hover table-bordered">
                                            <thead class="table-light text-center uppercase align-middle">
                                                <tr>
                                                    <th>
                                                        <div class="table-header-content">Mã NV</div>
                                                    </th>
                                                    <th>
                                                        <div class="table-header-content">Họ và tên</div>
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tbody class="text-center align-middle">
                                                {{-- Hàng Nhật - Hàng Chợ --}}
                                                @if ($tabCode == 'VVP' && isset($categories))
                                                    @foreach ($categories as $key => $category)
                                                        <tr style="height: 2.75rem">
                                                            <td colspan="9999" class="fw-bold bg-info">
                                                                <span class="d-none">
                                                                    {{ $category->name }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        @if (isset($celenderDetailsHNHC))
                                                            @foreach ($celenderDetailsHNHC as $key => $celenderDetailHNHC)
                                                                @if ($celenderDetailHNHC->employee->category_celender_id == $category->id)
                                                                    <tr style="height: 2.75rem">
                                                                        <td>
                                                                            {{ $celenderDetailHNHC->employee->code }}
                                                                        </td>
                                                                        <td class="text-start">
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
                                                        <tr style="height: 2.75rem">
                                                            <td>
                                                                {{ $celenderDetailEatroom->employee->code }}
                                                            </td>
                                                            <td class="text-start">
                                                                {{ $celenderDetailEatroom->employee->name }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif

                                                {{-- Đổ Rác WC --}}
                                                @if ($tabCode == 'part-time' && isset($celenderDetailsWC))
                                                    @foreach ($celenderDetailsWC as $key => $celenderDetailWC)
                                                        <tr style="height: 2.75rem">
                                                            <td>
                                                                {{ $celenderDetailWC->employee->code }}
                                                            </td>
                                                            <td class="text-start">
                                                                {{ $celenderDetailWC->employee->name }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif

                                                {{-- Trực WC Nữ --}}
                                                @if ($tabCode == 'women' && isset($celenderDetailsWCCleanWomen))
                                                    @foreach ($celenderDetailsWCCleanWomen as $key => $womenWC)
                                                        <tr style="height: 2.75rem">
                                                            <td>
                                                                {{ $womenWC->employee->code }}
                                                            </td>
                                                            <td class="text-start">
                                                                {{ $womenWC->employee->name }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif

                                                {{-- Trực WC Nam --}}
                                                @if ($tabCode == 'men' && isset($celenderDetailsWCCleanMen))
                                                    @foreach ($celenderDetailsWCCleanMen as $key => $menWC)
                                                        <tr style="height: 2.75rem">
                                                            <td>
                                                                {{ $menWC->employee->code }}
                                                            </td>
                                                            <td class="text-start">
                                                                {{ $menWC->employee->name }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-8 table-responsive">
                                        <table class="table table-hover table-bordered">
                                            <thead class="table-light text-center uppercase align-middle">
                                                <tr>
                                                    @foreach ($dates as $date)
                                                        @if ($tabCode == 'part-time' && $formatDate->dayOfWeek($date) == 'T7')
                                                            <th>
                                                                <div class="table-header-content">
                                                                    {{ $formatDate->formatTimeDate($date) }}
                                                                    <br />
                                                                    {{ $formatDate->dayOfWeek($date) }}
                                                                </div>
                                                            </th>
                                                        @elseif ($tabCode != 'part-time')
                                                            <th>
                                                                <div class="table-header-content">
                                                                    {{ $formatDate->formatTimeDate($date) }}
                                                                    <br />
                                                                    {{ $formatDate->dayOfWeek($date) }}
                                                                </div>
                                                            </th>
                                                        @endif
                                                    @endforeach
                                                </tr>
                                            </thead>

                                            <tbody class="text-center align-middle">
                                                {{-- Hàng Nhật - Hàng Chợ --}}
                                                @if ($tabCode == 'VVP' && isset($categories))
                                                    @foreach ($categories as $key => $category)
                                                        <tr style="height: 2.75rem">
                                                            <td colspan="9999" class="fw-bold bg-info">
                                                                {{ $category->name }}
                                                            </td>
                                                        </tr>
                                                        @if (isset($celenderDetailsHNHC))
                                                            @foreach ($celenderDetailsHNHC as $key => $celenderDetailHNHC)
                                                                @if ($celenderDetailHNHC->employee->category_celender_id == $category->id)
                                                                    <tr style="height: 2.75rem">
                                                                        @foreach ($dates as $key => $date)
                                                                            @php
                                                                                $fill = 'day'.$key + 1;
                                                                            @endphp

                                                                            <td>
                                                                                <span
                                                                                    class="badge {{ $workLegends[$celenderDetailHNHC->$fill]['class'] ?? '' }}">
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
                                                        <tr style="height: 2.75rem">
                                                            @foreach ($dates as $key => $date)
                                                                @php
                                                                    $fill = 'day'.$key + 1;
                                                                @endphp

                                                                <td>
                                                                    @if ($celenderDetailEatroom->$fill)
                                                                        <span
                                                                            class="badge {{ $workLegends['VS']['class'] ?? '' }}">
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
                                                        <tr style="height: 2.75rem">
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
                                                                                class="badge {{ $workLegends['VS']['class'] ?? '' }}">
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
                                                        <tr style="height: 2.75rem">
                                                            @foreach ($dates as $key => $date)
                                                                @php
                                                                    $fill = 'day'.$key + 1;
                                                                @endphp

                                                                <td>
                                                                    @if ($womenWC->$fill)
                                                                        <span
                                                                            class="badge {{ $workLegends['VS']['class'] ?? '' }}">
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
                                                        <tr style="height: 2.75rem">
                                                            @foreach ($dates as $key => $date)
                                                                @php
                                                                    $fill = 'day'.$key + 1;
                                                                @endphp

                                                                <td>
                                                                    @if ($menWC->$fill)
                                                                        <span
                                                                            class="badge {{ $workLegends['VS']['class'] ?? '' }}">
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

                    <a class="btn btn-link mt-3" href="{{ route('admin.celender.home') }}">
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
            /*** 1. Gán class theo giá trị input ***/
            const listClass = ['N', 'D', 'X', 'TC', 'LN']
            $('input').on('keyup', function () {
                if (!$(this).attr('id') || $(this).attr('id') !== 'employeeSearch') {
                    $(this).val($(this).val().toUpperCase())
                    for (let cls of listClass) {
                        $(this).removeClass(cls)
                    }
                    $(this).addClass($(this).val())
                }
            })

            /*** 2. Thêm ô tìm kiếm ***/
            $('.d-flex.flex-wrap.gap-3.align-items-center.m-3').after(`
            <div class="mb-3">
                <input type="text" id="employeeSearch" class="form-control w-100 w-md-25" placeholder="Tìm kiếm nhân viên...">
            </div>
        `)

            /*** 3. Tìm kiếm nhân viên ***/
            $('#employeeSearch').on('input', function () {
                const searchValue = $(this).val().toLowerCase().trim()
                const activeTabId = $('.tab-pane.active').attr('id')

                const $leftTable = $(`#${activeTabId} .col-2 table`)
                const $rightTable = $(`#${activeTabId} .col-10 table`)

                $leftRows.each(function (index) {
                    const $leftRow = $(this)
                    const $rightRow = $rightRows.eq(index)

                    if ($leftRow.find('.bg-info').length > 0) return

                    const code = $leftRow.find('td:first-child').text().toLowerCase().trim()
                    const name = $leftRow.find('td:nth-child(2)').text().toLowerCase().trim()

                    if (code.includes(searchValue) || name.includes(searchValue)) {
                        $leftRow.show()
                        $rightRow.show()
                        let $prevLeft = $leftRow.prev(),
                            $prevRight = $rightRow.prev()
                        while ($prevLeft.length && !$prevLeft.find('.bg-info').length) {
                            $prevLeft = $prevLeft.prev()
                            $prevRight = $prevRight.prev()
                        }
                        $prevLeft.show()
                        $prevRight.show()
                    } else {
                        $leftRow.hide()
                        $rightRow.hide()
                    }
                })

                /** Ẩn category nếu không có dòng hiển thị **/
                $(`#${activeTabId} .col-4 .bg-info`)
                    .closest('tr')
                    .each(function (i) {
                        const $leftHeader = $(this)
                        const $rightHeader = $(`#${activeTabId} .col-8 .bg-info`).closest('tr').eq(i)

                        let $next = $leftHeader.next(),
                            visible = false
                        while ($next.length && !$next.find('.bg-info').length) {
                            if ($next.is(':visible')) {
                                visible = true
                                break
                            }
                            $next = $next.next()
                        }

                        if (!visible) {
                            $leftHeader.hide()
                            $rightHeader.hide()
                        }
                    })
            })

            /*** 4. Reset tìm kiếm khi đổi tab ***/
            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function () {
                const $searchInput = $('#employeeSearch')
                if ($searchInput.val()) {
                    $searchInput.val('').trigger('input')
                }
            })

            /*** 5. Đồng bộ chiều cao dòng giữa 2 bảng ***/
            const $leftRows = $('.col-2 table tbody tr')
            const $rightRows = $('.col-10 table tbody tr')
            $leftRows.each(function (i) {
                const leftHeight = $(this).outerHeight()
                const rightHeight = $rightRows.eq(i)?.outerHeight() || 0
                const max = Math.max(leftHeight, rightHeight)
                $(this).height(max)
                $rightRows.eq(i)?.height(max)
            })
        })

        /*** 6. Các hàm toggle bên ngoài $(document).ready) để dùng onclick="..." ***/
        function toggleEdit(rowId) {
            const selects = document.querySelectorAll(`#${rowId} select`)
            const spans = document.querySelectorAll(`#${rowId} span[id^="${rowId}-view-"]`)
            selects.forEach((select) => select.classList.remove('d-none'))
            spans.forEach((span) => span.classList.add('d-none'))
            document.getElementById(`${rowId}-edit-btn`).classList.add('d-none')
            document.getElementById(`${rowId}-save-btn`).classList.remove('d-none')
            document.getElementById(`${rowId}-cancel-btn`).classList.remove('d-none')
        }

        function cancelEdit(rowId) {
            const selects = document.querySelectorAll(`#${rowId} select`)
            const spans = document.querySelectorAll(`#${rowId} span[id^="${rowId}-view-"]`)
            selects.forEach((select) => select.classList.add('d-none'))
            spans.forEach((span) => span.classList.remove('d-none'))
            document.getElementById(`${rowId}-edit-btn`).classList.remove('d-none')
            document.getElementById(`${rowId}-save-btn`).classList.add('d-none')
            document.getElementById(`${rowId}-cancel-btn`).classList.add('d-none')
        }
    </script>
@endsection
