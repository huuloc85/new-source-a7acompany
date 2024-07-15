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

    .table-show {
        overflow: scroll;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Danh Sách Bảng Lương</h4>
                </div>
            </div>
            <div class="row p-4 pb-0">
                <div class="col-12 col-sm-6 mb-3">
                    <div class="d-flex">
                        Tổng : {{count($salaryManagers)}}/{{$total}}
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center float-end">
                        <form action="">
                            <div class="input-group input-group-outline">
                                <input name="key" value="{{ request()->key}}" type="text" class="form-control search-role" placeholder="Nhập tiêu đề">
                                <button type="submit" class='btn btn-primary'>Tìm kiếm</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="px-0 pb-2 table-show">
                <div class="table-responsive p-0">
                    <div class="row mx-2">
                        @foreach ($salaryManagers as $key=> $salaryManager)
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4 mt-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="text-start pt-1">
                                        <a href="{{route('admin.employee-show.salary-detail', $salaryManager->id)}}">
                                            <h6 class="mb-0">{{$salaryManager->title}}</h6>
                                        </a>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                                <div class="card-footer p-3 d-flex justify-content-between">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Bắt đầu</h6>
                                        <p class="mb-0">{{$salaryManager->start_date}}</p>
                                    </div>
                                    <div class="text-end pt-1">
                                        <h6 class="mb-0">Kết thúc</h6>
                                        <p class="mb-0">{{$salaryManager->end_date}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div style="display: flex; justify-content: center; align-items: center; margin:20px">
                        <div>
                            {{ $salaryManagers->appends(request()->all())->links() }}
                        </div>
                    </div>
                    @if ($total == 0)
                    <div>
                        <p colspan="6" class="text-center pt-4">Hiện chưa có bảng lương nào!</p>
                    </div>
                    @endif
                </div>
            </div>
            <div class="ps-4">
                <a class="btn btn-danger" href="{{route('admin.home')}}">Quay lại</a>
            </div>
        </div>
    </div>
</div>

@endsection