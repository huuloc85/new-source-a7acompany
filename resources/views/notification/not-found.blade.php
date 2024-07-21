
@extends('master')
@section('content')
<style>
    @media (max-width: 600px) {
        .col-5 {
            width: 100% !important;
        }
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3">Không tìm thấy</h6>
                </div>
            </div>
            <div class="px-0 pb-2">
                <div class="table-responsive p-4">
                    <div class="row">
                        <div class="col-5">
                            <div class="mb-3">
                                <label class="form-label title">{{ $content }}</label>
                            </div>
                        </div>
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
