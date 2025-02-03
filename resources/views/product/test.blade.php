@extends('layouts.layout')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Sản Phẩm</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!-- Table to display product information -->
                        <table class="table">
                            <thead class="thead-light">
                                <tr class="text-center align-middle">
                                    <th>STT</th>
                                    <th>Tên Sản Phẩm</th>
                                    <th>Mã Sản Phẩm</th>
                                    <th>Vật Liệu</th>
                                    <th>Màu Sắc</th>
                                    <th>Số Lượng Trên Bao Bì</th>
                                    <th>Số Lượng Trên Thùng</th>
                                    <th>Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody class="text-center align-middle">
                                @foreach ($products as $key => $product)
                                    <tr>
                                        <th>{{ $loop->iteration }}</th>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->code }}</td>
                                        <td>{{ $product->material }}</td>
                                        <td>{{ $product->color }}</td>
                                        <td>
                                            {{ $product->quantity_per_package }}
                                        </td>
                                        <td>{{ $product->quanEntityBin }}</td>
                                        <td>
                                            <a
                                                href="{{ route('admin.product.editTest', $product->id) }}"
                                                class="btn btn-primary"
                                            >
                                                <i class="fas fa-edit"></i>
                                                Chỉnh sửa
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
