@extends('master')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Kế Hoạch Sản Xuất Tháng {{ $month }}</h4>
                    </div>
                    <div class="header-action">
                        <a href="{{ route('admin.product-plan.add') }}" class="btn btn-primary">Thêm Kế Hoạch Sản Phẩm</a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.product-plan.index') }}" class="mb-3">
                        <div class="form-row">
                            <div class="form-group col-2">
                                <label for="date">Tháng và Năm</label>
                                <input type="month" id="date" name="date" class="form-control"
                                    value="{{ $selectedDate }}" onchange="this.form.submit();">
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên Sản Phẩm</th>
                                    <th>Nguyên Vật Liệu</th>
                                    <th>Kế Hoạch Sản Xuất</th>
                                    <th>Dự Định Vật Liệu (KG)</th>
                                    <th>Loại Bao Bì</th>
                                    <th>Số Bao Bì/Thùng</th>
                                    <th>Tổng Bao Bì</th>
                                    <th>Loại Thùng</th>
                                    <th>Sản Phẩm/Thùng</th>
                                    <th>Số Lượng Thùng</th>
                                    <th>Tỷ Trọng Sản Phẩm (G)</th>
                                    <th>Kế Hoạch Sản Xuất/Ngày</th>
                                    <th>Số Cavity</th>
                                    <th>Chu Kỳ</th>
                                    <th>Tấn</th>
                                    <th>Máy</th>
                                    <th>Số Ngày Chạy Máy</th>
                                    <th>Số Ngày Còn SX (Ngày)</th>
                                    <th>Số Lượng Còn SX (PCS)</th>
                                    <th>Số Lượng Đã SX (PCS)</th>
                                    <th>Hành Động</th> <!-- Cột để chứa nút sửa -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productPlans as $plan)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $plan->product->name }}</td>
                                        <td>{{ $plan->material_name }}</td>
                                        <td>{{ $plan->production_plan }}</td>
                                        <td>{{ $plan->planned_material }}</td>
                                        <td>{{ $plan->packaging_type }}</td>
                                        <td>{{ $plan->packaging_count_per_box }}</td>
                                        <td>{{ $plan->total_packaging }}</td>
                                        <td>{{ $plan->box_type }}</td>
                                        <td>{{ $plan->products_per_box }}</td>
                                        <td>{{ $plan->box_quantity }}</td>
                                        <td>{{ $plan->product_density }}</td>
                                        <td>{{ $plan->daily_production_plan }}</td>
                                        <td>{{ $plan->cavity_count }}</td>
                                        <td>{{ $plan->cycle }}</td>
                                        <td>{{ $plan->ton }}</td>
                                        <td>{{ $plan->machine }}</td>
                                        <td>{{ $plan->machine_run_days }}</td>
                                        <td>{{ $plan->remaining_production_days }}</td>
                                        <td>{{ $plan->remaining_production_quantity }}</td>
                                        <td>{{ $plan->produced_quantity }}</td>
                                        <td>
                                            <!-- Nút Sửa -->
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $plan->id }}">
                                                Sửa
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
                            @method('PUT')
                            <div class="mb-3">
                                <label for="product_id" class="form-label">Tên Sản Phẩm</label>
                                <select class="form-select form-select-md @error('product_id') is-invalid @enderror"
                                    aria-label="Chọn sản phẩm" name="product_id" required>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ $plan->product_id == $product->id ? 'selected' : '' }}>
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
                            <div class="mb-3">
                                <label for="material_name" class="form-label">Nguyên Vật Liệu</label>
                                <input type="text" class="form-control @error('material_name') is-invalid @enderror"
                                    id="material_name" name="material_name"
                                    value="{{ old('material_name', $plan->material_name) }}" required>
                                @error('material_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="production_plan" class="form-label">Kế Hoạch Sản Xuất</label>
                                <input type="text" class="form-control @error('production_plan') is-invalid @enderror"
                                    id="production_plan" name="production_plan"
                                    value="{{ old('production_plan', $plan->production_plan) }}" required>
                                @error('production_plan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <!-- Thêm các trường khác tương tự như trên -->
                            <div class="mb-3">
                                <label for="planned_material" class="form-label">Dự Định Vật Liệu (KG)</label>
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
                            <!-- Thêm các trường khác tương tự như trên -->
                            <button type="submit" class="btn btn-primary btn-sm">Cập Nhật</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

<style>
    .table th,
    .table td {
        white-space: nowrap;
        /* Giữ cho các ô bảng không bị cắt ngắn */
    }

    .table td {
        min-width: 100px;
        /* Đặt độ rộng tối thiểu cho các ô bảng */
    }
</style>
