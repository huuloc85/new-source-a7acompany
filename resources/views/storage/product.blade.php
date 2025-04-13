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
                                        <th>Code</th>
                                        <th>Nhân Viên Nhập</th>
                                        <th>Mã Nhân Viên</th>
                                        <th>Số Lot</th>
                                        <th>Thùng Số</th>
                                        <th>Ngày Xuất</th>
                                        <th>Thời Gian</th>
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
                                                {{ $storageItem->product->code }}
                                            </td>
                                            <td>
                                                {{ $storageItem->employee->name }}
                                            </td>
                                            <td>
                                                {{ $storageItem->employee->code }}
                                            </td>
                                            <td>
                                                {{ $storageItem->lot }}
                                            </td>
                                            <td>
                                                {{ $storageItem->bin }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($storageItem->created_at)->format('d-m') }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($storageItem->created_at)->format('H:i:s') }}
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
