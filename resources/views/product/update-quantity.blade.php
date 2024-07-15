@extends('master')
@section('content')
    <style>
        /* Flexbox for responsive layout */
        .d-flex {
            display: flex;
            flex-direction: column;
        }

        @media (min-width: 576px) {
            .d-flex {
                flex-direction: row;
            }
        }

        /* Margins for buttons */
        .mb-2 {
            margin-bottom: 0.5rem;
        }

        .mb-sm-0 {
            margin-bottom: 0;
        }

        .me-sm-2 {
            margin-right: 0;

        }

        @media (min-width: 576px) {
            .me-sm-2 {
                margin-right: 0.5rem;
            }
        }
    </style>
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Cập Nhật Sản Lượng</h4>
                    </div>
                </div>
                <div class="p-4 pb-0 d-flex flex-column flex-sm-row">
                    {{-- <a class="btn btn-success mb-2 mb-sm-0 me-sm-2 text-uppercase" href="{{ route('admin.home') }}">Trang
                        chủ</a> --}}
                    <a href="{{ route('admin.employee.check-employee-todo') }}"
                        class="btn btn-danger mb-2 mb-sm-0 me-sm-2 text-uppercase">
                        <i class="fas fa-arrow-left"></i> Quay Lại Trang Nhập Sản Phẩm </a>

                    <a class="btn btn-warning mb-2 mb-sm-0 me-sm-2 text-uppercase"
                        href="{{ route('admin.product.history-update') }}" title="Xem lịch sử cập nhật sản lượng">
                        <i class="fas fa-history"></i> Lịch sử cập nhật sản lượng
                    </a>

                    @if (Auth::user() && Auth::user()->category_celender && Auth::user()->category_celender->id == 2)
                        <a class="btn btn-success mb-2 mb-sm-0 me-sm-2 text-uppercase"
                            href="{{ route('admin.product.update-error') }}">
                            <i class="fas fa-exclamation-triangle me-1"></i> Cập nhật hàng lỗi
                        </a>
                    @endif
                </div>

                <div class="p-4 pt-2">
                    <div class="alert alert-light border border-dark" role="alert">
                        <h4 class="alert-heading text-dark">Lưu ý:</h4>

                        <p class="font-weight-bold">
                            Nhân viên nhập sản lượng thì kiểm tra lịch sử trong phần
                            <a href="{{ route('admin.product.history-update') }}" class="text-primary text-uppercase">Lịch
                                sử cập nhật sản lượng</a>.
                        </p>

                        @if (Auth::user() && Auth::user()->category_celender && Auth::user()->category_celender->id == 2)
                            <p class="font-weight-bold">
                                Nhân viên nhập hàng lỗi thì phải chọn vào ô
                                <a href="{{ route('admin.product.update-error') }}" class="text-success text-uppercase">Cập
                                    Nhật hàng lỗi</a>
                                sau đó kiểm tra
                                <a href="{{ route('admin.product.history-update-error') }}"
                                    class="text-danger text-uppercase">lịch sử cập nhật hàng lỗi</a>.
                            </p>
                        @endif
                    </div>
                </div>
                <form action="{{ route('admin.product.handle-update-quantity') }}" method="POST">
                    @csrf
                    <div class="px-4 pb-2">
                        <h5 class="text-center">Thông tin cập nhật</h5>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div>
                                    <label class="form-label fw-bold" for="">Tên nhân viên:
                                        <label>{{ Auth()->user()->name ?? '' }}</label></label>
                                </div>
                                <div>
                                    <label class="form-label fw-bold" for="">Mã nhân viên:
                                        <label>{{ Auth()->user()->code ?? '' }}</label></label>
                                </div>
                                <div>
                                    <label class="form-label fw-bold" for="">Bộ phận:
                                        <label>{{ Auth()->user()->role->role_name ?? '' }}</label></label>
                                </div>
                                <div>
                                    <label class="form-label fw-bold" for="">Danh mục:
                                        <label>{{ Auth()->user()->category_celender->name ?? '' }}</label></label>
                                </div>
                                <div>
                                    <label class="form-label fw-bold" for="">Ca làm việc:
                                        <label>{{ $calendarDetail ?? '' }}</label></label>
                                </div>

                                <div class="justify-content-between">
                                    <div class="text-start">
                                        <label class="form-label" for="">Chọn sản phẩm:</label>
                                    </div>
                                    <div class="text-end">
                                        <select class="form-control @error('product_id') is-invalid @enderror"
                                            name="product_id" required>
                                            <option style="text-align: center" value="">Chọn sản phẩm</option>
                                            @foreach ($addQuantity as $checkEmployee)
                                                <option
                                                    {{ request()->product_id == $checkEmployee->product_id ? 'selected' : '' }}
                                                    value="{{ $checkEmployee->product_id }}">
                                                    {{ $checkEmployee->product->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('product_id')
                                            <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="justify-content-between pt-3">
                                    <div class="text-start">
                                        <label class="form-label" for="">Số lượng sản phẩm:</label>
                                    </div>
                                    <div class="text-end">
                                        <!-- Visible input for displaying formatted value -->
                                        <input type="text" class="form-control @error('quantity') is-invalid @enderror"
                                            placeholder="Số lượng" oninput="formatAndStoreValue(this)" id="formattedInput"
                                            required>

                                        <!-- Hidden input to store the actual numeric value -->
                                        <input type="hidden" name="quantity" id="numericInput"
                                            value="{{ old('quantity') }}">
                                    </div>

                                    <script>
                                        function formatAndStoreValue(input) {
                                            // Remove non-numeric characters
                                            let value = input.value.replace(/\D/g, '');

                                            // Format the number with commas for display
                                            let formattedValue = Number(value).toLocaleString();
                                            input.value = formattedValue;

                                            // Store the actual numeric value in the hidden input
                                            document.getElementById('numericInput').value = value;
                                        }
                                    </script>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 pb-0 d-flex">
                        <button type="submid" class="btn btn-success">Cập Nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
