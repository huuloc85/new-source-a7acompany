@extends('master')
@section('content')
<style>
    .form-control {
        border: 1px solid #d2d6da !important;
        padding-left: 10px;
    }

    .required {
        color: red;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Thêm Sản Phẩm</h4>
                </div>
            </div>
            <div class="px-0 pb-2">
                <div class="table-responsive p-4">
                    <form action="{{ route('admin.product.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-4">
                                <div class="mb-3">
                                    <label class="form-label">Mã liên kiện<span class="required">*</span></label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" placeholder="Mã liên kiện" name="code" value="{{ old('code') }}" required>
                                    @error('code')
                                    <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tên linh kiện<span class="required">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Tên linh kiện" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                    <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Sản lượng(MOQ)<span class="required">*</span></label>
                                    <input type="number" class="form-control @error('stockQuanMOQ') is-invalid @enderror" placeholder="Sản lượng(MOQ)" name="stockQuanMOQ" value="{{ old('stockQuanMOQ') }}" required>
                                    @error('stockQuanMOQ')
                                    <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- <div class="mb-3">
                                    <label class="form-label">Thùng caton/tháng(MOQ)<span class="required">*</span></label>
                                    <input type="number" class="form-control @error('quantityCaTon') is-invalid @enderror" placeholder="Thùng caton/tháng(MOQ)" name="quantityCaTon" value="{{ old('quantityCaTon') }}" required>
                                @error('quantityCaTon')
                                <div class="text text-danger">{{ $message }}</div>
                                @enderror
                            </div> --}}
                            <div class="mb-3">
                                <label class="form-label">Số lượng tồn đầu kì<span class="required">*</span></label>
                                <input type="number" class="form-control @error('stockQuan') is-invalid @enderror" placeholder="Số lượng tồn đầu kì" name="stockQuan" value="{{ old('stockQuan') }}" required>
                                @error('stockQuan')
                                <div class="text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label class="form-label">Kích thước khuôn<span class="required">*</span></label>
                                <select class="form-control @error('moldSize') is-invalid @enderror" name="moldSize" required>
                                    <option style="text-align: center" value="">----- Chọn kích thước khuôn
                                        -----</option>
                                    @foreach ($modelSizes as $modelSize)
                                    <option <?= request()->moldSize == $modelSize ? 'selected' : '' ?> value="{{ $modelSize }}">{{ $modelSize }}</option>
                                    @endforeach
                                </select>
                                @error('moldSize')
                                <div class="text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Số CAV(cái/ shot)<span class="required">*</span></label>
                                <input type="number" placeholder="Số CAV(cái/ shot)" class="form-control @error('CAV') is-invalid @enderror" name="CAV" value="{{ old('CAV') }}" required>
                                @error('CAV')
                                <div class="text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Chu kì(s/shot)<span class="required">*</span></label>
                                <input type="number" step="0.1" placeholder="Chu kì(s/shot)" class="form-control @error('cycle') is-invalid @enderror" name="cycle" value="{{ old('cycle') }}" required>
                                @error('cycle')
                                <div class="text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Số lượng tồn đầu hàng 200%<span class="required">*</span></label>
                                <input type="number" placeholder="Số lượng tồn đầu hàng 200%" class="form-control @error('stockQuan200') is-invalid @enderror" name="stockQuan200" value="{{ old('stockQuan200') }}" required>
                                @error('stockQuan200')
                                <div class="text text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-4">
                            {{-- <div class="mb-3">
                                    <label class="form-label">Dự định thời gian hoạt động thiết bị(ngày/tháng)<span class="required">*</span></label>
                                    <input type="number" step="0.1"  class="form-control @error('planTime') is-invalid @enderror" name="planTime" placeholder="Dự định thời gian hoạt động thiết bị(ngày/tháng)" value="{{ old('planTime') }}" required>
                            @error('planTime')
                            <div class="text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Thực tế thời gian hoạt động thiết bị(ngày/tháng)<span class="required">*</span></label>
                            <input type="number" step="0.1" class="form-control @error('realTime') is-invalid @enderror" placeholder="Thực tế thời gian hoạt động thiết bị(ngày/tháng)" name="realTime" value="{{ old('realTime') }}" required>
                            @error('realTime')
                            <div class="text text-danger">{{ $message }}</div>
                            @enderror
                        </div> --}}
                        <div class="mb-3">
                            <label class="form-label">Chọn mã thùng<span class="required">*</span></label>
                            <select class="form-control @error('binCode') is-invalid @enderror" name="binCode" required>
                                <option style="text-align: center" value="">----- Chọn mã thùng -----
                                </option>
                                @foreach ($models as $model)
                                <option <?= request()->binCode == $model ? 'selected' : '' ?> value="{{ $model }}">{{ $model }} </option>
                                @endforeach
                            </select>
                            @error('binCode')
                            <div class="text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Số lượng con/thùng<span class="required">*</span></label>
                            <input type="number" class="form-control @error('quanEntityBin') is-invalid @enderror" placeholder="Số lượng con/thùng" name="quanEntityBin" value="{{ old('quanEntityBin') }}" required>
                            @error('quanEntityBin')
                            <div class="text text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Chọn Công ty<span class="required">*</span></label><br>
                            <input type="checkbox" <?= old('company.FAPV') == '1' ? 'checked' : '' ?> name="company[]" value='FAPV'>
                            <label for="html">FAPV出荷 </label>&nbsp&nbsp&nbsp
                            <input type="checkbox" <?= old('company.FASV') == '1' ? 'checked' : '' ?> name="company[]" value='FASV'>
                            <label for="css">FASV出荷</label>&nbsp&nbsp&nbsp
                            <input type="checkbox" <?= old('company.FAVV') == '1' ? 'checked' : '' ?> name="company[]" value='FAVV'>
                            <label for="css">FAVV出荷</label><br>
                        </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Thêm mới</button>
            <a href="{{ route('admin.product.home') }}" type="button" class="btn btn-danger">Quay
                lại</a>
            </form>
        </div>
    </div>
</div>
</div>
</div>
@endsection