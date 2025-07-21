@extends('layouts.layout')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="container-fluid px-3">
        <div class="row gx-0">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header p-1 position-relative mt-n1 mx-1">
                        <div class="border-radius-lg ps-2 pt-4 pb-3">
                            <h4 class="card-title mb-0">Quét Sản Phẩm</h4>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <a class="btn btn-danger" href="{{ route('admin.home') }}">Trang chủ</a>

                            <a class="btn btn-success" href="{{ route('admin.storage.index') }}">Sản phẩm đã quét</a>
                        </div>

                        <p class="fs-5 text-muted text-center">Vui lòng dùng máy quét để quét sản phẩm</p>

                        <input
                            type="text"
                            id="scanner-input"
                            class="form-control text-center mb-4"
                            placeholder="Quét mã tại đây..."
                            autofocus />

                        <div id="scan-notification-box" class="alert d-none mt-3 text-center" role="alert"></div>

                        <input type="hidden" id="employee-id" value="{{ auth()->user()->id }}" />

                        <!-- JS + Pusher -->
                        <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
                        <script>
                            const products =
                                {!! json_encode($products->mapWithKeys(fn ($p) => [$p->code => ['id' => $p->id, 'name' => $p->name]])->toArray()) !!};
                            const scannerInput = document.getElementById('scanner-input');
                            const employeeId = document.getElementById('employee-id')?.value || null;
                            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                            let lastQrScanned = null;
                            let alertTimeout = null;
                            let inputDelayTimeout = null;

                            function keepFocus() {
                                scannerInput.focus();
                            }
                            window.addEventListener('load', keepFocus);
                            scannerInput.addEventListener('blur', () => setTimeout(keepFocus, 100));

                            function showAlert(boxId, type, message, isBarcodeSuccess = false) {
                                const box = document.getElementById(boxId);
                                if (alertTimeout) clearTimeout(alertTimeout);

                                box.className = '';
                                box.classList.add('alert', 'mt-3', `alert-${type}`, 'text-center');
                                box.innerHTML = message;
                                box.classList.remove('d-none');

                                const delay = isBarcodeSuccess ? 5000 : 3000;
                                alertTimeout = setTimeout(() => box.classList.add('d-none'), delay);
                            }

                            function extractProductFromString(input) {
                                for (const code in products) {
                                    if (input.includes(code)) {
                                        return {
                                            code,
                                            ...products[code],
                                        };
                                    }
                                }
                                return null;
                            }

                            scannerInput.addEventListener('input', function () {
                                const rawInput = scannerInput.value.trim();
                                if (inputDelayTimeout) clearTimeout(inputDelayTimeout);

                                inputDelayTimeout = setTimeout(() => {
                                    if (!rawInput) return;
                                    scannerInput.value = '';

                                    const isBarcode = rawInput.includes('a');
                                    const qrMatch = !isBarcode ? extractProductFromString(rawInput) : null;

                                    if (!isBarcode && !qrMatch) {
                                        showAlert(
                                            'scan-notification-box',
                                            'warning',
                                            'Không xác định được mã QR hợp lệ.',
                                        );
                                        return;
                                    }

                                    if (!isBarcode && qrMatch && lastQrScanned) {
                                        showAlert(
                                            'scan-notification-box',
                                            'warning',
                                            'Bạn đã quét mã QR rồi. Vui lòng quét mã <strong>Barcode</strong> tiếp theo.',
                                        );
                                        return;
                                    }

                                    if (isBarcode && !lastQrScanned) {
                                        showAlert(
                                            'scan-notification-box',
                                            'warning',
                                            'Vui lòng quét mã QR trước khi quét Barcode.',
                                        );
                                        return;
                                    }

                                    if (isBarcode && lastQrScanned) {
                                        const productIdFromBarcode = rawInput.split('a')[0];
                                        if (productIdFromBarcode !== String(lastQrScanned.id)) {
                                            const barcodeName =
                                                Object.values(products).find((p) => p.id == productIdFromBarcode)
                                                    ?.name || `ID ${productIdFromBarcode}`;
                                            showAlert(
                                                'scan-notification-box',
                                                'danger',
                                                `Mã QR là <strong>${lastQrScanned.name}</strong>, nhưng barcode là <strong>${barcodeName}</strong>. Kiểm tra lại!`,
                                            );
                                            return;
                                        }
                                    }

                                    const url = isBarcode
                                        ? '{{ route('admin.scan.barcode') }}'
                                        : '{{ route('admin.scan.qr') }}';
                                    const payload = isBarcode
                                        ? {
                                              barcode: rawInput,
                                              employee_id: employeeId,
                                          }
                                        : {
                                              qr_code: qrMatch.code,
                                              employee_id: employeeId,
                                          };

                                    fetch(url, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': csrf,
                                            Accept: 'application/json',
                                        },
                                        body: JSON.stringify(payload),
                                    })
                                        .then((res) => res.json())
                                        .then((data) => {
                                            let msg = '',
                                                type = 'info',
                                                isBarcodeSuccess = false;

                                            switch (data.status) {
                                                case 200:
                                                    if (isBarcode) {
                                                        msg = `Đã xuất thành công sản phẩm <strong>${data.product_name}</strong>`;
                                                        isBarcodeSuccess = true;
                                                        lastQrScanned = null;
                                                    } else {
                                                        msg = `Đã quét sản phẩm: <strong>${data.product_name}.</strong> Vui lòng quét mã <strong>Barcode</strong> tiếp theo.`;
                                                        lastQrScanned = {
                                                            id: data.product_id,
                                                            name: data.product_name,
                                                        };
                                                    }
                                                    type = 'success';
                                                    break;
                                                case 400:
                                                    msg = 'Không nhận được mã.';
                                                    type = 'warning';
                                                    break;
                                                case 404:
                                                    msg = 'Không tìm thấy sản phẩm.';
                                                    type = 'danger';
                                                    break;
                                                case 409:
                                                    msg = 'LOT đã tồn tại, không thể lưu trùng.';
                                                    type = 'warning';
                                                    break;
                                                case 422:
                                                    msg = 'Mã barcode không hợp lệ.';
                                                    type = 'danger';
                                                    break;
                                                default:
                                                    msg = `Lỗi không xác định (status ${data.status})`;
                                                    type = 'danger';
                                            }

                                            showAlert('scan-notification-box', type, msg, isBarcodeSuccess);
                                        })
                                        .catch((error) => {
                                            console.error('Lỗi khi gửi dữ liệu:', error);
                                            showAlert('scan-notification-box', 'danger', 'Đã xảy ra lỗi khi xử lý.');
                                        });
                                }, 50);
                            });

                            // === Pusher ===
                            const pusher = new Pusher('4c79f3c4bd6485b77f25', {
                                cluster: 'ap1',
                                forceTLS: true,
                            });

                            const qrChannel = pusher.subscribe('qr-channel');
                            qrChannel.bind('qr.scanned', function (data) {
                                const p = data.product;
                                if (p.status === 200) {
                                    lastQrScanned = {
                                        id: p.id,
                                        name: p.name,
                                    };
                                }

                                let msg = '';
                                switch (p.status) {
                                    case 200:
                                        msg = `Đã quét sản phẩm: <strong>${p.name}</strong>`;
                                        break;
                                    case 400:
                                        msg = 'Không nhận được mã QR';
                                        break;
                                    case 404:
                                        msg = 'Không tìm thấy sản phẩm';
                                        break;
                                    default:
                                        msg = `Lỗi xử lý QR (status ${p.status})`;
                                }

                                const type = p.status === 200 ? 'success' : p.status === 400 ? 'warning' : 'danger';
                                showAlert('scan-notification-box', type, msg);
                            });

                            const barcodeChannel = pusher.subscribe('barcode-channel');
                            barcodeChannel.bind('barcode.scanned', function (data) {
                                const p = data.product;
                                let msg = '',
                                    type = 'info';

                                if (p.status === 200) {
                                    lastQrScanned = null;
                                    msg = `Đã xuất thành công sản phẩm <strong>${p.name}</strong>`;
                                    type = 'success';
                                } else {
                                    switch (p.status) {
                                        case 409:
                                            msg = 'LOT đã tồn tại, không thể lưu trùng';
                                            type = 'warning';
                                            break;
                                        case 404:
                                            msg = 'Không tìm thấy sản phẩm';
                                            type = 'danger';
                                            break;
                                        case 422:
                                            msg = 'Barcode không hợp lệ';
                                            type = 'danger';
                                            break;
                                        default:
                                            msg = `Lỗi xử lý barcode (status ${p.status})`;
                                            type = 'danger';
                                    }
                                }

                                const isBarcodeSuccess = p.status === 200;
                                showAlert('scan-notification-box', type, msg, isBarcodeSuccess);
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
