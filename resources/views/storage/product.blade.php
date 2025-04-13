@extends('layouts.' . $layout)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">
                            Kho Đã Xuất Hàng {{ \Carbon\Carbon::now()->format('m-Y') }}
                        </h4>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" class="row g-3 align-items-end mb-4">
                        <div class="col-md-2">
                            <label for="month" class="form-label">Tháng</label>
                            <select name="month" id="month" class="form-select" onchange="this.form.submit()">
                                @foreach ($months as $m)
                                    <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>Tháng
                                        {{ $m }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="product_id" class="form-label">Sản Phẩm</label>
                            <select name="product_id" id="product_id" class="form-select" onchange="this.form.submit()">
                                <option value="">Tất cả</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="employee_id" class="form-label">Nhân Viên</label>
                            <select name="employee_id" id="employee_id" class="form-select" onchange="this.form.submit()">
                                <option value="">Tất cả</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                        {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="start_date" class="form-label">Từ Ngày</label>
                            <input type="date" name="start_date" id="start_date" class="form-control"
                                value="{{ request('start_date') }}" onchange="this.form.submit()">
                        </div>

                        <div class="col-md-2">
                            <label for="end_date" class="form-label">Đến Ngày</label>
                            <input type="date" name="end_date" id="end_date" class="form-control"
                                value="{{ request('end_date') }}" onchange="this.form.submit()">
                        </div>
                    </form>


                    <div class="table-responsive">
                        @if ($storage->isEmpty())
                            <div class="text-center">
                                Không có sản phẩm trong kho xuất hàng
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
