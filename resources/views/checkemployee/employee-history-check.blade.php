<style>
    /* Styles for small screens */
    @media (max-width: 767px) {
        .table-responsive table {
            overflow-x: auto;
            white-space: nowrap;
        }

        .table-responsive thead {
            display: none;
        }

        .table-responsive tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
        }

        .table-responsive tbody tr td {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem;
            border-top: none;
            text-align: center;
        }

        .table-responsive tbody tr td::before {
            content: attr(data-label);
            flex: 1;
            font-weight: bold;
            text-align: left;
        }

        .btn {
            height: 2.6rem;
        }

        .d-flex.flex-sm-row {
            flex-direction: column !important;
        }

        .d-flex.flex-sm-row .btn,
        .d-flex.flex-sm-row .form-inline {
            width: 100%;
            margin-bottom: 10px;
        }

        .d-flex.flex-sm-row .form-inline input {
            width: 100%;
        }

        .btn-sm-on-small {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            margin-left: 5px;
        }
    }
</style>
@extends('master')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                <div class="border-radius-lg ps-2 pt-4 pb-3">
                    <h4 class="card-title mb-0">Lịch Sử Các Bản Ghi Của Nhân Viên</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column flex-sm-row align-items-center">
                    <a href="{{ route('admin.employee.check-employee-todo') }}"
                        class="btn btn-danger mb-2 mb-sm-0 me-sm-2 btn-sm-on-small">
                        <i class="fas fa-chevron-left"></i> Quay lại
                    </a>
                    <a href="{{ route('admin.product.update-quantity') }}"
                        class="btn btn-success mb-2 mb-sm-0 me-sm-2 btn-sm-on-small">
                        <i class="fas fa-edit"></i> Cập Nhật Sản Lượng
                    </a>
                    <form method="GET" action="{{ route('admin.employee-history-check') }}"
                        class="form-inline mb-sm-0 me-sm-2 my-3">
                        <div class="form-group">
                            <label for="filter_date" class="visually-hidden">Chọn ngày</label>
                            <input type="date" class="form-control btn-sm-on-small" name="filter_date"
                                id="filter_date" value="{{ request('filter_date', now()->format('Y-m-d')) }}"
                                onchange="this.form.submit()">
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">STT</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Tên Nhân Viên</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Mã Nhân Viên</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Tên Sản Phẩm</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Số Lượng</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Ca Làm Việc</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Ngày Nhập</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Thời gian</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Làm Việc</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Trạng Thái</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($checkEmployeeHistory->isEmpty())
                            <tr>
                                <td class="text-center ps-4" colspan="11">Hiện tại chưa có lịch sử nào</td>
                            </tr>
                            @else
                            @foreach ($checkEmployeeHistory as $checkEmployee)
                            <tr>
                                <td class="text-center align-middle" data-label="STT">{{ $loop->iteration }}
                                </td>
                                <td class="text-center align-middle" data-label="Tên Nhân Viên">
                                    {{ $checkEmployee->employee->name }}
                                </td>
                                <td class="text-center align-middle" data-label="Mã Nhân Viên">
                                    {{ $checkEmployee->employee->code }}
                                </td>
                                <td class="text-center align-middle" data-label="Tên Sản Phẩm">
                                    {{ $checkEmployee->product->name }}
                                </td>
                                <td class="text-center align-middle" data-label="Số Lượng">
                                    @if ($checkEmployee->dailyQuantities->isEmpty())
                                    <span class="text-danger">Chưa có sản lượng</span>
                                    @else
                                    @foreach ($checkEmployee->dailyQuantities as $dailyQuantity)
                                    {{ number_format($dailyQuantity->quantity) }}
                                    <br>
                                    @endforeach
                                    @endif
                                </td>
                                <td class="text-center align-middle" data-label="Ca Làm Việc">
                                    {{ $checkEmployee->shift }}
                                </td>
                                <td class="text-center align-middle" data-label="Ngày Nhập">
                                    {{ \Carbon\Carbon::parse($checkEmployee->date)->format('d-m-Y') }}
                                </td>
                                <td class="text-center align-middle" data-label="Thời gian">
                                    @if ($checkEmployee->dailyQuantities->isEmpty())
                                    {{ \Carbon\Carbon::parse($checkEmployee->created_at)->format('H:i:s') }}
                                    @else
                                    @foreach ($checkEmployee->dailyQuantities as $dailyQuantity)
                                    {{ \Carbon\Carbon::parse($dailyQuantity->created_at)->format('H:i:s') }}
                                    <br>
                                    @endforeach
                                    @endif
                                </td>
                                <td class="text-center align-middle" data-label="Làm Việc">
                                    @if ($checkEmployee->status == 1)
                                    100%
                                    @elseif ($checkEmployee->status == 2)
                                    200%
                                    @else
                                    Không xác định
                                    @endif
                                </td>
                                <td class="text-center align-middle" data-label="Trạng Thái">
                                    @if ($checkEmployee->dailyQuantities->isNotEmpty())
                                    <span class="badge bg-success text-white" title="Đã nhập sản lượng">Đã
                                        nhập</span>
                                    @else
                                    <span class="badge bg-danger text-white"
                                        title="Chưa nhập sản lượng">Chưa nhập</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle mb-0" data-label="Thao Tác">
                                    <button class="btn btn-primary ml-2 mb-0 btn-sm btn-sm-on-small"
                                        data-toggle="modal" data-target="#editModal{{ $checkEmployee->id }}">
                                        <i class="fas fa-edit"></i> Sửa
                                    </button>
                                    <form
                                        action="{{ route('admin.employee.delete-employee-todo', ['id' => $checkEmployee->id]) }}"
                                        method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-danger mb-0 btn-sm btn-sm-on-small"
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa bản ghi này?')">
                                            <i class="fas fa-trash-alt"></i> Xóa
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals for Editing -->
@foreach ($checkEmployeeHistory as $checkEmployee)
<div class="modal fade" id="editModal{{ $checkEmployee->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editModalLabel{{ $checkEmployee->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $checkEmployee->id }}">Chỉnh Sửa Bản Ghi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.employee.update-employee-todo', ['id' => $checkEmployee->id]) }}"
                    method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Tên Sản Phẩm</label>
                        <select class="form-select form-select-md @error('product_id') is-invalid @enderror"
                            aria-label="Chọn sản phẩm" name="product_id" required>
                            @foreach ($products as $product)
                            <option value="{{ $product->id }}"
                                {{ $checkEmployee->product_id == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('product_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="shift" class="form-label">Ca Làm Việc</label>
                        <input type="text"
                            class="form-control form-control-md @error('shift') is-invalid @enderror"
                            id="shift" name="shift" value="{{ $checkEmployee->shift }}" readonly>
                        @error('shift')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng Thái</label>
                        <select class="form-control form-control-md @error('status') is-invalid @enderror"
                            id="status" name="status" aria-label="Chọn trạng thái" disabled>
                            <option value="1" {{ $checkEmployee->status == 1 ? 'selected' : '' }}>100%
                            </option>
                            <option value="2" {{ $checkEmployee->status == 2 ? 'selected' : '' }}>200%
                            </option>
                        </select>
                        <input type="hidden" name="status" value="{{ $checkEmployee->status }}">
                        @error('status')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm btn-sm-on-small">Cập Nhật</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection