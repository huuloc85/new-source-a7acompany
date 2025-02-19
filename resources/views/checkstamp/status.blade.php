@extends('layouts.'.$layout)

@section('styles')
    <style>
        @media (max-width: 768px) {
            .table-wrapper {
                overflow-x: hidden;
                /* Thay đổi từ auto thành hidden */
                width: 100%;
                padding: 0 10px;
                /* Thêm padding để tránh nội dung sát viền */
            }

            table {
                width: 100%;
                border-collapse: collapse;
                table-layout: fixed;
                /* Thêm vào để kiểm soát chiều rộng */
            }

            thead {
                display: none;
            }

            tbody tr {
                display: block;
                margin-bottom: 15px;
                border: 1px solid #e0e0e0;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
                padding: 8px;
                /* Giảm padding */
                background: #fff;
            }

            td {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                /* Thay đổi từ center thành flex-start */
                padding: 8px;
                /* Giảm padding */
                border-bottom: 1px solid #f0f0f0;
                text-align: left;
                flex-wrap: wrap;
                /* Thêm vào để cho phép wrap khi nội dung dài */
                word-break: break-word;
                /* Thêm vào để xử lý text dài */
            }

            td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #555;
                margin-right: 10px;
                /* Thêm margin right */
                min-width: 120px;
                /* Đặt chiều rộng tối thiểu cho label */
            }

            td:last-child {
                border-bottom: none;
            }

            /* Thêm style cho các badge */
            .badge {
                margin-left: auto;
                /* Đẩy badge về bên phải */
            }

            /* Điều chỉnh style cho các dòng tiêu đề phân loại */
            tr[class^='table-'] td {
                padding: 10px;
                justify-content: flex-start;
            }

            tr[class^='table-'] td::before {
                display: none;
            }
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Bảng Trạng Thái In Tem</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="p-3 border rounded mb-3">
                        <div>
                            <span class="fw-bold">Tên nhân viên:</span>
                            {{ Auth()->user()->name ?? '' }}
                        </div>
                        <div>
                            <span class="fw-bold">Mã nhân viên:</span>
                            {{ Auth()->user()->code ?? '' }}
                        </div>
                        <div>
                            <span class="fw-bold">Bộ phận:</span>
                            {{ Auth()->user()->role->role_name ?? '' }}
                        </div>
                    </div>
                    <div>
                        <a
                            class="btn btn-link"
                            href="{{ route('admin.home') }}"
                        >
                            <i class="fas fa-arrow-left"></i>
                            Quay lại
                        </a>
                    </div>
                    <form
                        method="GET"
                        action="{{ route('admin.checkstamp-employee') }}"
                        id="filter-form"
                        class="d-flex align-items-center gap-2 my-2"
                    >
                        <label for="date" class="form-label fw-bold mb-0">
                            Chọn ngày:
                        </label>
                        <select
                            id="date"
                            name="date"
                            class="form-select w-auto"
                            onchange="document.getElementById('filter-form').submit();"
                        >
                            @foreach ($availableDates as $availableDate)
                                <option
                                    value="{{ $availableDate }}"
                                    {{ $date == $availableDate ? 'selected' : '' }}
                                >
                                    {{ \Carbon\Carbon::parse($availableDate)->format('d-m') }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                    <div class="table-responsive">
                        @if ($pendingStamps->isEmpty() && $historyprint->isEmpty())
                            <div class="text-center">
                                Không có lịch sử in tem
                            </div>
                        @else
                            <table class="table table-hover">
                                {{--
                                    <thead class="text-uppercase text-center">
                                    <tr>
                                    <th>STT</th>
                                    <th>Tên Sản Phẩm</th>
                                    <th>Tên Nhân Viên</th>
                                    <th>Ngày (Số Lot)</th>
                                    <th>Ca</th>
                                    <th>Số Lượng</th>
                                    <th>Bắt Đầu Từ Tem Số</th>
                                    <th>Loại Tem</th>
                                    <th>Ngày Gửi Yêu Cầu</th>
                                    <th>Thời Gian Gửi Yêu Cầu</th>
                                    <th>Người In</th>
                                    <th>Thời Gian In</th>
                                    <th>Trạng Thái</th>
                                    </tr>
                                    </thead>
                                --}}
                                <tbody>
                                    @php
                                        $stt = 1;
                                    @endphp

                                    {{-- Tem Chưa IN --}}
                                    @if ($pendingStamps->isNotEmpty())
                                        {{-- Kiểm tra nếu có dữ liệu --}}
                                        <tr class="table-primary">
                                            <td colspan="13">
                                                <strong>Tem Chưa IN</strong>
                                            </td>
                                        </tr>
                                        @foreach ($pendingStamps as $stamp)
                                            <tr>
                                                <td data-label="STT">
                                                    {{ $stt++ }}
                                                </td>
                                                <td data-label="Tên Sản Phẩm">
                                                    {{ $stamp->product->name ?? 'N/A' }}
                                                </td>
                                                <td data-label="Tên Nhân Viên">
                                                    {{ $stamp->employee->name ?? 'N/A' }}
                                                </td>
                                                <td data-label="Ngày (Số Lot)">
                                                    {{ \Carbon\Carbon::parse($stamp->date)->format('d-m-Y') }}
                                                </td>
                                                <td data-label="Ca">
                                                    {{ $stamp->shift }}
                                                </td>
                                                <td data-label="Số Lượng">
                                                    {{ $stamp->binCount }}
                                                </td>
                                                <td
                                                    data-label="Bắt Đầu Từ Tem Số"
                                                >
                                                    {{ $stamp->binStart }}
                                                </td>
                                                <td data-label="Loại Tem">
                                                    {{ $stamp->type }}
                                                </td>
                                                <td
                                                    data-label="Ngày Gửi Yêu Cầu"
                                                >
                                                    {{ \Carbon\Carbon::parse($stamp->created_at)->format('d-m-Y') }}
                                                </td>
                                                <td
                                                    data-label="Thời Gian Gửi Yêu Cầu"
                                                >
                                                    {{ \Carbon\Carbon::parse($stamp->created_at)->format('H:i:s') }}
                                                </td>
                                                <td data-label="Người In">
                                                    Chưa In
                                                </td>
                                                <td data-label="Thời Gian In">
                                                    Chưa In
                                                </td>
                                                <td data-label="Trạng Thái">
                                                    <span
                                                        class="badge bg-warning"
                                                    >
                                                        Chờ In
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif

                                    {{-- Tem Đã IN --}}
                                    @if ($historyprint->isNotEmpty())
                                        {{-- Kiểm tra nếu có dữ liệu --}}
                                        <tr class="table-success">
                                            <td colspan="13">
                                                <strong>Tem Đã IN</strong>
                                            </td>
                                        </tr>
                                        @foreach ($historyprint as $history)
                                            <tr>
                                                <td data-label="STT">
                                                    {{ $stt++ }}
                                                </td>
                                                <td data-label="Tên Sản Phẩm">
                                                    {{ $history->sendstamp->product->name ?? 'N/A' }}
                                                </td>
                                                <td data-label="Tên Nhân Viên">
                                                    {{ $history->sendstamp->employee->name ?? 'N/A' }}
                                                </td>
                                                <td data-label="Ngày (Số Lot)">
                                                    {{ \Carbon\Carbon::parse($history->date)->format('d-m-Y') }}
                                                </td>
                                                <td data-label="Ca">
                                                    {{ $history->shift }}
                                                </td>
                                                <td data-label="Số Lượng">
                                                    {{ $history->binCount }}
                                                </td>
                                                <td
                                                    data-label="Bắt Đầu Từ Tem Số"
                                                >
                                                    {{ $history->binStart }}
                                                </td>
                                                <td data-label="Loại Tem">
                                                    {{ $history->type }}
                                                </td>
                                                <td
                                                    data-label="Ngày Gửi Yêu Cầu"
                                                >
                                                    {{ \Carbon\Carbon::parse($history->sendstamp->created_at)->format('d-m-Y') }}
                                                </td>
                                                <td
                                                    data-label="Thời Gian Gửi Yêu Cầu"
                                                >
                                                    {{ \Carbon\Carbon::parse($history->sendstamp->created_at)->format('H:i:s') }}
                                                </td>
                                                <td data-label="Người In">
                                                    {{ $history->employee->name ?? 'N/A' }}
                                                </td>
                                                <td data-label="Thời Gian In">
                                                    {{ \Carbon\Carbon::parse($history->created_at)->format('H:i:s') }}
                                                </td>
                                                <td data-label="Trạng Thái">
                                                    <span
                                                        class="badge bg-success"
                                                    >
                                                        Đã In
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif

                                    {{-- Tem Từ Chối --}}
                                    @if ($rejectedStamps->isNotEmpty())
                                        {{-- Kiểm tra nếu có dữ liệu --}}
                                        <tr class="table-danger">
                                            <td colspan="13">
                                                <strong>Tem Bị Từ Chối</strong>
                                            </td>
                                        </tr>
                                        @foreach ($rejectedStamps as $rejected)
                                            <tr>
                                                <td data-label="STT">
                                                    {{ $stt++ }}
                                                </td>
                                                <td data-label="Tên Sản Phẩm">
                                                    {{ $rejected->product->name ?? 'N/A' }}
                                                </td>
                                                <td data-label="Tên Nhân Viên">
                                                    {{ $rejected->employee->name ?? 'N/A' }}
                                                </td>
                                                <td data-label="Ngày (Số Lot)">
                                                    {{ \Carbon\Carbon::parse($rejected->date)->format('d-m-Y') }}
                                                </td>
                                                <td data-label="Ca">
                                                    {{ $rejected->shift }}
                                                </td>
                                                <td data-label="Số Lượng">
                                                    {{ $rejected->binCount }}
                                                </td>
                                                <td
                                                    data-label="Bắt Đầu Từ Tem Số"
                                                >
                                                    {{ $rejected->binStart }}
                                                </td>
                                                <td data-label="Loại Tem">
                                                    {{ $rejected->type }}
                                                </td>
                                                <td
                                                    data-label="Ngày Gửi Yêu Cầu"
                                                >
                                                    {{ \Carbon\Carbon::parse($rejected->created_at)->format('d-m-Y') }}
                                                </td>
                                                <td
                                                    data-label="Thời Gian Gửi Yêu Cầu"
                                                >
                                                    {{ \Carbon\Carbon::parse($rejected->created_at)->format('H:i:s') }}
                                                </td>
                                                <td data-label="Người In">
                                                    Chưa In
                                                </td>
                                                <td data-label="Thời Gian In">
                                                    Chưa In
                                                </td>
                                                <td data-label="Trạng Thái">
                                                    <span
                                                        class="badge bg-danger"
                                                    >
                                                        Từ Chối
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
