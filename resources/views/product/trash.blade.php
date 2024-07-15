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

        .trash {
            margin-left: 10px;
        }
    </style>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Thùng Rác Sản Phẩm</h4>
                    </div>
                </div>
                <div class="p-4 pb-0 d-flex">
                    <a href="{{ route('admin.product.home') }}" type="button" class ='btn btn-success'>Danh Sách Sản Phẩm</a>
                    <div style="flex-grow:1; display: flex; justify-content: end;">
                        <div>
                            <button type="button" class ='btn btn-primary' data-bs-toggle="modal"
                                data-bs-target="#searchModal">Tìm kiếm</button>
                            @include('product.search-advand', ['href' => 'admin.product.getTrash'])
                        </div>
                    </div>
                </div>
                <div class="ps-4 d-flex">
                    Tổng : {{ count($products) }}/{{ $total }}
                </div>
                <div class="px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0 table-hover">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-xxs font-weight-bolder col-1">STT</th>
                                    <th class="text-uppercase text-xxs font-weight-bolder col-2">Mã Linh Kiện</th>
                                    <th class="text-center text-uppercase text-xxs font-weight-bolder col-2">Tên Linh Kiện
                                    </th>
                                    <th class="text-center text-uppercase text-xxs font-weight-bolder">Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $key => $product)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-3 py-1">
                                                {{ $loop->iteration }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                {{ $product->code }}
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            {{ $product->name }}
                                        </td>
                                        <td class="align-middle text-center">
                                            <form action="{{ route('admin.product.restore', $product->id) }}"
                                                method="PUT">
                                                @method('PUT')
                                                @csrf
                                                <button
                                                    onclick="return confirm('Bạn có chắc muốn khôi phục sản phẩm này không?');"
                                                    class ='btn btn-warning' type="submit">Khôi phục</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($total == 0)
                                    <tr>
                                        <td colspan="6" class="text-center pt-4">Hiện tại chưa có sản phẩm nào trong
                                            thùng rác. quay lại
                                            <a class="href" href="{{ route('admin.product.home') }}">danh sách sản
                                                phẩm</a>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <div style="display: flex; justify-content: center; align-items: center; margin:20px">
                            <div>
                                {{ $products->appends(request()->all())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
