@extends('master')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                <div class="border-radius-lg ps-2 pt-4 pb-3">
                    <h4 class="card-title mb-0">Cập Nhật Loại Sản Phẩm Cần Kiểm Hàng Hoặc Sản Xuất</h4>
                </div>
            </div>
            <div class="p-4 pb-0 d-flex flex-column flex-sm-row">
                <a class="btn btn-dark mb-2 mb-sm-0 me-sm-2 text-uppercase" href="{{ route('admin.home') }}">
                    <i class="fas fa-home"></i> Trang chủ
                </a>
                <a class="btn btn-warning mb-2 mb-sm-0 me-sm-2 text-uppercase"
                    href="{{ route('admin.employee-history-check') }}">
                    <i class="fas fa-history"></i> Lịch Sử Sản Phẩm Đã Chọn Để Hoạt Động
                </a>
                </a>
                <a href="{{ route('admin.product.update-quantity') }}" class="btn btn-success mb-2 mb-sm-0 me-sm-2">
                    <i class="fas fa-edit"></i> Cập Nhật Sản Lượng
                </a>
            </div>

            <form action="{{ route('admin.employee.handle.check-employee-todo') }}" method="POST">
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
                                        @foreach ($products as $product)
                                        <option {{ old('product_id') == $product->id ? 'selected' : '' }}
                                            value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <input type="hidden" name="shift" value="{{ $calendarDetail ?? '' }}">
                            <input type="hidden" name="status" value="{{ $status }}">
                        </div>
                    </div>
                </div>
                <div class="p-4 pb-0 d-flex">
                    <button type="submit" class="btn btn-success">Cập Nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for Editing -->
@if (isset($editTodo))
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Chỉnh sửa hoạt động</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('employee-todo.update', $editTodo->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="product_id">Mã sản phẩm</label>
                        <input type="text" class="form-control" id="product_id" name="product_id"
                            value="{{ $editTodo->product_id }}" required>
                    </div>
                    <div class="form-group">
                        <label for="shift">Ca làm việc</label>
                        <input type="text" class="form-control" id="shift" name="shift"
                            value="{{ $editTodo->shift }}" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Trạng thái</label>
                        <select class="form-control" id="status" name="status">
                            <option value="1" {{ $editTodo->status == 1 ? 'selected' : '' }}>Trạng thái 1
                            </option>
                            <option value="2" {{ $editTodo->status == 2 ? 'selected' : '' }}>Trạng thái 2
                            </option>
                            <option value="0" {{ $editTodo->status == 0 ? 'selected' : '' }}>Trạng thái 0
                            </option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@if (isset($editTodo))
<script>
    $(document).ready(function() {
        $('#editModal').modal('show');
    });
</script>
@endif
@endsection