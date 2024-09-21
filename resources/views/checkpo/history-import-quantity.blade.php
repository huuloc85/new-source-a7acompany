@extends('master')
@section('content')
    <style>
        .table th,
        .table td {
            vertical-align: middle;
        }

        .product-card {
            width: 20%;
            padding: 10px;
        }

        .custom-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            /* Chuyển tiếp cho hiệu ứng hover */
            height: 100%;

        }

        .custom-card:hover {
            transform: scale(1.05);
            /* Phóng to khi hover */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Tăng độ bóng khi hover */
        }

        /* Đảm bảo card có chiều cao đều nhau */
        .h-100 {
            height: 100%;
        }


        .btn-group-custom {
            display: flex;
            justify-content: center;
        }

        .search-container {
            position: relative;
            width: 29.9%;
        }

        .form-control-search {
            padding-left: 2.5rem;
            /* border-radius: 20px; */
        }

        .search-icon {
            position: absolute;
            left: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1rem;
            color: #6c757d;
        }

        .form-control::placeholder {
            color: #6c757d;

        }

        @media (max-width: 576px) {
            .d-flex {
                flex-direction: column !important;
            }
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header p-2 position-relative mt-n1 mx-1 no-print">
                        <div class="border-radius-lg ps-2 pt-4 pb-3">
                            <h4 class="card-title mb-0">Danh Sách Lịch Sử Cập Nhật Sản Phẩm</h4>
                        </div>
                    </div>
                    <div class="d-flex my-2 ps-2 align-items-center">
                        <a href="{{ route('admin.checkpo.index') }}" class="btn btn-success me-2"
                            style="max-width: 150px;">Danh Sách PO</a>
                        <form action="{{ route('admin.history-import-quantity') }}" method="get" id="searchForm"
                            class="d-flex flex-grow-1">
                            @csrf
                            <input type="month" name="month" class="form-control me-2" onchange="this.form.submit()"
                                value="{{ isset($month) ? $month->format('Y-m') : Carbon::now()->format('Y-m') }}"
                                style="max-width: 20%;">
                            <select name="date" class="form-control" onchange="this.form.submit()"
                                style="max-width: 120px;">
                                <option value="">Chọn ngày</option>
                                @foreach ($dates as $date)
                                    <option value="{{ $date->date }}"
                                        {{ request('date') == $date->date ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::parse($date->date)->format('d-m-Y') }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                        <div class="search-container" style="{{ $selectedDate ? 'display: none;' : '' }}">
                            <input type="text" id="search" class="form-control form-control-search"
                                placeholder="Tìm kiếm theo tên sản phẩm hoặc ngày cần tìm">
                            <i class="search-icon fas fa-search"></i>
                        </div>
                    </div>
                    <div class="px-0 pb-2">
                        <div class="tab-content" id="myTabContent">
                            @if ($dailyQuantities->isEmpty())
                                <div class="text-center mt-3">Hiện tại không có lịch sử nào. <a
                                        href="{{ route('admin.checkpo.index') }}">Quay lại danh sách PO</a>.</div>
                            @else
                                <form action="{{ route('admin.checkpo.update') }}" method="POST">
                                    @csrf
                                    <div class="text-center mb-3" style="{{ !$selectedDate ? 'display: none;' : '' }}">
                                        <button type="submit" class="btn btn-success">Cập nhật số lượng</button>
                                    </div>

                                    @if ($selectedDate)
                                        <div id="product-grid" class="d-flex flex-wrap justify-content-start">
                                            @foreach ($products as $product)
                                                @php
                                                    $dailyQuantity = $dailyQuantities->firstWhere(
                                                        'product_id',
                                                        $product->id,
                                                    );
                                                    $currentQuantity = $dailyQuantity ? $dailyQuantity->quantity : 0;
                                                @endphp
                                                <div class="product-card col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                                                    <!-- Khoảng cách giữa các card -->
                                                    <div class="card custom-card">
                                                        <div class="card-body">
                                                            <h5 class="card-title">{{ $product->name }}</h5>
                                                            <p class="card-text">Số lượng hiện tại:
                                                                {{ number_format($currentQuantity) }}</p>
                                                            <input type="number" name="quantities[{{ $product->id }}]"
                                                                value="{{ old('quantities.' . $product->id, $currentQuantity) }}"
                                                                class="form-control" min="0">
                                                        </div>
                                                        <input type="hidden" name="product_id[]"
                                                            value="{{ $product->id }}">
                                                        <input type="hidden" name="status" value="8">
                                                        <input type="hidden" name="date" value="{{ $selectedDate }}">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table align-items-center mb-0 table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center text-uppercase text-md font-weight-bolder">
                                                            STT</th>
                                                        <th class="text-center text-uppercase text-md font-weight-bolder">
                                                            Tên sản phẩm</th>
                                                        <th class="text-center text-uppercase text-md font-weight-bolder">Số
                                                            lượng hiện tại</th>
                                                        <th class="text-center text-uppercase text-md font-weight-bolder"
                                                            style="display: none;">Cập nhật số lượng mới</th>
                                                        <th class="text-center text-uppercase text-md font-weight-bolder">
                                                            Ngày nhập sản lượng xuất hàng</th>
                                                        <th class="text-center text-uppercase text-md font-weight-bolder">
                                                            Thời gian cập nhật cuối cùng</th>
                                                        <th class="text-center text-uppercase text-md font-weight-bolder">
                                                            Người nhập</th>
                                                        <th class="text-center text-uppercase text-md font-weight-bolder">
                                                            Thao Tác</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($dailyQuantities as $dailyQuantity)
                                                        <tr>
                                                            <td class="text-center">{{ $loop->iteration }}</td>
                                                            <td class="text-center">{{ $dailyQuantity->product->name }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ number_format($dailyQuantity->quantity) }}</td>
                                                            <td class="text-center" style="display: none;">
                                                                <input type="number"
                                                                    name="quantities[{{ $dailyQuantity->product->id }}]"
                                                                    value="{{ old('quantities.' . $dailyQuantity->product->id, $dailyQuantity->quantity) }}"
                                                                    class="form-control" min="0">
                                                            </td>
                                                            <td class="text-center">
                                                                {{ \Carbon\Carbon::parse($dailyQuantity->date)->format('d-m-Y') }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ \Carbon\Carbon::parse($dailyQuantity->updated_at)->format('d-m-Y H:i:s') }}
                                                            </td>
                                                            <td class="text-center">{{ $dailyQuantity->employee->name }}
                                                            </td>
                                                            <td class="text-center">
                                                                <div>
                                                                    <form
                                                                        action="{{ route('admin.checkpo.delete', $dailyQuantity->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger"
                                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa không?')"
                                                                            title="Xóa">
                                                                            <i class="fas fa-trash-alt"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </td>
                                                            <input type="hidden" name="product_id[]"
                                                                value="{{ $dailyQuantity->product->id }}">
                                                            <input type="hidden" name="status" value="8">
                                                            <input type="hidden" name="date"
                                                                value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                    {{-- <div class="text-center mt-3">
                                        <button type="submit" class="btn btn-success">Cập nhật số lượng</button>
                                    </div> --}}
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectedDate = '{{ $selectedDate }}';

            if (selectedDate) {
                const columnsToHide = ['stt-column', 'date-column', 'update-time-column', 'employee-column',
                    'update-quantity-column'
                ];
                columnsToHide.forEach(className => {
                    const elements = document.querySelectorAll(`.${className}`);
                    elements.forEach(element => {
                        element.style.display = 'none';
                    });
                });

                const updateQuantityColumns = document.querySelectorAll(
                    'th:contains("Cập nhật số lượng mới"), td:nth-child(4)');
                updateQuantityColumns.forEach(column => {
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

                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    let found = false;

                    cells.forEach(cell => {
                        if (cell.textContent.toLowerCase().includes(searchTerm)) {
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
