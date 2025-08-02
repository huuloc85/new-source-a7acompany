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
                                <div id="reader" data-url="{{ route('admin.barcode.check') }}"></div>
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
        document.addEventListener('DOMContentLoaded', function () {
            let startApi = true

            // ✅ Hàm phát tiếng "bíp" bằng Web Audio API
            function playBeep(success = true) {
                const context = new (window.AudioContext || window.webkitAudioContext)()
                const oscillator = context.createOscillator()
                const gainNode = context.createGain()

                oscillator.type = 'square'
                oscillator.frequency.setValueAtTime(success ? 1000 : 300, context.currentTime)
                gainNode.gain.setValueAtTime(0.1, context.currentTime)

                oscillator.connect(gainNode)
                gainNode.connect(context.destination)

                oscillator.start()
                oscillator.stop(context.currentTime + 0.2)
            }

            // ✅ Thay alert có voice bằng alert + bíp
            function beepAndAlert(success, message, callback = null) {
                playBeep(success)

                setTimeout(() => {
                    alert(message)
                    if (typeof callback === 'function') {
                        callback()
                    }
                }, 100)
            }

            const html5QrCode = new Html5Qrcode('reader')

            html5QrCode.start(
                {
                    facingMode: 'environment',
                },
                {
                    fps: 10,
                    qrbox: {
                        width: 200,
                        height: 100,
                    },
                    formatsToSupport: [Html5QrcodeSupportedFormats.CODE_128],
                },
                function (code) {
                    if (!code || code === '*!' || code === "U8'48*(") return

                    const prefix = code.substring(0, 2)
                    const storedProductId = sessionStorage.getItem('product_id')

                    if (!storedProductId) {
                        startApi = false
                        html5QrCode
                            .stop()
                            .then(() => {
                                const msg = 'Vui lòng quét mã QR sản phẩm trước khi quét mã vạch'
                                beepAndAlert(false, msg, () => {
                                    window.location.href = '{{ route('admin.barcode.scanQr') }}'
                                })
                            })
                            .catch((err) => {
                                console.error('❌ Lỗi dừng camera:', err)
                            })
                        return
                    }
                    if (storedProductId != prefix) {
                        startApi = false
                        html5QrCode
                            .stop()
                            .then(() => {
                                const msg = 'Mã vạch không khớp với sản phẩm đã chọn. Vui lòng quét lại.'
                                beepAndAlert(false, msg, () => {
                                    location.reload()
                                })
                            })
                            .catch((err) => {
                                console.error('❌ Lỗi dừng camera:', err)
                            })
                        return
                    }

                    if (startApi) {
                        const url = document.querySelector('#reader').dataset.url

                        $.ajax({
                            url: url,
                            method: 'POST',
                            data: {
                                barcode: code,
                                _token: '{{ csrf_token() }}',
                            },
                            success: function (response) {
                                switch (response.status) {
                                    case 200:
                                        beepAndAlert(true, 'Cập nhật thành công', () => {
                                            sessionStorage.removeItem('product_id')
                                            window.location.href = '{{ route('admin.barcode.scanQr') }}'
                                        })
                                        break

                                    case 400:
                                        beepAndAlert(false, 'Mã này đã được quét rồi', () => {
                                            location.reload()
                                        })
                                        break

                                    case 404:
                                        beepAndAlert(false, 'Không tìm thấy sản phẩm', () => {
                                            location.reload()
                                        })
                                        break

                                    case 500:
                                        beepAndAlert(false, 'Mã không hợp lệ', () => {
                                            location.reload()
                                        })
                                        break

                                    default:
                                        beepAndAlert(false, 'Có lỗi xảy ra', () => {
                                            location.reload()
                                        })
                                        break
                                }
                            },
                            error: function () {
                                beepAndAlert(false, 'Không thể gửi mã vạch đến máy chủ')
                            },
                        })

                        startApi = false
                        setTimeout(() => (startApi = true), 5000)
                    }
                },
                function () {
                    // Không log lỗi camera liên tục
                },
            )
        })
    </script>
@endsection
