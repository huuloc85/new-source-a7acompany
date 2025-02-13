@extends('layouts.layout')

@php
    $lastItem = count($salaryManagers) > 0 ? $salaryManagers->lastItem() : 0;
@endphp

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Danh Sách Bảng Lương</h4>
                    </div>
                </div>
                <div class="card-body">
                    <a class="btn btn-link" href="{{ route('admin.home') }}">
                        <i class="fas fa-arrow-left"></i>
                        Quay lại
                    </a>
                    <div class="d-flex align-items-center justify-content-between flex-wrap-reverse gap-3 mb-3">
                        <span>
                            <strong>Tổng:</strong>
                            {{ $lastItem }}/{{ $total }}
                        </span>
                        <form action="">
                            <div class="input-group">
                                <input name="key" value="{{ request()->key }}" type="text" class="form-control"
                                    placeholder="Nhập tiêu đề" />
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                    <span hidden>Search</span>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="row">
                        @foreach ($salaryManagers as $key => $salaryManager)
                            <div class="col-12 col-sm-6 col-md-4 col-xxl-3">
                                <div class="card">
                                    <div class="card-header p-3">
                                        <a href="{{ route('admin.employee-show.salary-detail', $salaryManager->id) }}">
                                            <h6 class="mb-0">
                                                {{ $salaryManager->title }}
                                            </h6>
                                        </a>
                                    </div>
                                    <div class="card-footer p-3 d-flex justify-content-between">
                                        <div>
                                            <h6 class="mb-0">Bắt đầu</h6>
                                            <p class="mb-0">
                                                {{ $salaryManager->start_date }}
                                            </p>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Kết thúc</h6>
                                            <p class="mb-0">
                                                {{ $salaryManager->end_date }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if ($total == 0)
                        <p class="text-center">Hiện chưa có bảng lương nào!</p>
                    @endif

                    <div class="d-flex justify-content-center">
                        {{ $salaryManagers->appends(request()->all())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
