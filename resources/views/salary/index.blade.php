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

    @media screen and (max-width: 700px) {
        .header-title {
            display: block !important
        }

    }

    .table-container {
        overflow: auto;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                <div class="border-radius-lg ps-2 pt-4 pb-3">
                    <h4 class="card-title mb-0">Danh Sách Bảng Lương</h4>
                </div>
            </div>
            <div class="p-4 pb-0 d-flex header-title">
                <a class="btn btn-success import-salary" href="{{ route('admin.salary.getimport') }}">Import Bảng
                    Lương</a>
                <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                    <form action="">
                        <div class="input-group input-group-outline">
                            <input name="key" value="{{ request()->key }}" type="text"
                                class="form-control search-role" placeholder="Nhập từ khóa...">
                            <button type="submit" class='btn btn-primary'>Tìm kiếm</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="ps-4 d-flex">
                Tổng : {{ count($salaryManagers) }}/{{ $total }}
            </div>
            <div class="px-0 pb-2 table-container">
                <table class="table align-items-center mb-0 table-hover ">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">STT</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 col-3">Tiêu
                                đề</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 col-3">
                                Tổng(VND)</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 col-3">
                                Ngày bắt đầu</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 col-3">
                                Ngày kết thúc</th>
                            <th class="text-secondary opacity-7"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salaryManagers as $key => $salaryManager)
                        <tr>
                            <td>
                                <div class="d-flex px-3 py-1">
                                    {{ $loop->iteration }}
                                </div>
                            </td>
                            <td>
                                <p class="text-xs font-weight-bold mb-0 px-3">{{ $salaryManager->title }}</p>
                            </td>
                            <td>
                                <p class="text-xs font-weight-bold mb-0">
                                    {{ number_format($salaryManager->total, 2) }}
                                </p>
                            </td>
                            <td>
                                <p class="text-xs font-weight-bold mb-0">
                                    {{ $salaryManager->formatTimeDMY($salaryManager->start_date) }}
                                </p>
                            </td>
                            <td>
                                <p class="text-xs font-weight-bold mb-0">
                                    {{ $salaryManager->formatTimeDMY($salaryManager->end_date) }}
                                </p>
                            </td>
                            <td class="align-middle">
                                <form action="{{ route('admin.salary.delete', $salaryManager->id) }}"
                                    method="post">
                                    @method('DELETE')
                                    @csrf
                                    <a href="{{ route('admin.salary.detail', $salaryManager->id) }}"
                                        class="btn btn-primary mb-0">Chi tiết</a>
                                    <button
                                        onclick="return confirm('Hành động không thể khôi phục!! Bạn có chắc xoá bảng lương này không?');"
                                        class='btn btn-danger mb-0' type="submit">Xóa</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @if ($total == 0)
                        <tr>
                            <td colspan="5" class="text-center pt-4">Hiện tại chưa có bảng lương nào. Vui lòng
                                <a class="href" href="{{ route('admin.salary.getimport') }}">Thêm bảng lương</a>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                <div style="display: flex; justify-content: center; align-items: center; margin:20px">
                    <div>
                        {{ $salaryManagers->appends(request()->all())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection