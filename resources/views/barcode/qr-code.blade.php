@extends('layouts.'.$layout)
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/scan-barcode.css') }}" />
    <script src="https://unpkg.com/html5-qrcode@2.3.8/minified/html5-qrcode.min.js"></script>
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
            let startApi = true;

            const reader = document.getElementById('interactive');
            const url = reader.dataset.url;

            const html5QrCode = new Html5Qrcode('interactive');

            function onScanSuccess(decodedText, decodedResult) {
                if (
                    decodedText &&
                    decodedText !== '' &&
                    decodedText !== '*!' &&
                    decodedText !== "U8'48*("
                ) {
                    console.log('Mã quét:', decodedText);

                    if (startApi) {
                        $.ajax({
                            url: url,
                            method: 'POST',
                            data: {
                                barcode: decodedText,
                                _token: '{{ csrf_token() }}',
                            },
                            success: function (response) {
                                console.log(response.status);
                                switch (response.status) {
                                    case 200:
                                        alert(
                                            '✅ Cập nhật Sản lượng xuất hàng thành công, vui lòng nhấn OK và đợi 5s để tiếp tục quét mã!',
                                        );
                                        break;
                                    case 400:
                                        alert(
                                            '⚠️ Mã này đã được quét, vui lòng thử lại!',
                                        );
                                        break;
                                    case 404:
                                        alert('❌ Không tìm thấy sản phẩm!');
                                        break;
                                    case 500:
                                        alert('❌ Mã này không hợp lệ!');
                                        break;
                                    default:
                                        alert('Lỗi không xác định!');
                                        break;
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error(
                                    'Đã xảy ra lỗi khi gửi barcode:',
                                    error,
                                );
                            },
                        });

                        startApi = false;
                        setTimeout(function () {
                            console.log('⏱️ Chờ 5 giây...');
                            startApi = true;
                        }, 5000);
                    }
                }
            }

            Html5Qrcode.getCameras()
                .then((devices) => {
                    if (devices && devices.length) {
                        var cameraId = devices[0].id;
                        html5QrCode
                            .start(
                                {
                                    facingMode: 'environment',
                                },
                                {
                                    fps: 10,
                                    qrbox: 250,
                                },
                                onScanSuccess,
                                (errorMessage) => {
                                    // console.log('Scan error', errorMessage);
                                },
                            )
                            .catch((err) => {
                                console.error(
                                    'Không thể khởi động camera:',
                                    err,
                                );
                            });
                    }
                })
                .catch((err) => {
                    console.error('Không thể truy cập camera:', err);
                });
        });
    </script>
@endsection
