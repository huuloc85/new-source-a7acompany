@extends('layouts.layout')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Thêm Sản Phẩm</h4>
                    </div>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.product.home') }}" type="button" class="btn btn-link mb-3">
                        <i class="fas fa-arrow-left"></i>
                        Quay lại
                    </a>
                    <form action="{{ route('admin.product.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-12 col-sm-6 col-md-4">
                                <label class="form-label">
                                    Mã liên kiện
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror"
                                    placeholder="Mã liên kiện" name="code" value="{{ old('code') }}" required />
                                @error('code')
                                    <div class="text text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-sm-6 col-md-4">
                                <label class="form-label">
                                    Tên linh kiện
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Tên linh kiện" name="name" value="{{ old('name') }}" required />
                                @error('name')
                                    <div class="text text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-sm-6 col-md-4">
                                <label class="form-label">
                                    Sản lượng(MOQ)
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control @error('stockQuanMOQ') is-invalid @enderror"
                                    placeholder="Sản lượng(MOQ)" name="stockQuanMOQ" value="{{ old('stockQuanMOQ') }}"
                                    required />
                                @error('stockQuanMOQ')
                                    <div class="text text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            {{--
                                <div class="form-group col-12 col-sm-6 col-md-4">
                                <label class="form-label">Thùng caton/tháng(MOQ)<span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('quantityCaTon') is-invalid @enderror" placeholder="Thùng caton/tháng(MOQ)" name="quantityCaTon" value="{{ old('quantityCaTon') }}" required>
                                @error('quantityCaTon')
                                <div class="text text-danger">{{ $message }}</div>
                                @enderror
                                </div>
                            --}}
                            <div class="form-group col-12 col-sm-6 col-md-4">
                                <label class="form-label">
                                    Số lượng tồn đầu kì
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control @error('stockQuan') is-invalid @enderror"
                                    placeholder="Số lượng tồn đầu kì" name="stockQuan" value="{{ old('stockQuan') }}"
                                    required />
                                @error('stockQuan')
                                    <div class="text text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-sm-6 col-md-4">
                                <label class="form-label">
                                    Kích thước khuôn
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control @error('moldSize') is-invalid @enderror" name="moldSize"
                                    required>
                                    <option class="text-center" value="">
                                        ----- Chọn kích thước khuôn -----
                                    </option>
                                    @foreach ($modelSizes as $modelSize)
                                        <option <?= old('moldSize') == $modelSize ? 'selected' : '' ?>
                                            value="{{ $modelSize }}">
                                            {{ $modelSize }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('moldSize')
                                    <div class="text text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-sm-6 col-md-4">
                                <label class="form-label">
                                    Số CAV(cái/ shot)
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" placeholder="Số CAV(cái/ shot)"
                                    class="form-control @error('CAV') is-invalid @enderror" name="CAV"
                                    value="{{ old('CAV') }}" required />
                                @error('CAV')
                                    <div class="text text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-sm-6 col-md-4">
                                <label class="form-label">
                                    Chu kì(s/shot)
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" step="0.1" placeholder="Chu kì(s/shot)"
                                    class="form-control @error('cycle') is-invalid @enderror" name="cycle"
                                    value="{{ old('cycle') }}" required />
                                @error('cycle')
                                    <div class="text text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-sm-6 col-md-4">
                                <label class="form-label">
                                    Số lượng tồn đầu hàng 200%
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" placeholder="Số lượng tồn đầu hàng 200%"
                                    class="form-control @error('stockQuan200') is-invalid @enderror" name="stockQuan200"
                                    value="{{ old('stockQuan200') }}" required />
                                @error('stockQuan200')
                                    <div class="text text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            {{--
                                <div class="form-group col-12 col-sm-6 col-md-4">
                                <label class="form-label">Dự định thời gian hoạt động thiết bị(ngày/tháng)<span class="text-danger">*</span></label>
                                <input type="number" step="0.1"  class="form-control @error('planTime') is-invalid @enderror" name="planTime" placeholder="Dự định thời gian hoạt động thiết bị(ngày/tháng)" value="{{ old('planTime') }}" required>
                                @error('planTime')
                                <div class="text text-danger">{{ $message }}</div>
                                @enderror
                                </div>
                                <div class="form-group col-12 col-sm-6 col-md-4">
                                <label class="form-label">Thực tế thời gian hoạt động thiết bị(ngày/tháng)<span class="text-danger">*</span></label>
                                <input type="number" step="0.1" class="form-control @error('realTime') is-invalid @enderror" placeholder="Thực tế thời gian hoạt động thiết bị(ngày/tháng)" name="realTime" value="{{ old('realTime') }}" required>
                                @error('realTime')
                                <div class="text text-danger">{{ $message }}</div>
                                @enderror
                                </div>
                            --}}
                            <div class="form-group col-12 col-sm-6 col-md-4">
                                <label class="form-label">
                                    Chọn mã thùng
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control @error('binCode') is-invalid @enderror" name="binCode" required>
                                    <option class="text-center" value="">
                                        ----- Chọn mã thùng -----
                                    </option>
                                    @foreach ($models as $model)
                                        <option <?= old('binCode') == $model ? 'selected' : '' ?>
                                            value="{{ $model }}">
                                            {{ $model }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('binCode')
                                    <div class="text text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-sm-6 col-md-4">
                                <label class="form-label">
                                    Số lượng con/thùng
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control @error('quanEntityBin') is-invalid @enderror"
                                    placeholder="Số lượng con/thùng" name="quanEntityBin"
                                    value="{{ old('quanEntityBin') }}" required />
                                @error('quanEntityBin')
                                    <div class="text text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-12 col-sm-6 col-md-4">
                                <label class="form-label">
                                    Chọn Công ty
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <div class="form-check form-check-inline p-0 m-0">
                                        <input type="checkbox" name="company[]" value="FAPV" id="company-1" required
                                            {{ in_array('FAPV', old('company', [])) ? 'checked' : '' }} />

                                        <label for="company-1">FAPV出荷</label>
                                    </div>
                                    <div class="form-check form-check-inline p-0 m-0">
                                        <input type="checkbox" name="company[]" value="FASV" id="company-2" required
                                            {{ in_array('FASV', old('company', [])) ? 'checked' : '' }} />

                                        <label for="company-2">FASV出荷</label>
                                    </div>
                                    <div class="form-check form-check-inline p-0 m-0">
                                        <input type="checkbox" name="company[]" value="FAVV" id="company-3" required
                                            {{ in_array('FAVV', old('company', [])) ? 'checked' : '' }} />

                                        <label for="company-3">FAVV出荷</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">
                            Thêm mới
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            function updateCompanyRequired() {
                if ($("input[name='company[]']:checked").length > 0) {
                    $("input[name='company[]']").prop('required', false);
                } else {
                    $("input[name='company[]']").prop('required', true);
                }
            }

            $("input[name='company[]']").on('change', updateCompanyRequired);

            // Call the function on load
            updateCompanyRequired();
        });
    </script>
@endsection
