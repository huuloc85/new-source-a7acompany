@extends('master')
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
<link rel="stylesheet" href="{{ asset('assets/css/scan-barcode.css') }}">
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3">Quét mã vạch</h6>
                </div>
            </div>
            <div class="px-0 pb-2">
                <div class="table-responsive p-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label title">Hướng dẫn quét mã vạch<span class="required">*</span>:</label><br>
                                <label class="form-label content">Bước 1: Đưa mã vạch vào khung màn hình quét.</label><br>
                                <label class="form-label content">Bước 2: Cân chỉnh để camera có thể nhận diện mã vạch rõ ràng.</label><br>
                                <label class="form-label content">Bước 3: Đợi đến khi có thông báo quét mã.</label><br>
                                <label class="form-label content">Lưu ý: Khoảng nghĩ giữa 2 lần quét mã thành công là <span class="required">5 giây</span>.</label><br>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div id="interactive" data-url="{{ route('admin.barcode.check')}}" class="viewport"></div>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var startApi = true;
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('#interactive'),
                constraints: {
                    facingMode: "environment"
                }
            },
            decoder: {
                readers: ["code_128_reader"]
            }
        }, function (err) {
            if (err) {
                console.log(err);
                return;
            }
            console.log("QuaggaJS đã được khởi tạo thành công");
            Quagga.start();
        });

        Quagga.onDetected(function (result) {
            if (result && result.codeResult.code != "" && result.codeResult.code != "*!" && result.codeResult.code != "U8'48*(") {
                var code = result.codeResult.code;

                if (startApi) {
                    console.log(startApi);
                    var url = $('#interactive').data('url');
                    $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        barcode: code,
                        _token: '{{ csrf_token() }}'
                    },
                        success: function(response) {
                            console.log(response.status);
                            var status = response.status;
                            switch (status) {
                                case 200:
                                    alert("Cập nhật Sản lượng xuất hàng thành công, Vui lòng nhấn Ok và đợi 5s để tiếp tục quét mã!!!");
                                    break;
                                case 400:
                                    alert("Mã này đã được quét vui lòng thử lại!!!");
                                    break;
                                case 404:
                                    alert("Không tìm thấy sản phẩm!!!");
                                    break;
                                case 500:
                                    alert("Mã này khồng hợp lệ!!!");
                                    break;
                                default:
                                    break;
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Đã xảy ra lỗi khi gửi barcode:", error);
                        }
                    });

                    startApi = false;
                    setTimeout(function () {
                        console.log("Wait for 5s!!!");
                        startApi = true;
                    }, 5000);
                }
            }
        });
    });
</script>
@endsection
