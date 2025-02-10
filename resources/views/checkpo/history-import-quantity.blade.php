@extends('layouts.layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Danh Sách Lịch Sử Cập Nhật Sản Phẩm</h4>
                    </div>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.checkpo.index') }}" class="btn btn-link mb-3">
                        <i class="fas fa-arrow-left"></i>
                        Quay lại danh sách PO
                    </a>
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                        <form action="{{ route('admin.history-import-quantity') }}" method="get" id="searchForm"
                            class="d-flex flex-wrap flex-sm-nowrap gap-3 align-items-center">
                            @csrf
                            <input type="month" name="month" class="form-control" onchange="this.form.submit()"
                                value="{{ isset($month) ? $month->format('Y-m') : Carbon::now()->format('Y-m') }}" />
                            <select name="date" class="form-control" onchange="this.form.submit()">
                                <option value="">Tất cả ngày</option>
                                @foreach ($dates as $date)
                                    <option value="{{ $date->date }}"
                                        {{ request('date') == $date->date ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::parse($date->date)->format('d-m-Y') }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                        <div class="d-flex border rounded align-items-center ps-2">
                            <i class="fas fa-search"></i>
                            <input type="text" id="search" class="form-control border-0"
                                placeholder="Tìm kiếm theo tên sản phẩm hoặc ngày cần tìm" />
                        </div>
                    </div>
                    <div class="tab-content" id="myTabContent">
                        @if ($dailyQuantities->isEmpty())
                            <div class="text-center mt-3">
                                Hiện tại không có lịch sử nào.
                                <a href="{{ route('admin.checkpo.index') }}">
                                    Quay lại danh sách PO
                                </a>
                                .
                            </div>
                        @else
                            @if ($selectedDate)
                                <form action="{{ route('admin.checkpo.update') }}" method="POST">
                                    @csrf
                                    <div class="text-center mb-3">
                                        <button type="submit" class="btn btn-success">
                                            Cập nhật số lượng
                                        </button>
                                    </div>
                                    <div class="row g-3">
                                        @foreach ($products as $product)
                                            @php
                                                $dailyQuantity = $dailyQuantities->firstWhere(
                                                    'product_id',
                                                    $product->id,
                                                );
                                                $currentQuantity = $dailyQuantity ? $dailyQuantity->quantity : 0;
                                            @endphp

                                            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                                <!-- Khoảng cách giữa các card -->
                                                <div class="card m-0">
                                                    <div class="card-body">
                                                        <h5 class="card-title">
                                                            {{ $product->name }}
                                                        </h5>
                                                        <p class="card-text">
                                                            Số lượng hiện tại:
                                                            {{ number_format($currentQuantity) }}
                                                        </p>
                                                        <input type="number" name="quantities[{{ $product->id }}]"
                                                            value="{{ old('quantities.' . $product->id, $currentQuantity) }}"
                                                            class="form-control" min="0" />
                                                    </div>
                                                    <input type="hidden" name="product_id[]"
                                                        value="{{ $product->id }}" />
                                                    <input type="hidden" name="status" value="8" />
                                                    <input type="hidden" name="date" value="{{ $selectedDate }}" />
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="text-center mt-3">
                                        <button type="submit" class="btn btn-success">
                                            Cập nhật số lượng
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light text-uppercase text-center align-middle">
                                            <tr>
                                                <th>STT</th>
                                                <th>Tên sản phẩm</th>
                                                <th>Số lượng hiện tại</th>
                                                <th>
                                                    Ngày nhập sản lượng xuất
                                                    hàng
                                                </th>
                                                <th>
                                                    Thời gian cập nhật cuối cùng
                                                </th>
                                                <th>Người nhập</th>
                                                <th>Thao Tác</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center align-middle">
                                            @foreach ($dailyQuantities as $dailyQuantity)
                                                <tr>
                                                    <th>
                                                        {{ $loop->iteration }}
                                                    </th>
                                                    <td>
                                                        {{ $dailyQuantity->product->name }}
                                                    </td>
                                                    <td>
                                                        {{ number_format($dailyQuantity->quantity) }}
                                                    </td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($dailyQuantity->date)->format('d-m-Y') }}
                                                    </td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($dailyQuantity->updated_at)->format('d-m-Y H:i:s') }}
                                                    </td>
                                                    <td>
                                                        {{ $dailyQuantity->employee->name }}
                                                    </td>
                                                    <td>
                                                        <button type="submit" class="btn btn-danger" data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal{{ $dailyQuantity->id }}">
                                                            <i class="fas fa-trash-alt"></i>
                                                            Xóa
                                                        </button>
                                                    </td>
                                                </tr>

                                                {{-- Modal delete --}}
                                                <div class="modal fade" id="deleteModal{{ $dailyQuantity->id }}"
                                                    tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel">
                                                                    Xác nhận xóa
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>
                                                                    Bạn có chắc
                                                                    chắn muốn
                                                                    xóa
                                                                    <span class="fw-bold">
                                                                        {{ $dailyQuantity->product->name }}
                                                                    </span>
                                                                    không?
                                                                </p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form
                                                                    action="{{ route('admin.checkpo.delete', $dailyQuantity->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">
                                                                        Xóa
                                                                    </button>
                                                                </form>
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">
                                                                    Hủy
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectedDate = '{{ $selectedDate }}';

            if (selectedDate) {
                const columnsToHide = [
                    'stt-column',
                    'date-column',
                    'update-time-column',
                    'employee-column',
                    'update-quantity-column',
                ];
                columnsToHide.forEach((className) => {
                    const elements = document.querySelectorAll(`.${className}`);
                    elements.forEach((element) => {
                        element.style.display = 'none';
                    });
                });

                const updateQuantityColumns = document.querySelectorAll(
                    'th:contains("Cập nhật số lượng mới"), td:nth-child(4)',
                );
                updateQuantityColumns.forEach((column) => {
                    column.style.display = 'none';
                });
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
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

                    if (searchTerm === '' || found) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection
