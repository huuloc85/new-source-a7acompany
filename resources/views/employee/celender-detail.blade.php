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

    .table-show {
        overflow: scroll;
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

    .CN {
        background-color: #919cc9 !important;
    }

    .T7 {
        background-color: #919cc9 !important;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                <div class="border-radius-lg ps-2 pt-4 pb-3">
                    <h4 class="card-title mb-0">Lịch Làm Việc</h4>
                </div>
            </div>
            <div class="px-0 pb-2 table-show">
                <div class="table-responsive p-0">
                    <div class="row mx-2">
                        {{-- Hàng nhật --}}
                        @if (isset($celenderDetailHNHC))
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4 mt-4">
                            <div class="card mb-2">
                                <div class="card-header p-3 pt-2">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Chú thích lịch làm việc</h6>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                                <div class="card-footer p-2 d-flex justify-content-between">
                                    <div class="text-start pt-1 ms-2">
                                        <h6 class="mb-0 N">N</h6>
                                    </div>
                                    <div class="text-end pt-1 me-2">
                                        <h6 class="mb-0">Ca ngày</h6>
                                    </div>
                                </div>
                                <div class="card-footer p-2 d-flex justify-content-between">
                                    <div class="text-start pt-1 ms-2">
                                        <h6 class="mb-0 D">D</h6>
                                    </div>
                                    <div class="text-end pt-1 me-2">
                                        <h6 class="mb-0">Ca đêm</h6>
                                    </div>
                                </div>
                                <div class="card-footer p-2 d-flex justify-content-between">
                                    <div class="text-start pt-1 ms-2">
                                        <h6 class="mb-0 X">X</h6>
                                    </div>
                                    <div class="text-end pt-1 me-2">
                                        <h6 class="mb-0">Nghĩ</h6>
                                    </div>
                                </div>
                                <div class="card-footer p-2 d-flex justify-content-between">
                                    <div class="text-start pt-1 ms-2">
                                        <h6 class="mb-0 TC">TC</h6>
                                    </div>
                                    <div class="text-end pt-1 me-2">
                                        <h6 class="mb-0">Tăng cường đêm</h6>
                                    </div>
                                </div>
                                <div class="card-footer p-2 d-flex justify-content-between">
                                    <div class="text-start pt-1 ms-2">
                                        <h6 class="mb-0 LN">LN</h6>
                                    </div>
                                    <div class="text-end pt-1 me-2">
                                        <h6 class="mb-0">Làm thêm ca ngày</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Lịch làm việc</h6>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                                @foreach ($dates as $key => $date)
                                <?php
                                $fill = 'day' . $key + 1;
                                ?>
                                <div
                                    class="card-footer p-2 d-flex justify-content-between {{ $formatDate->dayOfWeek($date) }}">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">{{ $formatDate->formatTimeDMY($date) }} -
                                            {{ $formatDate->dayOfWeek($date) }}
                                        </h6>
                                    </div>
                                    <div class="text-end pt-1 me-2">
                                        <h6 class="mb-0 {{ $celenderDetailHNHC->$fill ?? '' }}">
                                            {{ $celenderDetailHNHC->$fill ?? '' }}
                                        </h6>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4 mt-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Lịch làm việc</h6>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                                <div class="card-footer p-3 d-flex justify-content-between">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Hiện tại chưa có phân công.</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        {{-- Trực Nhà Ăn --}}
                        @if (isset($celenderDetailEatroom))
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4 mt-4">
                            <div class="card mb-2">
                                <div class="card-header p-3 pt-2">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Chú thích lịch trực phòng ăn </h6>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                                <div class="card-footer p-2 d-flex justify-content-between">
                                    <div class="text-start pt-1 ms-2">
                                        <h6 class="mb-0 X">X</h6>
                                    </div>
                                    <div class="text-end pt-1 me-2">
                                        <h6 class="mb-0">Ngày trực vệ sinh</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Lịch trực phòng ăn</h6>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                                <?php $checkEatroom = false; ?>
                                @foreach ($dates as $key => $date)
                                <?php $fill = 'day' . $key + 1; ?>
                                @if ($celenderDetailEatroom->$fill != null)
                                <?php $checkEatroom = true; ?>
                                <div class="card-footer p-2 d-flex justify-content-between">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">{{ $formatDate->formatTimeDMY($date) }} -
                                            {{ $formatDate->dayOfWeek($date) }}
                                        </h6>
                                    </div>
                                    <div class="text-end pt-1 me-2">
                                        <h6 class="mb-0 {{ $celenderDetailEatroom->$fill ?? '' }}">
                                            {{ $celenderDetailEatroom->$fill ?? '' }}
                                        </h6>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                                @if ($checkEatroom == false)
                                <div class="card-footer p-2 d-flex justify-content-between">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Hiện tại chưa có phân công.</h6>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @else
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4 mt-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Lịch trực phòng ăn</h6>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                                <div class="card-footer p-2 d-flex justify-content-between">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Hiện tại chưa có phân công.</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        {{-- Đổ rác WC --}}
                        @if (isset($celenderDetailWC))
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4 mt-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Lịch đổ rác WC</h6>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                                <?php $keyDate = 0;
                                $checkWC = false; ?>
                                @foreach ($dates as $key => $date)
                                @if ($formatDate->dayOfWeek($date) == 'T7')
                                <?php $fill = 'day' . $keyDate + 1;
                                $keyDate += 1; ?>
                                @if ($celenderDetailWC->$fill != null)
                                <?php $checkWC = true; ?>
                                <div class="card-footer p-2 d-flex justify-content-between">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">{{ $formatDate->formatTimeDMY($date) }} -
                                            {{ $formatDate->dayOfWeek($date) }}
                                        </h6>
                                    </div>
                                    <div class="text-end pt-1 me-2">
                                        <h6 class="mb-0 {{ $celenderDetailWC->$fill ?? '' }}">
                                            {{ $celenderDetailWC->$fill ?? '' }}
                                        </h6>
                                    </div>
                                </div>
                                @endif
                                @endif
                                @endforeach
                                @if ($checkWC == false)
                                <div class="card-footer p-2 d-flex justify-content-between">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Hiện tại chưa có phân công.</h6>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @else
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4 mt-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Lịch đổ rác WC</h6>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                                <div class="card-footer p-2 d-flex justify-content-between">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Hiện tại chưa có phân công.</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Trực WC --}}
                        @if (Auth()->User()->gender == 'Nữ')
                        {{-- Trực WC Nữ --}}
                        @if (isset($celenderDetailWCCleanWomen))
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4 mt-4">
                            <div class="card mb-2">
                                <div class="card-header p-3 pt-2">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Chú thích lịch trực WC Nữ</h6>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                                <div class="card-footer p-2 d-flex justify-content-between">
                                    <div class="text-start pt-1 ms-2">
                                        <h6 class="mb-0 X">X</h6>
                                    </div>
                                    <div class="text-end pt-1 me-2">
                                        <h6 class="mb-0">Ngày trực vệ sinh</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Lịch trực WC Nữ</h6>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                                <?php $checkWCCleanWomen = false; ?>
                                @foreach ($dates as $key => $date)
                                <?php $fill = 'day' . ($key + 1); ?>
                                @if ($celenderDetailWCCleanWomen->$fill != null)
                                <?php $checkWCCleanWomen = true; ?>
                                <div class="card-footer p-2 d-flex justify-content-between">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">{{ $formatDate->formatTimeDMY($date) }} -
                                            {{ $formatDate->dayOfWeek($date) }}
                                        </h6>
                                    </div>
                                    <div class="text-end pt-1 me-2">
                                        <h6
                                            class="mb-0 {{ $celenderDetailWCCleanWomen->$fill ?? '' }}">
                                            {{ $celenderDetailWCCleanWomen->$fill ?? '' }}
                                        </h6>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                                @if ($checkWCCleanWomen == false)
                                <div class="card-footer p-2 d-flex justify-content-between">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Hiện tại chưa có phân công.</h6>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @else
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4 mt-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Lịch trực WC Nữ</h6>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                                <div class="card-footer p-2 d-flex justify-content-between">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Hiện tại chưa có phân công.</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @elseif (Auth()->User()->gender == 'Nam')
                        {{-- Trực WC Nam --}}
                        @if (isset($celenderDetailWCCleanMen))
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4 mt-4">
                            <div class="card mb-2">
                                <div class="card-header p-3 pt-2">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Chú thích lịch trực WC Nam</h6>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                                <div class="card-footer p-2 d-flex justify-content-between">
                                    <div class="text-start pt-1 ms-2">
                                        <h6 class="mb-0 X">X</h6>
                                    </div>
                                    <div class="text-end pt-1 me-2">
                                        <h6 class="mb-0">Ngày trực vệ sinh</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Lịch trực WC Nam</h6>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                                <?php $checkWCCleanMen = false; ?>
                                @foreach ($dates as $key => $date)
                                <?php $fill = 'day' . ($key + 1); ?>
                                @if ($celenderDetailWCCleanMen->$fill != null)
                                <?php $checkWCCleanMen = true; ?>
                                <div class="card-footer p-2 d-flex justify-content-between">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">{{ $formatDate->formatTimeDMY($date) }} -
                                            {{ $formatDate->dayOfWeek($date) }}
                                        </h6>
                                    </div>
                                    <div class="text-end pt-1 me-2">
                                        <h6 class="mb-0 {{ $celenderDetailWCCleanMen->$fill ?? '' }}">
                                            {{ $celenderDetailWCCleanMen->$fill ?? '' }}
                                        </h6>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                                @if ($checkWCCleanMen == false)
                                <div class="card-footer p-2 d-flex justify-content-between">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Hiện tại chưa có phân công.</h6>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @else
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4 mt-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Lịch trực WC Nam</h6>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                                <div class="card-footer p-2 d-flex justify-content-between">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Hiện tại chưa có phân công.</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
            </div>
            <div class="ps-4">
                <a class="btn btn-danger" href="{{ route('admin.employee-show.celender') }}">Quay lại</a>
            </div>
        </div>
    </div>
</div>

@endsection