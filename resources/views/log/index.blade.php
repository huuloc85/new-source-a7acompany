@extends('layouts.'.$layout)
@php
    $startValue = count($logs) > 0 ? $logs->firstItem() : 0;
    $toValue = count($logs) > 0 ? $logs->lastItem() : 0;

    $limitList = [10, 20, 50, 100, 200, 300, 500];
    $currentLimit = request('limit') ?? 10;
@endphp

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Danh Sách Log</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div
                        class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2"
                    >
                        <button
                            type="button"
                            class="btn btn-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteAllModal"
                        >
                            <i class="fas fa-trash-alt"></i>
                            Xoá tất cả
                        </button>
                        <form action="">
                            <input
                                type="hidden"
                                name="limit"
                                value="{{ $currentLimit }}"
                            />
                            <div class="input-group">
                                <input
                                    name="key"
                                    value="{{ request()->key }}"
                                    type="text"
                                    class="form-control"
                                    placeholder="Nhận từ khóa..."
                                />
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="mb-2">
                        <span class="fw-bold">
                            {{ $startValue }}
                        </span>
                        -
                        <span class="fw-bold">
                            {{ $toValue }}
                        </span>
                        của
                        <span class="fw-bold">
                            {{ $total }}
                        </span>
                    </div>
                    <form action="">
                        <input
                            type="hidden"
                            name="key"
                            value="{{ request()->key }}"
                        />
                        <div class="row g-1 align-items-center mb-2">
                            <div class="col-auto">
                                <label class="col-form-label">Số lượng:</label>
                            </div>
                            <div class="col-auto">
                                <select
                                    name="limit"
                                    class="form-select"
                                    onchange="this.form.submit()"
                                >
                                    @foreach ($limitList as $limit)
                                        <option
                                            value="{{ $limit }}"
                                            {{ $currentLimit == $limit ? 'selected' : '' }}
                                        >
                                            {{ $limit }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead
                                class="table-light text-uppercase align-middle"
                            >
                                <tr>
                                    <th class="text-center">STT</th>
                                    <th>Vị trí</th>
                                    <th>Nội dung</th>
                                    <th>Dòng</th>
                                    <th>Thời gian</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $key => $log)
                                    <tr class="align-middle">
                                        <td class="text-center">
                                            {{ $loop->iteration + $logs->firstItem() - 1 }}
                                        </td>
                                        <td>
                                            {{ $log->table }}
                                        </td>
                                        <td>
                                            {{ $log->content }}
                                        </td>
                                        <td>
                                            {{ $log->row }}
                                        </td>
                                        <td>
                                            {{ $log->created_at }}
                                        </td>
                                        <td class="text-center">
                                            <button
                                                type="button"
                                                class="btn btn-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $log->id }}"
                                            >
                                                <i class="fas fa-trash-alt"></i>
                                                Xóa
                                            </button>
                                        </td>
                                    </tr>
                                    {{-- Modal delete --}}
                                    <div
                                        class="modal fade"
                                        id="deleteModal{{ $log->id }}"
                                        tabindex="-1"
                                        aria-labelledby="deleteModalLabel{{ $log->id }}"
                                        aria-hidden="true"
                                    >
                                        <div
                                            class="modal-dialog modal-dialog-centered"
                                        >
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5
                                                        class="modal-title"
                                                        id="deleteModalLabel{{ $log->id }}"
                                                    >
                                                        Xác nhận
                                                    </h5>
                                                    <button
                                                        type="button"
                                                        class="btn-close"
                                                        data-bs-dismiss="modal"
                                                        aria-label="Close"
                                                    ></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>
                                                        Bạn có chắc muốn xóa Log
                                                        "
                                                        <span class="fw-bold">
                                                            #{{ $loop->iteration + $logs->firstItem() - 1 }}
                                                        </span>
                                                        " không?
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button
                                                        type="button"
                                                        class="btn btn-secondary"
                                                        data-bs-dismiss="modal"
                                                    >
                                                        Đóng
                                                    </button>
                                                    <form
                                                        action="{{ route('admin.log.delete', $log->id) }}"
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
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $logs->appends(request()->all())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Delete all --}}
    <div
        class="modal fade"
        id="deleteAllModal"
        tabindex="-1"
        aria-labelledby="deleteAllModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAllModalLabel">
                        Xác nhận
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <p>
                        Bạn có chắc muốn
                        <span class="fw-bold">xóa tất cả</span>
                        Log không?
                    </p>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Đóng
                    </button>
                    <form
                        action="{{ route('admin.log.delete.all') }}"
                        method="post"
                    >
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            Xóa
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
