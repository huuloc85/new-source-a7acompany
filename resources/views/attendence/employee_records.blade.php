@extends('master')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                <div class="border-radius-lg ps-2 pt-4 pb-3">
                    <h4 class="card-title mb-0">Bảng Chấm Công Tháng {{ $month }} Của Nhân Viên
                        {{ $employeeName }}</h4>
                </div>
            </div>
            <div class="card-body">
                @if ($records->isEmpty())
                    <p class="text-center">Không có bản ghi nào cho tháng này.</p>
                @else
                    <div class="grid-container">
                        @foreach ($records as $record)
                            <div class="grid-item {{ $record->status }}">
                                <div class="record-header">
                                    <span>Tên Nhân Viên: {{ $employeeName }}</span>
                                </div>
                                <div class="record-body">
                                    <p>Thứ: {{ $record->day_of_week }}</p>
                                    <p>Ngày: {{ $record->formatted_date }}</p>
                                    <p class="{{ $record->time_in == 'Chưa Chấm Công Vào' ? 'text-danger' : '' }}">
                                        Thời Gian Vào: {{ $record->time_in }}
                                    </p>
                                    <p class="{{ $record->time_out == 'Chưa Chấm Công Ra' ? 'text-danger' : '' }}">
                                        Thời Gian Ra: {{ $record->time_out }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .grid-item {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 1rem;
            background-color: #f9f9f9;
        }

        .record-header {
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
            font-weight: bold;
        }

        .record-body p {
            margin: 0.5rem 0;
        }
    </style>
@endsection
