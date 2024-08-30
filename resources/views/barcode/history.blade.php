@extends('master')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Lịch Sử In Tem</h4>
                    </div>
                </div>
                <form method="GET" action="{{ route('admin.product.barcode.history') }}" class="row g-3 mb-3 px-4">
                    <div class="col-md-6 col-lg-2">
                        <label for="month" class="form-label">Chọn Tháng</label>
                        <input type="month" name="month" id="month" class="form-control"
                            value="{{ request('month', \Carbon\Carbon::now()->format('Y-m')) }}"
                            onchange="this.form.submit()">
                    </div>
                    <div class="col-md-6 col-lg-1">
                        <label for="type" class="form-label">Chọn Loại Tem</label>
                        <select name="type" id="type" class="form-control" onchange="this.form.submit()">
                            <option value="">Tất Cả</option>
                            <option value="Tem Thùng" {{ request('type') == 'Tem Thùng' ? 'selected' : '' }}>Tem Thùng
                            </option>
                            <option value="Tem Bịch" {{ request('type') == 'Tem Bịch' ? 'selected' : '' }}>Tem Bịch</option>
                        </select>
                    </div>
                </form>
                <div class="card-body">
                    @if ($historyprint->isEmpty())
                        <div class="text-center">
                            <p class="text-muted">Không có lịch sử in tem</p>
                        </div>
                    @else
                        <table class="table table-hover mb-4">
                            <thead class="text-center">
                                <tr>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        STT</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Tên Sản Phẩm</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Tên Nhân Viên</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Ngày</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Ca</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Số Lượng Thùng</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Thùng Bắt Đầu</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Loại Tem</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Thời Gian In</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach ($historyprint as $history)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $history->product->name }}</td>
                                        <td>{{ $history->employee->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($history->date)->format('d-m-Y') }}</td>
                                        <td>{{ $history->shift }}</td>
                                        <td>{{ $history->binCount }}</td>
                                        <td>{{ $history->binStart }}</td>
                                        <td>{{ $history->type }}</td>
                                        <td>{{ \Carbon\Carbon::parse($history->created_at)->format('d-m-Y H:i:s') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection
