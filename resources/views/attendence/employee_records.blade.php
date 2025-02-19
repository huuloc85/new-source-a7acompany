@extends('layouts.'.$layout)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">
                            Bảng Lịch Sử Chấm Công Tháng
                            {{ \Carbon\Carbon::parse($currentMonth)->format('m-Y') }}
                        </h4>
                    </div>
                </div>

                <div class="card-body">
                    <div>
                        <span class="fw-bold">Tên nhân viên:</span>
                        {{ Auth()->user()->name ?? '' }}
                    </div>
                    <div>
                        <span class="fw-bold">Mã nhân viên:</span>
                        {{ Auth()->user()->code ?? '' }}
                    </div>
                    <div>
                        <span class="fw-bold">Bộ phận:</span>
                        {{ Auth()->user()->role->role_name ?? '' }}
                    </div>
                    <form
                        method="GET"
                        action="{{ route('admin.attendence.index') }}"
                    >
                        <div class="form-group">
                            <label class="form-label" for="month">
                                Chọn tháng:
                            </label>
                            <input
                                type="month"
                                id="month"
                                name="month"
                                value="{{ $currentMonth }}"
                                class="form-control"
                                onchange="this.form.submit()"
                            />
                        </div>
                    </form>
                    <div class="table-responsive">
                        @if ($records->isEmpty())
                            <p class="text-center">
                                Hiện tại chưa có thông tin nào.
                            </p>
                        @else
                            <table class="table table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-uppercase">STT</th>
                                        <th class="text-uppercase">
                                            Mã Nhân Viên
                                        </th>
                                        <th class="text-uppercase">
                                            Tên Nhân Viên
                                        </th>
                                        <th class="text-uppercase">
                                            Ngày Chấm
                                        </th>
                                        <th class="text-uppercase">
                                            Thời Gian
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($records as $record)
                                        <tr class="text-center">
                                            <td class="fw-bold">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ $record->employee_code }}
                                            </td>
                                            <td>
                                                {{ $record->employee ? $record->employee->name : 'Không xác định' }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($record->date)->format('d-m-Y') }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($record->datetime)->format('H:i:s') }}
                                            </td>
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
