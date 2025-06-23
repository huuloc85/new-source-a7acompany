@extends('layouts.' . $layout)
<style>
    /* Ngăn chặn scroll ngang toàn trang */
    html,
    body {
        overflow-x: hidden;
        width: 100%;
    }

    /* Đảm bảo các khối chính không vượt chiều rộng màn hình */
    .container,
    .row,
    .card,
    .content,
    .main-content {
        max-width: 100vw;
        overflow-x: hidden;
    }

    /* Cho tất cả thành phần tính kích thước chính xác */
    * {
        box-sizing: border-box;
    }

    /* Card mobile padding đẹp hơn */
    @media (max-width: 767.98px) {
        .card-body p {
            font-size: 15px;
        }
    }
</style>


@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">
                            Kho Đã Xuất Hàng Ngày {{ \Carbon\Carbon::now()->format('d-m') }}
                        </h4>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" class="row g-3 align-items-end mb-4">
                        {{-- <div class="col-md-2">
                            <label for="month" class="form-label">Tháng</label>
                            <select name="month" id="month" class="form-select" onchange="this.form.submit()">
                                @foreach ($months as $m)
                                    <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>Tháng
                                        {{ $m }}</option>
                                @endforeach
                            </select>
                        </div> --}}

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
                            <label for="filter_date" class="form-label">Ngày</label>
                            <select name="filter_date" id="filter_date" class="form-select" onchange="this.form.submit()">
                                @foreach ($availableDates as $date)
                                    <option value="{{ $date }}" {{ $filterDate === $date ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                    @if (!$storage->isEmpty())
                        {{-- BẢNG CHO DESKTOP --}}
                        <div class="table-responsive d-none d-md-block">
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
                                    @foreach ($storage as $groupKey => $items)
                                        @php
                                            [$date, $employeeId, $productId] = explode('|', $groupKey);
                                            $employee = $items->first()->employee;
                                            $product = $items->first()->product;
                                        @endphp

                                        <tr class="table-secondary">
                                            <td colspan="9" class="text-start fw-bold">
                                                Ngày Xuất: {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }} |
                                                Nhân viên: {{ $employee->name }} |
                                                Sản phẩm: {{ $product->name }} ({{ $items->count() }} thùng)
                                            </td>
                                        </tr>

                                        @foreach ($items as $item)
                                            <tr>
                                                <th>{{ $loop->parent->iteration }}.{{ $loop->iteration }}</th>
                                                <td>{{ $item->product->name }}</td>
                                                <td>{{ $item->product->code }}</td>
                                                <td>{{ $item->employee->name }}</td>
                                                <td>{{ $item->employee->code }}</td>
                                                <td>{{ $item->lot }}</td>
                                                <td>{{ $item->bin }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('H:i:s') }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- CARD CHO MOBILE --}}
                        <div class="d-md-none">
                            @foreach ($storage as $groupKey => $items)
                                @php
                                    [$date, $employeeId, $productId] = explode('|', $groupKey);
                                    $employee = $items->first()->employee;
                                    $product = $items->first()->product;
                                @endphp

                                <div class="mb-2 fw-bold text-primary">
                                    Ngày Xuất: {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }} |
                                    Nhân viên: {{ $employee->name }} |
                                    Sản phẩm: {{ $product->name }} ({{ $items->count() }} thùng)
                                </div>

                                @foreach ($items as $item)
                                    <div class="card mb-3 shadow-sm">
                                        <div class="card-body p-3">
                                            <p class="mb-1"><strong>STT:</strong>
                                                {{ $loop->parent->iteration }}.{{ $loop->iteration }}</p>
                                            <p class="mb-1"><strong>Tên Sản Phẩm:</strong> {{ $item->product->name }}</p>
                                            <p class="mb-1"><strong>Code:</strong> {{ $item->product->code }}</p>
                                            <p class="mb-1"><strong>Nhân Viên Nhập:</strong> {{ $item->employee->name }}
                                            </p>
                                            <p class="mb-1"><strong>Mã Nhân Viên:</strong> {{ $item->employee->code }}
                                            </p>
                                            <p class="mb-1"><strong>Số Lot:</strong> {{ $item->lot }}</p>
                                            <p class="mb-1"><strong>Thùng Số:</strong> {{ $item->bin }}</p>
                                            <p class="mb-1"><strong>Ngày Xuất:</strong>
                                                {{ \Carbon\Carbon::parse($item->created_at)->format('d-m') }}</p>
                                            <p class="mb-0"><strong>Thời Gian:</strong>
                                                {{ \Carbon\Carbon::parse($item->created_at)->format('H:i:s') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    @else
                        {{-- THÔNG BÁO KHI KHÔNG CÓ DỮ LIỆU --}}
                        <div class="text-center my-4 text-danger fw-bold">
                            Không có sản phẩm trong kho xuất hàng
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
