@extends('layouts.layout')

@php
    $workLegends = [
        'N' => ['id' => 'N', 'title' => 'Ca ngày', 'class' => 'text-bg-primary'],
        'D' => ['id' => 'D', 'title' => 'Ca đêm', 'class' => 'text-bg-dark'],
        'X' => ['id' => 'X', 'title' => 'Nghĩ', 'class' => 'text-bg-danger'],
        'TC' => ['id' => 'TC', 'title' => 'Tăng cường đêm', 'class' => 'text-bg-danger'],
        'LN' => ['id' => 'LN', 'title' => 'Làm thêm ca ngày', 'class' => 'text-bg-danger'],
        'VS' => ['id' => 'VS', 'title' => 'Ngày trực vệ sinh', 'class' => 'text-bg-warning'],
    ];

    $checkEatRoom = false;
    $checkWC = false;
    $keyDate = 0;
    $checkWCCleanWomen = false;
    $checkWCCleanMen = false;
@endphp

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Lịch Làm Việc</h4>
                    </div>
                </div>

                <div class="card-body">
                    <a class="btn btn-link" href="{{ route('admin.employee-show.celender') }}">
                        <i class="fas fa-arrow-left"></i>
                        Quay lại
                    </a>
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <ul class="list-group mb-3">
                                <li class="list-group-item text-bg-light">
                                    <h6 class="fw-bold m-0">Chú thích</h6>
                                </li>
                                @foreach ($workLegends as $workLegend)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="badge {{ $workLegend['class'] }}">
                                            {{ $workLegend['id'] }}
                                        </span>
                                        <span class="fw-semibold">
                                            {{ $workLegend['title'] }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        {{-- Hàng nhật --}}
                        <div class="col-12 col-sm-6">
                            <ul class="list-group mb-3">
                                <li class="list-group-item text-bg-light">
                                    <h6 class="fw-bold m-0">Lịch làm việc</h6>
                                </li>
                                @if (isset($celenderDetailHNHC))
                                    @foreach ($dates as $key => $date)
                                        @php
                                            $fill = 'day' . $key + 1;
                                            $isWeekend =
                                                $formatDate->dayOfWeek($date) == 'T7' ||
                                                $formatDate->dayOfWeek($date) == 'CN';
                                        @endphp

                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center {{ $isWeekend ? 'text-bg-secondary' : '' }}">
                                            <span>
                                                {{ $formatDate->formatTimeDMY($date) }}
                                                -
                                                {{ $formatDate->dayOfWeek($date) }}
                                            </span>
                                            <span
                                                class="badge {{ $workLegends[$celenderDetailHNHC->$fill]['class'] ?? '' }}">
                                                {{ $celenderDetailHNHC->$fill ?? '' }}
                                            </span>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Hiện tại chưa có phân công.
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div class="col-12 col-sm-6">
                            {{-- Trực Nhà Ăn --}}
                            <ul class="list-group mb-3">
                                <li class="list-group-item text-bg-light">
                                    <h6 class="fw-bold m-0">
                                        Lịch trực phòng ăn
                                    </h6>
                                </li>

                                @if (isset($celenderDetailEatroom))
                                    @foreach ($dates as $key => $date)
                                        @php
                                            $fill = 'day' . $key + 1;
                                        @endphp

                                        @if ($celenderDetailEatroom->$fill != null)
                                            @php
                                                $checkEatRoom = true;
                                            @endphp

                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span>
                                                    {{ $formatDate->formatTimeDMY($date) }}
                                                    -
                                                    {{ $formatDate->dayOfWeek($date) }}
                                                </span>
                                                <span class="badge text-bg-warning">
                                                    VS
                                                </span>
                                            </li>
                                        @endif
                                    @endforeach
                                @endif

                                @if (!$checkEatRoom)
                                    <li class="list-group-item">
                                        Hiện tại chưa có phân công.
                                    </li>
                                @endif
                            </ul>
                            {{-- Đổ rác WC --}}
                            <ul class="list-group mb-3">
                                <li class="list-group-item text-bg-light">
                                    <h6 class="fw-bold m-0">Lịch đổ rác WC</h6>
                                </li>

                                @if (isset($celenderDetailWC))
                                    @foreach ($dates as $key => $date)
                                        @if ($formatDate->dayOfWeek($date) == 'T7')
                                            @php
                                                $fill = 'day' . ($keyDate + 1);
                                                $keyDate += 1;
                                            @endphp

                                            @if ($celenderDetailWC->$fill != null)
                                                @php
                                                    $checkWC = true;
                                                @endphp

                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>
                                                        {{ $formatDate->formatTimeDMY($date) }}
                                                        -
                                                        {{ $formatDate->dayOfWeek($date) }}
                                                    </span>
                                                    <span class="badge text-bg-warning">
                                                        VS
                                                    </span>
                                                </li>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif

                                @if (!$checkWC)
                                    <li class="list-group-item">
                                        Hiện tại chưa có phân công.
                                    </li>
                                @endif
                            </ul>
                            {{-- Trực WC --}}
                            @if (Auth()->User()->gender == 'Nữ')
                                {{-- Trực WC Nữ --}}
                                <ul class="list-group mb-3">
                                    <li class="list-group-item text-bg-light">
                                        <h6 class="fw-bold m-0">
                                            Lịch trực WC Nữ
                                        </h6>
                                    </li>
                                    @if (isset($celenderDetailWCCleanWomen))
                                        @foreach ($dates as $key => $date)
                                            @php
                                                $fill = 'day' . ($key + 1);
                                            @endphp

                                            @if ($celenderDetailWCCleanWomen->$fill != null)
                                                @php
                                                    $checkWCCleanWomen = true;
                                                @endphp

                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>
                                                        {{ $formatDate->formatTimeDMY($date) }}
                                                        -
                                                        {{ $formatDate->dayOfWeek($date) }}
                                                    </span>
                                                    <span class="badge text-bg-warning">
                                                        VS
                                                    </span>
                                                </li>
                                            @endif
                                        @endforeach
                                    @endif

                                    @if (!$checkWCCleanWomen)
                                        <li class="list-group-item">
                                            Hiện tại chưa có phân công.
                                        </li>
                                    @endif
                                </ul>
                            @elseif (Auth()->User()->gender == 'Nam')
                                {{-- Trực WC Nam --}}
                                <ul class="list-group mb-3">
                                    <li class="list-group-item text-bg-light">
                                        <h6 class="fw-bold m-0">
                                            Lịch trực WC Nam
                                        </h6>
                                    </li>
                                    @if (isset($celenderDetailWCCleanMen))
                                        @foreach ($dates as $key => $date)
                                            @php
                                                $fill = 'day' . ($key + 1);
                                            @endphp

                                            @if ($celenderDetailWCCleanMen->$fill != null)
                                                @php
                                                    $checkWCCleanMen = true;
                                                @endphp

                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>
                                                        {{ $formatDate->formatTimeDMY($date) }}
                                                        -
                                                        {{ $formatDate->dayOfWeek($date) }}
                                                    </span>
                                                    <span class="badge text-bg-warning">
                                                        VS
                                                    </span>
                                                </li>
                                            @endif
                                        @endforeach
                                    @endif

                                    @if (!$checkWCCleanMen)
                                        <li class="list-group-item">
                                            Hiện tại chưa có phân công.
                                        </li>
                                    @endif
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
