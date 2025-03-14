@extends('layouts.layout')
@section('styles')
    <link
        rel="stylesheet"
        href="{{ asset('assets/css/add-packing-stamp.css') }}"
    />
@endsection

@php
    $groups = isset($binArray) ? array_chunk($binArray, 8) : [];
@endphp

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card my-4 no-print">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Tạo Tem Bịch</h4>
                    </div>
                </div>
                <div class="px-0 pb-2">
                    <div class="table-responsive p-4">
                        <form
                            action="{{ route('admin.packing.register') }}"
                            method="post"
                        >
                            @method('POST')
                            @csrf
                            <div class="row">
                                <!-- Ngày -->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        Ngày
                                        <span class="required text-danger">
                                            *
                                        </span>
                                    </label>
                                    <input
                                        type="date"
                                        id="date"
                                        class="form-control @error('date') is-invalid @enderror"
                                        placeholder="Ngày"
                                        name="date"
                                        value="{{ $request->date ?? '' }}"
                                        required
                                    />
                                    @error('date')
                                        <div class="text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Ca -->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        Ca
                                        <span class="required text-danger">
                                            *
                                        </span>
                                    </label>
                                    <select
                                        id="shift"
                                        class="form-control @error('shift') is-invalid @enderror"
                                        name="shift"
                                        required
                                    >
                                        <option
                                            style="text-align: center"
                                            value=""
                                        >
                                            ----- Ca làm việc -----
                                        </option>
                                        <option
                                            <?= ($request->shift ?? '') == 1 ? 'selected' : '' ?>
                                            value="1"
                                        >
                                            Ca 1
                                        </option>
                                        <option
                                            <?= ($request->shift ?? '') == 2 ? 'selected' : '' ?>
                                            value="2"
                                        >
                                            Ca 2
                                        </option>
                                    </select>
                                    @error('shift')
                                        <div class="text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Số lượng thùng (tem) -->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        Số lượng thùng (tem)
                                        <span class="required text-danger">
                                            *
                                        </span>
                                    </label>
                                    <input
                                        min="1"
                                        max="999"
                                        id="binCount"
                                        class="form-control @error('binCount') is-invalid @enderror"
                                        placeholder="Số lượng thùng"
                                        name="binCount"
                                        value="{{ $request->binCount ?? '' }}"
                                        required
                                    />
                                    @error('binCount')
                                        <div class="text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <small class="form-text text-muted">
                                        <strong>
                                            Lưu ý: Trường hợp nếu cần in lại
                                            nhiều tem với số thùng khác nhau thì
                                            nhập số lượng tem theo các số lượng
                                            cần in, ví dụ: cần in 2 tem lẻ 1 và
                                            2 thì nhập số lượng là 2
                                        </strong>
                                    </small>
                                </div>

                                <!-- Thùng bắt đầu -->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        Thùng bắt đầu
                                        <span class="required text-danger">
                                            *
                                        </span>
                                    </label>
                                    <input
                                        min="0"
                                        id="binStart"
                                        class="form-control @error('binStart') is-invalid @enderror"
                                        placeholder="Thùng bắt đầu"
                                        name="binStart"
                                        value="{{ $request->binStart ?? '' }}"
                                        required
                                    />
                                    @error('binStart')
                                        <div class="text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <small class="form-text text-muted">
                                        <strong>
                                            Lưu ý: Trường hợp nếu cần in lại
                                            nhiều tem với số thùng khác nhau thì
                                            nhập cách mỗi số thùng ví dụ thùng 1
                                            và 2 thì nhập, ví dụ: 1,2
                                        </strong>
                                    </small>
                                </div>

                                <!-- Sản phẩm -->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        Sản phẩm
                                        <span class="required text-danger">
                                            *
                                        </span>
                                    </label>
                                    <select
                                        onchange="selectProduct(event)"
                                        class="form-control @error('product_code') is-invalid @enderror"
                                        name="product_code"
                                        required
                                    >
                                        <option
                                            style="text-align: center"
                                            value=""
                                        >
                                            ----- Chọn sản phẩm -----
                                        </option>
                                        @foreach ($products as $pro)
                                            <option
                                                {{ ($product->code ?? '') == $pro->code ? 'selected' : '' }}
                                                value="{{ $pro->code }}-{{ $pro->quantity_per_package }}"
                                            >
                                                {{ $pro->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_code')
                                        <div class="text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Code -->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        Code
                                        <span class="required text-danger">
                                            *
                                        </span>
                                    </label>
                                    <input
                                        type="text"
                                        id="product_code"
                                        class="form-control @error('code') is-invalid @enderror"
                                        placeholder="Code"
                                        name="code"
                                        value="{{ $product->code ?? '' }}"
                                        required
                                        readonly
                                    />
                                    @error('code')
                                        <div class="text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- PCS -->
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">
                                        PCS
                                        <span class="required text-danger">
                                            *
                                        </span>
                                    </label>
                                    <input
                                        type="number"
                                        min="1"
                                        id="product_pcs"
                                        class="form-control @error('pcs') is-invalid @enderror"
                                        placeholder="PCS"
                                        name="pcs"
                                        value="{{ $product->quantity_per_package ?? '' }}"
                                        required
                                        readonly
                                    />
                                    @error('pcs')
                                        <div class="text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <input
                                    type="hidden"
                                    id="type"
                                    name="type"
                                    value="Tem Bịch"
                                />
                            </div>
                            <br />
                            <div>
                                <div class="col-12">
                                    <button
                                        type="submit"
                                        id="register-barcode"
                                        class="btn btn-success"
                                    >
                                        Tạo tem
                                    </button>
                                    <a
                                        class="btn btn-primary"
                                        href="{{ route('admin.product.packing') }}"
                                    >
                                        Làm mới
                                    </a>
                                    @if (isset($binArray))
                                        <a
                                            class="btn btn-secondary"
                                            id="save-print"
                                            data-url="{{ route('admin.barcode.save.print') }}"
                                            href="#"
                                        >
                                            Print
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @if (isset($binArray))
                <div>
                    <h1 class="no-print">Print Preview</h1>
                    <div class="print-wrapper">
                        @foreach ($groups as $group)
                            <div class="print-page">
                                <div class="page-container">
                                    <div class="row">
                                        @foreach ($group as $item)
                                            <div class="col-6">
                                                <table
                                                    class="table table-bordered"
                                                >
                                                    <tbody
                                                        class="text-center align-content-center"
                                                        style="font-size: 11px"
                                                    >
                                                        <tr>
                                                            <td
                                                                class="text-start"
                                                            >
                                                                Tên sản phẩm
                                                                <br />
                                                                品名
                                                            </td>
                                                            <td
                                                                class="fw-bold fs-6"
                                                                style="
                                                                    width: 9.25rem;
                                                                "
                                                            >
                                                                {{ $product->name }}
                                                            </td>
                                                            <td
                                                                class=""
                                                                style="
                                                                    width: 4.625rem;
                                                                "
                                                            >
                                                                CODE
                                                            </td>
                                                            <td
                                                                class="fw-bold fs-6"
                                                                style="
                                                                    width: 4.625rem;
                                                                "
                                                            >
                                                                {{ $product->code }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                class="text-start"
                                                            >
                                                                Nguyên liệu
                                                                <br />
                                                                原材料
                                                            </td>
                                                            <td class="fs-6">
                                                                {{ $product->material }}
                                                            </td>
                                                            <td class="">
                                                                Màu sắc 色
                                                            </td>
                                                            <td class="fs-6">
                                                                {{ $product->color }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                class="text-start"
                                                            >
                                                                Số lượng
                                                                <br />
                                                                数量
                                                            </td>
                                                            <td
                                                                colspan="3"
                                                                class="fw-bold fs-6"
                                                            >
                                                                {{ $product->quantity_per_package }}
                                                                PCS
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                class="text-start"
                                                            >
                                                                Lotno
                                                                <br />
                                                                ロット No
                                                            </td>
                                                            <td
                                                                colspan="3"
                                                                class="fw-bold fs-6"
                                                            >
                                                                <div
                                                                    class=""
                                                                    style="
                                                                        display: flex;
                                                                        justify-content: space-between;
                                                                        align-items: center;
                                                                    "
                                                                >
                                                                    <span>
                                                                        {{ $lotNo['lot'] }}
                                                                    </span>
                                                                    -
                                                                    <span>
                                                                        {{ $lotNo['date'] }}
                                                                    </span>
                                                                    -
                                                                    <span>
                                                                        {{ $lotNo['shift'] }}
                                                                    </span>
                                                                    -
                                                                    <span>
                                                                        {{ $item['bin'] }}
                                                                    </span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                class="text-start"
                                                            >
                                                                Kiểm tra
                                                                <br />
                                                                検査
                                                            </td>
                                                            <td class="">
                                                                Kiểm tra 100%
                                                                <br />
                                                                檢查(100%)
                                                            </td>
                                                            <td
                                                                colspan="2"
                                                                class=""
                                                            >
                                                                Kiểm tra 200%
                                                                <br />
                                                                檢查(200%)
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                class="text-start"
                                                            >
                                                                Mộc
                                                                <br />
                                                                合格印
                                                            </td>
                                                            <td></td>
                                                            <td
                                                                colspan="2"
                                                            ></td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                class="text-start"
                                                            >
                                                                Người kiểm
                                                                <br />
                                                                検査
                                                            </td>
                                                            <td></td>
                                                            <td
                                                                colspan="2"
                                                            ></td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                colspan="2"
                                                                class="text-center"
                                                            >
                                                                (Thời gian) 時間
                                                            </td>
                                                            <td colspan="2">
                                                                {{ $lotNo['date_time'] }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
    <script>
        var lastPrintTime = null; // Biến lưu thời gian lần in gần nhất
        var isPrintShortcutActivated = false; // Biến theo dõi trạng thái nhấn Ctrl + P

        function selectProduct(event) {
            var data = event.target.value.split('-', 2);
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
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (response) {
                        console.log(response.status);
                        if (callback) callback(); // Gọi callback sau khi lưu thành công
                    },
                    error: function (xhr, status, error) {
                        console.error(
                            'Đã xảy ra lỗi khi gửi lưu lịch sử print:',
                            error,
                        );
                    },
                });
            }
        }

        function handlePrint() {
            var currentTime = new Date().getTime();

            if (lastPrintTime === null) {
                lastPrintTime = currentTime;
                savePrint(function () {
                    setTimeout(function () {
                        window.print();
                        isPrintShortcutActivated = false; // Reset trạng thái sau khi in
                    }, 100);
                });
            } else {
                var timeDiff = (currentTime - lastPrintTime) / 1000 / 60;

                if (timeDiff <= 5) {
                    Swal.fire({
                        title: 'Cảnh báo!',
                        text: 'Bạn đã in trước đó chưa đầy 5 phút. Bạn có chắc chắn muốn in thêm không?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Có',
                        cancelButtonText: 'Không',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            lastPrintTime = currentTime;
                            savePrint(function () {
                                setTimeout(function () {
                                    window.print();
                                    isPrintShortcutActivated = false; // Reset trạng thái sau khi in
                                }, 100);
                            });
                        }
                    });
                } else {
                    lastPrintTime = currentTime;
                    savePrint(function () {
                        setTimeout(function () {
                            window.print();
                            isPrintShortcutActivated = false; // Reset trạng thái sau khi in
                        }, 100);
                    });
                }
            }
        }

        $(document).ready(function () {
            $(document).keydown(function (event) {
                // Kích hoạt Ctrl + P để lưu lịch sử in
                if (event.ctrlKey && event.key === 'p') {
                    event.preventDefault(); // Ngăn hành động mặc định
                    isPrintShortcutActivated = true; // Đánh dấu Ctrl + P đã được nhấn
                    handlePrint(); // Gọi hàm in
                }

                // Ngăn chặn Ctrl+Shift+P nếu Ctrl+P chưa được nhấn
                if (event.ctrlKey && event.shiftKey && event.key === 'P') {
                    if (!isPrintShortcutActivated) {
                        event.preventDefault();
                        Swal.fire({
                            title: 'Thông báo',
                            text: 'Vui lòng nhấn Ctrl + P trước khi sử dụng Ctrl + Shift + P.',
                            icon: 'info',
                            confirmButtonText: 'Đồng ý',
                        });
                    } else {
                        // Nếu Ctrl + P đã được nhấn, bạn có thể thực hiện hành động cho Ctrl + Shift + P ở đây
                        console.log('Ctrl + Shift + P được nhấn!');
                        // Thực hiện hành động khác nếu cần
                    }
                }
            });

            $('#save-print').click(function (event) {
                event.preventDefault();
                handlePrint();
            });
        });
    </script>
@endsection
