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
                        {{--
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
                            Cân chỉnh để camera có thể nhận diện mã QR CODE rõ ràng.
                            </p>
                            <p class="fs-6">
                            <span class="fw-bold">Bước 3:</span>
                            Đợi đến khi có thông báo quét mã.
                            </p>
                            <p class="fs-6">
                            <span class="fw-bold">Lưu ý:</span>
                            Khoảng nghĩ giữa 2 lần quét mã QR CODE thành công là
                            <span class="text-danger">5 giây</span>
                            .
                            </p>
                            </div>
                            </div>
                            </div>
                        --}}
                        <div class="row">
                            <div class="col-12">
                                <div id="reader-wrapper">
                                    <div id="reader" data-url="{{ route('admin.barcode.checkQr') }}"></div>
                                </div>
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
        let startApi = true
        let cachedVoice = null
        const html5QrCode = new Html5Qrcode('reader')
        const ctx = new (window.AudioContext || window.webkitAudioContext)()

        // 🔊 Phát âm thanh: success hoặc error
        function playBeep(type = 'success', callback) {
            const oscillator = ctx.createOscillator()
            const gain = ctx.createGain()

            oscillator.type = 'sine'

            if (type === 'success') {
                oscillator.frequency.setValueAtTime(880, ctx.currentTime) // âm cao
                gain.gain.setValueAtTime(0.7, ctx.currentTime)
                oscillator.connect(gain)
                gain.connect(ctx.destination)
                oscillator.start()
                oscillator.stop(ctx.currentTime + 0.2)
            } else if (type === 'error' || type === 'cancel') {
                oscillator.frequency.setValueAtTime(300, ctx.currentTime) // âm thấp
                gain.gain.setValueAtTime(0.6, ctx.currentTime)
                oscillator.connect(gain)
                gain.connect(ctx.destination)
                oscillator.start()
                oscillator.stop(ctx.currentTime + 0.5)
            }

            oscillator.onended = () => {
                if (typeof callback === 'function') callback()
            }
        }

        // 🗣️ Đọc giọng kèm alert nếu cần
        function speakText(text, afterAlertCallback = null, showAlert = false) {
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

            if (showAlert) {
                setTimeout(() => {
                    alert(text)
                    if (typeof afterAlertCallback === 'function') {
                        afterAlertCallback() // chạy sau khi bấm OK
                    }
                }, 100)
            } else {
                utterance.onend = () => {
                    if (typeof afterAlertCallback === 'function') afterAlertCallback()
                }
            }
        }
        // Tải giọng nói và bắt đầu quét QR
        function loadVoicesAndStartQR() {
            const synth = window.speechSynthesis
            let voices = synth.getVoices()
            if (voices.length > 0) {
                cachedVoice =
                    voices.find((v) => v.lang === 'vi-VN' && v.name.includes('Google')) ||
                    voices.find((v) => v.lang === 'vi-VN')
                startQRScanner()
            } else {
                window.speechSynthesis.onvoiceschanged = () => {
                    voices = synth.getVoices()
                    cachedVoice =
                        voices.find((v) => v.lang === 'vi-VN' && v.name.includes('Google')) ||
                        voices.find((v) => v.lang === 'vi-VN')
                    startQRScanner()
                }
            }
        }

        function startQRScanner() {
            html5QrCode
                .start(
                    {
                        facingMode: 'environment',
                    },
                    {
                        fps: 10,
                        qrbox: 300,
                    },
                    onScanSuccess,
                )
                .catch((err) => {
                    console.error('❌ Lỗi khởi động camera:', err)
                    playBeep('cancel', () => {
                        speakText('Không thể khởi động camera', null, true)
                    })
                })
        }

        function onScanSuccess(decodedText, decodedResult) {
            if (!startApi) return

            const invalidCodes = ['*!', "U8'48*("]
            if (!decodedText || invalidCodes.includes(decodedText)) return

            const match = decodedText.match(/\b([A-Z0-9]{5})\b/)
            const code = match ? match[1] : null

            if (!code) {
                playBeep('cancel', () => {
                    speakText(
                        'Không tìm thấy mã hợp lệ trong mã QR',
                        () => {
                            location.reload()
                        },
                        true,
                    )
                })
                return
            }

            startApi = false

            playBeep('success', () => {
                const url = document.querySelector('#reader').dataset.url

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
                            const productId = data.product_id
                            const productName = data.product_name

                            sessionStorage.setItem('product_id', productId)

                            speakText(
                                'Đã quét thành công sản phẩm ' + productName + '. Vui lòng quét mã vạch sản phẩm',
                                () => {
                                    html5QrCode
                                        .stop()
                                        .then(() => {
                                            window.location.href = '{{ route('admin.barcode.scan') }}'
                                        })
                                        .catch((err) => {
                                            console.error('❌ Lỗi dừng camera:', err)
                                            playBeep('cancel', () => {
                                                speakText(
                                                    'Lỗi dừng camera, đang chuyển trang',
                                                    () => {
                                                        window.location.href = '{{ route('admin.barcode.scan') }}'
                                                    },
                                                    true,
                                                )
                                            })
                                        })
                                },
                                true,
                            )
                        } else {
                            playBeep('cancel', () => {
                                speakText(
                                    'Không thành công: ' + data.message,
                                    () => {
                                        startApi = true
                                    },
                                    true,
                                )
                            })
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('❌ Lỗi kết nối:', error)
                        playBeep('cancel', () => {
                            speakText(
                                'Có lỗi khi gửi mã QR',
                                () => {
                                    startApi = true
                                },
                                true,
                            )
                        })
                    },
                })
            })
        }

        loadVoicesAndStartQR()
    </script>
@endsection
