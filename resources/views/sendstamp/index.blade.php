@extends('layouts.layout')
<style>
    .form-label {
        font-weight: bold;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        padding: 10px;
    }

    .btn {
        padding: 10px;
        font-weight: bold;
    }

    .shadow-sm {
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
    }
</style>
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Gửi Yêu Cầu In Tem</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column flex-sm-row gap-2 mb-3">
                        <a class="btn btn-warning text-uppercase" href="{{ route('admin.checkstamp-employee') }}"
                            title="Kiểm Tra Tình Trạng Tem">
                            <i class="fas fa-history"></i> Kiểm Tra Tình Trạng Tem
                        </a>
                    </div>

                    <h5 class="text-center fw-bold">Thông tin nhân viên</h5>
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
                                    <span class="fw-bold">Ca làm việc:</span>
                                    {{ $calendarDetail ?? '' }}
                                </div>
                            </div>

                            <form action="{{ route('admin.handleAdd-send-stamp') }}" method="POST"
                                class="border p-3 rounded shadow-sm bg-light">
                                @csrf
                                <h5 class="text-center fw-bold">Tạo Tem</h5>
                                <div class="mb-3">
                                    <label for="product_id" class="form-label fw-bold">Sản phẩm:</label>
                                    <small class="text-muted d-block mb-1">Chọn sản phẩm mà bạn muốn tạo tem.</small>
                                    <select name="product_id" class="form-select" required>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <input type="hidden" name="employee_id" value="{{ Auth::id() }}">

                                <div class="mb-3">
                                    <label for="date" class="form-label fw-bold">Ngày:</label>
                                    <small class="text-muted d-block mb-1">Chọn ngày cần in tem.</small>
                                    <input type="date" name="date" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="shift" class="form-label fw-bold">Ca làm việc:</label>
                                    <small class="text-muted d-block mb-1">Chọn ca làm việc khi sản xuất sản phẩm.</small>
                                    <select name="shift" class="form-select" required>
                                        <option value="1">Ca 1</option>
                                        <option value="2">Ca 2 </option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="binCount" class="form-label fw-bold">Số lượng tem:</label>
                                    <small class="text-muted d-block mb-1">Nhập số lượng tem cần in.</small>
                                    <input type="number" name="binCount" class="form-control" min="0" required>
                                </div>

                                <div class="mb-3">
                                    <label for="binStart" class="form-label fw-bold">Bắt Đầu Từ Tem Số:</label>
                                    <small class="text-muted d-block mb-1">Nhập số thứ tự bắt đầu của tem.</small>
                                    <input type="number" name="binStart" class="form-control" min="0" required>
                                </div>

                                <div class="mb-3">
                                    <label for="type" class="form-label fw-bold">Loại Tem:</label>
                                    <select name="type" class="form-select" required>
                                        <option value="Tem Thùng">Tem Thùng</option>
                                        <option value="Tem Bịch">Tem Bịch</option>
                                    </select>
                                </div>

                                <input type="hidden" name="status" value="pending">

                                <button type="submit" class="btn btn-success w-100 text-uppercase fw-bold">
                                    <i class="fas fa-paper-plane"></i> Gửi Yêu Cầu In Tem
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
