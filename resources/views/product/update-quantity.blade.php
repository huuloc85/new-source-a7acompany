@extends('layouts.'.$layout)
@php
    $isError = Auth::user() && Auth::user()->category_celender && Auth::user()->category_celender->id == 2;
@endphp

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
                        {{--
                            <a class="btn btn-success mb-2 mb-sm-0 me-sm-2 text-uppercase" href="{{ route('admin.home') }}">Trang
                            chủ</a>
                        --}}
                        <a
                            href="{{ route('admin.employee.check-employee-todo') }}"
                            class="btn btn-primary text-uppercase"
                        >
                            <i class="fas fa-arrow-left"></i>
                            Quay Lại Trang Nhập Sản Phẩm
                        </a>

                        <a
                            class="btn btn-warning text-uppercase"
                            href="{{ route('admin.product.history-update') }}"
                            title="Xem lịch sử cập nhật sản lượng"
                        >
                            <i class="fas fa-history"></i>
                            Lịch sử cập nhật sản lượng
                        </a>

                        @if ($isError)
                            <a
                                class="btn btn-danger text-uppercase"
                                href="{{ route('admin.product.update-error') }}"
                            >
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Cập nhật hàng lỗi
                            </a>
                        @endif
                    </div>

                    <div
                        class="alert alert-light border border-dark"
                        role="alert"
                    >
                        <h4 class="alert-heading text-dark">Lưu ý:</h4>

                        <p class="font-weight-bold">
                            Nhân viên nhập sản lượng thì kiểm tra lịch sử trong
                            phần
                            <a
                                href="{{ route('admin.product.history-update') }}"
                                class="text-primary text-uppercase"
                            >
                                Lịch sử cập nhật sản lượng
                            </a>
                            .
                        </p>

                        @if ($isError)
                            <p class="font-weight-bold">
                                Nhân viên nhập hàng lỗi thì phải chọn vào ô
                                <a
                                    href="{{ route('admin.product.update-error') }}"
                                    class="text-success text-uppercase"
                                >
                                    Cập Nhật hàng lỗi
                                </a>
                                sau đó kiểm tra
                                <a
                                    href="{{ route('admin.product.history-update-error') }}"
                                    class="text-danger text-uppercase"
                                >
                                    lịch sử cập nhật hàng lỗi
                                </a>
                                .
                            </p>
                        @endif
                    </div>

                    <h5 class="text-center">Thông tin cập nhật</h5>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="p-3 border rounded mb-3">
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
                                    {{ Auth()->user()->category_celender->name ?? '' }}
                                </div>
                                <div>
                                    <span class="fw-bold">Ca làm việc:</span>
                                    {{ $calendarDetail ?? '' }}
                                </div>
                            </div>
                            <form
                                action="{{ route('admin.product.handle-update-quantity') }}"
                                method="POST"
                            >
                                @csrf
                                <div class="form-group">
                                    <label class="form-label" for="">
                                        Chọn sản phẩm:
                                    </label>
                                    <select
                                        class="form-control @error('product_id') is-invalid @enderror"
                                        name="product_id"
                                        required
                                    >
                                        <option class="text-center" value="">
                                            Chọn sản phẩm
                                        </option>
                                        @foreach ($addQuantity as $checkEmployee)
                                            <option
                                                {{ request()->product_id == $checkEmployee->product_id ? 'selected' : '' }}
                                                value="{{ $checkEmployee->product_id }}"
                                            >
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
                                    <label class="form-label" for="">
                                        Số lượng sản phẩm:
                                    </label>
                                    <input
                                        type="number"
                                        min="0"
                                        class="form-control @error('quantity') is-invalid @enderror"
                                        placeholder="Số lượng"
                                        name="quantity"
                                        required
                                    />
                                </div>
                                <button type="submit" class="btn btn-success">
                                    Cập Nhật
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
