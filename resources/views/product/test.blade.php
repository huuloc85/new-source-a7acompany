@extends('master')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                <div class="border-radius-lg ps-2 pt-4 pb-3">
                    <h4 class="card-title mb-0">Sản Phẩm</h4>
                </div>
            </div>
            <div class="card-body">
                <!-- Table to display product information -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tên Sản Phẩm</th>
                            <th>Mã Sản Phẩm</th>
                            <th>Vật Liệu</th>
                            <th>Màu Sắc</th>
                            <th>Số Lượng Trên Bao Bì</th>
                            <th>Số Lượng Trên Thùng</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->code }}</td>
                                <td>{{ $product->material }}</td>
                                <td>{{ $product->color }}</td>
                                <td>{{ $product->quantity_per_package }}</td>
                                <td>{{ $product->quanEntityBin }}</td>
                                <td>
                                    <a href="{{ route('admin.product.editTest', $product->id) }}"
                                        class="btn btn-warning btn-sm">Chỉnh
                                        sửa</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
