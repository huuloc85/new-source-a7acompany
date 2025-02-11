@extends('layouts.'.$layout)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Nhập Kế Hoạch Sản Xuất</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form
                        method="POST"
                        action="{{ route('admin.product-plan.handleConfig') }}"
                    >
                        @csrf
                        <div class="table-responsive mb-3">
                            <table class="table">
                                <thead
                                    class="text-uppercase text-center align-middle"
                                >
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
                                    @if (count($productionPlans) == 0)
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                Không có kế hoạch sản xuất nào
                                            </td>
                                        </tr>
                                    @endif

                                    @foreach ($productionPlans as $plan)
                                        <tr>
                                            <td>{{ $plan->product->name }}</td>
                                            <td>
                                                <input
                                                    type="hidden"
                                                    name="plans[{{ $plan->id }}][id]"
                                                    value="{{ $plan->id }}"
                                                />
                                                <input
                                                    type="hidden"
                                                    name="plans[{{ $plan->id }}][product_id]"
                                                    value="{{ $plan->product_id }}"
                                                />

                                                <input
                                                    type="number"
                                                    name="plans[{{ $plan->id }}][production_plan]"
                                                    class="form-control"
                                                    value="{{ old('plans.'.$plan->id.'.production_plan', $plan->production_plan) }}"
                                                />
                                            </td>
                                            <td>
                                                <input
                                                    type="number"
                                                    name="plans[{{ $plan->id }}][product_density]"
                                                    class="form-control"
                                                    value="{{ old('plans.'.$plan->id.'.product_density', $plan->product_density) }}"
                                                />
                                            </td>
                                            <td>
                                                <input
                                                    type="number"
                                                    name="plans[{{ $plan->id }}][packaging_count_per_box]"
                                                    class="form-control"
                                                    value="{{ old('plans.'.$plan->id.'.packaging_count_per_box', $plan->packaging_count_per_box) }}"
                                                />
                                            </td>
                                            <td>
                                                <input
                                                    type="number"
                                                    name="plans[{{ $plan->id }}][products_per_box]"
                                                    class="form-control"
                                                    value="{{ old('plans.'.$plan->id.'.products_per_box', $plan->products_per_box) }}"
                                                />
                                            </td>
                                            <td>
                                                <input
                                                    type="number"
                                                    name="plans[{{ $plan->id }}][cycle]"
                                                    class="form-control"
                                                    value="{{ old('plans.'.$plan->id.'.cycle', $plan->cycle) }}"
                                                />
                                            </td>
                                            <td>
                                                <input
                                                    type="number"
                                                    name="plans[{{ $plan->id }}][cavity_count]"
                                                    class="form-control"
                                                    value="{{ old('plans.'.$plan->id.'.cavity_count', $plan->cavity_count) }}"
                                                />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                Cập nhật
                            </button>
                            <a
                                href="{{ route('admin.product-plan.index') }}"
                                class="btn btn-secondary"
                            >
                                Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
