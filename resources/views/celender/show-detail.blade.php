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

        .href {
            color: blue !important;
        }

        .search-role {
            height: 37px;
        }

        .test {
            max-width: 70%;
            overflow-x: auto;
        }

        .date-celender {
            border-radius: 5px;
        }

        .date-list {
            margin-left: 0px !important;
            padding: 0px !important
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

        .keywork {
            border: 1px solid black;
            width: 25px;
            text-align: center;
        }

        .bg-yellow {
            background-color: yellow;
        }

        .employee-list {
            max-height: 500px;
            overflow-y: auto;

        }

        .employee-card {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
            background-color: #f9f9f9;
            margin-bottom: 10px;
            min-height: 50px;
        }

        .modal-lg {
            max-width: 80%;
        }
    </style>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Chi Tiết Lịch Làm Việc</h4>
                    </div>
                </div>
                <div class="ps-2 d-flex my-2">
                    <a class="btn btn-success" href="{{ route('admin.celender.home') }}">Lịch làm việc</a>
                    <button type="button" class="btn btn-primary ms-2" data-bs-toggle="modal"
                        data-bs-target="#todayEmployeesModal">
                        Nhân viên làm việc hôm nay
                    </button>
                </div>
                <div class="px-0 pb-2">
                    <ul class="nav nav-tabs px-4" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="VVP-tab" data-bs-toggle="tab" data-bs-target="#VVP"
                                type="button" role="tab" aria-controls="VVP" aria-selected="true">Hàng Nhật - Hàng
                                Chợ</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="A7A-tab" data-bs-toggle="tab" data-bs-target="#A7A"
                                type="button" role="tab" aria-controls="A7A" aria-selected="false">Trực Phòng
                                Ăn</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="parttime-tab" data-bs-toggle="tab" data-bs-target="#parttime"
                                type="button" role="tab" aria-controls="parttime" aria-selected="false">Đổ Rác
                                WC</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="women-tab" data-bs-toggle="tab" data-bs-target="#women"
                                type="button" role="tab" aria-controls="women" aria-selected="false">Trực WC
                                Nữ</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="men-tab" data-bs-toggle="tab" data-bs-target="#men"
                                type="button" role="tab" aria-controls="men" aria-selected="false">Trực WC Nam</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <!-- Hàng Nhật -->
                        <div class="tab-pane fade show active" id="VVP" role="tabpanel" aria-labelledby="VVP-tab">
                            <!-- Nội dung cho tab VVP -->
                            <div class="px-0 pb-2 d-flex mt-4">
                                <div class="ms-2"><label class="N keywork">N </label><label for="">Ca ngày</label>
                                </div>
                                <div class="ms-4"><label class="D keywork">D </label><label for="">Ca đêm</label>
                                </div>
                                <div class="ms-4"><label class="X keywork">X </label><label for="">Nghĩ</label>
                                </div>
                                <div class="ms-4"><label class="TC keywork bg-yellow">TC </label><label
                                        for="">Tăng cường đêm</label></div>
                                <div class="ms-4"><label class="LN keywork bg-yellow">LN </label><label for="">Làm
                                        thêm ca ngày</label></div>
                            </div>
                            <div class="px-0 pb-2">
                                <div class="table-responsive p-0 d-flex">
                                    <div class="">
                                        <table class="table align-items-center mb-4">
                                            <tbody>
                                                @if (isset($categories))
                                                    @foreach ($categories as $key => $category)
                                                        <tr>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder col-1 text-center">
                                                                Mã NV<br>&nbsp;</th>
                                                            <th
                                                                class="text-uppercase text-xxs font-weight-bolder ps-2 col-1 text-center">
                                                                Họ và tên<br>&nbsp;</th>
                                                            @foreach ($dates as $key => $date)
                                                                <th
                                                                    class="text-uppercase text-xxs font-weight-bolder col-1 date-list text-center">
                                                                    {{ $formatDate->formatTimeDate($date) }}<br>{{ $formatDate->dayOfWeek($date) }}
                                                                </th>
                                                            @endforeach
                                                        </tr>
                                                        <tr>
                                                            <td colspan="34" class="text-center bg-info">
                                                                <p class="text-xs font-weight-bold mb-0 px-3">
                                                                    {{ $category->name }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        @if (isset($celenderDetailsHNHC))
                                                            @foreach ($celenderDetailsHNHC as $key => $celenderDetailHNHC)
                                                                @if ($celenderDetailHNHC->employee->category_celender_id == $category->id)
                                                                    <tr>
                                                                        <td>
                                                                            <p class="text-xs font-weight-bold mb-0">
                                                                                {{ $celenderDetailHNHC->employee->code }}
                                                                            </p>
                                                                        </td>
                                                                        <td>
                                                                            <p class="text-xs font-weight-bold mb-0">
                                                                                {{ $celenderDetailHNHC->employee->name }}
                                                                            </p>
                                                                        </td>
                                                                        @foreach ($dates as $key => $date)
                                                                            <?php $fill = 'day' . $key + 1; ?>
                                                                            <td>
                                                                                <input
                                                                                    class="form-controll mw-input {{ $celenderDetailHNHC->$fill ?? '' }}"
                                                                                    type="text"
                                                                                    value="{{ $celenderDetailHNHC->$fill ?? '' }}"
                                                                                    disabled>
                                                                            </td>
                                                                        @endforeach
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Trực Nhà Ăn -->
                        <div class="tab-pane fade" id="A7A" role="tabpanel" aria-labelledby="A7A-tab">
                            <!-- Nội dung cho tab Trực Nhà Ăn -->
                            <div class="px-0 pb-2 d-flex ms-4 mt-4">
                                <div class="ms-5"><label class="X keywork">X </label><label for="">Ngày trực vệ
                                        sinh</label></div>
                            </div>
                            <div class="px-0 pb-2">
                                <div class="table-responsive p-0 d-flex">
                                    <div class="">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1">Mã
                                                        NV<br>&nbsp;</th>
                                                    <th class="text-uppercase text-xxs font-weight-bolder ps-2 col-1">Họ và
                                                        tên<br>&nbsp;</th>
                                                    @foreach ($dates as $key => $date)
                                                        <th
                                                            class="text-uppercase text-xxs font-weight-bolder col-1 date-list text-center">
                                                            {{ $formatDate->formatTimeDate($date) }}<br>{{ $formatDate->dayOfWeek($date) }}
                                                        </th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (isset($celenderDetailsEatroom))
                                                    @foreach ($celenderDetailsEatroom as $key => $celenderDetailEatroom)
                                                        <tr>
                                                            <td>
                                                                <p class="text-xs font-weight-bold mb-0">
                                                                    {{ $celenderDetailEatroom->employee->code }}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs font-weight-bold mb-0">
                                                                    {{ $celenderDetailEatroom->employee->name }}
                                                                </p>
                                                            </td>
                                                            @foreach ($dates as $key => $date)
                                                                <?php $fill = 'day' . $key + 1; ?>
                                                                <td>
                                                                    <input
                                                                        class="form-controll mw-input {{ $celenderDetailEatroom->$fill ?? '' }}"
                                                                        type="text"
                                                                        value="{{ $celenderDetailEatroom->$fill ?? '' }}"
                                                                        disabled>
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
                        </div>

                        <!-- VỨt Rác WC -->
                        <div class="tab-pane fade" id="parttime" role="tabpanel" aria-labelledby="parttime-tab">
                            <!-- Nội dung cho tab Vứt Rác WC -->
                            <div class="px-0 pb-2 d-flex ms-4 mt-4">
                                <div class="ms-5"><label class="X keywork">X </label><label for="">Ngày trực vệ
                                        sinh</label></div>
                            </div>
                            <div class="px-0 pb-2">
                                <div class="table-responsive p-0 d-flex">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-xxs font-weight-bolder col-1">Mã
                                                    NV<br>&nbsp;</th>
                                                <th class="text-uppercase text-xxs font-weight-bolder ps-2 col-1">Họ và
                                                    tên<br>&nbsp;</th>
                                                @foreach ($dates as $key => $date)
                                                    @if ($formatDate->dayOfWeek($date) == 'T7')
                                                        <th
                                                            class="text-uppercase text-xxs font-weight-bolder col-1 date-list text-center">
                                                            {{ $formatDate->formatTimeDate($date) }}<br>{{ $formatDate->dayOfWeek($date) }}
                                                        </th>
                                                    @endif
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (isset($celenderDetailsWC))
                                                @foreach ($celenderDetailsWC as $key => $celenderDetailWC)
                                                    <tr>
                                                        <td>
                                                            <p class="text-xs font-weight-bold mb-0">
                                                                {{ $celenderDetailWC->employee->code }}
                                                            </p>
                                                        </td>
                                                        <td>
                                                            <p class="text-xs font-weight-bold mb-0">
                                                                {{ $celenderDetailWC->employee->name }}
                                                            </p>
                                                        </td>
                                                        <?php $keyDate = 0; ?>
                                                        @foreach ($dates as $key => $date)
                                                            @if ($formatDate->dayOfWeek($date) == 'T7')
                                                                <?php $fill = 'day' . $keyDate + 1;
                                                                $keyDate += 1; ?>
                                                                <td class="text-center">
                                                                    <input
                                                                        class="form-controll mw-input-wc {{ $celenderDetailWC->$fill ?? '' }}"
                                                                        type="text"
                                                                        value="{{ $celenderDetailWC->$fill ?? '' }}"
                                                                        disabled>
                                                                </td>
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Women Tab -->
                        <div class="tab-pane fade" id="women" role="tabpanel" aria-labelledby="women-tab">
                            <!-- Nội dung cho tab Women -->
                            <div class="px-0 pb-2 d-flex ms-4 mt-4">
                                <div class="ms-5"><label class="X keywork">X </label><label for="">Ngày trực vệ
                                        sinh</label></div>
                            </div>
                            <div class="px-0 pb-2">
                                <div class="table-responsive p-0 d-flex">
                                    <div class="">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1">Mã
                                                        NV<br>&nbsp;</th>
                                                    <th class="text-uppercase text-xxs font-weight-bolder ps-2 col-1">Họ và
                                                        tên<br>&nbsp;</th>
                                                    @foreach ($dates as $key => $date)
                                                        <th
                                                            class="text-uppercase text-xxs font-weight-bolder col-1 date-list text-center">
                                                            {{ $formatDate->formatTimeDate($date) }}<br>{{ $formatDate->dayOfWeek($date) }}
                                                        </th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (isset($celenderDetailsWCCleanWomen))
                                                    @foreach ($celenderDetailsWCCleanWomen as $key => $celenderDetailWCClean)
                                                        <tr>
                                                            <td>
                                                                <p class="text-xs font-weight-bold mb-0">
                                                                    {{ $celenderDetailWCClean->employee->code }}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs font-weight-bold mb-0">
                                                                    {{ $celenderDetailWCClean->employee->name }}
                                                                </p>
                                                            </td>
                                                            @foreach ($dates as $key => $date)
                                                                <?php $fill = 'day' . $key + 1; ?>
                                                                <td>
                                                                    <input
                                                                        class="form-controll mw-input {{ $celenderDetailWCClean->$fill ?? '' }}"
                                                                        type="text"
                                                                        value="{{ $celenderDetailWCClean->$fill ?? '' }}"
                                                                        disabled>
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
                        </div>

                        <!-- Men Tab -->
                        <div class="tab-pane fade" id="men" role="tabpanel" aria-labelledby="men-tab">
                            <!-- Nội dung cho tab Men -->
                            <div class="px-0 pb-2 d-flex ms-4 mt-4">
                                <div class="ms-5"><label class="X keywork">X </label><label for="">Ngày trực vệ
                                        sinh</label></div>
                            </div>
                            <div class="px-0 pb-2">
                                <div class="table-responsive p-0 d-flex">
                                    <div class="">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-xxs font-weight-bolder col-1">Mã
                                                        NV<br>&nbsp;</th>
                                                    <th class="text-uppercase text-xxs font-weight-bolder ps-2 col-1">Họ và
                                                        tên<br>&nbsp;</th>
                                                    @foreach ($dates as $key => $date)
                                                        <th
                                                            class="text-uppercase text-xxs font-weight-bolder col-1 date-list text-center">
                                                            {{ $formatDate->formatTimeDate($date) }}<br>{{ $formatDate->dayOfWeek($date) }}
                                                        </th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (isset($celenderDetailsWCCleanMen))
                                                    @foreach ($celenderDetailsWCCleanMen as $key => $celenderDetailWCClean)
                                                        <tr>
                                                            <td>
                                                                <p class="text-xs font-weight-bold mb-0">
                                                                    {{ $celenderDetailWCClean->employee->code }}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs font-weight-bold mb-0">
                                                                    {{ $celenderDetailWCClean->employee->name }}
                                                                </p>
                                                            </td>
                                                            @foreach ($dates as $key => $date)
                                                                <?php $fill = 'day' . $key + 1; ?>
                                                                <td>
                                                                    <input
                                                                        class="form-controll mw-input {{ $celenderDetailWCClean->$fill ?? '' }}"
                                                                        type="text"
                                                                        value="{{ $celenderDetailWCClean->$fill ?? '' }}"
                                                                        disabled>
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
                        </div>
                    </div>
                    <a href="{{ route('admin.celender.home') }}" type="button" class="btn btn-danger ms-2">Quay lại</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Nhân Viên Làm Việc Hôm Nay --}}
    <div class="modal fade" id="todayEmployeesModal" tabindex="-1" aria-labelledby="todayEmployeesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- Header danh sách nhân viên -->
                    <h5 class="mt-4">
                        Danh sách nhân viên làm việc ngày
                        @if ($day)
                            {{ \Carbon\Carbon::createFromFormat('d', $day)->format('d/m/Y') }}
                        @else
                            {{ $today->format('d/m/Y') }}
                        @endif
                        ({{ $employeesTodayCount }} nhân viên)
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form filter theo ngày -->
                    <form action="{{ route('admin.celender.detail', ['id' => $id]) }}" method="GET">
                        <div class="form-group">
                            <label for="day">Chọn ngày:</label>
                            <select name="day" id="day" class="form-control" onchange="this.form.submit()"
                                required>
                                <option value="">Chọn ngày</option>
                                @for ($i = 1; $i <= 31; $i++)
                                    <option value="{{ $i }}" {{ $day == $i ? 'selected' : '' }}>Ngày
                                        {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </form>

                    @if ($employeesToday->isEmpty())
                        <p>Không có nhân viên nào làm việc trong ngày này.</p>
                    @else
                        <div class="employee-list row">
                            @foreach ($employeesToday as $detail)
                                <div class="col-3 employee-card">
                                    <p>{{ $detail->employee->name }}</p>
                                    <p><strong>Ca làm việc:</strong> {{ $detail->shift }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            const listClass = ['N', 'D', 'X', 'TC', 'LN'];
            $('input').on('keyup', function() {
                $(this).val($(this).val().toUpperCase());
                for (let i = 0; i < listClass.length; i++) {
                    if ($(this).hasClass(listClass[i])) {
                        $(this).removeClass(listClass[i]);
                    }
                }
                $(this).addClass($(this).val());
            });
        });
    </script>
@endsection
