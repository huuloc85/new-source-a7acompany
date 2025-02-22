@extends('layouts.'.$layout)

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/add-barcode.css') }}" />
@endsection

@section('content')
    <div class="row">
        <div class="col-12 print-container">
            <div class="card my-4">
                <div
                    class="card-header p-1 position-relative mt-n1 mx-1 no-print"
                >
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">In Tem Thùng</h4>
                    </div>
                </div>
                <div class="px-0 pb-2 my-3">
                    <div class="table-responsive p-4">
                        <div>
                            <div class="container-gird">
                                <div class="grid-container">
                                    @if (isset($binArray))
                                        @foreach ($binArray as $key => $bin)
                                            <div class="container grid-item">
                                                @if ($bin['bin'] != 'xxxx')
                                                    <table
                                                        class="table table-bordered"
                                                    >
                                                        <tr>
                                                            <td
                                                                colspan="1"
                                                                class="align-content-center"
                                                            >
                                                                <img
                                                                    src="{{ asset('assets/img/logos/VVP.png') }}"
                                                                    alt=""
                                                                    width="110"
                                                                    title="VINH VINH PHAT ONE MEMBER CO.LTD"
                                                                />
                                                                {{--
                                                                    <div class="logo-text qr-add">VINH VINH PHAT ONE MEMBER
                                                                    CO. LTD</div>
                                                                --}}
                                                            </td>
                                                            <td
                                                                colspan="4"
                                                                class="align-content-center"
                                                            >
                                                                <div
                                                                    class="qrcode-img"
                                                                >
                                                                    VINH VINH
                                                                    PHAT ONE
                                                                    MEMBER CO.,
                                                                    LTD
                                                                    <br />
                                                                    Add: 359 Ap
                                                                    Chien Luoc
                                                                    Street, Khu
                                                                    Pho 2, Binh
                                                                    Hung Hoa A
                                                                    Ward, Binh
                                                                    Tan
                                                                    District, Ho
                                                                    Chi Minh
                                                                    City
                                                                    <br />
                                                                    Fac: 2861,
                                                                    National
                                                                    Highway 1,
                                                                    Hamlet 3,
                                                                    Binh Chanh
                                                                    Commune,
                                                                    Binh Chanh
                                                                    District,
                                                                    HCM City
                                                                    <br />
                                                                    Tel:
                                                                    0283.620.4978
                                                                    Fax:
                                                                    0283.620.4978
                                                                    <br />
                                                                    Made in Viet
                                                                    Nam
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                class="text-start"
                                                            >
                                                                Tên khách hàng
                                                                <br />
                                                                外メーカー名
                                                            </td>
                                                            <td
                                                                colspan="5"
                                                                class="text-center"
                                                            >
                                                                <p
                                                                    class="fw-bold mb-0"
                                                                >
                                                                    FURUKAWA
                                                                    AUTOMOTIVE
                                                                    PARTS
                                                                    <br />
                                                                    (VIET NAM)
                                                                    INC
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                class="text-start"
                                                            >
                                                                Tên sản phẩm
                                                                <br />
                                                                品名
                                                            </td>
                                                            <td
                                                                class="text-center"
                                                            >
                                                                <p
                                                                    class="fw-bold mb-0 fs-13"
                                                                >
                                                                    {{ $product->name }}
                                                                </p>
                                                            </td>
                                                            <td
                                                                colspan="2"
                                                                class="align-content-center"
                                                            >
                                                                CODE
                                                            </td>
                                                            <td
                                                                colspan="2"
                                                                class="text-center"
                                                            >
                                                                <p
                                                                    class="fw-bold mb-0 fs-13"
                                                                >
                                                                    {{ $product->code }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                class="text-start"
                                                            >
                                                                Nguyên liệu
                                                                <br />
                                                                原材料
                                                            </td>
                                                            <td
                                                                class="text-center align-content-center"
                                                            >
                                                                <p class="mb-0">
                                                                    {{ $product->material }}
                                                                </p>
                                                            </td>
                                                            <td
                                                                colspan="2"
                                                                class="text-center"
                                                            >
                                                                Màu sắc 色
                                                            </td>
                                                            <td
                                                                colspan="2"
                                                                class="text-center align-content-center"
                                                            >
                                                                <p class="mb-0">
                                                                    {{ $product->color }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                class="text-start"
                                                            >
                                                                Số lượng
                                                                <br />
                                                                数量
                                                            </td>
                                                            <td
                                                                colspan="5"
                                                                class="text-center align-content-center"
                                                            >
                                                                <p
                                                                    class="fw-bold mb-0 fs-13"
                                                                >
                                                                    {{ $product->quanEntityBin }}PCS
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                class="text-start"
                                                            >
                                                                Lotno
                                                                <br />
                                                                ロット No
                                                            </td>
                                                            <td
                                                                colspan="4"
                                                                class="text-center align-content-center"
                                                            >
                                                                <div
                                                                    class="lot-container"
                                                                >
                                                                    <p
                                                                        class="fw-bold mb-0 fs-13"
                                                                    >
                                                                        {{ $lotNo['lot'] }}
                                                                    </p>
                                                                    <p
                                                                        class="fw-bold mb-0 fs-13"
                                                                    >
                                                                        -
                                                                    </p>
                                                                    <p
                                                                        class="fw-bold mb-0 fs-13"
                                                                    >
                                                                        {{ $lotNo['date'] }}
                                                                    </p>
                                                                    <p
                                                                        class="fw-bold mb-0 fs-13"
                                                                    >
                                                                        -
                                                                    </p>
                                                                    <p
                                                                        class="fw-bold mb-0 fs-13"
                                                                    >
                                                                        {{ $lotNo['shift'] }}
                                                                    </p>
                                                                    <p
                                                                        class="fw-bold mb-0 fs-13"
                                                                    >
                                                                        -
                                                                    </p>
                                                                    <p
                                                                        class="fw-bold mb-0 fs-13"
                                                                    >
                                                                        {{ $bin['bin'] }}
                                                                    </p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            {{--
                                                                <td class="text-start">
                                                                Mã vạch<br>バーコード
                                                                </td>
                                                                <td colspan="5"
                                                                class="text-center align-content-center">
                                                                <img id="barcode-image"
                                                                src="data:image/png;base64,{{ $bin['barcode'] }}"
                                                                alt="Mã vạch">
                                                                </td>
                                                            --}}
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                class="text-start"
                                                            >
                                                                Kiểm tra
                                                                <br />
                                                                検査
                                                            </td>
                                                            <td
                                                                colspan="3"
                                                                class="text-center align-content-center"
                                                            >
                                                                Kiểm tra 200%
                                                                <br />
                                                                檢查(200%)
                                                            </td>
                                                            <td
                                                                colspan="3"
                                                                class="text-center align-content-center"
                                                            >
                                                                Kiểm tra (Xuất
                                                                hàng)
                                                                <br />
                                                                検査 (出荷)
                                                            </td>
                                                        </tr>
                                                        <tr class="moc-style">
                                                            <td
                                                                class="text-start"
                                                            >
                                                                Mộc
                                                                <br />
                                                                合格印
                                                            </td>
                                                            <td
                                                                colspan="3"
                                                            ></td>
                                                            <td
                                                                colspan="1"
                                                                style="
                                                                    position: relative;
                                                                "
                                                            >
                                                                <div
                                                                    class="square"
                                                                ></div>
                                                            </td>
                                                        </tr>
                                                        <tr
                                                            class="under-moc-style"
                                                        >
                                                            <td
                                                                class="text-start"
                                                            >
                                                                Người kiểm
                                                                <br />
                                                                検査
                                                            </td>
                                                            <td
                                                                colspan="3"
                                                            ></td>
                                                            <td
                                                                colspan="2"
                                                            ></td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                class="text-start"
                                                            >
                                                                (Thời gian)
                                                            </td>
                                                            <td colspan="5">
                                                                {{ $lotNo['date_time'] }}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
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

                            <input
                                type="hidden"
                                id="sendStampId"
                                value="{{ $sendStamp->id }}"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var lastPrintTime = null; // Biến lưu thời gian lần in gần nhất
        var isPrintShortcutActivated = false; // Biến theo dõi trạng thái nhấn Ctrl + P

        function savePrint(sendStampId, callback) {
            var url = $('#save-print').data('url');

            if (sendStampId) {
                console.log('Đang lưu lịch sử in...', sendStampId);
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        sendStampId: sendStampId,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (response) {
                        console.log('res', response);

                        var notifications =
                            localStorage.getItem('notifications');
                        if (notifications) {
                            notifications = JSON.parse(notifications);
                            notifications = notifications.filter(
                                function (item) {
                                    if (item.recordId == sendStampId) {
                                        document
                                            .getElementById(
                                                'notification-stamp-' +
                                                    sendStampId,
                                            )
                                            .remove();
                                    }
                                    return item.recordId != sendStampId;
                                },
                            );
                            localStorage.setItem(
                                'notifications',
                                JSON.stringify(notifications),
                            );
                            if (notifications.length == 0) {
                                document.getElementById(
                                    'notificationList',
                                ).innerHTML =
                                    `<li class="text-muted text-center p-3">Không có thông báo</li>`;
                            }
                            document.getElementById(
                                'notificationCount',
                            ).innerText = notifications.length;
                        }
                        console.log('Đã xóa thông báo in thành công!');
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
                    }, 1000);
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
                                }, 1000);
                            });
                        }
                    });
                } else {
                    lastPrintTime = currentTime;
                    savePrint(sendStampId, function () {
                        setTimeout(function () {
                            window.print();
                            isPrintShortcutActivated = false; // Reset trạng thái sau khi in
                        }, 1000);
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
