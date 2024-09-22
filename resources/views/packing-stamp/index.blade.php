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
                                <!-- Ngày -->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Ngày<span class="required text-danger">*</span></label>
                                    <input type="date" id="date"
                                        class="form-control @error('date') is-invalid @enderror" placeholder="Ngày"
                                        name="date" value="{{ $request->date ?? '' }}" required>
                                    @error('date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Ca -->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Ca<span class="required text-danger">*</span></label>
                                    <select id="shift" class="form-control @error('shift') is-invalid @enderror"
                                        name="shift" required>
                                        <option style="text-align: center" value="">----- Ca làm việc -----</option>
                                        <option <?= ($request->shift ?? '') == 1 ? 'selected' : '' ?> value ="1">Ca 1
                                        </option>
                                        <option <?= ($request->shift ?? '') == 2 ? 'selected' : '' ?> value ="2">Ca 2
                                        </option>
                                    </select>
                                    @error('shift')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Số lượng thùng (tem) -->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Số lượng thùng (tem)<span
                                            class="required text-danger">*</span></label>
                                    <input min="1" max="999" id="binCount"
                                        class="form-control @error('binCount') is-invalid @enderror"
                                        placeholder="Số lượng thùng" name="binCount" value="{{ $request->binCount ?? '' }}"
                                        required>
                                    @error('binCount')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <strong>Lưu ý: Trường hợp nếu cần in lại nhiều tem với số thùng khác nhau thì nhập
                                            số lượng tem theo các số lượng cần in, ví dụ: cần in 2 tem lẻ 1,2 thì nhập số
                                            lượng là 2</strong>
                                    </small>
                                </div>

                                <!-- Thùng bắt đầu -->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Thùng bắt đầu<span
                                            class="required text-danger">*</span></label>
                                    <input min="0" id="binStart"
                                        class="form-control @error('binStart') is-invalid @enderror"
                                        placeholder="Thùng bắt đầu" name="binStart" value="{{ $request->binStart ?? '' }}"
                                        required>
                                    @error('binStart')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        <strong>Lưu ý: Trường hợp nếu cần in lại nhiều tem với số thùng khác nhau thì nhập
                                            cách mỗi số thùng ví dụ thùng 1 và 2 thì nhập, ví dụ: 1,2</strong>
                                    </small>
                                </div>

                                <!-- Sản phẩm -->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Sản phẩm<span class="required text-danger">*</span></label>
                                    <select onchange="selectProduct(event)"
                                        class="form-control @error('product_code') is-invalid @enderror" name="product_code"
                                        required>
                                        <option style="text-align: center" value="">----- Chọn sản phẩm -----</option>
                                        @foreach ($products as $pro)
                                            <option {{ ($product->code ?? '') == $pro->code ? 'selected' : '' }}
                                                value="{{ $pro->code }}-{{ $pro->quantity_per_package }}">
                                                {{ $pro->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_code')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Code -->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Code<span class="required text-danger">*</span></label>
                                    <input type="text" id="product_code"
                                        class="form-control @error('code') is-invalid @enderror" placeholder="Code"
                                        name="code" value="{{ $product->code ?? '' }}" required readonly>
                                    @error('code')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- PCS -->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">PCS<span class="required text-danger">*</span></label>
                                    <input type="number" min="1" id="product_pcs"
                                        class="form-control @error('pcs') is-invalid @enderror" placeholder="PCS"
                                        name="pcs" value="{{ $product->quantity_per_package ?? '' }}" required
                                        readonly>
                                    @error('pcs')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <input type="hidden" id="type" name="type" value="Tem Bịch">
                            </div><br>
                            <div>
                                <div class="col-12 no-print">
                                    <button type="submit" id="register-barcode" class="btn btn-success">Tạo tem</button>
                                    <a class="btn btn-primary" href="{{ route('admin.product.packing') }}">Làm mới</a>
                                    @if (isset($binArray))
                                        <a class="btn btn-secondary" onclick="handlePrint(event)" href="#">Print</a>
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
                                                                    {{ $product->material }}
                                                                </p>
                                                            </td>
                                                            <td class="text-center">
                                                                Màu sắc 色
                                                            </td>
                                                            <td colspan="2" class="text-center align-content-center">
                                                                <p class="mb-0 fs-13">
                                                                    {{ $product->color }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-start">
                                                                Số lượng<br>数量
                                                            </td>
                                                            <td colspan="5" class="text-center align-content-center">
                                                                <p class="fw-bold mb-0 fs-13">
                                                                    {{ $product->quantity_per_package }}PCS</p>
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
                                {{-- @if (isset($binArray))
                                    <div class="no-print">
                                        <a class="btn btn-secondary" id="save-print"
                                            data-url="{{ route('admin.barcode.save.print') }}" onclick="savePrint(event)"
                                            href="javascript:window.print()">Print</a>
                                    </div>
                                @endif --}}
                                @if (isset($binArray))
                                    <div class="no-print">
                                        <a class="btn btn-secondary" id="save-print"
                                            data-url="{{ route('admin.barcode.save.print') }}" href="#">Print</a>
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
        var lastPrintTime = null; // Biến lưu thời gian lần in gần nhất

        function selectProduct(event) {
            var data = event.target.value.split("-", 2);
            $('#product_code').val(data[0]);
            $('#product_pcs').val(data[1]);
        }

        function savePrint(callback) {
            var url = $('#save-print').data('url');
            var productCode = $('#product_code').val();
            var date = $('#date').val();
            var shift = $('#shift').val();
            var binCount = $('#binCount').val();
            var binStart = $('#binStart').val();
            var type = $('#type').val();

            if (productCode) {
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        productCode: productCode,
                        date: date,
                        shift: shift,
                        binCount: binCount,
                        binStart: binStart,
                        type: type,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log(response.status);
                        if (callback) callback(); // Gọi callback sau khi lưu thành công
                    },
                    error: function(xhr, status, error) {
                        console.error("Đã xảy ra lỗi khi gửi lưu lịch sử print:", error);
                    }
                });
            }
        }

        function handlePrint() {
            var currentTime = new Date().getTime();

            if (lastPrintTime === null) {
                // Lần in đầu tiên, không có cảnh báo
                lastPrintTime = currentTime;
                savePrint(function() {
                    setTimeout(function() {
                        window.print();
                    }, 100);
                });
            } else {
                var timeDiff = (currentTime - lastPrintTime) / 1000 / 60;

                if (timeDiff <= 5) {
                    // Nếu thời gian in thứ hai trong vòng 5 phút, hiển thị cảnh báo
                    Swal.fire({
                        title: 'Cảnh báo!',
                        text: 'Bạn đã in trước đó chưa đầy 5 phút. Bạn có chắc chắn muốn in thêm không?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Có',
                        cancelButtonText: 'Không'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Nếu người dùng xác nhận in, cho phép in
                            lastPrintTime = currentTime;
                            savePrint(function() {
                                setTimeout(function() {
                                    window.print();
                                }, 100);
                            });
                        }
                    });
                } else {
                    // Nếu đã hơn 5 phút, không cần cảnh báo
                    lastPrintTime = currentTime;
                    savePrint(function() {
                        setTimeout(function() {
                            window.print();
                        }, 100);
                    });
                }
            }
        }

        $(document).ready(function() {
            // Xử lý sự kiện nhấn Ctrl+P
            $(document).keydown(function(event) {
                if (event.ctrlKey && event.key === 'p') {
                    event.preventDefault(); // Ngăn chặn việc tự động mở hộp thoại in
                    handlePrint(); // Xử lý việc in
                }
            });

            // Xử lý khi nhấn vào nút Print
            $('#save-print').click(function(event) {
                event.preventDefault(); // Ngăn chặn việc mở hộp thoại in tự động
                handlePrint(); // Gọi cùng một logic xử lý in
            });
        });
    </script>
@endsection
