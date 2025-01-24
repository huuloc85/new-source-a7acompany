@extends('layouts.layout')
@php
    $isManager = Auth()->user()->role_id != 14 || Auth()->user()->role_id != 18;
    $startValue = count($celenders) > 0 ? $celenders->firstItem() : 0;
    $toValue = count($celenders) > 0 ? $celenders->lastItem() : 0;
@endphp

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Danh Sách Lịch Làm Việc</h4>
        </div>
        <div class="card-body">
            <div
                class="d-flex justify-content-between align-items-center flex-wrap"
            >
                @if ($isManager)
                    <button
                        type="button"
                        class="btn btn-success mb-2"
                        data-bs-toggle="modal"
                        data-bs-target="#importCelender"
                    >
                        <i class="fas fa-plus"></i>
                        Import lịch làm việc
                    </button>
                    @include('celender.import')
                    @error('title')
                        <div class="text text-danger">
                            {{ $message }}
                        </div>
                    @enderror

                    @error('date')
                        <div class="text text-danger">
                            {{ $message }}
                        </div>
                    @enderror

                    @error('fileImport')
                        <div class="text text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                @endif

                <form action="" class="mb-2">
                    <div class="input-group">
                        <input
                            name="key"
                            value="{{ request()->key }}"
                            type="text"
                            class="form-control"
                            placeholder="Nhập tiêu đề..."
                        />
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                            <span hidden>Search</span>
                        </button>
                    </div>
                </form>
            </div>
            <div class="mb-2">
                From
                <span class="fw-bold">
                    {{ $startValue }}
                </span>
                to
                <span class="fw-bold">
                    {{ $toValue }}
                </span>
                of
                <span class="fw-bold">
                    {{ $total }}
                </span>
                entires
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr class="text-uppercase text-center">
                            <th scope="col">STT</th>
                            <th class="text-start" scope="col">Tiêu đề</th>
                            <th scope="col">Ngày bắt đầu</th>
                            <th scope="col">Chức năng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($celenders as $key => $celender)
                            <tr class="text-center">
                                <td class="fw-bold" scope="row">
                                    {{ $loop->iteration + $celenders->firstItem() - 1 }}
                                </td>
                                <td class="text-start" scope="row">
                                    {{ $celender->title }}
                                </td>
                                <td scope="row">
                                    {{ $celender->formatTimeDMY($celender->date) }}
                                </td>
                                <td scope="row">
                                    <a
                                        href="{{ route('admin.celender.detail', $celender->id) }}"
                                        class="btn btn-primary"
                                    >
                                        <i class="fas fa-circle-info"></i>
                                        Chi tiết
                                    </a>
                                    <!-- Button trigger modal delete -->
                                    <button
                                        type="button"
                                        class="btn btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalDelete-{{ $celender->id }}"
                                    >
                                        <i class="fas fa-trash-alt"></i>
                                        Xóa
                                    </button>
                                </td>
                            </tr>
                            <!-- Modal delete -->
                            <div
                                class="modal fade"
                                id="modalDelete-{{ $celender->id }}"
                                tabindex="-1"
                                aria-labelledby="modalDeleteLabel-{{ $celender->id }}"
                                aria-hidden="true"
                            >
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1
                                                class="modal-title fs-5"
                                                id="modalDeleteLabel-{{ $celender->id }}"
                                            >
                                                Xóa lịch làm việc
                                            </h1>
                                        </div>
                                        <div class="modal-body">
                                            <p>
                                                Hành động không thể khôi phục!!
                                                Bạn có chắc xoá lịch làm việc
                                                <span class="fw-bold">
                                                    {{ $celender->title }}
                                                </span>
                                                không?
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <form
                                                action="{{ route('admin.celender.delete', $celender->id) }}"
                                                method="post"
                                            >
                                                @method('DELETE')
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="btn btn-danger"
                                                >
                                                    Xóa
                                                </button>
                                            </form>
                                            <button
                                                type="button"
                                                class="btn btn-secondary"
                                                data-bs-dismiss="modal"
                                            >
                                                Đóng
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if ($total == 0)
                            <tr>
                                <td colspan="4" class="text-center">
                                    Hiện tại chưa có lịch làm việc nào.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $celenders->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
