@extends('layouts.'.$layout)
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/scan-qrcode.css') }}" />
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
                                    id="reader"
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
        import { Html5Qrcode } from 'html5-qrcode';
        document.addEventListener('DOMContentLoaded', function () {
            let startApi = true;

            const reader = document.getElementById('reader');
            const url = reader.dataset.url;

            const html5QrCode = new Html5Qrcode('reader');

            const onScanSuccess = (decodedText, decodedResult) => {
                if (
                    decodedText &&
                    decodedText !== '*!' &&
                    decodedText !== "U8'48*("
                ) {
                    console.log('✅ Mã quét:', decodedText);

                    if (startApi) {
                        fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document
                                    .querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                            },
                            body: JSON.stringify({
                                barcode: decodedText,
                            }),
                        })
                            .then((res) => res.json())
                            .then((response) => {
                                switch (response.status) {
                                    case 200:
                                        alert(
                                            '✅ Cập nhật thành công. Đợi 5 giây...',
                                        );
                                        break;
                                    case 400:
                                        alert('⚠️ Mã đã được quét.');
                                        break;
                                    case 404:
                                        alert('❌ Không tìm thấy sản phẩm.');
                                        break;
                                    default:
                                        alert('⚠️ Lỗi không xác định.');
                                        break;
                                }
                            })
                            .catch((err) => console.error('Lỗi gửi mã:', err));

                        startApi = false;
                        setTimeout(() => {
                            startApi = true;
                        }, 5000);
                    }
                }
            };

            Html5Qrcode.getCameras()
                .then((devices) => {
                    if (devices && devices.length) {
                        const cameraId = devices[0].id;
                        html5QrCode
                            .start(
                                {
                                    deviceId: {
                                        exact: cameraId,
                                    },
                                },
                                {
                                    fps: 10,
                                    qrbox: 250,
                                },
                                onScanSuccess,
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
