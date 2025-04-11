@extends('layouts.' . $layout)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">
                            Bảng Lịch Sử In Tem Ngày
                            {{ \Carbon\Carbon::parse($date)->format('d-m') }}
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
                                        <th>Ca</th>
                                        <th>Số Lượng Tem</th>
                                        <th>Bắt Đầu Từ Tem Số</th>
                                        <th>Loại Tem</th>
                                        <th>Ngày Gửi</th>
                                        <th>Thời Gian Gửi</th>
                                        <th>Người In</th>
                                        <th>Thời Gian In</th>
                                        <th>Trạng Thái</th>
                                        <th>Thao Tác</th>
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
                                                {{ \Carbon\Carbon::parse($history->date)->format('d-m-Y') }}
                                            </td>
                                            <td>{{ $history->shift }}</td>
                                            <td>{{ $history->binCount }}</td>
                                            <td>{{ $history->binStart }}</td>
                                            <td>{{ $history->type }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($history->created_at)->format('d-m-Y') }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($history->created_at)->format('H:i:s') }}
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
