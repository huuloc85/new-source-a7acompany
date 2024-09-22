@extends('master')

@section('content')
    <style>
        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }

            .form-label {
                font-size: 14px;
            }

            .form-control {
                font-size: 14px;
            }

            table {
                font-size: 12px;
            }

            th,
            td {
                padding: 8px;
            }
        }
    </style>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Bảng Lịch Sử Chấm Công Tháng
                            {{ \Carbon\Carbon::parse($currentMonth)->format('m-Y') }}
                        </h4>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div>
                            <label class="form-label fw-bold" for="">Tên nhân viên:
                                <label>{{ Auth()->user()->name ?? '' }}</label></label>
                        </div>
                        <div>
                            <label class="form-label fw-bold" for="">Mã nhân viên:
                                <label>{{ Auth()->user()->code ?? '' }}</label></label>
                        </div>
                        <div>
                            <label class="form-label fw-bold" for="">Bộ phận:
                                <label>{{ Auth()->user()->role->role_name ?? '' }}</label></label>
                        </div>
                    </div>
                    <form method="GET" action="{{ route('admin.attendence.index') }}">
                        <div class="form-group">
                            <label for="month">Chọn tháng:</label>
                            <input type="month" id="month" name="month" value="{{ $currentMonth }}"
                                class="form-control" onchange="document.getElementById('filterForm').submit();">
                        </div>

                    </form>
                    <div class="table-responsive">
                        @if ($records->isEmpty())
                            <p class="text-center">Hiện tại chưa có thông tin nào.</p>
                        @else
                            <table class="table table-hover mb-4">
                                <thead class="text-center">
                                    <tr>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                            STT</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                            Mã Nhân Viên</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                            Tên Nhân Viên</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                            Ngày Chấm</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                            Thời Gian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($records as $record)
                                        <tr class="text-center">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $record->employee_code }}</td>
                                            <td>{{ $record->employee ? $record->employee->name : 'Không xác định' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($record->date)->format('d-m-Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($record->datetime)->format('H:i:s') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
