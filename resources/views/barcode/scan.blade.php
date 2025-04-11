@extends('layouts.'.$layout)
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/scan-barcode.css') }}" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Quét Mã Vạch</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsives">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <h2 class="fs-2 mb-3">
                                        <span class="text-danger">*</span>
                                        Hướng dẫn quét mã vạch:
                                    </h2>
                                    <p class="fs-6">
                                        <span class="fw-bold">Bước 1:</span>
                                        Đưa mã vạch vào khung màn hình quét.
                                    </p>
                                    <p class="fs-6">
                                        <span class="fw-bold">Bước 2:</span>
                                        Cân chỉnh để camera có thể nhận diện mã
                                        vạch rõ ràng.
                                    </p>
                                    <p class="fs-6">
                                        <span class="fw-bold">Bước 3:</span>
                                        Đợi đến khi có thông báo quét mã.
                                    </p>
                                    <p class="fs-6">
                                        <span class="fw-bold">Lưu ý:</span>
                                        Khoảng nghĩ giữa 2 lần quét mã thành
                                        công là
                                        <span class="text-danger">5 giây</span>
                                        .
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div
                                    id="interactive"
                                    data-url="{{ route('admin.barcode.check') }}"
                                    class="viewport"
                                ></div>
                            </div>
                        </div>
                        <div class="row">
                            <div>
                                <a
                                    class="btn btn-danger"
                                    href="{{ route('admin.home') }}"
                                >
                                    Trang chủ
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var startApi = true;

            Quagga.init(
                {
                    inputStream: {
                        name: 'Live',
                        type: 'LiveStream',
                        target: document.querySelector('#interactive'),
                        constraints: {
                            facingMode: 'environment',
                        },
                    },
                    decoder: {
                        readers: ['code_128_reader'],
                    },
                },
                function (err) {
                    if (err) {
                        return;
                    }
                    Quagga.start();
                },
            );

            Quagga.onDetected(function (result) {
                if (
                    result &&
                    result.codeResult.code !== '' &&
                    result.codeResult.code !== '*!' &&
                    result.codeResult.code !== "U8'48*("
                ) {
                    var code = result.codeResult.code;
                    var prefix = code.substring(0, 2); // 🟡 Lấy 2 số đầu
                    var storedProductId = sessionStorage.getItem('product_id'); // 🟢 Đã lưu sau quét QR

                    if (!storedProductId) {
                        Quagga.stop();
                        startApi = false;
                        alert(
                            '⚠️ Vui lòng quét mã QR sản phẩm trước khi quét mã barcode!',
                        );
                        location.reload();
                        return;
                    }

                    if (storedProductId != prefix) {
                        Quagga.stop();
                        startApi = false;
                        alert(
                            '⚠️ Mã barcode không khớp với sản phẩm đã chọn! Vui lòng quét lại.',
                        );
                        location.reload();
                        return;
                    }

                    // ✅ Trùng thì gửi API tiếp
                    if (startApi) {
                        var url = $('#interactive').data('url');

                        $.ajax({
                            url: url,
                            method: 'POST',
                            data: {
                                barcode: code,
                                _token: '{{ csrf_token() }}',
                            },
                            success: function (response) {
                                var status = response.status;

                                switch (status) {
                                    case 200:
                                        // 👉 Hỏi người dùng muốn đến đâu sau khi quét thành công
                                        const goToScanQr = confirm(
                                            '✅ Cập nhật thành công! Nhấn "OK" để đến trang QUÉT QR, hoặc "Hủy" để về TRANG CHÍNH.',
                                        );

                                        sessionStorage.removeItem('product_id'); // 🧹 Xóa sau khi quét xong

                                        if (goToScanQr) {
                                            window.location.href =
                                                '{{ route('admin.barcode.scanQr') }}';
                                        } else {
                                            window.location.href =
                                                '{{ route('admin.home') }}';
                                        }
                                        break;

                                    case 400:
                                        alert('⚠️ Mã này đã được quét rồi!');
                                        location.reload(); // 🔄 Refresh
                                        break;

                                    case 404:
                                        alert('❌ Không tìm thấy sản phẩm!');
                                        location.reload(); // 🔄 Refresh
                                        break;

                                    case 500:
                                        alert('❌ Mã không hợp lệ!');
                                        location.reload(); // 🔄 Refresh
                                        break;

                                    default:
                                        console.warn(
                                            '⚠️ Trạng thái không xác định:',
                                            status,
                                        );
                                        location.reload(); // 🔄 Refresh fallback
                                        break;
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error('❌ Lỗi khi gửi barcode:', error);
                            },
                        });

                        startApi = false;
                        setTimeout(function () {
                            startApi = true;
                        }, 5000);
                    }
                } else {
                    console.warn('🚫 Mã không hợp lệ hoặc bị từ chối');
                }
            });
        });
    </script>
@endsection
