@extends('layouts.'.$layout)
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
                    <div class="d-flex flex-column flex-sm-row align-items-center gap-2 mb-3">
                        <a class="btn btn-link" href="{{ route('admin.home') }}">
                            <i class="fas fa-arrow-left"></i>
                            Quay lại
                        </a>
                        <a
                            class="btn btn-warning d-flex align-items-center gap-2 text-uppercase fw-bold px-4"
                            href="{{ route('admin.checkstamp-employee') }}"
                            title="Kiểm Tra Tình Trạng Tem">
                            <i class="fas fa-history"></i>
                            Kiểm Tra Yêu Cầu In Tem
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

                            <form
                                action="{{ route('admin.handleAdd-send-stamp') }}"
                                method="POST"
                                class="border p-3 rounded shadow-sm bg-light">
                                @csrf
                                <h5 class="text-center fw-bold">Tạo Tem</h5>
                                <div id="product-entries">
                                    <div class="product-entry border p-3 rounded mb-3">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Sản phẩm:</label>
                                            <select name="product_id[]" class="form-select" required>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">
                                                        {{ $product->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Ngày:</label>
                                            <input type="date" name="date[]" class="form-control" required />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Ca làm việc:</label>
                                            <select name="shift[]" class="form-select" required>
                                                <option value="1">Ca 1</option>
                                                <option value="2">Ca 2</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Số lượng tem:</label>
                                            <input
                                                name="binCount[]"
                                                class="form-control"
                                                type="number"
                                                min="0"
                                                required />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Bắt Đầu Từ Tem Số:</label>
                                            <input name="binStart[]" class="form-control" required />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Loại Tem:</label>
                                            <select name="type[]" class="form-select" required>
                                                <option value="Tem Thùng">Tem Thùng</option>
                                                <option value="Tem Bịch">Tem Bịch</option>
                                            </select>
                                        </div>
                                        <button type="button" class="btn btn-danger remove-entry" disabled>Xóa</button>
                                    </div>
                                </div>
                                <button type="button" id="add-row" class="btn btn-primary w-100 mb-3">
                                    Thêm Sản Phẩm
                                </button>
                                <input type="hidden" name="employee_id" value="{{ Auth::id() }}" />
                                <input type="hidden" name="status" value="pending" />
                                <button type="submit" class="btn btn-success w-100 text-uppercase fw-bold">
                                    <i class="fas fa-paper-plane"></i>
                                    Gửi Yêu Cầu In Tem
                                </button>
                            </form>

                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    let addButton = document.getElementById('add-row')
                                    let productEntries = document.getElementById('product-entries')
                                    let productSelectHTML = document.querySelector(
                                        '.product-entry select[name="product_id[]"]',
                                    ).outerHTML

                                    function updateRemoveButtonState() {
                                        let removeButtons = document.querySelectorAll('.remove-entry')
                                        if (removeButtons.length === 1) {
                                            removeButtons[0].disabled = true
                                        } else {
                                            removeButtons.forEach((button) => (button.disabled = false))
                                        }
                                    }

                                    updateRemoveButtonState()

                                    addButton.addEventListener('click', function () {
                                        let entry = document.querySelector('.product-entry').cloneNode(true)

                                        // Xóa giá trị trong các input và select, nhưng giữ nguyên danh sách sản phẩm
                                        entry.querySelectorAll('input').forEach((el) => (el.value = ''))
                                        entry.querySelector('select[name="product_id[]"]').outerHTML = productSelectHTML

                                        entry.querySelector('.remove-entry').addEventListener('click', function () {
                                            entry.remove()
                                            updateRemoveButtonState()
                                        })

                                        productEntries.appendChild(entry)
                                        updateRemoveButtonState()
                                    })

                                    document.querySelectorAll('.remove-entry').forEach((button) => {
                                        button.addEventListener('click', function () {
                                            this.closest('.product-entry').remove()
                                            updateRemoveButtonState()
                                        })
                                    })
                                })
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
