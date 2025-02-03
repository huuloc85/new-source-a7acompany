@extends('layouts.layout')

@php
    $lastItem = count($celenders) > 0 ? $celenders->lastItem() : 0;
@endphp

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Danh Sách Lịch Làm Việc</h4>
        </div>
        <div class="card-body">
            <div>
                <a class="btn btn-link" href="{{ route('admin.home') }}">
                    <i class="fas fa-arrow-left"></i>
                    Quay lại
                </a>
            </div>
            <div
                class="d-flex align-items-center justify-content-between flex-wrap-reverse gap-3 mb-3"
            >
                <div>
                    <strong>Tổng:</strong>
                    {{ $lastItem }}/{{ $total }}
                </div>
                <form action="">
                    @csrf
                    <div class="input-group input-group-sm">
                        <input
                            name="key"
                            value="{{ request()->key }}"
                            type="text"
                            class="form-control"
                            placeholder="Nhập tiêu đề..."
                        />
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                            <span hidden>Tìm kiếm</span>
                        </button>
                    </div>
                </form>
            </div>
            <div>
                <div class="row">
                    @foreach ($celenders as $key => $celender)
                        <div class="col-12 col-sm-6 col-lg-4 col-xxl-3">
                            <div class="card">
                                <div
                                    class="card-header"
                                    style="height: 4.25rem"
                                >
                                    <a
                                        href="{{ route('admin.employee-show.celender-detail', $celender->id) }}"
                                    >
                                        <h6>
                                            {{ $celender->title }}
                                        </h6>
                                    </a>
                                </div>
                                <div class="card-footer">
                                    <div>
                                        <h6 class="mb-0">Bắt đầu</h6>
                                        <p class="mb-0">
                                            {{ $celender->formatTimeDMY($celender->date) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="d-flex justify-content-center">
                    {{ $celenders->appends(request()->all())->links() }}
                </div>
                @if ($total == 0)
                    <p class="text-center">Hiện chưa có lịch làm việc nào!</p>
                @endif
            </div>
        </div>
    </div>
@endsection
