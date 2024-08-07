@extends('master')

@section('content')
    <style>
        .container {
            margin-top: 20px;
            width: 100%;
            padding: 0 15px;
            /* Thêm padding để tránh bị dính vào mép */
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
            /* Đảm bảo card chiếm toàn bộ chiều rộng của cột */
            overflow: hidden;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #ddd;
            padding: 16px;
        }

        .card-title {
            margin: 0;
            font-size: 1.25rem;
            font-weight: bold;
            color: #333;
        }

        .card-body {
            padding: 16px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }

        .form-control {
            width: 100%;
            box-sizing: border-box;
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1rem;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            color: #fff;
            padding: 8px 16px;
            font-size: 1rem;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            display: inline-block;
            text-decoration: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            color: #fff;
            padding: 8px 16px;
            font-size: 1rem;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            display: inline-block;
            text-decoration: none;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        .form-buttons .btn {
            margin: 0 5px;
        }
    </style>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Nhập Kế Hoạch Sản Xuất</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.product-plan.handleConfig') }}">
                            @csrf

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Kế hoạch sản xuất</th>
                                        <th>Tỉ lệ sản phẩm</th>
                                        <th>Số lượng bao bì mỗi thùng</th>
                                        <th>Số sản phẩm mỗi thùng</th>
                                        <th>Chu kỳ</th>
                                        <th>Số lượng cavity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($productionPlans as $plan)
                                        <tr>
                                            <td>{{ $plan->product->name }}</td>
                                            <td>
                                                <input type="hidden" name="plans[{{ $plan->id }}][id]"
                                                    value="{{ $plan->id }}">
                                                <input type="number" name="plans[{{ $plan->id }}][production_plan]"
                                                    class="form-control"
                                                    value="{{ old('plans.' . $plan->id . '.production_plan', $plan->production_plan) }}">
                                            </td>
                                            <td>
                                                <input type="number" name="plans[{{ $plan->id }}][product_density]"
                                                    class="form-control"
                                                    value="{{ old('plans.' . $plan->id . '.product_density', $plan->product_density) }}">
                                            </td>
                                            <td>
                                                <input type="number"
                                                    name="plans[{{ $plan->id }}][packaging_count_per_box]"
                                                    class="form-control"
                                                    value="{{ old('plans.' . $plan->id . '.packaging_count_per_box', $plan->packaging_count_per_box) }}">
                                            </td>
                                            <td>
                                                <input type="number" name="plans[{{ $plan->id }}][products_per_box]"
                                                    class="form-control"
                                                    value="{{ old('plans.' . $plan->id . '.products_per_box', $plan->products_per_box) }}">
                                            </td>
                                            <td>
                                                <input type="number" name="plans[{{ $plan->id }}][cycle]"
                                                    class="form-control"
                                                    value="{{ old('plans.' . $plan->id . '.cycle', $plan->cycle) }}">
                                            </td>
                                            <td>
                                                <input type="number" name="plans[{{ $plan->id }}][cavity_count]"
                                                    class="form-control"
                                                    value="{{ old('plans.' . $plan->id . '.cavity_count', $plan->cavity_count) }}">
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            <div class="form-buttons">
                                <button type="submit" class="btn btn-primary">Cập nhật tất cả kế hoạch sản xuất</button>
                                <a href="{{ route('admin.product-plan.index') }}" class="btn btn-secondary">Hủy</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
