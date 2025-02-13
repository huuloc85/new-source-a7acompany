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
                    <ul class="nav nav-tabs flex-nowrap text-nowrap overflow-x-auto overflow-y-hidden" id="myTab"
                        role="tablist">
                        @foreach ($tabWork as $key => $tab)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ $key }}-tab"
                                    data-bs-toggle="tab" data-bs-target="#{{ $key }}" type="button" role="tab"
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
                        @foreach ($tabWork as $key => $tab)
                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $key }}"
                                role="tabpanel" aria-labelledby="{{ $key }}-tab">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead class="table-light text-center uppercase align-middle">
                                            <tr>
                                                <th>Mã NV</th>
                                                <th>Họ và tên</th>
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
                                                        <td colspan="9999" class="fw-bold bg-info">
                                                            {{ $category->name }}
                                                        </td>
                                                    </tr>
                                                    @if (isset($celenderDetailsHNHC))
                                                        @foreach ($celenderDetailsHNHC as $key => $celenderDetailHNHC)
                                                            @if ($celenderDetailHNHC->employee->category_celender_id == $category->id)
                                                                <tr>
                                                                    <td>
                                                                        {{ $celenderDetailHNHC->employee->code }}
                                                                    </td>
                                                                    <td class="text-start">
                                                                        {{ $celenderDetailHNHC->employee->name }}
                                                                    </td>
                                                                    @foreach ($dates as $key => $date)
                                                                        @php
                                                                            $fill = 'day' . $key + 1;
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
                                                                $fill = 'day' . $key + 1;
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
                                                                    $fill = 'day' . $keyDate + 1;
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
                                                                $fill = 'day' . $key + 1;
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
                                                                $fill = 'day' . $key + 1;
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
