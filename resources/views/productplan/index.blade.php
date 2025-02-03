@extends('layouts.layout')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Kế Hoạch Sản Xuất Tháng {{ $currentMonth }}</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-3 flex-wrap mb-3">
                        <a
                            href="{{ route('admin.product-plan.add') }}"
                            class="btn btn-success"
                        >
                            <i class="fas fa-plus"></i>
                            Thêm Sản Phẩm Vào Kế Hoạch
                        </a>
                        <a
                            href="{{ route('admin.product-plan.config') }}"
                            class="btn btn-warning"
                        >
                            <i class="fas fa-plus"></i>
                            Thêm Kế Hoạch Sản Xuất
                        </a>
                    </div>
                    <div class="d-flex gap-3 flex-wrap mb-3">
                        <form
                            action="{{ route('admin.product-plan.export') }}"
                            method="GET"
                        >
                            <input
                                type="hidden"
                                name="month"
                                value="{{ $selectedMonth }}"
                            />
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-file-export"></i>
                                Xuất Excel
                            </button>
                        </form>
                        <form
                            method="GET"
                            action="{{ route('admin.product-plan.index') }}"
                            id="month-filter-form"
                        >
                            <select
                                class="form-select @error('month') is-invalid @enderror"
                                id="month"
                                name="month"
                                onchange="document.getElementById('month-filter-form').submit()"
                            >
                                @foreach ($months as $month)
                                    <option
                                        value="{{ $month }}"
                                        {{ $month === $selectedMonth ? 'selected' : '' }}
                                    >
                                        {{ $month }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <!-- Bảng kế hoạch sản xuất -->
                    <div class="col-12 table-responsive">
                        <table
                            class="table table-bordered table-hover table-striped"
                        >
                            <thead
                                class="text-uppercase text-center align-middle"
                            >
                                <tr>
                                    <!-- Tiêu đề gộp cho Kế Hoạch Sản Xuất -->
                                    <th colspan="22">Kế Hoạch Sản Xuất</th>
                                    <!-- Tiêu đề gộp cho Thông Tin Ngày -->
                                    <th colspan="{{ 2 * count($listDate) }}">
                                        Bảng Sản Xuất Hằng Ngày
                                    </th>
                                    <th rowspan="3">Hành Động</th>
                                </tr>
                                <tr>
                                    <th rowspan="2">STT</th>
                                    <th rowspan="2">Tên Sản Phẩm</th>
                                    <th rowspan="2">Nguyên Vật Liệu</th>
                                    <th rowspan="2">Màu Sắc</th>
                                    <th rowspan="2">Kế Hoạch Sản Xuất</th>
                                    <th rowspan="2">Dự Định Vật Liệu (KG)</th>
                                    <th rowspan="2">Loại Bao Bì</th>
                                    <th rowspan="2">Số Bao Bì/Thùng</th>
                                    <th rowspan="2">Tổng Bao Bì</th>
                                    <th rowspan="2">Loại Thùng</th>
                                    <th rowspan="2">Sản Phẩm/Thùng</th>
                                    <th rowspan="2">Số Lượng Thùng</th>
                                    <th rowspan="2">Tỷ Trọng Sản Phẩm (G)</th>
                                    <th rowspan="2">Kế Hoạch Sản Xuất/Ngày</th>
                                    <th rowspan="2">Số Cavity</th>
                                    <th rowspan="2">Chu Kỳ</th>
                                    <th rowspan="2">Tấn</th>
                                    <th rowspan="2">Máy</th>
                                    <th rowspan="2">Số Ngày Chạy Máy</th>
                                    <th rowspan="2">Số Ngày Còn SX (Ngày)</th>
                                    <th rowspan="2">Số Lượng Còn SX (PCS)</th>
                                    <th rowspan="2">Số Lượng Đã SX (PCS)</th>

                                    <!-- Tiêu đề cho các ngày -->
                                    @foreach ($listDate as $date)
                                        @php
                                            $dateObj = Carbon\Carbon::parse($date);
                                        @endphp

                                        <th colspan="2">
                                            Ngày
                                            {{ $dateObj->format('d-m') }}
                                        </th>
                                    @endforeach
                                </tr>
                                <tr>
                                    <!-- Tiêu đề cho các ca -->
                                    @foreach ($listDate as $date)
                                        <th>Ca 1</th>
                                        <th>Ca 2</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="text-center align-middle">
                                @foreach ($productPlans as $plan)
                                    <tr>
                                        <td class="fw-bold">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ $plan->product->name }}
                                        </td>
                                        <td>
                                            {{ $plan->product->material }}
                                        </td>
                                        <td>
                                            {{ $plan->product->color }}
                                        </td>
                                        <td>
                                            {{ number_format($plan->production_plan) }}
                                        </td>
                                        <td>
                                            {{ number_format($plan->planned_material) }}
                                        </td>
                                        <td>
                                            {{ $plan->packaging_type }}
                                        </td>
                                        <td>
                                            {{ $plan->packaging_count_per_box }}
                                        </td>
                                        <td>
                                            {{ number_format($plan->total_packaging) }}
                                        </td>
                                        <td>
                                            {{ $plan->box_type }}
                                        </td>
                                        <td>
                                            {{ number_format($plan->products_per_box) }}
                                        </td>
                                        <td>
                                            {{ $plan->box_quantity }}
                                        </td>
                                        <td>
                                            {{ $plan->product_density }}
                                        </td>
                                        <td>
                                            {{ number_format($plan->daily_production_plan) }}
                                        </td>
                                        <td>
                                            {{ $plan->cavity_count }}
                                        </td>
                                        <td>
                                            {{ $plan->cycle }}
                                        </td>
                                        <td>
                                            {{ $plan->ton }}
                                        </td>
                                        <td>
                                            {{ $plan->machine }}
                                        </td>
                                        <td>
                                            {{ $plan->machine_run_days }}
                                        </td>
                                        <td>
                                            {{ $plan->remaining_production_days }}
                                        </td>
                                        <td>
                                            {{ number_format($plan->remaining_production_quantity) }}
                                        </td>
                                        <td>
                                            {{ number_format($plan->produced_quantity) }}
                                        </td>

                                        <!-- Thông tin ngày -->
                                        @foreach ($listDate as $date)
                                            @php
                                                $formattedDate = Carbon\Carbon::parse($date)->startOfDay();
                                                $dailyQuantitiesOfTheDay = $plan->product
                                                    ->DailyQuantities()
                                                    ->where('status', 1)
                                                    ->whereDate('date', $formattedDate)
                                                    ->get();

                                                $totalQuanDateCa1 = 0;
                                                $totalQuanDateCa2 = 0;

                                                foreach ($dailyQuantitiesOfTheDay as $dailyQuantity) {
                                                    $created_at = Carbon\Carbon::parse($dailyQuantity->created_at);
                                                    $nextDayEightAM = $formattedDate->copy()->addDay()->setHour(10);

                                                    if ($created_at->isSameDay($formattedDate)) {
                                                        $totalQuanDateCa1 += $dailyQuantity->quantity;
                                                    } elseif (
                                                        $created_at->isSameDay($formattedDate->copy()->addDay()) &&
                                                        $created_at->lessThan($nextDayEightAM)
                                                    ) {
                                                        $totalQuanDateCa2 += $dailyQuantity->quantity;
                                                    }
                                                }
                                            @endphp

                                            <td>
                                                {{ number_format($totalQuanDateCa1) }}
                                            </td>
                                            <td>
                                                {{ number_format($totalQuanDateCa2) }}
                                            </td>
                                        @endforeach

                                        {{-- Actions --}}
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal-{{ $plan->id }}"
                                            >
                                                <i class="fas fa-edit"></i>
                                                Sửa
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal-{{ $plan->id }}"
                                            >
                                                <i class="fas fa-trash-alt"></i>
                                                Xóa
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($productPlans as $plan)
        {{-- Modal Edit --}}
        <div
            class="modal fade"
            id="editModal-{{ $plan->id }}"
            tabindex="-1"
            aria-labelledby="editModalLabel-{{ $plan->id }}"
            aria-hidden="true"
        >
            <div
                class="modal-dialog modal-dialog-scrollable modal-dialog-centered"
            >
                <form
                    action="{{ route('admin.product-plan.update', ['id' => $plan->id]) }}"
                    method="POST"
                    class="modal-content"
                >
                    @csrf
                    <div class="modal-header">
                        <h5
                            class="modal-title"
                            id="editModalLabel-{{ $plan->id }}"
                        >
                            Chỉnh Sửa Kế Hoạch Sản Xuất
                        </h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="product_id" class="form-label">
                                Tên Sản Phẩm
                            </label>
                            <select
                                class="form-select form-control @error('product_id') is-invalid @enderror"
                                id="product_select"
                                name="product_id"
                                required
                            >
                                @foreach ($products as $product)
                                    <option
                                        value="{{ $product->id }}"
                                        data-bin-code="{{ $product->binCode }}"
                                        data-quan-entity-bin="{{ $product->quanEntityBin }}"
                                        data-material="{{ $product->material }}"
                                        data-color="{{ $product->color }}"
                                        {{ $plan->product_id == $product->id ? 'selected' : '' }}
                                    >
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('product_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="material">Nguyên Vật Liệu</label>
                            <input
                                type="text"
                                class="form-control @error('material') is-invalid @enderror"
                                id="material"
                                name="material"
                                value="{{ old('material', $plan->material) }}"
                                required
                            />
                            @error('material')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="color">Màu Sắc</label>
                            <input
                                type="text"
                                class="form-control @error('color') is-invalid @enderror"
                                id="color"
                                name="color"
                                value="{{ old('color', $plan->color) }}"
                                required
                            />
                            @error('color')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="packaging_type">Loại Bao Bì</label>
                            <select
                                class="form-select @error('packaging_type') is-invalid @enderror"
                                id="packaging_type"
                                name="packaging_type"
                                required
                            >
                                @foreach ($packagingTypes as $packagingType)
                                    <option
                                        value="{{ $packagingType }}"
                                        {{ old('packaging_type', $plan->packaging_type) == $packagingType ? 'selected' : '' }}
                                    >
                                        {{ $packagingType }}
                                    </option>
                                @endforeach
                            </select>
                            @error('packaging_type')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="production_plan">
                                Kế Hoạch Sản Xuất
                            </label>
                            <input
                                type="text"
                                class="form-control @error('production_plan') is-invalid @enderror"
                                id="production_plan"
                                name="production_plan"
                                value="{{ old('production_plan', $plan->production_plan) }}"
                            />
                            @error('production_plan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="planned_material">
                                Dự Định Vật Liệu (KG)
                            </label>
                            <input
                                type="number"
                                step="0.01"
                                class="form-control @error('planned_material') is-invalid @enderror"
                                id="planned_material"
                                name="planned_material"
                                value="{{ old('planned_material', $plan->planned_material) }}"
                                required
                            />
                            @error('planned_material')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="packaging_count_per_box">
                                Số Bao Bì/Thùng
                            </label>
                            <input
                                type="number"
                                class="form-control @error('packaging_count_per_box') is-invalid @enderror"
                                id="packaging_count_per_box"
                                name="packaging_count_per_box"
                                value="{{ old('packaging_count_per_box', $plan->packaging_count_per_box) }}"
                                required
                            />
                            @error('packaging_count_per_box')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="total_packaging">Tổng Bao Bì</label>
                            <input
                                type="number"
                                class="form-control @error('total_packaging') is-invalid @enderror"
                                id="total_packaging"
                                name="total_packaging"
                                value="{{ old('total_packaging', $plan->total_packaging) }}"
                                required
                            />
                            @error('total_packaging')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="box_type">Loại Thùng</label>
                            <input
                                type="text"
                                class="form-control @error('box_type') is-invalid @enderror"
                                id="box_type"
                                name="box_type"
                                value="{{ old('box_type', $plan->box_type) }}"
                                required
                            />
                            @error('box_type')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="products_per_box">Sản Phẩm/Thùng</label>
                            <input
                                type="number"
                                class="form-control @error('products_per_box') is-invalid @enderror"
                                id="products_per_box"
                                name="products_per_box"
                                value="{{ old('products_per_box', $plan->products_per_box) }}"
                                required
                            />
                            @error('products_per_box')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="box_quantity">Số Lượng Thùng</label>
                            <input
                                type="number"
                                class="form-control @error('box_quantity') is-invalid @enderror"
                                id="box_quantity"
                                name="box_quantity"
                                value="{{ old('box_quantity', $plan->box_quantity) }}"
                                required
                            />
                            @error('box_quantity')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="product_density">
                                Tỷ Trọng Sản Phẩm (G)
                            </label>
                            <input
                                class="form-control @error('product_density') is-invalid @enderror"
                                id="product_density"
                                name="product_density"
                                value="{{ old('product_density', $plan->product_density) }}"
                                required
                            />
                            @error('product_density')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="daily_production_plan">
                                Kế Hoạch Sản Xuất/Ngày
                            </label>
                            <input
                                type="number"
                                class="form-control @error('daily_production_plan') is-invalid @enderror"
                                id="daily_production_plan"
                                name="daily_production_plan"
                                value="{{ old('daily_production_plan', $plan->daily_production_plan) }}"
                                required
                            />
                            @error('daily_production_plan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="cavity_count">Số Cavity</label>
                            <input
                                type="number"
                                class="form-control @error('cavity_count') is-invalid @enderror"
                                id="cavity_count"
                                name="cavity_count"
                                value="{{ old('cavity_count', $plan->cavity_count) }}"
                                required
                            />
                            @error('cavity_count')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="cycle">Chu Kỳ</label>
                            <input
                                class="form-control @error('cycle') is-invalid @enderror"
                                id="cycle"
                                name="cycle"
                                value="{{ old('cycle', $plan->cycle) }}"
                                required
                            />
                            @error('cycle')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="ton">Tấn</label>
                            <input
                                type="number"
                                class="form-control @error('ton') is-invalid @enderror"
                                id="ton"
                                name="ton"
                                value="{{ old('ton', $plan->ton) }}"
                                required
                            />
                            @error('ton')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="machine">Máy</label>
                            <input
                                type="text"
                                class="form-control @error('machine') is-invalid @enderror"
                                id="machine"
                                name="machine"
                                value="{{ old('machine', $plan->machine) }}"
                                required
                            />
                            @error('machine')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="machine_run_days">
                                Số Ngày Chạy Máy
                            </label>
                            <input
                                type="number"
                                class="form-control @error('machine_run_days') is-invalid @enderror"
                                id="machine_run_days"
                                name="machine_run_days"
                                value="{{ old('machine_run_days', $plan->machine_run_days) }}"
                                required
                            />
                            @error('machine_run_days')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="remaining_production_days">
                                Số Ngày Còn SX (Ngày)
                            </label>
                            <input
                                type="number"
                                class="form-control @error('remaining_production_days') is-invalid @enderror"
                                id="remaining_production_days"
                                name="remaining_production_days"
                                value="{{ old('remaining_production_days', $plan->remaining_production_days) }}"
                                required
                            />
                            @error('remaining_production_days')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="remaining_production_quantity">
                                Số Lượng Còn SX (PCS)
                            </label>
                            <input
                                type="number"
                                class="form-control @error('remaining_production_quantity') is-invalid @enderror"
                                id="remaining_production_quantity"
                                name="remaining_production_quantity"
                                value="{{ old('remaining_production_quantity', $plan->remaining_production_quantity) }}"
                                required
                            />
                            @error('remaining_production_quantity')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="produced_quantity">
                                Số Lượng Đã SX (PCS)
                            </label>
                            <input
                                type="number"
                                class="form-control @error('produced_quantity') is-invalid @enderror"
                                id="produced_quantity"
                                name="produced_quantity"
                                value="{{ old('produced_quantity', $plan->produced_quantity) }}"
                                required
                            />
                            @error('produced_quantity')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            Lưu
                        </button>
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Hủy
                        </button>
                    </div>
                </form>
            </div>
        </div>
        {{-- Modal Delete --}}
        <div
            class="modal fade"
            id="deleteModal-{{ $plan->id }}"
            tabindex="-1"
            aria-labelledby="deleteModalLabel-{{ $plan->id }}"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered">
                <form
                    action="{{ route('admin.product-plan.delete', $plan->id) }}"
                    method="POST"
                    class="modal-content"
                >
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h1
                            class="modal-title fs-5"
                            id="deleteModalLabel-{{ $plan->id }}"
                        >
                            Modal title
                        </h1>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <p>
                            Bạn có chắc chắn muốn xóa kế hoạch của sản phẩm "
                            <span class="fw-bold">
                                {{ $plan->product->name }}
                            </span>
                            " không?
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">
                            Xóa
                        </button>
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var productSelect = document.getElementById('product_select');
            var boxTypeInput = document.getElementById('box_type');
            var productsPerBoxInput =
                document.getElementById('products_per_box');
            var materialInput = document.getElementById('material');
            var colorInput = document.getElementById('color');

            productSelect.addEventListener('change', function () {
                var selectedOption =
                    productSelect.options[productSelect.selectedIndex];
                var binCode = selectedOption.getAttribute('data-bin-code');
                var quanEntityBin = selectedOption.getAttribute(
                    'data-quan-entity-bin',
                );
                var material = selectedOption.getAttribute('data-material');
                var color = selectedOption.getAttribute('data-color');

                boxTypeInput.value = binCode || '';
                productsPerBoxInput.value = quanEntityBin || '';
                materialInput.value = material || '';
                colorInput.value = color || '';
            });
            var event = new Event('change');
            productSelect.dispatchEvent(event);
        });
    </script>
@endsection
