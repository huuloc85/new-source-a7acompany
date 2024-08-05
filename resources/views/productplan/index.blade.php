@extends('master')

@section('content')
    <style>
        .modal-dialog {
            max-width: 50%;
            width: 50%;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 0.5rem;
            text-align: left;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .form-group input,
        .form-group select {
            width: 100%;
        }

        .form-buttons {
            grid-column: span 4;
            display: flex;
            justify-content: center;
            gap: 1rem;
        }
    </style>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Kế Hoạch Sản Xuất Tháng {{ $currentMonth }}</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <form method="GET" action="{{ route('admin.product-plan.index') }}" id="month-filter-form"
                            class="col-1">
                            <div class="form-group">
                                <select class="form-control @error('month') is-invalid @enderror bg-light border-primary"
                                    id="month" name="month"
                                    onchange="document.getElementById('month-filter-form').submit()" required>
                                    @foreach ($months as $month)
                                        <option value="{{ $month }}"
                                            {{ $month === $selectedMonth ? 'selected' : '' }}>
                                            {{ $month }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('month')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </form>

                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.product-plan.add') }}" class="btn btn-success">Thêm Sản Phẩm Vào Kế
                                Hoạch
                            </a>
                            <a href="{{ route('admin.product-plan.config') }}" class="btn btn-warning">Thêm Kế Hoạch Sản
                                Xuất</a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th class="text-center">Tên Sản Phẩm</th>
                                    <th class="text-center">Nguyên Vật Liệu</th>
                                    <th class="text-center">Kế Hoạch Sản Xuất</th>
                                    <th class="text-center">Dự Định Vật Liệu (KG)</th>
                                    <th class="text-center">Loại Bao Bì</th>
                                    <th class="text-center">Số Bao Bì/Thùng</th>
                                    <th class="text-center">Tổng Bao Bì</th>
                                    <th class="text-center">Loại Thùng</th>
                                    <th class="text-center">Sản Phẩm/Thùng</th>
                                    <th class="text-center">Số Lượng Thùng</th>
                                    <th class="text-center">Tỷ Trọng Sản Phẩm (G)</th>
                                    <th class="text-center">Kế Hoạch Sản Xuất/Ngày</th>
                                    <th class="text-center">Số Cavity</th>
                                    <th class="text-center">Chu Kỳ</th>
                                    <th class="text-center">Tấn</th>
                                    <th class="text-center">Máy</th>
                                    <th class="text-center">Số Ngày Chạy Máy</th>
                                    <th class="text-center">Số Ngày Còn SX (Ngày)</th>
                                    <th class="text-center">Số Lượng Còn SX (PCS)</th>
                                    <th class="text-center">Số Lượng Đã SX (PCS)</th>
                                    <th class="text-center">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productPlans as $plan)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $plan->product->name }}</td>
                                        <td class="text-center">{{ $plan->material_name }}</td>
                                        <td class="text-center">{{ $plan->production_plan }}</td>
                                        <td class="text-center">{{ $plan->planned_material }}</td>
                                        <td class="text-center">{{ $plan->packaging_type }}</td>
                                        <td class="text-center">{{ $plan->packaging_count_per_box }}</td>
                                        <td class="text-center">{{ $plan->total_packaging }}</td>
                                        <td class="text-center">{{ $plan->box_type }}</td>
                                        <td class="text-center">{{ $plan->products_per_box }}</td>
                                        <td class="text-center">{{ $plan->box_quantity }}</td>
                                        <td class="text-center">{{ $plan->product_density }}</td>
                                        <td class="text-center">{{ $plan->daily_production_plan }}</td>
                                        <td class="text-center">{{ $plan->cavity_count }}</td>
                                        <td class="text-center">{{ $plan->cycle }}</td>
                                        <td class="text-center">{{ $plan->ton }}</td>
                                        <td class="text-center">{{ $plan->machine }}</td>
                                        <td class="text-center">{{ $plan->machine_run_days }}</td>
                                        <td class="text-center">{{ $plan->remaining_production_days }}</td>
                                        <td class="text-center">{{ $plan->remaining_production_quantity }}</td>
                                        <td class="text-center">{{ $plan->produced_quantity }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $plan->id }}">
                                                Sửa
                                            </button>
                                            <form action="{{ route('admin.product-plan.delete', $plan->id) }}"
                                                method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa kế hoạch sản phẩm này?')">
                                                    Xóa
                                                </button>
                                            </form>
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

    <!-- Modal Edit -->
    @foreach ($productPlans as $plan)
        <div class="modal fade" id="editModal{{ $plan->id }}" tabindex="-1" role="dialog"
            aria-labelledby="editModalLabel{{ $plan->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel{{ $plan->id }}">Chỉnh Sửa Kế Hoạch Sản Xuất</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.product-plan.update', ['id' => $plan->id]) }}" method="POST">
                            @csrf
                            <div class="grid-container">
                                <div class="form-group">
                                    <label for="product_id" class="form-label">Tên Sản Phẩm</label>
                                    <select class="form-select form-control @error('product_id') is-invalid @enderror"
                                        id="product_select" name="product_id" required>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" data-bin-code="{{ $product->binCode }}"
                                                data-quan-entity-bin="{{ $product->quanEntityBin }}"
                                                {{ $plan->product_id == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="material_name">Nguyên Vật Liệu</label>
                                    <select class="form-select @error('material_name') is-invalid @enderror"
                                        id="material_name" name="material_name" required>
                                        @foreach ($materials as $material)
                                            <option value="{{ $material }}"
                                                {{ old('material_name', $plan->material_name) == $material ? 'selected' : '' }}>
                                                {{ $material }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('material_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="packaging_type">Loại Bao Bì</label>
                                    <select class="form-select @error('packaging_type') is-invalid @enderror"
                                        id="packaging_type" name="packaging_type" required>
                                        @foreach ($packagingTypes as $packagingType)
                                            <option value="{{ $packagingType }}"
                                                {{ old('packaging_type', $plan->packaging_type) == $packagingType ? 'selected' : '' }}>
                                                {{ $packagingType }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('packaging_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="production_plan">Kế Hoạch Sản Xuất</label>
                                    <input type="text"
                                        class="form-control @error('production_plan') is-invalid @enderror"
                                        id="production_plan" name="production_plan"
                                        value="{{ old('production_plan', $plan->production_plan) }}" required>
                                    @error('production_plan')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="planned_material">Dự Định Vật Liệu (KG)</label>
                                    <input type="number" step="0.01"
                                        class="form-control @error('planned_material') is-invalid @enderror"
                                        id="planned_material" name="planned_material"
                                        value="{{ old('planned_material', $plan->planned_material) }}" required>
                                    @error('planned_material')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="packaging_count_per_box">Số Bao Bì/Thùng</label>
                                    <input type="number"
                                        class="form-control @error('packaging_count_per_box') is-invalid @enderror"
                                        id="packaging_count_per_box" name="packaging_count_per_box"
                                        value="{{ old('packaging_count_per_box', $plan->packaging_count_per_box) }}"
                                        required>
                                    @error('packaging_count_per_box')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="total_packaging">Tổng Bao Bì</label>
                                    <input type="number"
                                        class="form-control @error('total_packaging') is-invalid @enderror"
                                        id="total_packaging" name="total_packaging"
                                        value="{{ old('total_packaging', $plan->total_packaging) }}" required>
                                    @error('total_packaging')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="box_type">Loại Thùng</label>
                                    <input type="text" class="form-control @error('box_type') is-invalid @enderror"
                                        id="box_type" name="box_type" value="{{ old('box_type', $plan->box_type) }}"
                                        required>
                                    @error('box_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="products_per_box">Sản Phẩm/Thùng</label>
                                    <input type="number"
                                        class="form-control @error('products_per_box') is-invalid @enderror"
                                        id="products_per_box" name="products_per_box"
                                        value="{{ old('products_per_box', $plan->products_per_box) }}" required>
                                    @error('products_per_box')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="box_quantity">Số Lượng Thùng</label>
                                    <input type="number"
                                        class="form-control @error('box_quantity') is-invalid @enderror"
                                        id="box_quantity" name="box_quantity"
                                        value="{{ old('box_quantity', $plan->box_quantity) }}" required>
                                    @error('box_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="product_density">Tỷ Trọng Sản Phẩm (G)</label>
                                    <input type="number"
                                        class="form-control @error('product_density') is-invalid @enderror"
                                        id="product_density" name="product_density"
                                        value="{{ old('product_density', $plan->product_density) }}" required>
                                    @error('product_density')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="daily_production_plan">Kế Hoạch Sản Xuất/Ngày</label>
                                    <input type="number"
                                        class="form-control @error('daily_production_plan') is-invalid @enderror"
                                        id="daily_production_plan" name="daily_production_plan"
                                        value="{{ old('daily_production_plan', $plan->daily_production_plan) }}" required>
                                    @error('daily_production_plan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="cavity_count">Số Cavity</label>
                                    <input type="number"
                                        class="form-control @error('cavity_count') is-invalid @enderror"
                                        id="cavity_count" name="cavity_count"
                                        value="{{ old('cavity_count', $plan->cavity_count) }}" required>
                                    @error('cavity_count')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="cycle">Chu Kỳ</label>
                                    <input type="number" class="form-control @error('cycle') is-invalid @enderror"
                                        id="cycle" name="cycle" value="{{ old('cycle', $plan->cycle) }}" required>
                                    @error('cycle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="ton">Tấn</label>
                                    <input type="number" class="form-control @error('ton') is-invalid @enderror"
                                        id="ton" name="ton" value="{{ old('ton', $plan->ton) }}" required>
                                    @error('ton')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="machine">Máy</label>
                                    <input type="text" class="form-control @error('machine') is-invalid @enderror"
                                        id="machine" name="machine" value="{{ old('machine', $plan->machine) }}"
                                        required>
                                    @error('machine')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="machine_run_days">Số Ngày Chạy Máy</label>
                                    <input type="number"
                                        class="form-control @error('machine_run_days') is-invalid @enderror"
                                        id="machine_run_days" name="machine_run_days"
                                        value="{{ old('machine_run_days', $plan->machine_run_days) }}" required>
                                    @error('machine_run_days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="remaining_production_days">Số Ngày Còn SX (Ngày)</label>
                                    <input type="number"
                                        class="form-control @error('remaining_production_days') is-invalid @enderror"
                                        id="remaining_production_days" name="remaining_production_days"
                                        value="{{ old('remaining_production_days', $plan->remaining_production_days) }}"
                                        required>
                                    @error('remaining_production_days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="remaining_production_quantity">Số Lượng Còn SX (PCS)</label>
                                    <input type="number"
                                        class="form-control @error('remaining_production_quantity') is-invalid @enderror"
                                        id="remaining_production_quantity" name="remaining_production_quantity"
                                        value="{{ old('remaining_production_quantity', $plan->remaining_production_quantity) }}"
                                        required>
                                    @error('remaining_production_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="produced_quantity">Số Lượng Đã SX (PCS)</label>
                                    <input type="number"
                                        class="form-control @error('produced_quantity') is-invalid @enderror"
                                        id="produced_quantity" name="produced_quantity"
                                        value="{{ old('produced_quantity', $plan->produced_quantity) }}" required>
                                    @error('produced_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-buttons">
                                    <button type="submit" class="btn btn-primary">Lưu</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
