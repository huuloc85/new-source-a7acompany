@extends('layouts.' . $layout)
<style>
    /* Ngăn chặn scroll ngang toàn trang */
    html,
    body {
        overflow-x: hidden;
        width: 100%;
    }

    /* Đảm bảo các khối chính không vượt chiều rộng màn hình */
    .container,
    .row,
    .card,
    .content,
    .main-content {
        max-width: 100vw;
        overflow-x: hidden;
    }

    /* Cho tất cả thành phần tính kích thước chính xác */
    * {
        box-sizing: border-box;
    }

    /* Card mobile padding đẹp hơn */
    @media (max-width: 767.98px) {
        .card-body p {
            font-size: 15px;
        }
    }

    /* Căn giữa tiêu đề modal và làm nổi bật icon */
    .modal-header .modal-title {
        display: flex;
        align-items: center;
        font-weight: 600;
        font-size: 1.25rem;
    }

    /* Căn chỉnh các biểu tượng Bootstrap trong header */
    .modal-header .bi {
        font-size: 1.3rem;
    }

    /* Tăng padding và làm mềm đường viền modal */
    .modal-content {
        border-radius: 0.75rem;
        border-width: 2px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    /* Nền body nhẹ hơn và padding rộng hơn */
    .modal-body {
        background-color: #f8f9fa;
        padding: 1.5rem;
    }

    /* Làm nổi bật alert */
    .alert {
        border-radius: 0.5rem;
        font-size: 0.95rem;
    }

    /* Badge đẹp hơn */
    .badge {
        padding: 0.45em 0.65em;
        font-size: 0.85em;
        font-weight: 600;
    }

    /* Card gọn gàng, hiện đại hơn */
    .card {
        border-radius: 0.75rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    /* Tăng spacing giữa các hàng */
    .card-body .row>div {
        margin-bottom: 1rem;
    }

    /* Danh sách thùng thiếu đẹp hơn */
    .badge.font-monospace {
        font-size: 0.9em;
        padding: 0.35em 0.65em;
    }

    /* Footer gọn gàng và đều nút */
    .modal-footer {
        padding: 1rem 1.5rem;
        background-color: #f1f3f5;
        border-top: 1px solid #dee2e6;
    }

    /* Nút bấm bóng nhẹ và cân đối */
    .modal-footer .btn {
        min-width: 120px;
        font-weight: 500;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    /* Nút đóng alert đẹp */
    .alert .btn-close {
        top: 0.5rem;
        right: 0.75rem;
    }

    /* Responsive tối ưu hơn nếu cần */
    @media (max-width: 768px) {
        .modal-dialog {
            margin: 1.5rem auto;
        }
    }
</style>

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">
                            Kho Đã Xuất Hàng Ngày {{ \Carbon\Carbon::now()->format('d-m') }}
                        </h4>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" class="row g-3 align-items-end" id="searchForm">
                        <div class="col-md-2">
                            <label for="product_id" class="form-label">Sản Phẩm</label>
                            <select name="product_id" id="product_id" class="form-select" onchange="this.form.submit()">
                                <option value="">Tất cả</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="employee_id" class="form-label">Nhân Viên</label>
                            <select name="employee_id" id="employee_id" class="form-select" onchange="this.form.submit()">
                                <option value="">Tất cả</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                        {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="filter_month" class="form-label">Tháng</label>
                            <select name="filter_month" id="filter_month" class="form-select" onchange="this.form.submit()">
                                @foreach ($availableMonths as $month)
                                    <option value="{{ $month }}"
                                        {{ request('filter_month') == $month ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::parse($month . '-01')->format('m/Y') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="filter_date" class="form-label">Ngày</label>
                            <select name="filter_date" id="filter_date" class="form-select" onchange="this.form.submit()">
                                @foreach ($availableDates as $date)
                                    <option value="{{ $date }}" {{ $filterDate === $date ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="lot_product_id" class="form-label">Sản phẩm để kiểm Lot <span
                                    class="text-danger">*</span></label>
                            <select name="lot_product_id" id="lot_product_id" class="form-select" required>
                                <option value="">Chọn sản phẩm </option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ request('lot_product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="lot" class="form-label">Mã Lot <span class="text-danger">*</span></label>
                            <input type="text" name="lot" id="lot" class="form-control"
                                placeholder="VD: 05062025-2-013" value="{{ request('lot') }}" required>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label d-block">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100" id="searchBtn">
                                <i class="bi bi-search"></i> Tìm thùng bị sót
                            </button>
                        </div>
                    </form>

                    @if (!$storage->isEmpty())
                        <div class="mb-3 d-flex flex-wrap gap-2 justify-content-start">
                            <button id="sortButton" onclick="sortGroupsByLot()"
                                class="btn btn-outline-primary btn-sm px-3 d-flex align-items-center"
                                title="Sắp xếp theo Lot">
                                <i class="bi bi-sort-down"></i>
                                <span id="sortButtonText" class="ms-1">Sắp xếp</span>
                                <span id="sortButtonSpinner" class="spinner-border spinner-border-sm ms-2 d-none"
                                    role="status" aria-hidden="true"></span>
                            </button>

                            <button id="resetButton" onclick="resetTable()"
                                class="btn btn-outline-secondary btn-sm px-3 d-none d-flex align-items-center"
                                title="Khôi phục thứ tự ban đầu">
                                <i class="bi bi-arrow-counterclockwise"></i>
                                <span class="ms-1">Khôi phục</span>
                            </button>
                        </div>

                        {{-- BẢNG CHO DESKTOP --}}
                        <div class="table-responsive d-none d-md-block">
                            <table class="table table-hover">
                                <thead class="text-uppercase text-center">
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên Sản Phẩm</th>
                                        <th>Code</th>
                                        <th>Nhân Viên Nhập</th>
                                        <th>Mã Nhân Viên</th>
                                        <th>Số Lot</th>
                                        <th>Thùng Số</th>
                                        <th>Ngày Xuất</th>
                                        <th>Thời Gian</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center align-middle">
                                    @foreach ($storage as $groupKey => $items)
                                        @php
                                            [$date, $employeeId, $productId] = explode('|', $groupKey);
                                            $employee = $items->first()->employee;
                                            $product = $items->first()->product;
                                        @endphp

                                        <tr class="table-secondary">
                                            <td colspan="9" class="text-start fw-bold">
                                                Ngày Xuất: {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }} |
                                                Nhân viên: {{ $employee->name }} |
                                                Sản phẩm: {{ $product->name }} ({{ $items->count() }} thùng)
                                            </td>
                                        </tr>

                                        @foreach ($items as $item)
                                            <tr>
                                                <th>{{ $loop->parent->iteration }}.{{ $loop->iteration }}</th>
                                                <td>{{ $item->product->name }}</td>
                                                <td>{{ $item->product->code }}</td>
                                                <td>{{ $item->employee->name }}</td>
                                                <td>{{ $item->employee->code }}</td>
                                                <td>{{ $item->lot }}</td>
                                                <td>{{ $item->bin }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('H:i:s') }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- CARD CHO MOBILE --}}
                        <div class="d-md-none">
                            @foreach ($storage as $groupKey => $items)
                                @php
                                    [$date, $employeeId, $productId] = explode('|', $groupKey);
                                    $employee = $items->first()->employee;
                                    $product = $items->first()->product;
                                @endphp

                                <div class="mb-2 fw-bold text-primary">
                                    Ngày Xuất: {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }} |
                                    Nhân viên: {{ $employee->name }} |
                                    Sản phẩm: {{ $product->name }} ({{ $items->count() }} thùng)
                                </div>

                                @foreach ($items as $item)
                                    <div class="card mb-3 shadow-sm">
                                        <div class="card-body p-3">
                                            <p class="mb-1"><strong>STT:</strong>
                                                {{ $loop->parent->iteration }}.{{ $loop->iteration }}</p>
                                            <p class="mb-1"><strong>Tên Sản Phẩm:</strong> {{ $item->product->name }}
                                            </p>
                                            <p class="mb-1"><strong>Code:</strong> {{ $item->product->code }}</p>
                                            <p class="mb-1"><strong>Nhân Viên Nhập:</strong> {{ $item->employee->name }}
                                            </p>
                                            <p class="mb-1"><strong>Mã Nhân Viên:</strong> {{ $item->employee->code }}
                                            </p>
                                            <p class="mb-1"><strong>Số Lot:</strong> {{ $item->lot }}</p>
                                            <p class="mb-1"><strong>Thùng Số:</strong> {{ $item->bin }}</p>
                                            <p class="mb-1"><strong>Ngày Xuất:</strong>
                                                {{ \Carbon\Carbon::parse($item->created_at)->format('d-m') }}</p>
                                            <p class="mb-0"><strong>Thời Gian:</strong>
                                                {{ \Carbon\Carbon::parse($item->created_at)->format('H:i:s') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    @else
                        {{-- THÔNG BÁO KHI KHÔNG CÓ DỮ LIỆU --}}
                        <div class="text-center my-4 text-danger fw-bold">
                            Không có sản phẩm trong kho xuất hàng
                        </div>
                    @endif
                    <script>
                        let originalHTMLDesktop = null;
                        let originalHTMLMobile = null;

                        function extractLotKey(lot) {
                            const match = lot.match(/([A-Z]?)-(\d{8})-(\d+)-(\d+)/);
                            if (!match) return '';
                            const [, , date, shift, bin] = match;
                            return `${date}-${shift.padStart(2, '0')}-${bin.padStart(3, '0')}`;
                        }

                        function sortGroupsByLot() {
                            const isDesktop = window.matchMedia("(min-width: 768px)").matches;

                            const button = document.getElementById('sortButton');
                            const text = document.getElementById('sortButtonText');
                            const spinner = document.getElementById('sortButtonSpinner');
                            const resetBtn = document.getElementById('resetButton');

                            text.textContent = "Đang sắp xếp...";
                            spinner.classList.remove('d-none');
                            button.disabled = true;

                            setTimeout(() => {
                                if (isDesktop) {
                                    sortTableDesktop();
                                } else {
                                    sortCardMobile();
                                }

                                text.textContent = "Đã sắp xếp theo Lot";
                                spinner.classList.add('d-none');
                                button.disabled = false;
                                resetBtn.classList.remove('d-none');

                            }, 100);
                        }

                        function sortTableDesktop() {
                            const table = document.querySelector('.table-responsive table');
                            const tbody = table?.querySelector('tbody');
                            if (!tbody) return;

                            if (!originalHTMLDesktop) {
                                originalHTMLDesktop = tbody.innerHTML;
                            }

                            const allRows = Array.from(tbody.querySelectorAll('tr'));
                            let groups = [],
                                currentGroup = null;

                            for (let row of allRows) {
                                if (row.classList.contains('table-secondary')) {
                                    currentGroup = {
                                        header: row,
                                        items: []
                                    };
                                    groups.push(currentGroup);
                                } else if (currentGroup) {
                                    currentGroup.items.push(row);
                                }
                            }

                            groups.forEach(group => {
                                group.items.sort((a, b) => {
                                    const lotA = extractLotKey(a.children[5]?.textContent.trim() || '');
                                    const lotB = extractLotKey(b.children[5]?.textContent.trim() || '');
                                    return lotA.localeCompare(lotB);
                                });
                            });

                            tbody.innerHTML = '';
                            groups.forEach((group, groupIndex) => {
                                tbody.appendChild(group.header);
                                group.items.forEach((row, idx) => {
                                    const sttCell = row.querySelector('th');
                                    if (sttCell) {
                                        sttCell.textContent = `${groupIndex + 1}.${idx + 1}`;
                                    }
                                    tbody.appendChild(row);
                                });
                            });
                        }

                        function sortCardMobile() {
                            const mobileWrapper = document.querySelector('.d-md-none');
                            if (!mobileWrapper) return;

                            if (!originalHTMLMobile) {
                                originalHTMLMobile = mobileWrapper.innerHTML;
                            }

                            const cardGroups = [];
                            const children = Array.from(mobileWrapper.children);
                            let currentHeader = null;
                            let currentCards = [];

                            children.forEach(el => {
                                if (el.classList.contains('fw-bold')) {
                                    if (currentHeader && currentCards.length > 0) {
                                        cardGroups.push({
                                            header: currentHeader,
                                            cards: currentCards
                                        });
                                    }
                                    currentHeader = el;
                                    currentCards = [];
                                } else if (el.classList.contains('card')) {
                                    currentCards.push(el);
                                }
                            });

                            if (currentHeader && currentCards.length > 0) {
                                cardGroups.push({
                                    header: currentHeader,
                                    cards: currentCards
                                });
                            }

                            cardGroups.forEach(group => {
                                group.cards.sort((a, b) => {
                                    const lotA = extractLotKey(
                                        a.querySelector('p:nth-child(6)')?.textContent.split(':').pop().trim() || ''
                                    );
                                    const lotB = extractLotKey(
                                        b.querySelector('p:nth-child(6)')?.textContent.split(':').pop().trim() || ''
                                    );
                                    return lotA.localeCompare(lotB);
                                });
                            });

                            mobileWrapper.innerHTML = '';
                            cardGroups.forEach((group, idx) => {
                                mobileWrapper.appendChild(group.header);
                                group.cards.forEach((card, j) => {
                                    const stt = card.querySelector('p strong')?.parentNode;
                                    if (stt) stt.innerHTML = `<strong>STT:</strong> ${idx + 1}.${j + 1}`;
                                    mobileWrapper.appendChild(card);
                                });
                            });
                        }

                        function resetTable() {
                            const isDesktop = window.matchMedia("(min-width: 768px)").matches;

                            if (isDesktop) {
                                const tbody = document.querySelector('.table-responsive table tbody');
                                if (originalHTMLDesktop && tbody) {
                                    tbody.innerHTML = originalHTMLDesktop;
                                }
                            } else {
                                const wrapper = document.querySelector('.d-md-none');
                                if (originalHTMLMobile && wrapper) {
                                    wrapper.innerHTML = originalHTMLMobile;
                                }
                            }

                            document.getElementById('sortButtonText').textContent = "Sắp xếp theo ngày-ca-bin (Lot)";
                            document.getElementById('resetButton').classList.add('d-none');
                        }
                    </script>


                </div>
            </div>
        </div>
    </div>
@endsection

{{-- MODAL LOT - Chỉ render khi cần thiết --}}
@if (!empty($lotModalData))
    @php $data = $lotModalData; @endphp

    <!-- Modal -->
    <div class="modal fade" id="lotModal" tabindex="-1" aria-labelledby="lotModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-primary">
                <div
                    class="modal-header {{ isset($data['error']) ? 'bg-danger' : ($data['status'] === 'success' ? 'bg-success' : 'bg-warning') }} text-white">
                    <h5 class="modal-title" id="lotModalLabel">
                        <i
                            class="bi {{ isset($data['error']) ? 'bi-exclamation-triangle' : ($data['status'] === 'success' ? 'bi-check-circle' : 'bi-info-circle') }} me-2"></i>
                        Thông tin kiểm tra Lot
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    @if (isset($data['error']))
                        <div class="alert alert-danger d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div>
                                <strong>Lỗi:</strong> {{ $data['error'] }}
                                @if (isset($data['code']))
                                    <br><small class="text-muted">Mã lot nhập: {{ $data['code'] }}</small>
                                @endif
                            </div>
                        </div>
                    @else
                        {{-- Thông tin lot --}}
                        <div class="card shadow-sm border rounded-3 p-3">
                            <div class="row gx-4 gy-3 align-items-start">
                                <!-- Thông tin Lot -->
                                <div class="col-md-6">
                                    <h6 class="text-uppercase text-secondary fw-semibold mb-2">Thông tin Lot</h6>
                                    <p class="mb-1"><strong>Mã Lot:</strong> <span
                                            class="text-danger fw-semibold">{{ $data['code'] }}</span></p>
                                    <p class="mb-1"><strong>Sản phẩm:</strong> <span
                                            class="text-dark">{{ $data['product'] }}</span></p>
                                    <p class="mb-0"><strong>Ngày sản xuất:</strong> <span
                                            class="text-dark">{{ $data['date'] }}</span></p>
                                </div>

                                <!-- Thống kê -->
                                <div class="col-md-6">
                                    <h6 class="text-uppercase text-secondary fw-semibold mb-2">Thống kê</h6>
                                    <p class="mb-1"><strong>Yêu cầu:</strong>
                                        <span class="badge bg-primary text-uppercase">{{ $data['expected'] }}
                                            thùng</span>
                                    </p>
                                    <p class="mb-1"><strong>Đã nhập:</strong>
                                        <span class="badge bg-info text-uppercase text-dark">{{ $data['actual'] }}
                                            thùng</span>
                                    </p>
                                    <p class="mb-0"><strong>Tỷ lệ:</strong>
                                        <span
                                            class="badge {{ $data['status'] === 'success' ? 'bg-success' : 'bg-warning text-dark' }}">
                                            {{ $data['expected'] > 0 ? round(($data['actual'] / $data['expected']) * 100, 1) : 0 }}%
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>


                        {{-- Kết quả kiểm tra --}}
                        @if ($data['missing'] > 0)
                            <div class="alert alert-warning">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <strong>Còn thiếu {{ $data['missing'] }} thùng</strong>
                                </div>

                                @if (!empty($data['missingLots']))
                                    <div class="mt-3">
                                        <strong>Danh sách thùng thiếu:</strong>
                                        <div class="mt-2" style="max-height: 200px; overflow-y: auto;">
                                            @foreach (array_chunk($data['missingLots'], 5) as $chunk)
                                                <div class="mb-1">
                                                    @foreach ($chunk as $miss)
                                                        <span
                                                            class="badge bg-warning text-dark me-1 mb-1 font-monospace">{{ $miss }}</span>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="alert alert-success">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    <strong>Hoàn thành! </strong> Đã nhập đủ số lượng thùng yêu cầu.
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Đóng
                    </button>
                    <button type="button" class="btn btn-primary" onclick="resetSearchForm()">
                        <i class="bi bi-arrow-clockwise me-1"></i>Tìm kiếm mới
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Script xử lý modal --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delay nhỏ để tránh flash content
            setTimeout(() => {
                try {
                    const modalElement = document.getElementById('lotModal');
                    if (modalElement && typeof bootstrap !== 'undefined') {
                        const modal = new bootstrap.Modal(modalElement, {
                            backdrop: 'static',
                            keyboard: true
                        });

                        modal.show();

                        // Auto close URL params khi modal đóng
                        modalElement.addEventListener('hidden.bs.modal', function() {
                            cleanupUrl();
                        });
                    }
                } catch (error) {
                    console.error('Modal error:', error);
                }
            }, 100);
        });

        // Reset form và đóng modal
        function resetSearchForm() {
            // Đóng modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('lotModal'));
            if (modal) modal.hide();

            // Reset form fields
            document.getElementById('lot').value = '';
            document.getElementById('lot_product_id').value = '';

            // Clean URL
            cleanupUrl();

            // Focus vào field đầu tiên
            document.getElementById('lot_product_id').focus();
        }

        // Clean URL parameters
        function cleanupUrl() {
            if (typeof window.history !== 'undefined') {
                const url = new URL(window.location);
                url.searchParams.delete('lot');
                url.searchParams.delete('lot_product_id');
                window.history.replaceState({}, document.title, url.toString());
            }
        }

        // Prevent form submission nếu thiếu data
        document.getElementById('searchForm')?.addEventListener('submit', function(e) {
            const lot = document.getElementById('lot')?.value?.trim();
            const lotProductId = document.getElementById('lot_product_id')?.value;

            // Chỉ validate khi click nút search lot
            if (e.submitter?.id === 'searchBtn') {
                if (!lot || !lotProductId) {
                    e.preventDefault();

                    // Hiển thị thông báo lỗi đẹp hơn
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-warning alert-dismissible fade show mt-3';
                    alertDiv.innerHTML = `
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Thiếu thông tin!</strong> Vui lòng chọn sản phẩm và nhập mã lot.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;

                    // Insert alert sau form
                    const form = document.getElementById('searchForm');
                    form.parentNode.insertBefore(alertDiv, form.nextSibling);

                    // Auto remove alert sau 5s
                    setTimeout(() => {
                        if (alertDiv.parentNode) {
                            alertDiv.remove();
                        }
                    }, 5000);

                    // Focus vào field trống đầu tiên
                    if (!lotProductId) {
                        document.getElementById('lot_product_id').focus();
                    } else if (!lot) {
                        document.getElementById('lot').focus();
                    }

                    return false;
                }

                // Show loading state
                e.submitter.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Đang tìm...';
                e.submitter.disabled = true;
            }
        });
    </script>
@endif
