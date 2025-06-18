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
                        {{--
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
                            Cân chỉnh để camera có thể nhận diện mã vạch rõ ràng.
                            </p>
                            <p class="fs-6">
                            <span class="fw-bold">Bước 3:</span>
                            Đợi đến khi có thông báo quét mã.
                            </p>
                            <p class="fs-6">
                            <span class="fw-bold">Lưu ý:</span>
                            Khoảng nghĩ giữa 2 lần quét mã thành công là
                            <span class="text-danger">5 giây</span>
                            .
                            </p>
                            </div>
                            </div>
                            </div>
                        --}}
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
            let cachedVoice = null

            // 🗣️ Giọng nói + alert (sau vài mili-giây) + callback sau alert
            function speakText(text, afterAlertCallback = null) {
                const synth = window.speechSynthesis
                if (synth.speaking) synth.cancel()

                const utterance = new SpeechSynthesisUtterance(text)
                utterance.lang = 'vi-VN'
                utterance.pitch = 1
                utterance.rate = 1.4
                utterance.volume = 1

                if (cachedVoice) {
                    utterance.voice = cachedVoice
                } else {
                    const voices = synth.getVoices().filter((v) => v.lang === 'vi-VN')
                    if (voices.length > 0) {
                        cachedVoice = voices.find((v) => v.name.includes('Google')) || voices[0]
                        utterance.voice = cachedVoice
                    }
                }

                synth.speak(utterance)

                // ✅ Hiện alert sau vài mili-giây để không block voice
                setTimeout(() => {
                    alert(text)
                    if (typeof afterAlertCallback === 'function') {
                        afterAlertCallback()
                    }
                }, 100)
            }

            window.speechSynthesis.getVoices() // tải trước giọng

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
                        html5QrCode.stop()
                        startApi = false
                        const msg = 'Vui lòng quét mã QR sản phẩm trước khi quét mã vạch'
                        speakText(msg, () => {
                            window.location.href = '{{ route('admin.barcode.scanQr') }}'
                        })
                        return
                    }

                    if (storedProductId != prefix) {
                        html5QrCode.stop()
                        startApi = false
                        const msg = 'Mã vạch không khớp với sản phẩm đã chọn. Vui lòng quét lại.'
                        speakText(msg, () => {
                            location.reload()
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
                                        speakText('Cập nhật thành công', () => {
                                            sessionStorage.removeItem('product_id')
                                            window.location.href = '{{ route('admin.barcode.scanQr') }}'
                                        })
                                        break

                                    case 400:
                                        speakText('Mã này đã được quét rồi', () => {
                                            location.reload()
                                        })
                                        break

                                    case 404:
                                        speakText('Không tìm thấy sản phẩm', () => {
                                            location.reload()
                                        })
                                        break

                                    case 500:
                                        speakText('Mã không hợp lệ', () => {
                                            location.reload()
                                        })
                                        break

                                    default:
                                        speakText('Có lỗi xảy ra', () => {
                                            location.reload()
                                        })
                                        break
                                }
                            },
                            error: function () {
                                speakText('Không thể gửi mã vạch đến máy chủ')
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
