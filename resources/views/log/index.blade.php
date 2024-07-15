@extends('master')
@section('content')

<style>
    .form-control {
        border: 1px solid #d2d6da !important;
        padding-left: 10px;
    }
    .active > .page-link{
        color:white !important
    }
    .href {
        color: blue !important;
    }
    .search-role {
        height: 37px;
    }
    .table td, .table th {
        white-space: normal;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Danh Sách Log</h4>
                </div>
            </div>
            <div class="p-4 pb-0 d-flex">
                <form action="{{route('admin.log.delete.all')}}" method="post">
                    @csrf
                    <button onclick="return confirm('Bạn có chắc muốn xóa tất cả Log không?');" type="sunbmit" class='btn btn-danger'>Xoá tất cả</button>
                </form>
                <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                    <form action="">
                        <div class="input-group input-group-outline">
                            <input name="key" value="{{ request()->key}}" type="text" class="form-control search-role" placeholder="Nhận từ khóa...">
                            <button type="submit" class ='btn btn-primary'>Tìm kiếm</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="ps-4 d-flex">
                Tổng : {{count($logs)}}/{{$total}}
            </div>
            <div class="px-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0 table-striped">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">STT</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 col-2">Vị trí</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 col-4">Nội dung</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 col-2">Dòng</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 col-2">Thời gian</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $key=> $log)
                            <tr>
                                <td>
                                    <div class="d-flex px-3 py-1">
                                        {{ $loop->iteration }}
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-3">{{$log->table}}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{$log->content}}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{$log->row}}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{$log->created_at}}</p>
                                </td>
                                <td class="align-middle">
                                    <form action="{{route('admin.log.delete', $log->id)}}" method="post">
                                        @method('DELETE')
                                        @csrf
                                        <button onclick="return confirm('Bạn có chắc muốn xóa Log này không?');" class ='btn btn-danger' type="submit">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div style="display: flex; justify-content: center; align-items: center; margin:20px">
                        <div>
                            {{ $logs->appends(request()->all())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection