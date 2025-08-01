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
