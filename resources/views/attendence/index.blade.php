@extends('layouts.layout')
@php
    $startValue = count($records) > 0 ? $records->firstItem() : 0;
    $toValue = count($records) > 0 ? $records->lastItem() : 0;
    $total = $records->total();

    $limitList = [50, 100, 200, 300, 500];
    $currentLimit = request('limit') ?? 50;
@endphp

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">
                            Lịch Sử Chấm Công Tháng
                            {{ \Carbon\Carbon::parse($currentMonth)->format('m-Y') }}
                        </h4>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.attendence.index') }}">
                        <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#addDataModal">
                                <i class="fas fa-plus"></i>
                                <span>Thêm Dữ Liệu Chấm Công</span>
                            </button>
                            <a href="{{ route('admin.attendence.records') }}" class="btn btn-primary">
                                <div class="fas fa-table"></div>
                                <span>Bảng Tính Công</span>
                            </a>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-12 col-sm-4 col-md-3">
                                <input type="month" name="month" id="month" class="form-control"
                                    placeholder="Chọn tháng" value="{{ $currentMonth }}" onchange="this.form.submit()" />
                            </div>
                            <div class="col-12 col-sm-4 col-md-3">
                                <select name="category" id="category" class="form-select" onchange="this.form.submit()">
                                    <option value="">Tất cả</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-sm-4 col-md-6">
                                <input type="text" id="search" class="form-control"
                                    placeholder="Tìm kiếm theo tên nhân viên hoặc ngày chấm công" />
                            </div>
                        </div>
                        <div class="mb-2">
                            <span class="fw-bold">
                                {{ $startValue }}
                            </span>
                            -
                            <span class="fw-bold">
                                {{ $toValue }}
                            </span>
                            của
                            <span class="fw-bold">
                                {{ $total }}
                            </span>
                        </div>
                        <div class="row g-2 align-items-center">
                            <div class="col-auto">
                                <label class="col-form-label">Số lượng:</label>
                            </div>
                            <div class="col-auto">
                                <select name="limit" class="form-select" onchange="this.form.submit()">
                                    @foreach ($limitList as $limit)
                                        <option value="{{ $limit }}"
                                            {{ $currentLimit == $limit ? 'selected' : '' }}>
                                            {{ $limit }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light text-uppercase">
                                <tr>
                                    <th class="text-center" scope="col">STT</th>
                                    <th scope="col">Mã Nhân Viên</th>
                                    <th scope="col">Tên Nhân Viên</th>
                                    <th class="text-center" scope="col">
                                        Ngày Chấm
                                    </th>
                                    <th class="text-center" scope="col">
                                        Thời Gian
                                    </th>
                                    <th class="text-center" scope="col">
                                        Danh Mục Làm Việc
                                    </th>
                                    <th class="text-center" scope="col">
                                        Hành Động
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($records as $record)
                                    <tr>
                                        <td class="fw-bold text-center" scope="row">
                                            {{ $loop->iteration + $startValue - 1 }}
                                        </td>
                                        <td scope="row">
                                            {{ $record->employee_code }}
                                        </td>
                                        <td scope="row">
                                            {{ $record->employee ? $record->employee->name : 'Không xác định' }}
                                        </td>
                                        <td class="text-center" scope="row">
                                            {{ \Carbon\Carbon::parse($record->date)->format('d-m-Y') }}
                                        </td>
                                        <td class="text-center" scope="row">
                                            {{ \Carbon\Carbon::parse($record->datetime)->format('H:i:s') }}
                                        </td>
                                        <td class="text-center" scope="row">
                                            {{ $record->employee ? $record->employee->category_celender->name : 'Không xác định' }}
                                        </td>
                                        <td class="text-center" scope="row">
                                            <!-- Update Button -->
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#updateModal-{{ $loop->iteration }}">
                                                Cập Nhật
                                            </button>
                                            <!-- Delete Button -->
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#modalDelete-{{ $loop->iteration }}">
                                                Xóa
                                            </button>
                                        </td>
                                    </tr>
                                    <!-- Update Modal -->
                                    <div class="modal fade" id="updateModal-{{ $loop->iteration }}" tabindex="-1"
                                        aria-labelledby="updateModalLabel-{{ $loop->iteration }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form method="POST"
                                                    action="{{ route('admin.attendence.update', ['employee_code' => $record->employee_code, 'datetime' => $record->datetime]) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">
                                                            Cập Nhật Thời Gian
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="datetime" class="form-label">
                                                                Thời Gian
                                                            </label>
                                                            <input type="datetime" class="form-control" name="datetime"
                                                                value="{{ \Carbon\Carbon::parse($record->datetime) }}"
                                                                required />
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
                                                            Đóng
                                                        </button>
                                                        <button type="submit" class="btn btn-primary">
                                                            Lưu Thay Đổi
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal delete -->
                                    <div class="modal fade" id="modalDelete-{{ $loop->iteration }}" tabindex="-1"
                                        aria-labelledby="modalDeleteLabel-{{ $loop->iteration }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5"
                                                        id="modalDeleteLabel-{{ $loop->iteration }}">
                                                        Xóa dữ liệu chấm công
                                                    </h1>
                                                </div>
                                                <div class="modal-body">
                                                    <p>
                                                        Bạn có chắc chắn muốn
                                                        xóa dữ liệu chấm công
                                                        <span class="fw-bold">
                                                            #{{ $loop->iteration }}
                                                        </span>
                                                        không?
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form
                                                        action="{{ route('admin.attendence.destroy', ['employee_code' => $record->employee_code, 'datetime' => $record->datetime]) }}"
                                                        method="post">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">
                                                            Xóa
                                                        </button>
                                                    </form>
                                                    <button type="submit" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">
                                                        Đóng
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-2">
                        {{ $records->appends(request()->all())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal thêm dữ liệu -->
    <div class="modal fade" id="addDataModal" tabindex="-1" aria-labelledby="addDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="addDataForm" action="{{ route('admin.attendence.handleRecords') }}" method="POST"
                class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addDataModalLabel">
                        Thêm Dữ Liệu Chấm Công
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="employee_name" class="form-label">
                            Chọn Nhân Viên
                        </label>
                        <select class="form-control" id="employee_name" name="employee_name" required>
                            <option value="">Chọn nhân viên</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->name }}" data-code="{{ $employee->code }}">
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="employee_code" class="form-label">
                            Mã Nhân Viên
                        </label>
                        <input type="text" class="form-control" id="employee_code" name="employee_code" required
                            readonly />
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Ngày Chấm</label>
                        <input type="date" class="form-control" id="date" name="date" required />
                    </div>
                    <div class="mb-3">
                        <label for="time" class="form-label">Giờ</label>
                        <input type="time" class="form-control" id="time" name="time" required />
                    </div>

                    {{--
                        <div class="mb-3">
                        <label for="shift" class="form-label">Ca Làm Việc</label>
                        <input type="text" class="form-control" id="shift" name="shift">
                        </div>
                    --}}
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        Lưu Dữ Liệu
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const table = document.querySelector('.table');
            const rows = table.querySelectorAll('tbody tr');

            searchInput.addEventListener('input', function() {
                const searchTerm = searchInput.value.toLowerCase();

                rows.forEach((row) => {
                    const cells = row.querySelectorAll('td');
                    let found = false;

                    cells.forEach((cell) => {
                        if (
                            cell.textContent.toLowerCase().includes(searchTerm)
                        ) {
                            found = true;
                        }
                    });

                    if (searchTerm === '' || found) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            document
                .getElementById('employee_name')
                .addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const employeeCode =
                        selectedOption.getAttribute('data-code');
                    document.getElementById('employee_code').value =
                        employeeCode;
                });
        });
    </script>
@endsection
