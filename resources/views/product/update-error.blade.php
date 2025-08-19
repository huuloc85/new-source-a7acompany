@extends('layouts.'.$layout)
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Cập Nhật Sản Lượng</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column flex-sm-row gap-2 mb-3">
                        <a class="btn btn-dark" href="{{ route('admin.home') }}">
                            <i class="fas fa-home me-1"></i>
                            Trang chủ
                        </a>

                        <a
                            class="btn btn-primary"
                            href="{{ route('admin.product.update-quantity') }}"
                            title="Quay lại trang trước">
                            <i class="fas fa-arrow-left"></i>
                            Quay lại trang trước
                        </a>

                        <a
                            class="btn btn-warning"
                            href="{{ route('admin.product.history-update-error') }}"
                            title="Xem lịch sử cập nhật lỗi">
                            <i class="fas fa-exclamation-circle"></i>
                            Lịch sử cập nhật lỗi
                        </a>
                    </div>

                    <h5 class="text-center">Thông tin cập nhật lỗi</h5>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div>
                                <span class="fw-bold">Tên nhân viên:</span>
                                {{ Auth()->user()->name ?? '' }}
                            </div>
                            <div>
                                <span class="fw-bold">Mã nhân viên:</span>
                                {{ Auth()->user()->code ?? '' }}
                            </div>
                            <div>
                                <span class="fw-bold">Bộ phận:</span>
                                {{ Auth()->user()->role->role_name ?? '' }}
                            </div>
                            <div>
                                <span class="fw-bold">Danh mục:</span>
                                {{ Auth()->user()->calendarCategory->name ?? '' }}
                            </div>
                            <div>
                                <span class="fw-bold">Ca làm việc:</span>
                                {{ $calendarDetail ?? '' }}
                            </div>
                            <form action="{{ route('admin.product.handle-update-error') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label" for="">Chọn sản phẩm:</label>
                                    <select
                                        class="form-control @error('product_id') is-invalid @enderror"
                                        name="product_id"
                                        required>
                                        <option class="text-center" value="">Chọn sản phẩm</option>
                                        @foreach ($addQuantityError as $checkEmployee)
                                            <option
                                                {{ request()->product_id == $checkEmployee->product_id ? 'selected' : '' }}
                                                value="{{ $checkEmployee->product_id }}">
                                                {{ $checkEmployee->product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <div class="text text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="">Số lượng sản phẩm:</label>
                                    <input
                                        type="number"
                                        min="0"
                                        class="form-control @error('quantity') is-invalid @enderror"
                                        placeholder="Số lượng"
                                        name="quantity"
                                        required />
                                </div>
                                <button type="submit" class="btn btn-success">Cập Nhật</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
