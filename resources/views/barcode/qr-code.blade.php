@extends('layouts.'.$layout)

{{--
    @section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/scan-qrcode.css') }}" />
    @endsection
--}}

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Quét QR CODE</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <h2 class="fs-2 mb-3">
                                        <span class="text-danger">*</span>
                                        Hướng dẫn quét QR CODE:
                                    </h2>
                                    <p class="fs-6">
                                        <span class="fw-bold">Bước 1:</span>
                                        Đưa mã QR CODE vào khung màn hình quét.
                                    </p>
                                    <p class="fs-6">
                                        <span class="fw-bold">Bước 2:</span>
                                        Cân chỉnh để camera có thể nhận diện mã
                                        QR CODE rõ ràng.
                                    </p>
                                    <p class="fs-6">
                                        <span class="fw-bold">Bước 3:</span>
                                        Đợi đến khi có thông báo quét mã.
                                    </p>
                                    <p class="fs-6">
                                        <span class="fw-bold">Lưu ý:</span>
                                        Khoảng nghĩ giữa 2 lần quét mã QR CODE
                                        thành công là
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
                                    data-url="{{ route('admin.barcode.checkQr') }}"
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
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        let startApi = true;
        const html5QrCode = new Html5Qrcode('reader');

        function onScanSuccess(decodedText, decodedResult) {
            if (!startApi) return; // 🔁 Ngăn quét nhiều lần
            const invalidCodes = ['*!', "U8'48*("];
            if (!decodedText || invalidCodes.includes(decodedText)) return;

            const match = decodedText.match(/\b([A-Z0-9]{5})\b/);
            const code = match ? match[1] : null;

            if (code) {
                startApi = false; // ❌ Tắt quét tiếp

                alert('✅ Đã quét mã: ' + code);
                const url = document.querySelector('#reader').dataset.url;
                // 👉 Gửi lên server để lấy product_id
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        qr_code: code,
                    }),
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.status === 200) {
                            const productId = data.product_id;

                            // ✅ Lưu product_id vào sessionStorage
                            sessionStorage.setItem('product_id', productId);

                            // ✅ Ngừng camera rồi chuyển trang
                            html5QrCode
                                .stop()
                                .then(() => {
                                    window.location.href =
                                        '{{ route('admin.barcode.scan') }}';
                                })
                                .catch((err) => {
                                    console.error('❌ Lỗi dừng camera:', err);
                                    window.location.href =
                                        '{{ route('admin.barcode.scan') }}';
                                });
                        } else {
                            alert('❌ ' + data.message);
                            startApi = true; // Cho phép quét lại
                        }
                    })
                    .catch((err) => {
                        console.error('❌ Lỗi kết nối:', err);
                        alert('⚠️ Lỗi khi gửi mã QR!');
                        startApi = true;
                    });
            } else {
                alert('⚠️ Không tìm thấy mã hợp lệ trong QR!');
            }
        }

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
            )
            .catch((err) => {
                console.error('❌ Lỗi khởi động camera:', err);
            });
    </script>
@endsection
