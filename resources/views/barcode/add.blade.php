@extends('master')
@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/add-barcode.css') }}">
    <div class="row">
        <div class="col-12 print-container">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 no-print">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Tạo mã vạch</h6>
                    </div>
                </div>
                <div class="px-0 pb-2">
                    <div class="table-responsive p-4">
                        <form action="{{ route('admin.barcode.register') }}" method="post">
                            @method('POST')
                            @csrf
                            <div class="row no-print">
                                <div>QRCode</div>
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label class="form-label">Ngày<span class="required">*</span></label>
                                        <input id="date" type="date" class="form-control @error('date') is-invalid @enderror"
                                            placeholder="Ngày" name="date" value="{{ $request->date ?? '' }}" required>
                                        @error('date')
                                            <div class="text text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Ca<span class="required">*</span></label>
                                    <select id="shift" class="form-control @error('shift') is-invalid @enderror" name="shift"
                                        required>
                                        <option style="text-align: center" value="">----- Ca làm việc -----</option>
                                        <option <?= ($request->shift ?? '') == 1 ? 'selected' : '' ?> value="1">Ca 1</option>
                                        <option <?= ($request->shift ?? '') == 2 ? 'selected' : '' ?> value="2">Ca 2</option>
                                    </select>
                                    @error('shift')
                                        <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Số lượng thùng(tem)<span class="required">*</span></label>
                                    <input id="binCount" type="number" min="1" max="999"
                                        class="form-control @error('binCount') is-invalid @enderror"
                                        placeholder="Số lượng thùng" name="binCount" value="{{ $request->binCount ?? '' }}" required>
                                    @error('binCount')
                                        <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Thùng bắt đầu<span class="required">*</span></label>
                                    <input id="binStart" type="number" min="0"
                                        class="form-control @error('binStart') is-invalid @enderror"
                                        placeholder="Thùng bắt đầu" name="binStart" value="{{ $request->binStart ?? '' }}" required>
                                    @error('binStart')
                                        <div class="text text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>BarCode</div>
                                <div class="col-3">
                                    <label class="form-label">Sản phẩm<span class="required">*</span></label>
                                    <select onchange="selectProduct(event)"
                                        class="form-control @error('product_code') is-invalid @enderror" name="product_code"
                                        required>
                                        <option style="text-align: center" value="">----- Chọn sản phẩm -----</option>
                                        @foreach ($products as $pro)
                                            <option {{ ($product->code ?? '') == $pro->code ? 'selected' : '' }}
                                                value="{{ $pro->code }}-{{ $pro->quanEntityBin }}">{{ $pro->name }}</option>
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
                            </div><br>
                            <div>
                                <div class="col-12 no-print">
                                    <button type="submit" id="register-barcode" class="btn btn-success">Tạo tem</button>
                                    <a class="btn btn-primary" href="{{ route('admin.product.barcode') }}">Làm mới</a>
                                    @if (isset($binArray))
                                        <a class="btn btn-secondary" onclick="savePrint(event)" href="javascript:window.print()">Print</a>
                                    @endif
                                </div>
                                <div class="container-gird">
                                    <div class="grid-container">
                                        @if (isset($barcode) && isset($qrCode))
                                            @foreach ($binArray as $key => $bin)
                                                <div class="container grid-item">
                                                    @if ($bin['bin'] != "xxxx")
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <td colspan="2" class="align-content-center">
                                                                    <img src="{{ asset('assets/img/logos/VVP.png') }}" alt="" width="110" title="VINH VINH PHAT ONE MEMBER CO.LTD">
                                                                    <div class="logo-text qr-add">VINH VINH PHAT ONE MEMBER CO. LTD</div>
                                                                </td>
                                                                <td colspan="4" class="align-content-center">
                                                                    <div class="qrcode-img">{!! $qrCode !!}</div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-start">
                                                                    Tên khách hàng<br>外メーカー名
                                                                </td>
                                                                <td colspan="5" class="text-center">
                                                                    <p class="fw-bold mb-0">
                                                                        FURUKAWA AUTOMOTIVE PARTS<br>(VIET NAM)INC
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-start">
                                                                    Tên sản phẩm<br>品名
                                                                </td>
                                                                <td  class="text-center">
                                                                    <p class="fw-bold mb-0">{{ $product->name }}</p>
                                                                </td>
                                                                <td colspan="2" class="align-content-center">
                                                                    CODE
                                                                </td>
                                                                <td colspan="2" class="text-center">
                                                                    <p class="fw-bold mb-0">{{ $product->code }}</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-start">
                                                                    Nguyên liệu<br>原材料
                                                                </td>
                                                                <td class="text-center align-content-center">
                                                                    <p class="mb-0">{{ $product?->productionPlans()?->firstOrFail()?->material_name ?? "NULL" }}</p>
                                                                </td>
                                                                <td colspan="2" class="text-center">
                                                                    Màu sắc 色
                                                                </td>
                                                                <td colspan="2" class="text-center align-content-center">
                                                                    <p class="mb-0">NATURAL</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-start">
                                                                    Số lượng<br>数量
                                                                </td>
                                                                <td colspan="5" class="text-center align-content-center">
                                                                    <p class="fw-bold mb-0">{{ $product->quanEntityBin }}PCS</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-start">
                                                                    Lotno<br>ロット No
                                                                </td>
                                                                <td colspan="4" class="text-center align-content-center">
                                                                    <div class="lot-container">
                                                                        <p class="fw-bold mb-0">{{ $lotNo['lot'] }}</p>
                                                                        <p class="fw-bold mb-0">-</p>
                                                                        <p class="fw-bold mb-0">{{ $lotNo['date'] }}</p>
                                                                        <p class="fw-bold mb-0">-</p>
                                                                        <p class="fw-bold mb-0">{{ $lotNo['shift'] }}</p>
                                                                        <p class="fw-bold mb-0">-</p>
                                                                        <p class="fw-bold mb-0">{{ $bin['bin'] }}</p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-start">
                                                                    Mã vạch<br>バーコード
                                                                </td>
                                                                <td colspan="5" class="text-center align-content-center">
                                                                    <img id="barcode-image"
                                                                        src="data:image/png;base64,{{ $bin['barcode'] }}"
                                                                        alt="Mã vạch">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-start">
                                                                    Kiểm tra<br>検査
                                                                </td>
                                                                <td colspan="2" class="text-center align-content-center">
                                                                    Kiểm tra 200%<br>檢查(200%)
                                                                </td>
                                                                <td colspan="3" class="text-center align-content-center">
                                                                    Kiểm tra (Xuất hàng) 検査 (出荷)
                                                                </td>
                                                            </tr>
                                                            <tr class="moc-style">
                                                                <td class="text-start" style="height:100px !important">
                                                                    Mộc<br>合格印

                                                                </td>
                                                                <td colspan="3"></td>
                                                                <td colspan="2" style="position: relative;">
                                                                    <div class="square"></div>
                                                                </td>
                                                            </tr>
                                                            <tr class="under-moc-style">
                                                                <td class="text-start">
                                                                    Người kiểm<br>検査
                                                                </td>
                                                                <td colspan="3"> </td>
                                                                <td colspan="2"> </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-start">(Thời gian)</td>
                                                                <td colspan="5">{{ $lotNo['date_time'] }}</td>
                                                            </tr>
                                                        </table>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                @if (isset($binArray))
                                    <div class="no-print">
                                        <a class="btn btn-secondary" id="save-print" data-url="{{ route('admin.barcode.save.print')}}" onclick="savePrint(event)" href="javascript:window.print()">Print</a>
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

        function savePrint(event) {
            var url = $('#save-print').data('url');
            var productCode = $('#product_code').val();
            var date = $('#date').val();
            var shift = $('#shift').val();
            var binCount = $('#binCount').val();
            var binStart = $('#binStart').val();

            if(productCode) {
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        productCode: productCode,
                        date: date,
                        shift: shift,
                        binCount: binCount,
                        binStart: binStart,
                        _token: '{{ csrf_token() }}'
                    },
                        success: function(response) {
                            console.log(response.status);
                        },
                        error: function(xhr, status, error) {
                            console.error("Đã xảy ra lỗi khi gửi lưu lịch sử print:", error);
                        }
                    });
            }
        }
    </script>
@endsection
