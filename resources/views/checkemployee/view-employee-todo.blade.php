@extends('master')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Danh Sách Nhân Viên Làm Việc Trong Ngày</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="pb-2 d-flex flex-column flex-sm-row">
                        <a href="{{ route('admin.daily.productivity.history') }}"
                            class="btn btn-primary mb-2 mb-sm-0 me-sm-2">
                            <i class="fas fa-history"></i> Kiểm Tra Nhân Viên Nhập Sản Lượng
                        </a>

                        <form method="GET" action="{{ route('admin.checkemployee.view-employee-todo') }}"
                            class="form-inline mb-sm-0 me-sm-2">
                            <div class="form-group mb-0">
                                <label for="filter_date" class="visually-hidden">Chọn ngày</label>
                                <input type="date" class="form-control form-control-md me-2" name="filter_date"
                                    id="filter_date" value="{{ request('filter_date', now()->format('Y-m-d')) }}"
                                    onchange="this.form.submit()">
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">STT</th>
                                    <th class="text-center">Tên Nhân Viên</th>
                                    <th class="text-center">Mã Nhân Viên</th>
                                    <th class="text-center">Tên Sản Phẩm</th>
                                    <th class="text-center">Ca Làm Việc</th>
                                    <th class="text-center">Ngày Nhập</th>
                                    <th class="text-center">Thời gian Bắt Đầu</th>
                                    <th class="text-center">Thời gian Kết Thúc</th>
                                    <th class="text-center">Nhân Viên</th>
                                    {{-- <th class="text-center">Trạng Thái</th> --}}
                                    <th class="text-center">Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($checkEmployeeHistoryforAdmin->isEmpty())
                                    <tr>
                                        <td colspan="10" class="text-center">Hiện tại chưa có lịch sử nào</td>
                                    </tr>
                                @else
                                    @foreach ($checkEmployeeHistoryforAdmin as $checkEmployee)
                                        <tr>
                                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                            <td class="text-center align-middle">{{ $checkEmployee->employee->name }}</td>
                                            <td class="text-center align-middle">{{ $checkEmployee->employee->code }}</td>
                                            <td class="text-center align-middle">{{ $checkEmployee->product->name }}</td>
                                            <td class="text-center align-middle">{{ $checkEmployee->shift }}</td>
                                            <td class="text-center align-middle">{{ $checkEmployee->date->format('d-m-Y') }}
                                            </td>
                                            <td class="text-center align-middle">
                                                <span
                                                    class="badge bg-success text-white">{{ $checkEmployee->created_at->format('H:i:s') }}</span>
                                            </td>
                                            <td class="text-center align-middle">
                                                @if ($checkEmployee->dailyQuantities->isNotEmpty())
                                                    @foreach ($checkEmployee->dailyQuantities as $dailyQuantity)
                                                        <span class="badge bg-success text-white">
                                                            {{ $dailyQuantity->created_at_formatted }} </span>
                                                    @endforeach
                                                @else
                                                    <span class="badge bg-danger text-white">Chưa Nhập Sản Lượng</span>
                                                @endif
                                            </td>
                                            <td class="text-center align-middle">
                                                @if ($checkEmployee->status == 1)
                                                    100%
                                                @elseif ($checkEmployee->status == 2)
                                                    200%
                                                @else
                                                    Không xác định
                                                @endif
                                            </td>
                                            <td class="text-center align-middle mb-0">
                                                <button class="btn btn-primary btn-sm ml-2 mb-0" data-toggle="modal"
                                                    data-target="#editModal{{ $checkEmployee->id }}">
                                                    <i class="fas fa-edit"></i> Sửa
                                                </button>
                                                <form
                                                    action="{{ route('admin.checkemployee.delete', ['id' => $checkEmployee->id]) }}"
                                                    method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm mb-0"
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
    @foreach ($checkEmployeeHistoryforAdmin as $checkEmployee)
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
                        <form
                            action="{{ route('admin.checkemployee.update-employee-todo', ['id' => $checkEmployee->id]) }}"
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
                                    class="form-control form-control-md @error('shift') is-invalid @enderror" id="shift"
                                    name="shift" value="{{ $checkEmployee->shift }}" readonly>
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
                            <button type="submit" class="btn btn-primary btn-sm">Cập Nhật</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
