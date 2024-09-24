@extends('master')
@section('content')
    <style>
        .search-container {
            position: relative;
            max-width: 100%;
        }

        .form-control-search {
            padding-left: 2.5rem;
            border-radius: 25px;
            border: 1px solid #ced4da;
            width: 100%;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.1rem;
            color: #6c757d;
            pointer-events: none;
        }

        .form-control::placeholder {
            color: #6c757d;
        }

        .btn-custom {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            border-radius: 0.375rem;
        }

        .p-4 {
            padding: 1rem !important;
        }

        .btn {
            margin: 0 0.5rem;
        }
    </style>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Danh Sách Nhân Sự</h4>
                    </div>
                </div>
                <div class="p-4 pb-0 d-flex align-items-center">
                    <a href="{{ route('admin.employee.add') }}" type="button" class="btn btn-success btn-sm btn-custom"
                        title="Thêm Nhân Sự" data-bs-toggle="tooltip">
                        <i class="fas fa-user-plus fa-lg"></i>
                    </a>
                    <a href="{{ route('admin.employee.getTrash') }}" type="button"
                        class="btn btn-warning btn-sm btn-custom" title="Thùng Rác" data-bs-toggle="tooltip">
                        <i class="fas fa-trash fa-lg"></i>
                    </a>
                    <a href="http://192.168.1.2/doc/index.html#/portal/login" type="button"
                        class="btn btn-dark btn-sm btn-custom" title="Thêm Nhân Sự Vào Máy Chấm Công"
                        data-bs-toggle="tooltip">
                        <i class="fas fa-fingerprint fa-lg"></i>
                    </a>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-primary btn-sm btn-custom" data-bs-toggle="modal"
                            data-bs-target="#searchModal" title="Tìm kiếm" data-bs-toggle="tooltip">
                            <i class="fas fa-search fa-lg"></i>
                        </button>
                        @include('employee.search-advand', ['href' => 'admin.employee.home'])
                    </div>
                    <div class="position-relative flex-grow-1 ms-2">
                        <input type="text" id="search" class="form-control form-control-search"
                            placeholder="Tìm kiếm theo tên nhân viên, mã nhân viên hoặc chức vụ">
                        <i class="search-icon fas fa-search"></i>
                    </div>
                </div>
                <div class="ps-4 d-flex">
                    Tổng : {{ count($employees) }}/{{ $total }}
                </div>
                <div class="table-responsive">
                    <div id="datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="table-responsive border-bottom">
                            <table id="datatable" class="table table-hover dataTable no-footer" data-toggle="data-table"
                                aria-describedby="datatable_info">
                                <thead>
                                    <tr class="text-center">
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            STT</th>
                                        <th
                                            class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-8">
                                            Tên/Điện thoại</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Chức vụ</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Mã nhân viên</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Danh mục lịch làm việc</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Chức
                                            năng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $key => $employee)
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-center align-items-center px-2 py-1">
                                                    {{ $loop->iteration }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1 ps-5">
                                                    <div>
                                                        <img src="{{ asset('storage/employee/' . $employee->photo) }}"
                                                            class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center md-2">
                                                        <h6 class="mb-0 text-sm">{{ $employee->name }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $employee->phone }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $employee->role->role_name }}
                                                </p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <p class="text-xs font-weight-bold mb-0">{{ $employee->code }}</p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $employee->category_celender->name }}
                                                </p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <form action="{{ route('admin.employee.delete', $employee->id) }}"
                                                    method="post" class="mb-0">
                                                    @method('DELETE')
                                                    @csrf
                                                    <a href="{{ route('admin.employee.edit', $employee->id) }}"
                                                        class="btn btn-primary mb-0">Cập nhật</a>
                                                    <button
                                                        onclick="return confirm('Bạn có chắc muốn đưa nhân viên này vào thùng rác không?');"
                                                        class='btn btn-danger mb-0' type="submit">Xóa</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($total == 0)
                                        <tr>
                                            <td colspan="6" class="text-center pt-4">Hiện tại chưa có Nhân viên nào.
                                                Vui lòng
                                                <a class="href" href="{{ route('admin.employee.add') }}">Thêm nhân
                                                    viên</a>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div style="display: flex; justify-content: center; align-items: center; padding: 10px">
                            {{ $employees->appends(request()->all())->links() }}
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });


        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const table = document.querySelector('#datatable');
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

                    row.style.display = found ? '' : 'none';
                });
            });
        });
    </script>
@endsection
