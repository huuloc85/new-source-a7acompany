@extends('layouts.' . $layout)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">
                            Kho Đã Xuất Hàng
                        </h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        @if ($storage->isEmpty())
                            <div class="text-center">
                                Không có lịch sử in tem
                            </div>
                        @else
                            <table class="table table-hover">
                                <thead class="text-uppercase text-center">
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên Sản Phẩm</th>
                                        <th>Nhân Viên Gửi</th>
                                        <th>Số Lot</th>
                                        <th>Thùng Số</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center align-middle">
                                    @foreach ($storage as $storageItem)
                                        <tr>
                                            <th>{{ $loop->iteration }}</th>
                                            <td>
                                                {{ $storageItem->product->name }}
                                            </td>
                                            <td>
                                                {{ $storageItem->employee->name }}
                                            </td>
                                            <td>
                                                {{ $storageItem->lot }}
                                            </td>
                                            <td>
                                                {{ $storageItem->bin }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
