@extends('master')

@section('content')
    <style>
        /* Điều chỉnh kích thước modal */
        .modal-dialog {
            max-width: 80%;
            width: 80%;
        }

        /* Sử dụng CSS Grid để chia modal thành 3 cột */
        .modal-body form {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        /* Định dạng các nhóm form để các nhãn có chiều dài ngắn hơn */
        .modal-body .form-group {
            display: flex;
            flex-direction: column;
        }

        .modal-body .form-group label {
            flex: 0 0 auto;
            margin-bottom: 0.5rem;
            text-align: left;
            /* Đảm bảo nhãn căn trái */
        }

        .modal-body .form-group input,
        .modal-body .form-group select {
            flex: 1;
            width: 100%;
        }
    </style>
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
                            <div class="form-group">
                                <label for="product_id" class="form-label">Tên Sản Phẩm</label>
                                <select class="form-select form-control @error('product_id') is-invalid @enderror"
                                    name="product_id" required>
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
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="packaging_type">Loại Bao Bì</label>
                                <select class="form-select @error('packaging_type') is-invalid @enderror"
                                    id="packaging_type" name="packaging_type" required>
                                    @foreach ($packagingTypes as $type)
                                        <option value="{{ $type }}"
                                            {{ old('packaging_type', $plan->packaging_type) == $type ? 'selected' : '' }}>
                                            {{ $type }}
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
                                <label for="production_plan">Kế Hoạch Sản Xuất</label>
                                <input type="text" class="form-control @error('production_plan') is-invalid @enderror"
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
                                <input type="number" step="1"
                                    class="form-control @error('packaging_count_per_box') is-invalid @enderror"
                                    id="packaging_count_per_box" name="packaging_count_per_box"
                                    value="{{ old('packaging_count_per_box', $plan->packaging_count_per_box) }}" required>
                                @error('packaging_count_per_box')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="total_packaging">Tổng Bao Bì</label>
                                <input type="number" step="1"
                                    class="form-control @error('total_packaging') is-invalid @enderror"
                                    id="total_packaging" name="total_packaging"
                                    value="{{ old('total_packaging', $plan->total_packaging) }}" required>
                                @error('total_packaging')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="box_type">Loại Thùng</label>
                                <input type="text" class="form-control @error('box_type') is-invalid @enderror"
                                    id="box_type" name="box_type" value="{{ old('box_type', $plan->box_type) }}"
                                    required readonly>
                                @error('box_type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="products_per_box">Sản Phẩm/Thùng</label>
                                <input type="number" step="1"
                                    class="form-control @error('products_per_box') is-invalid @enderror"
                                    id="products_per_box" name="products_per_box"
                                    value="{{ old('products_per_box', $plan->products_per_box) }}" required readonly>
                                @error('products_per_box')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- <div class="form-group">
                                <label for="products_per_box">Sản Phẩm/Thùng</label>
                                <input type="number" id="products_per_box" name="products_per_box" class="form-control"
                                    required>
                            </div> --}}

                            <div class="form-group">
                                <label for="box_quantity">Số Lượng <br>Thùng</label>
                                <input type="number" step="1"
                                    class="form-control @error('box_quantity') is-invalid @enderror" id="box_quantity"
                                    name="box_quantity" value="{{ old('box_quantity', $plan->box_quantity) }}" required>
                                @error('box_quantity')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="product_density">Tỷ Trọng <br> Sản Phẩm (G)</label>
                                <input type="number" step="0.01"
                                    class="form-control @error('product_density') is-invalid @enderror"
                                    id="product_density" name="product_density"
                                    value="{{ old('product_density', $plan->product_density) }}" required>
                                @error('product_density')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="daily_production_plan">Kế Hoạch Sản Xuất/Ngày</label>
                                <input type="number" step="1"
                                    class="form-control @error('daily_production_plan') is-invalid @enderror"
                                    id="daily_production_plan" name="daily_production_plan"
                                    value="{{ old('daily_production_plan', $plan->daily_production_plan) }}" required>
                                @error('daily_production_plan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="cavity_count">Số Cavity</label>
                                <input type="number" step="1"
                                    class="form-control @error('cavity_count') is-invalid @enderror" id="cavity_count"
                                    name="cavity_count" value="{{ old('cavity_count', $plan->cavity_count) }}" required>
                                @error('cavity_count')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="cycle">Chu Kỳ</label>
                                <input type="text" class="form-control @error('cycle') is-invalid @enderror"
                                    id="cycle" name="cycle" value="{{ old('cycle', $plan->cycle) }}" required>
                                @error('cycle')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="ton">Tấn</label>
                                <input type="number" step="0.01"
                                    class="form-control @error('ton') is-invalid @enderror" id="ton"
                                    name="ton" value="{{ old('ton', $plan->ton) }}" required>
                                @error('ton')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="machine">Máy</label>
                                <input type="number" step="1"
                                    class="form-control @error('machine') is-invalid @enderror" id="machine"
                                    name="machine" value="{{ old('machine', $plan->machine) }}" required>
                                @error('machine')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="machine_run_days">Số Ngày <br>Chạy Máy</label>
                                <input type="number" step="1"
                                    class="form-control @error('machine_run_days') is-invalid @enderror"
                                    id="machine_run_days" name="machine_run_days"
                                    value="{{ old('machine_run_days', $plan->machine_run_days) }}" required>
                                @error('machine_run_days')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="remaining_production_days">Số Ngày <br>Còn SX (Ngày)</label>
                                <input type="number" step="1"
                                    class="form-control @error('remaining_production_days') is-invalid @enderror"
                                    id="remaining_production_days" name="remaining_production_days"
                                    value="{{ old('remaining_production_days', $plan->remaining_production_days) }}"
                                    required>
                                @error('remaining_production_days')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="remaining_production_quantity">Số Lượng <br> Còn SX (PCS)</label>
                                <input type="number" step="1"
                                    class="form-control @error('remaining_production_quantity') is-invalid @enderror"
                                    id="remaining_production_quantity" name="remaining_production_quantity"
                                    value="{{ old('remaining_production_quantity', $plan->remaining_production_quantity) }}"
                                    required>
                                @error('remaining_production_quantity')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="produced_quantity">Số Lượng <br> Đã SX (PCS)</label>
                                <input type="number" step="1"
                                    class="form-control @error('produced_quantity') is-invalid @enderror"
                                    id="produced_quantity" name="produced_quantity"
                                    value="{{ old('produced_quantity', $plan->produced_quantity) }}" required>
                                @error('produced_quantity')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Lưu Thay Đổi</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const productSelect = document.querySelector('select[name="product_id"]');
                const boxTypeInput = document.querySelector('input[name="box_type"]');

                productSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const binCode = selectedOption.getAttribute('data-bin-code');
                    const quanEntityBin = selectedOption.getAttribute('data-quan-entity-bin');

                    // Cập nhật giá trị của các trường khác dựa trên sản phẩm đã chọn
                    if (boxTypeInput) {
                        boxTypeInput.value = binCode || ''; // Hoặc quanEntityBin nếu cần
                    }
                });

                // Gọi sự kiện change để cập nhật giá trị khi trang được tải
                productSelect.dispatchEvent(new Event('change'));
            });
        </script>
    @endforeach
@endsection
