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
                                <div id="reader" data-url="{{ route('admin.barcode.checkQr') }}"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div>
                                <a class="btn btn-danger" href="{{ route('admin.home') }}">Trang chủ</a>
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
        const ctx = new (window.AudioContext || window.webkitAudioContext)();

        // 🔊 Phát âm thanh: success hoặc error
        function playBeep(type = 'success', callback) {
            const oscillator = ctx.createOscillator();
            const gain = ctx.createGain();

            oscillator.type = 'sine';

            if (type === 'success') {
                oscillator.frequency.setValueAtTime(880, ctx.currentTime);
                gain.gain.setValueAtTime(0.7, ctx.currentTime);
            } else {
                oscillator.frequency.setValueAtTime(300, ctx.currentTime);
                gain.gain.setValueAtTime(0.6, ctx.currentTime);
            }

            oscillator.connect(gain);
            gain.connect(ctx.destination);

            oscillator.start();
            oscillator.stop(ctx.currentTime + (type === 'success' ? 0.2 : 0.5));

            oscillator.onended = () => {
                if (typeof callback === 'function') callback();
            };
        }

        function showAlertBeep(message, type = 'error', callback = null) {
            playBeep(type, () => {
                setTimeout(() => {
                    alert(message);
                    if (typeof callback === 'function') callback();
                }, 100);
            });
        }

        function startQRScanner() {
            html5QrCode
                .start(
                    {
                        facingMode: 'environment',
                    },
                    {
                        fps: 30,
                        qrbox: 120,
                    },
                    onScanSuccess,
                )
                .catch((err) => {
                    console.error('❌ Lỗi khởi động camera:', err);
                    showAlertBeep('Không thể khởi động camera', 'cancel');
                });
        }

        function onScanSuccess(decodedText, decodedResult) {
            if (!startApi) return;

            const invalidCodes = ['*!', "U8'48*("];
            if (!decodedText || invalidCodes.includes(decodedText)) return;

            const match = decodedText.match(/\b([A-Z0-9]{5})\b/);
            const code = match ? match[1] : null;

            if (!code) {
                showAlertBeep('Không tìm thấy mã hợp lệ trong mã QR', 'cancel', () => {});
                return;
            }

            startApi = false;

            playBeep('success', () => {
                const url = document.querySelector('#reader').dataset.url;

                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    contentType: 'application/json',
                    data: JSON.stringify({
                        qr_code: code,
                    }),
                    success: function (data) {
                        if (data.status === 200) {
                            const productId = data.product_id;
                            const productName = data.product_name;

                            sessionStorage.setItem('product_id', productId);

                            showAlertBeep(
                                'Đã quét thành công sản phẩm ' + productName + '. Vui lòng quét mã vạch sản phẩm',
                                'success',
                                () => {
                                    html5QrCode
                                        .stop()
                                        .then(() => {
                                            window.location.href = '{{ route('admin.barcode.scan') }}';
                                        })
                                        .catch((err) => {
                                            console.error('❌ Lỗi dừng camera:', err);
                                            showAlertBeep('Lỗi dừng camera, đang chuyển trang', 'cancel', () => {
                                                window.location.href = '{{ route('admin.barcode.scan') }}';
                                            });
                                        });
                                },
                            );
                        } else {
                            showAlertBeep('Không thành công: ' + data.message, 'cancel', () => {
                                startApi = true;
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('❌ Lỗi kết nối:', error);
                        showAlertBeep('Có lỗi khi gửi mã QR', 'cancel', () => {
                            startApi = true;
                        });
                    },
                });
            });
        }

        // Bắt đầu quét ngay (không cần chờ tải giọng nói)
        startQRScanner();
    </script>
@endsection
