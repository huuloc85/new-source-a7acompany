@extends('layouts.layout')
@section('styles')
    <link
        rel="stylesheet"
        href="{{ asset('assets/css/add-packing-stamp.css') }}"
    />
@endsection

@section('content')
    <div class="row">
        <div class="col-12 print-container">
            <div class="card no-print">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Tạo Tem Bịch</h4>
                    </div>
                </div>
            </div>
        </div>
        <br />
        <div class="table-responsive">
            <div class="container-gird no-break">
                <div class="grid-container">
                    @if (isset($binArray))
                        @foreach ($binArray as $key => $bin)
                            <div class="container grid-item">
                                <table
                                    class="table table-bordered"
                                    style="margin-top: 5px; margin-bottom: 5px"
                                >
                                    <tr>
                                        <td class="text-start w-120 w-5">
                                            Tên sản
                                            <br />
                                            phẩm
                                            <br />
                                            品名
                                        </td>
                                        <td
                                            colspan="2"
                                            class="text-center jtf-center"
                                        >
                                            <p class="fw-bold mb-0 fs-20 fs-13">
                                                {{ $product->name }}
                                            </p>
                                        </td>
                                        <td
                                            class="align-content-center w-120 w-5 code"
                                        >
                                            CODE
                                        </td>
                                        <td
                                            colspan="2"
                                            class="text-center jtf-center"
                                        >
                                            <p class="fw-bold mb-0 fs-13">
                                                {{ $product->code }}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">
                                            Nguyên liệu
                                            <br />
                                            原材料
                                        </td>
                                        <td
                                            colspan="2"
                                            class="text-center align-content-center"
                                        >
                                            <p class="mb-0 fs-13">
                                                {{ $product->material }}
                                            </p>
                                        </td>
                                        <td class="text-center">Màu sắc 色</td>
                                        <td
                                            colspan="2"
                                            class="text-center align-content-center"
                                        >
                                            <p class="mb-0 fs-13">
                                                {{ $product->color }}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">
                                            Số lượng
                                            <br />
                                            数量
                                        </td>
                                        <td
                                            colspan="5"
                                            class="text-center align-content-center"
                                        >
                                            <p class="fw-bold mb-0 fs-13">
                                                {{ $product->quantity_per_package }}PCS
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">
                                            Lotno
                                            <br />
                                            ロット No
                                        </td>
                                        <td
                                            colspan="5"
                                            class="text-center align-content-center"
                                        >
                                            <div class="lot-container">
                                                <p class="fw-bold mb-0 fs-13">
                                                    {{ $lotNo['lot'] }}
                                                </p>
                                                <p class="fw-bold mb-0 fs-13">
                                                    -
                                                </p>
                                                <p class="fw-bold mb-0 fs-13">
                                                    {{ $lotNo['date'] }}
                                                </p>
                                                <p class="fw-bold mb-0 fs-13">
                                                    -
                                                </p>
                                                <p class="fw-bold mb-0 fs-13">
                                                    {{ $lotNo['shift'] }}
                                                </p>
                                                <p class="fw-bold mb-0 fs-13">
                                                    -
                                                </p>
                                                <p class="fw-bold mb-0 fs-13">
                                                    {{ $bin['bin'] }}
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">
                                            Kiểm tra
                                            <br />
                                            検査
                                        </td>
                                        <td
                                            colspan="2"
                                            class="text-center align-content-center"
                                        >
                                            Kiểm tra 100%
                                            <br />
                                            檢查(100%)
                                        </td>
                                        <td
                                            colspan="3"
                                            class="text-center align-content-center"
                                        >
                                            Kiểm tra 200%
                                            <br />
                                            檢查(200%)
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-start moc-style">
                                            Mộc
                                            <br />
                                            合格印
                                        </td>
                                        <td colspan="2"></td>
                                        <td colspan="3"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">
                                            Người kiểm
                                            <br />
                                            検査
                                        </td>
                                        <td colspan="2"></td>
                                        <td colspan="3"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-center">
                                            (Thời gian) 時間
                                        </td>
                                        <td colspan="2">
                                            {{ $lotNo['date_time'] }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="sendStampId" value="{{ $sendStamp->id }}" />
    @if (isset($binArray))
        <div class="no-print">
            <a
                class="btn btn-secondary"
                id="save-print"
                data-url="{{ route('admin.stamp.save.print') }}"
                href="#"
            >
                Print
            </a>
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        var lastPrintTime = null; // Biến lưu thời gian lần in gần nhất
        var isPrintShortcutActivated = false; // Biến theo dõi trạng thái nhấn Ctrl + P

        function savePrint(sendStampId, callback) {
            var url = $('#save-print').data('url');

            if (sendStampId) {
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        sendStampId: sendStampId,
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
            var sendStampId = $('#sendStampId').val(); // Lấy sendStampId từ input ẩn hoặc DOM
            var currentTime = new Date().getTime();

            if (!sendStampId) {
                Swal.fire({
                    title: 'Lỗi!',
                    text: 'Không tìm thấy thông tin in. Vui lòng kiểm tra lại.',
                    icon: 'error',
                    confirmButtonText: 'Đóng',
                });
                return;
            }

            if (lastPrintTime === null) {
                lastPrintTime = currentTime;
                savePrint(sendStampId, function () {
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
                            savePrint(sendStampId, function () {
                                setTimeout(function () {
                                    window.print();
                                    isPrintShortcutActivated = false; // Reset trạng thái sau khi in
                                }, 100);
                            });
                        }
                    });
                } else {
                    lastPrintTime = currentTime;
                    savePrint(sendStampId, function () {
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
                        console.log('Ctrl + Shift + P được nhấn!');
                    }
                }
            });

            $('#save-print').click(function (event) {
                event.preventDefault();
                handlePrint(); // Gọi hàm in khi nhấn nút
            });
        });
    </script>
@endsection
