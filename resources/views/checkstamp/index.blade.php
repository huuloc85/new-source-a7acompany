@extends('layouts.'.$layout)

@section('styles')
    <style>
        tr[data-id]:not(.highlight-row) td,
        tr[data-id]:not(.highlight-row) th {
            background-color: transparent;
            border-color: transparent;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">
                            Bảng Yêu Cầu In Tem Ngày
                            {{ \Carbon\Carbon::parse($date)->format('d-m') }}
                        </h4>
                    </div>
                </div>
                <div class="card-body">
                    <form
                        method="GET"
                        action="{{ route('admin.checkstamp') }}"
                        class="row g-3 mb-3"
                        id="filterForm"
                    >
                        <div class="row">
                            <div class="col-md-3">
                                <select
                                    name="product_name"
                                    class="form-control"
                                    onchange="submitForm()"
                                >
                                    <option value="">Chọn Sản Phẩm</option>
                                    @foreach ($products as $product)
                                        <option
                                            value="{{ $product->name }}"
                                            {{ request('product_name') == $product->name ? 'selected' : '' }}
                                        >
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select
                                    name="employee_name"
                                    class="form-control"
                                    onchange="submitForm()"
                                >
                                    <option value="">Chọn Nhân Viên</option>
                                    @foreach ($employees as $employee)
                                        <option
                                            value="{{ $employee->name }}"
                                            {{ request('employee_name') == $employee->name ? 'selected' : '' }}
                                        >
                                            {{ $employee->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select
                                    name="status"
                                    class="form-control"
                                    onchange="submitForm()"
                                >
                                    <option value="">Trạng Thái</option>
                                    <option
                                        value="pending"
                                        {{ request('status') == 'pending' ? 'selected' : '' }}
                                    >
                                        Chờ In
                                    </option>
                                    <option
                                        value="approve"
                                        {{ request('status') == 'approve' ? 'selected' : '' }}
                                    >
                                        Đã In
                                    </option>
                                    <option
                                        value="rejected"
                                        {{ request('status') == 'rejected' ? 'selected' : '' }}
                                    >
                                        Từ Chối
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input
                                    type="date"
                                    name="date"
                                    class="form-control"
                                    value="{{ request('date', \Carbon\Carbon::today()->toDateString()) }}"
                                    onchange="submitForm()"
                                />
                            </div>
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
                                        <th>Ngày (Số Lot)</th>
                                        <th>Ca</th>
                                        <th>Số Lượng Tem</th>
                                        <th>Bắt Đầu Từ Tem Số</th>
                                        <th>Loại Tem</th>
                                        <th>Ngày Gửi Yêu Cầu</th>
                                        <th>Thời Gian Gửi Yêu Cầu</th>
                                        <th>Trạng Thái</th>
                                        <th>Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center align-middle">
                                    @foreach ($historyprint as $history)
                                        <tr data-id="{{ $history->id }}">
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
                                                {{ \Carbon\Carbon::parse($history->created_at)->format('d-m-Y') }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($history->created_at)->format('H:i:s') }}
                                            </td>
                                            <td>
                                                @php
                                                    $statusClasses = [
                                                        'pending' => 'bg-warning',
                                                        'approve' => 'bg-success',
                                                        'rejected' => 'bg-danger',
                                                    ];
                                                    $statusText = [
                                                        'pending' => 'Chờ In',
                                                        'approve' => 'Đã In',
                                                        'rejected' => 'Từ Chối',
                                                    ];
                                                @endphp

                                                <span
                                                    class="badge {{ $statusClasses[$history->status] ?? 'bg-secondary' }}"
                                                >
                                                    {{ $statusText[$history->status] ?? $history->status }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($history->status == 'approve' || $history->status == 'rejected')
                                                    <button
                                                        class="btn btn-secondary"
                                                        disabled
                                                    >
                                                        <i
                                                            class="fas fa-print"
                                                        ></i>
                                                        In
                                                    </button>
                                                @else
                                                    <a
                                                        href="{{ route('admin.send-stamp.print', $history->id) }}"
                                                        class="btn btn-primary"
                                                    >
                                                        <i
                                                            class="fas fa-print"
                                                        ></i>
                                                        In
                                                    </a>
                                                @endif

                                                @if ($history->status == 'pending')
                                                    <form
                                                        action="{{ route('admin.stamp.reject.print', $history->id) }}"
                                                        method="POST"
                                                        style="display: inline"
                                                    >
                                                        @csrf
                                                        <button
                                                            type="submit"
                                                            class="btn btn-danger"
                                                            onclick="return confirm('Bạn có chắc chắn muốn từ chối không?');"
                                                        >
                                                            <i
                                                                class="fas fa-ban"
                                                            ></i>
                                                            Từ chối
                                                        </button>
                                                    </form>
                                                @endif
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
        $(document).ready(function () {
            const searchInput = document.getElementById('search');
            const table = document.querySelector('.table');
            const rows = table.querySelectorAll('tbody tr');

            searchInput.addEventListener('input', function () {
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
    <script>
        function submitForm() {
            document.getElementById('filterForm').submit();
        }
    </script>
@endsection
