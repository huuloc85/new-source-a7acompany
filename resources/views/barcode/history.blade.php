@extends('layouts.layout')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">
                            Lịch Sử In Tem</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.product.barcode.history') }}" class="row g-3 mb-3">
                        <div class="col-12 col-md-6 col-lg-2">
                            <label for="month" class="form-label">
                                Chọn Tháng
                            </label>
                            <input type="month" name="month" id="month" class="form-control"
                                value="{{ request('month', \Carbon\Carbon::now()->format('Y-m')) }}"
                                onchange="this.form.submit()" />
                        </div>
                        <div class="col-12 col-md-6 col-lg-2">
                            <label for="type" class="form-label">
                                Chọn Loại Tem
                            </label>
                            <select name="type" id="type" class="form-control" onchange="this.form.submit()">
                                <option value="">Tất Cả</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6 col-lg-2">
                            <label for="product_id" class="form-label">
                                Chọn Sản Phẩm
                            </label>
                            <select name="product_id" id="product_id" class="form-control" onchange="this.form.submit()">
                                <option value="">Tất Cả</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6 col-lg-2">
                            <label for="employee_id" class="form-label">
                                Chọn Nhân Viên
                            </label>
                            <select name="employee_id" id="employee_id" class="form-control" onchange="this.form.submit()">
                                <option value="">Tất Cả</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                        {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6 col-lg-2">
                            <label for="date" class="form-label">
                                Chọn Ngày
                            </label>
                            <select name="date" id="date" class="form-control" onchange="this.form.submit()">
                                <option value="">Tất Cả</option>
                                @foreach ($dates as $date)
                                    <option value="{{ $date }}" {{ request('date') == $date ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::parse($date)->format('d-m') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex align-items-center border rounded">
                            <i class="fas fa-search"></i>
                            <input type="text" id="search" class="form-control border-0"
                                placeholder="Tìm kiếm theo tên sản phẩm" />
                        </div>
                    </form>
                    <div class="table-responsive">
                        @if ($historyprint->isEmpty())
                            <div class="text-center">
                                Không có lịch sử in tem
                            </div>
                        @else
                            <table class="table table-hover">
                                <thead class="text-uppercase text-center">
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên Sản Phẩm</th>
                                        <th>Tên Nhân Viên</th>
                                        <th>Ngày</th>
                                        <th>Ca</th>
                                        <th>Số Lượng Thùng</th>
                                        <th>Thùng Bắt Đầu</th>
                                        <th>Loại Tem</th>
                                        <th>Thời Gian In</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center align-middle">
                                    @foreach ($historyprint as $history)
                                        <tr>
                                            <th>{{ $loop->iteration }}</th>
                                            <td>
                                                {{ $history->product->name }}
                                            </td>
                                            <td>
                                                {{ $history->employee->name }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($history->date)->format('d-m-Y') }}
                                            </td>
                                            <td>{{ $history->shift }}</td>
                                            <td>{{ $history->binCount }}</td>
                                            <td>{{ $history->binStart }}</td>
                                            <td>{{ $history->type }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($history->created_at)->format('d-m-Y H:i:s') }}
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

@section('scripts')
    <script>
        $(document).ready(function() {
            const searchInput = document.getElementById('search');
            const table = document.querySelector('.table');
            const rows = table.querySelectorAll('tbody tr');

            searchInput.addEventListener('input', function() {
                const searchTerm = searchInput.value.toLowerCase();

                rows.forEach((row) => {
                    const cells = row.querySelectorAll('td');
                    let found = false;

                    cells.forEach((cell) => {
                        if (
                            cell.textContent.toLowerCase().includes(searchTerm)
                        ) {
                            found = true;
                        }
                    });
                    row.style.display = found ? '' : 'none';
                });
            });
        });
    </script>
@endsection
