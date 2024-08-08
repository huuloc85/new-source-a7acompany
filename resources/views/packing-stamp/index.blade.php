@extends('master')
@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/add-packing-stamp.css') }}">
    <div class="row">
        <div class="col-12 print-container">
            <div class="card my-4">
                <div class="card-header p-1 position-relative mt-n1 mx-1 no-print">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Tạo Tem Bịch</h4>
                    </div>
                </div>
                <div class="px-0 pb-2">
                    <div class="table-responsive p-4">
                        <form action="{{ route('admin.packing.register') }}" method="post">
                            @method('POST')
                            @csrf
                            <div class="row no-print">
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label class="form-label">Ngày<span class="required">*</span></label>
                                        <input type="date" class="form-control @error('date') is-invalid @enderror"
                                            placeholder="Ngày" name="date" value="{{ $request->date ?? '' }}" required>
                                        @error('date')
                                            <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Ca<span class="required">*</span></label>
                                    <select id="shift" class="form-control @error('shift') is-invalid @enderror"
                                        name="shift" required>
                                        <option style="text-align: center" value="">----- Ca làm việc -----</option>
                                        <option value="1" {{ old('shift') == 1 ? 'selected' : '' }}>Ca 1</option>
                                        <option value="2" {{ old('shift') == 2 ? 'selected' : '' }}>Ca 2</option>
                                    </select>
                                    @error('shift')
                                        <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Số lượng thùng(tem)<span class="required">*</span></label>
                                    <input type="number" min="1" max="999"
                                        class="form-control @error('binCount') is-invalid @enderror"
                                        placeholder="Số lượng thùng" name="binCount" value="{{ $request->binCount ?? '' }}"
                                        required>
                                    @error('binCount')
                                        <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Thùng bắt đầu<span class="required">*</span></label>
                                    <input type="number" min="0"
                                        class="form-control @error('binStart') is-invalid @enderror"
                                        placeholder="Thùng bắt đầu" name="binStart" value="{{ $request->binStart ?? '' }}"
                                        required>
                                    @error('binStart')
                                        <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Sản phẩm<span class="required">*</span></label>
                                    <select onchange="selectProduct(event)"
                                        class="form-control @error('product_code') is-invalid @enderror" name="product_code"
                                        required>
                                        <option style="text-align: center" value="">----- Chọn sản phẩm -----</option>
                                        @foreach ($products as $pro)
                                            <option {{ ($product->code ?? '') == $pro->code ? 'selected' : '' }}
                                                value="{{ $pro->code }}-{{ $pro->quanEntityBin }}">{{ $pro->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_code')
                                        <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Code<span class="required">*</span></label>
                                    <input type="text" id="product_code"
                                        class="form-control @error('code') is-invalid @enderror" placeholder="Code"
                                        name="code" value="{{ $product->code ?? '' }}" required readonly>
                                    @error('code')
                                        <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-3">
                                    <label class="form-label">PCS<span class="required">*</span></label>
                                    <input type="number" min="1" id="product_pcs"
                                        class="form-control @error('pcs') is-invalid @enderror" placeholder="PCS"
                                        name="pcs" value="{{ $product->quanEntityBin ?? '' }}" required readonly>
                                    @error('pcs')
                                        <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Thời gian<span class="required">*</span></label>
                                    <input id="date_time" type="datetime-local"
                                        class="form-control @error('date_time') is-invalid @enderror"
                                        placeholder="Thời gian" name="date_time"
                                        value="{{ old('date_time', Carbon\Carbon::now()->format('Y-m-d\TH:i')) }}"
                                        required>
                                    @error('date_time')
                                        <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <script>
                                    document.getElementById('shift').addEventListener('change', function() {
                                        var shift = this.value;
                                        var dateTimeInput = document.getElementById('date_time');
                                        var now = new Date();

                                        if (shift == '1') {
                                            now.setHours(7, 30, 0); // 7:30 AM
                                        } else if (shift == '2') {
                                            now.setHours(19, 30, 0); // 7:30 PM
                                        }

                                        var year = now.getFullYear();
                                        var month = (now.getMonth() + 1).toString().padStart(2, '0');
                                        var day = now.getDate().toString().padStart(2, '0');
                                        var hours = now.getHours().toString().padStart(2, '0');
                                        var minutes = now.getMinutes().toString().padStart(2, '0');
                                        var formattedDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;

                                        dateTimeInput.value = formattedDateTime;
                                    });
                                </script>
                            </div><br>
                            <div>
                                <div class="col-12 no-print">
                                    <button type="submit" id="register-barcode" class="btn btn-success">Tạo tem</button>
                                    <a class="btn btn-primary" href="{{ route('admin.product.packing') }}">Làm mới</a>
                                    @if (isset($binArray))
                                        <a class="btn btn-secondary" href="javascript:window.print()">Print</a>
                                    @endif
                                </div>
                                <div class="container-gird">
                                    <div class="grid-container">
                                        @if (isset($binArray))
                                            @foreach ($binArray as $key => $bin)
                                                <div class="container grid-item">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td class="text-start w-120 w-5">
                                                                Tên sản<br>phẩm<br>品名
                                                            </td>
                                                            <td colspan="2" class="text-center jtf-center">
                                                                <p class="fw-bold mb-0 fs-20 fs-13">{{ $product->name }}
                                                                </p>
                                                            </td>
                                                            <td class="align-content-center w-120 w-5 code">
                                                                CODE
                                                            </td>
                                                            <td colspan="2" class="text-center jtf-center">
                                                                <p class="fw-bold mb-0 fs-13">{{ $product->code }}</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-start">
                                                                Nguyên liệu<br>原材料
                                                            </td>
                                                            <td colspan="2" class="text-center align-content-center">
                                                                <p class="mb-0 fs-13">
                                                                    {{ $product?->productionPlans()?->firstOrFail()?->material_name ?? 'NULL' }}
                                                                </p>
                                                            </td>
                                                            <td class="text-center">
                                                                Màu sắc<br>色
                                                            </td>
                                                            <td colspan="2" class="text-center align-content-center">
                                                                <p class="mb-0 fs-13">NATURAL</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-start">
                                                                Số lượng<br>数量
                                                            </td>
                                                            <td colspan="5" class="text-center align-content-center">
                                                                <p class="fw-bold mb-0 fs-13">
                                                                    {{ $product->quanEntityBin }}PCS</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-start">
                                                                Lotno<br>ロット No
                                                            </td>
                                                            <td colspan="5" class="text-center align-content-center">
                                                                <div class="lot-container">
                                                                    <p class="fw-bold mb-0 fs-13">{{ $lotNo['lot'] }}</p>
                                                                    <p class="fw-bold mb-0 fs-13">-</p>
                                                                    <p class="fw-bold mb-0 fs-13">{{ $lotNo['date'] }}</p>
                                                                    <p class="fw-bold mb-0 fs-13">-</p>
                                                                    <p class="fw-bold mb-0 fs-13">{{ $lotNo['shift'] }}
                                                                    </p>
                                                                    <p class="fw-bold mb-0 fs-13">-</p>
                                                                    <p class="fw-bold mb-0 fs-13">{{ $bin['bin'] }}</p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-start">
                                                                Kiểm tra<br>検査
                                                            </td>
                                                            <td colspan="2" class="text-center align-content-center">
                                                                Kiểm tra 100%<br>檢查(100%)
                                                            </td>
                                                            <td colspan="3" class="text-center align-content-center">
                                                                Kiểm tra 200%<br>檢查(200%)
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-start moc-style">
                                                                Mộc<br>合格印
                                                            </td>
                                                            <td colspan="2"></td>
                                                            <td colspan="3"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-start">
                                                                Người kiểm<br>検査
                                                            </td>
                                                            <td colspan="2"> </td>
                                                            <td colspan="3"> </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" class="text-center">(Thời gian) 時間</td>
                                                            <td colspan="2">{{ $lotNo['date_time'] }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                @if (isset($binArray))
                                    <div class="no-print">
                                        <a class="btn btn-secondary" href="javascript:window.print()">Print</a>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function selectProduct(event) {
            var data = event.target.value.split("-", 2);
            $('#product_code').val(data[0]);
            $('#product_pcs').val(data[1]);
        }
    </script>
@endsection
