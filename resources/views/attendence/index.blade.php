@extends('master')

@section('content')
    <style>
        .form-control,
        .form-select {
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }

        .form-control-search {
            padding-left: 2.5rem;
            border-radius: 25px;
            border: 1px solid #ced4da;
            font-size: 0.875rem;

        }

        .position-relative {
            position: relative;
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1rem;
            color: #6c757d;
        }

        .form-control::placeholder {
            color: #6c757d;
        }

        input#search:focus::placeholder {
            color: transparent;
        }

        @media (max-width: 768px) {

            .mb-2,
            .btn {
                width: 100%;
            }

            .btn {
                margin-bottom: 0.5rem;
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
                <div class="d-flex flex-wrap align-items-center my-2 ps-2 pe-2">
                    <form method="GET" action="{{ route('admin.attendence.index') }}" class="d-flex flex-wrap w-100">
                        <div class="mb-2 me-2">
                            <input type="month" name="month" id="month" class="form-control"
                                placeholder="Chọn tháng" value="{{ $currentMonth }}" onchange="this.form.submit()">
                        </div>
                        <div class="mb-2 me-2">
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
                        <div class="mb-2 me-2 flex-grow-1 position-relative">
                            <input type="text" id="search" class="form-control form-control-search"
                                placeholder="Tìm kiếm theo tên nhân viên hoặc ngày chấm công">
                            <i class="search-icon fas fa-search"></i>
                        </div>
                        <div class="mb-2 d-flex">
                            <button type="button" class="btn btn-success btn-sm shadow-sm me-2" data-bs-toggle="modal"
                                data-bs-target="#addDataModal">
                                Thêm Dữ Liệu Chấm Công
                            </button>
                            <a href="{{ route('admin.attendence.records') }}" class="btn btn-primary btn-sm shadow-sm">
                                Bảng Tính Công
                            </a>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
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
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Danh Mục Làm Việc</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Hành Động</th>
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
                                        <td>
                                            <form method="POST"
                                                action="{{ route('admin.attendence.destroy', ['employee_code' => $record->employee_code, 'datetime' => $record->datetime]) }}"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa không?');">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal thêm dữ liệu -->
    <div class="modal fade" id="addDataModal" tabindex="-1" aria-labelledby="addDataModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDataModalLabel">Thêm Dữ Liệu Chấm Công</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addDataForm" action="{{ route('admin.attendence.handleRecords') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="employee_name" class="form-label">Chọn Nhân Viên</label>
                            <select class="form-control" id="employee_name" name="employee_name" required>
                                <option value="">Chọn nhân viên</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->name }}" data-code="{{ $employee->code }}">
                                        {{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="employee_code" class="form-label">Mã Nhân Viên</label>
                            <input type="text" class="form-control" id="employee_code" name="employee_code" required
                                readonly>
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Ngày Chấm</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                        <div class="mb-3">
                            <label for="time" class="form-label">Giờ</label>
                            <input type="time" class="form-control" id="time" name="time" required>
                        </div>


                        {{-- <div class="mb-3">
                                <label for="shift" class="form-label">Ca Làm Việc</label>
                                <input type="text" class="form-control" id="shift" name="shift">
                            </div> --}}
                        <button type="submit" class="btn btn-primary">Lưu Dữ Liệu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const table = document.querySelector('.table');
            const rows = table.querySelectorAll('tbody tr');

            searchInput.addEventListener('input', function() {
                const searchTerm = searchInput.value.toLowerCase();

                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    let found = false;

                    cells.forEach(cell => {
                        if (cell.textContent.toLowerCase().includes(searchTerm)) {
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
            document.getElementById('employee_name').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const employeeCode = selectedOption.getAttribute('data-code');
                document.getElementById('employee_code').value = employeeCode;
            });
        });
    </script>
@endsection
