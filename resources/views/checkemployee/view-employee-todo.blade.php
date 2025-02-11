@extends('layouts.'.$layout)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">
                            Danh Sách Nhân Viên Làm Việc Trong Ngày
                        </h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column flex-sm-row gap-2 mb-3">
                        <a
                            href="{{ route('admin.daily.productivity.history') }}"
                            class="btn btn-primary"
                        >
                            <i class="fas fa-history"></i>
                            Kiểm Tra Nhân Viên Nhập Sản Lượng
                        </a>

                        <form
                            method="GET"
                            action="{{ route('admin.checkemployee.view-employee-todo') }}"
                            class="form-inline"
                        >
                            <input
                                type="date"
                                class="form-control form-control-md me-2"
                                name="filter_date"
                                id="filter_date"
                                value="{{ request('filter_date', now()->format('Y-m-d')) }}"
                                onchange="this.form.submit()"
                            />
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-uppercase">STT</th>
                                    <th class="text-uppercase">
                                        Tên Nhân Viên
                                    </th>
                                    <th class="text-uppercase">Mã Nhân Viên</th>
                                    <th class="text-uppercase">Tên Sản Phẩm</th>
                                    <th class="text-uppercase">Ca Làm Việc</th>
                                    <th class="text-uppercase">Ngày Nhập</th>
                                    <th class="text-uppercase">
                                        Thời gian Bắt Đầu
                                    </th>
                                    <th class="text-uppercase">
                                        Thời gian Kết Thúc
                                    </th>
                                    <th class="text-uppercase">Nhân Viên</th>
                                    {{-- <th class="text-uppercase">Trạng Thái</th> --}}
                                    <th class="text-uppercase">Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($checkEmployeeHistoryForAdmin->isEmpty())
                                    <tr>
                                        <td colspan="10" class="text-center">
                                            Hiện tại chưa có lịch sử nào
                                        </td>
                                    </tr>
                                @endif

                                @foreach ($checkEmployeeHistoryForAdmin as $checkEmployee)
                                    <tr class="text-center align-middle">
                                        <td class="fw-bold">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ $checkEmployee->employee->name }}
                                        </td>
                                        <td>
                                            {{ $checkEmployee->employee->code }}
                                        </td>
                                        <td>
                                            {{ $checkEmployee->product->name }}
                                        </td>
                                        <td>
                                            {{ $checkEmployee->shift }}
                                        </td>
                                        <td>
                                            {{ $checkEmployee->date->format('d-m-Y') }}
                                        </td>
                                        <td>
                                            <span class="badge bg-success">
                                                {{ $checkEmployee->created_at->format('H:i:s') }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($checkEmployee->dailyQuantities->isNotEmpty())
                                                @foreach ($checkEmployee->dailyQuantities as $dailyQuantity)
                                                    <span
                                                        class="badge bg-success"
                                                    >
                                                        {{ $dailyQuantity->created_at_formatted }}
                                                    </span>
                                                @endforeach
                                            @else
                                                <span
                                                    class="badge bg-danger text-white"
                                                >
                                                    Chưa Nhập Sản Lượng
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($checkEmployee->status == 1)
                                                100%
                                            @elseif ($checkEmployee->status == 2)
                                                200%
                                            @else
                                                    Không xác định
                                            @endif
                                        </td>
                                        <td class="mb-0">
                                            <button
                                                class="btn btn-primary"
                                                data-toggle="modal"
                                                data-target="#editModal-{{ $checkEmployee->id }}"
                                            >
                                                <i class="fas fa-edit"></i>
                                                Sửa
                                            </button>
                                            <button
                                                class="btn btn-danger"
                                                data-toggle="modal"
                                                data-target="#deleteModal-{{ $checkEmployee->id }}"
                                            >
                                                <i class="fas fa-trash-alt"></i>
                                                Xóa
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- Modal edit --}}
                                    <div
                                        class="modal fade"
                                        id="editModal-{{ $checkEmployee->id }}"
                                        tabindex="-1"
                                        aria-labelledby="editModalLabel-{{ $checkEmployee->id }}"
                                        aria-hidden="true"
                                    >
                                        <div
                                            class="modal-dialog modal-dialog-centered"
                                        >
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5
                                                        class="modal-title"
                                                        id="editModalLabel-{{ $checkEmployee->id }}"
                                                    >
                                                        Chỉnh Sửa Bản Ghi
                                                    </h5>
                                                    <button
                                                        type="button"
                                                        class="btn-close"
                                                        data-dismiss="modal"
                                                        aria-label="Close"
                                                    ></button>
                                                </div>
                                                <form
                                                    action="{{ route('admin.checkemployee.update-employee-todo', ['id' => $checkEmployee->id]) }}"
                                                    method="POST"
                                                >
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label
                                                                for="product_id"
                                                                class="form-label"
                                                            >
                                                                Tên Sản Phẩm
                                                            </label>
                                                            <select
                                                                class="form-select form-select-md @error('product_id') is-invalid @enderror"
                                                                aria-label="Chọn sản phẩm"
                                                                name="product_id"
                                                                required
                                                            >
                                                                @foreach ($products as $product)
                                                                    <option
                                                                        value="{{ $product->id }}"
                                                                        {{ $checkEmployee->product_id == $product->id ? 'selected' : '' }}
                                                                    >
                                                                        {{ $product->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('product_id')
                                                                <div
                                                                    class="invalid-feedback"
                                                                >
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                        <div class="mb-3">
                                                            <label
                                                                for="shift"
                                                                class="form-label"
                                                            >
                                                                Ca Làm Việc
                                                            </label>
                                                            <input
                                                                type="text"
                                                                class="form-control form-control-md @error('shift') is-invalid @enderror"
                                                                id="shift"
                                                                name="shift"
                                                                value="{{ $checkEmployee->shift }}"
                                                                readonly
                                                            />
                                                            @error('shift')
                                                                <div
                                                                    class="invalid-feedback"
                                                                >
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                        <div class="mb-3">
                                                            <label
                                                                for="status"
                                                                class="form-label"
                                                            >
                                                                Trạng Thái
                                                            </label>
                                                            <select
                                                                class="form-control form-control-md @error('status') is-invalid @enderror"
                                                                id="status"
                                                                name="status"
                                                                aria-label="Chọn trạng thái"
                                                                disabled
                                                            >
                                                                <option
                                                                    value="1"
                                                                    {{ $checkEmployee->status == 1 ? 'selected' : '' }}
                                                                >
                                                                    100%
                                                                </option>
                                                                <option
                                                                    value="2"
                                                                    {{ $checkEmployee->status == 2 ? 'selected' : '' }}
                                                                >
                                                                    200%
                                                                </option>
                                                            </select>
                                                            <input
                                                                type="hidden"
                                                                name="status"
                                                                value="{{ $checkEmployee->status }}"
                                                            />
                                                            @error('status')
                                                                <div
                                                                    class="invalid-feedback"
                                                                >
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button
                                                            type="submit"
                                                            class="btn btn-primary"
                                                        >
                                                            Cập Nhật
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Modal delete --}}
                                    <div
                                        class="modal fade"
                                        id="deleteModal-{{ $checkEmployee->id }}"
                                        tabindex="-1"
                                        aria-labelledby="deleteModalLabel-{{ $checkEmployee->id }}"
                                        aria-hidden="true"
                                    >
                                        <div
                                            class="modal-dialog modal-dialog-centered"
                                        >
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5
                                                        class="modal-title"
                                                        id="deleteModalLabel-{{ $checkEmployee->id }}"
                                                    >
                                                        Xóa Bản Ghi
                                                    </h5>
                                                    <button
                                                        type="button"
                                                        class="btn-close"
                                                        data-dismiss="modal"
                                                        aria-label="Close"
                                                    ></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>
                                                        Bạn có chắc chắn muốn
                                                        xóa bản ghi
                                                        <strong>
                                                            {{ '#'.$loop->iteration }}
                                                        </strong>
                                                        không?
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <form
                                                        action="{{ route('admin.checkemployee.delete', ['id' => $checkEmployee->id]) }}"
                                                        method="POST"
                                                    >
                                                        @csrf
                                                        @method('DELETE')
                                                        <button
                                                            type="submit"
                                                            class="btn btn-danger"
                                                        >
                                                            Xóa
                                                        </button>
                                                    </form>
                                                    <button
                                                        type="button"
                                                        class="btn btn-secondary"
                                                        data-dismiss="modal"
                                                    >
                                                        Hủy
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
