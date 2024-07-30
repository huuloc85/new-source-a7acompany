@extends('master')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Kế Hoạch Sản Xuất Tháng</h4>
                    </div>
                    <div class="header-action">
                        <a href="{{ route('admin.product-plan.add') }}" class="btn btn-primary">Thêm Kế Hoạch Sản Phẩm</a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Nội dung khác của trang có thể được thêm ở đây -->
                </div>
            </div>
        </div>
    </div>
@endsection
