@extends('master')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Bảng Chấm Công {{ \Carbon\Carbon::parse($currentMonth)->format('m-Y') }}
                        </h4>
                    </div>
                </div>
                <div class="d-flex align-items-center ps-2">
                    <form method="GET" action="{{ route('admin.attendence.index') }}" class="d-flex">
                        <div class="form-group mb-0 me-2" style="margin-right: 10px;">
                            <input type="month" name="month" id="month" class="form-control"
                                placeholder="Chọn tháng" value="{{ $currentMonth }}" onchange="this.form.submit()">
                        </div>
                        <div class="form-group mb-0">
                            <select name="category" id="category" class="form-control" onchange="this.form.submit()">
                                <option value="">Tất cả</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>


                <div class="card-body">
                    <table class="table table-hover mb-4">
                        <thead class="text-center">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                    STT</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                    Mã Nhân Viên</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                    Tên Nhân Viên</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                    Ngày Chấm</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                    Thời Gian</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                    Danh Mục Làm Việc</th>
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

                                    <td>{{ $record->employee ? $record->employee->category_celender->name : 'Không xác định' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
