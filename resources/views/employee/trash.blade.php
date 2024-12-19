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
    </style>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Danh Sách Nhân Viên Đã Nghỉ Việc </h4>
                    </div>
                </div>
                <div class="p-4 pb-0 d-flex">
                    <a href="{{ route('admin.employee.home') }}" type="button" class='btn btn-success'>Danh Sách Nhân Viên</a>
                    <div style="flex-grow:1; display: flex; justify-content: end;">
                        <div>
                            <button type="button" class='btn btn-primary' data-bs-toggle="modal"
                                data-bs-target="#searchModal">Tìm kiếm</button>
                            @include('employee.search-advand', ['href' => 'admin.employee.getTrash'])
                        </div>
                    </div>
                </div>
                <div class="ps-4 d-flex">
                    Tổng : {{ count($employees) }}/{{ $total }}
                </div>
                <div class="px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0 table-hover">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">STT</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tên/Điện
                                        thoại</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Chức vụ
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Mã nhân
                                        viên</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Danh mục
                                        lịch làm việc</th>
                                    <th class=""></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $key => $employee)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-3 py-1">
                                                {{ $loop->iteration }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    <img src="{{ asset('storage/employee/' . $employee->photo) }}"
                                                        class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $employee->name }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $employee->phone }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $employee->role->role_name }}</p>
                                        </td>
                                        <td class="align-middle text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $employee->code }}</p>
                                        </td>
                                        <td class="align-middle text-center">
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ $employee->category_celender->name }}
                                            </p>
                                        </td>
                                        <td class="align-middle">
                                            <form action="{{ route('admin.employee.restore', $employee->id) }}"
                                                method="put">
                                                @method('PUT')
                                                @csrf
                                                <button
                                                    onclick="return confirm('Bạn có chắc muốn khôi phục nhân viên này không?');"
                                                    class='btn btn-danger' type="submit">Khôi Phục</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($total == 0)
                                    <tr>
                                        <td colspan="6" class="text-center pt-4">Hiện tại chưa có Nhân viên nào trong
                                            thùng rác.
                                            <a class="href" href="{{ route('admin.employee.home') }}">Danh sách nhân
                                                viên</a>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <div style="display: flex; justify-content: center; align-items: center; margin:20px">
                            <div>
                                {{ $employees->appends(request()->all())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
