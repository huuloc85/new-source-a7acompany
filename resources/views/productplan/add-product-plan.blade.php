@extends('layouts.'.$layout)

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">
                            Nhập Thông Tin Chi Tiết Kế Hoạch Sản Phẩm
                        </h4>
                    </div>
                </div>
                <div class="card-body">
                    <form
                        action="{{ route('admin.product-plan.store') }}"
                        method="POST"
                    >
                        @csrf
                        <div class="row">
                            <div class="form-group col-12 col-sm-6 col-md-4">
                                <label for="product_select">
                                    Chọn Sản Phẩm
                                </label>
                                <select
                                    class="form-control"
                                    id="product_select"
                                    name="product_id"
                                    required
                                >
                                    <option value="">Tất Cả Sản Phẩm</option>
                                    @foreach ($products as $product)
                                        <option
                                            value="{{ $product->id }}"
                                            data-bin-code="{{ $product->binCode }}"
                                            data-quan-entity-bin="{{ $product->quanEntityBin }}"
                                            data-material="{{ $product->material }}"
                                            data-color="{{ $product->color }}"
                                        >
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{--
                                <div class="form-group col-12 col-sm-6 col-md-4">
                                <label for="material">Nguyên Liệu</label>
                                <input type="text" id="material" name="material" class="form-control" required>
                                </div>
                            --}}
                            {{--
                                <div class="form-group col-12 col-sm-6 col-md-4">
                                <label for="color">Màu Sắc</label>
                                <input type="text" id="color" name="color" class="form-control" required>
                                </div>
                            --}}

                            <div class="form-group col-12 col-sm-6 col-md-4">
                                <label for="production_plan">
                                    Kế Hoạch Sản Xuất (PCS)
                                </label>
                                <input
                                    type="number"
                                    id="production_plan"
                                    name="production_plan"
                                    class="form-control"
                                    required
                                />
                            </div>

                            <div class="form-group col-12 col-sm-6 col-md-4">
                                <label for="packaging_type">Loại Bao Bì</label>
                                <select
                                    id="packaging_type"
                                    name="packaging_type"
                                    class="form-control"
                                    required
                                >
                                    <option value="">Tất Cả Bao Bì</option>
                                    @foreach ($packagingTypes as $type)
                                        <option value="{{ $type }}">
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-sm-6 col-md-4">
                                <label for="packaging_count_per_box">
                                    Số Bao Bì/Thùng
                                </label>
                                <input
                                    type="number"
                                    min="0"
                                    id="packaging_count_per_box"
                                    name="packaging_count_per_box"
                                    class="form-control"
                                    required
                                />
                            </div>
                            <div class="form-group col-12 col-sm-6 col-md-4">
                                <label for="box_type">Loại Thùng</label>
                                <input
                                    type="text"
                                    id="box_type"
                                    name="box_type"
                                    class="form-control"
                                    required
                                />
                            </div>

                            <div class="form-group col-12 col-sm-6 col-md-4">
                                <label for="products_per_box">
                                    Sản Phẩm/Thùng
                                </label>
                                <input
                                    type="number"
                                    id="products_per_box"
                                    name="products_per_box"
                                    class="form-control"
                                    required
                                />
                            </div>
                            <div class="form-group col-12 col-sm-6 col-md-4">
                                <label for="product_density">
                                    Tỷ Trọng Sản Phẩm (G)
                                </label>
                                <input
                                    type="number"
                                    id="product_density"
                                    name="product_density"
                                    class="form-control"
                                    required
                                />
                            </div>
                            <div class="form-group col-12 col-sm-6 col-md-4">
                                <label for="cavity_count">Số Cavity</label>
                                <input
                                    type="number"
                                    id="cavity_count"
                                    name="cavity_count"
                                    class="form-control"
                                    required
                                />
                            </div>

                            <div class="form-group col-12 col-sm-6 col-md-4">
                                <label for="cycle">Chu Kỳ</label>
                                <input
                                    type="number"
                                    id="cycle"
                                    name="cycle"
                                    class="form-control"
                                    required
                                />
                            </div>
                            <div class="form-group col-12 col-sm-6 col-md-4">
                                <label for="ton">Tấn</label>
                                <input
                                    type="number"
                                    id="ton"
                                    name="ton"
                                    class="form-control"
                                    required
                                />
                            </div>
                            <div class="form-group col-12 col-sm-6 col-md-4">
                                <label for="machine">Máy</label>
                                <input
                                    type="text"
                                    id="machine"
                                    name="machine"
                                    class="form-control"
                                    required
                                />
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            Lưu Kế Hoạch
                        </button>
                        <a
                            href="{{ route('admin.product-plan.index') }}"
                            class="btn btn-secondary"
                        >
                            Hủy
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document
            .getElementById('product_select')
            .addEventListener('change', function () {
                var selectedOption = this.options[this.selectedIndex];
                var binCode = selectedOption.getAttribute('data-bin-code');
                var quanEntityBin = selectedOption.getAttribute(
                    'data-quan-entity-bin',
                );
                var material = selectedOption.getAttribute('data-material');
                var color = selectedOption.getAttribute('data-color');

                document.getElementById('box_type').value = binCode || '';
                document.getElementById('products_per_box').value =
                    quanEntityBin || '';
                document.getElementById('material').value = material || '';
                document.getElementById('color').value = color || '';
            });
    </script>
@endsection
