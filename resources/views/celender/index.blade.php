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
    </style>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Danh Sách Lịch Làm Việc</h4>
                    </div>
                </div>
                <div class="p-4 pb-0 d-flex">
                    <div style="">
                        <div>
                            @if (Auth()->user()->role_id != 14 && Auth()->user()->role_id != 18)
                                <button type="button" class='btn btn-primary' data-bs-toggle="modal"
                                    data-bs-target="#importCelender">Import lịch làm việc</button>
                                @include('celender.import')
                                @error('title')
                                    <div class="text text-danger">{{ $message }}</div>
                                @enderror
                                @error('date')
                                    <div class="text text-danger">{{ $message }}</div>
                                @enderror
                                @error('fileImport')
                                    <div class="text text-danger">{{ $message }}</div>
                                @enderror
                            @endif
                        </div>
                    </div>
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                        <form action="">
                            <div class="input-group input-group-outline">
                                <input name="key" value="{{ request()->key }}" type="text"
                                    class="form-control search-role" placeholder="Nhập tiêu đề...">
                                <button type="submit" class='btn btn-primary'>Tìm kiếm</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="ps-4 d-flex">
                    Tổng : {{ count($celenders) }}/{{ $total }}
                </div>
                <div class="px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0 table-hover">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">STT</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 col-3">
                                        Tiêu đề</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 col-3">
                                        Ngày bắt đầu</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($celenders as $key => $celender)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-3 py-1">
                                                {{ $loop->iteration }}
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0 px-3">{{ $celender->title }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0 px-3">
                                                {{ $celender->formatTimeDMY($celender->date) }}
                                            </p>
                                        </td>
                                        <td class="align-middle">
                                            <form action="{{ route('admin.celender.delete', $celender->id) }}"
                                                method="post">
                                                @method('DELETE')
                                                @csrf
                                                <a href="{{ route('admin.celender.detail', $celender->id) }}"
                                                    class="btn btn-primary mb-0">Chi tiết</a>
                                                @if (Auth()->user()->role_id != 14 && Auth()->user()->role_id != 18)
                                                    <button
                                                        onclick="return confirm('Hành động không thể khôi phục!! Bạn có chắc xoá lịch làm việc này không?');"
                                                        class='btn btn-danger mb-0' type="submit">Xóa</button>
                                                @endif
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($total == 0)
                                    <tr>
                                        <td colspan="6" class="text-center pt-4">Hiện tại chưa có lịch làm việc nào.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <div style="display: flex; justify-content: center; align-items: center; margin:20px">
                            <div>
                                {{ $celenders->appends(request()->all())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
