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
        margin-right: 0.5rem;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                <div class="border-radius-lg ps-2 pt-4 pb-3">
                    <h4 class="card-title mb-0">Cập Nhật Sản Lượng</h4>
                </div>
            </div>
            <div class="p-4 pb-0 d-flex flex-column flex-sm-row">
                <a class="btn btn-dark mb-2 mb-sm-0 me-sm-2" href="{{ route('admin.home') }}">
                    <i class="fas fa-home me-1"></i> Trang chủ
                </a>


                <a class="btn btn-danger mb-2 mb-sm-0 me-sm-2" href="{{ route('admin.product.update-quantity') }}"
                    title="Quay lại trang trước">
                    <i class="fas fa-arrow-left"></i> Quay lại trang trước
                </a>

                <a class="btn btn-warning mb-2 mb-sm-0" href="{{ route('admin.product.history-update-error') }}"
                    title="Xem lịch sử cập nhật lỗi">
                    <i class="fas fa-exclamation-circle"></i> Lịch sử cập nhật lỗi
                </a>
            </div>

            <form action="{{ route('admin.product.handle-update-error') }}" method="POST">
                @csrf
                <div class="px-4 pb-2">
                    <h5 class="text-center">Thông tin cập nhật lỗi</h5>
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
                                        @foreach ($addQuantityError as $checkEmployee)
                                        <option
                                            {{ request()->product_id == $checkEmployee->product_id ? 'selected' : '' }}
                                            value="{{ $checkEmployee->product_id }}">
                                            {{ $checkEmployee->product->name }}
                                        </option>
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
                                    <input type="text" class="form-control @error('quantity') is-invalid @enderror"
                                        placeholder="Số lượng" oninput="formatAndStoreValue(this)" id="formattedInput"
                                        required>
                                    <input type="hidden" name="quantity" id="numericInput"
                                        value="{{ old('quantity') }}">
                                </div>
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
@endsection