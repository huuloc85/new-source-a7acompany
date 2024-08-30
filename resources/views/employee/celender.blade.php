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
            <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                <div class="border-radius-lg ps-2 pt-4 pb-3">
                    <h4 class="card-title mb-0">Danh Sách Lịch Làm Việc</h4>
                </div>
            </div>
            <div class="row p-4 pb-0">
                <div class="col-12 col-sm-6 mb-3">
                    <div class="d-flex">
                        Tổng : {{count($celenders)}}/{{$total}}
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                        <form action="">
                            <div class="input-group input-group-outline">
                                <input name="key" value="{{ request()->key}}" type="text" class="form-control search-role" placeholder="Nhập tiêu đề...">
                                <button type="submit" class='btn btn-primary'>Tìm kiếm</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="px-0 pb-2 table-show">
                <div class="table-responsive p-0">
                    <div class="row mx-2">
                        @foreach ($celenders as $key=> $celender)
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4 mt-4">
                            <div class="card">
                                <div class="card-header p-3 pt-2">
                                    <div class="text-start pt-1">
                                        <a href="{{route('admin.employee-show.celender-detail', $celender->id)}}">
                                            <h6 class="mb-0">{{$celender->title}}</h6>
                                        </a>
                                    </div>
                                </div>
                                <hr class="dark horizontal my-0">
                                <div class="card-footer p-3 d-flex justify-content-between">
                                    <div class="text-start pt-1">
                                        <h6 class="mb-0">Bắt đầu</h6>
                                        <p class="mb-0">{{$celender->formatTimeDMY($celender->date)}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div style="display: flex; justify-content: center; align-items: center; margin:20px">
                        <div>
                            {{ $celenders->appends(request()->all())->links() }}
                        </div>
                    </div>
                    @if ($total == 0)
                    <div>
                        <p colspan="6" class="text-center pt-4">Hiện chưa có lịch làm việc nào!</p>
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