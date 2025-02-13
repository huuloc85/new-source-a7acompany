<!DOCTYPE html>
<html>
    <head>
        <link
            href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"
            rel="stylesheet"
        />
        <style>
            .modal-header {
                border-radius: 0.3rem 0.3rem 0 0;
                padding: 1rem 1.5rem;
            }

            .modal-title {
                font-size: 1.25rem;
                font-weight: 600;
                margin: 0;
            }

            .modal-body {
                padding: 1.5rem;
                font-size: 1rem;
                line-height: 1.6;
            }

            .modal-footer {
                padding: 1rem 1.5rem;
                border-top: 1px solid #dee2e6;
                flex-wrap: nowrap;
            }

            .btn-close {
                color: white;
                opacity: 0.8;
                transition: opacity 0.2s;
            }

            .btn-close:hover {
                opacity: 1;
            }

            .btn-understand {
                background-color: #0d6efd;
                color: white;
                padding: 0.5rem 1.5rem;
                border-radius: 0.25rem;
                border: none;
                transition: all 0.2s;
            }

            .btn-understand:hover {
                background-color: #0b5ed7;
                transform: translateY(-1px);
            }

            .modal-content {
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
                border: none;
                border-radius: 0.5rem;
            }

            .cleaning-icon {
                font-size: 2rem;
                margin-right: 0.5rem;
                color: #0d6efd;
            }

            /* Responsive Design */
            @media (max-width: 576px) {
                .modal-dialog {
                    margin: 0.5rem;
                }

                .modal-header {
                    padding: 0.75rem 1rem;
                }

                .modal-title {
                    font-size: 1.1rem;
                }

                .cleaning-icon {
                    font-size: 1.5rem;
                }

                .modal-body {
                    padding: 1rem;
                }

                .modal-footer {
                    padding: 0.75rem 1rem;
                    flex-direction: column;
                    gap: 0.5rem;
                }

                .btn {
                    width: 100%;
                    margin: 0 !important;
                }

                .alert {
                    padding: 0.75rem;
                }

                .alert small {
                    font-size: 0.8rem;
                }
            }

            @media (min-width: 577px) and (max-width: 768px) {
                .modal-dialog {
                    max-width: 90%;
                }

                .modal-title {
                    font-size: 1.2rem;
                }

                .cleaning-icon {
                    font-size: 1.75rem;
                }

                .modal-body {
                    padding: 1.25rem;
                }
            }

            @media (min-width: 769px) and (max-width: 992px) {
                .modal-dialog {
                    max-width: 80%;
                }
            }

            /* Landscape Mode */
            @media (max-height: 576px) and (orientation: landscape) {
                .modal-dialog {
                    max-height: 100vh;
                }

                .modal-body {
                    max-height: 60vh;
                    overflow-y: auto;
                }
            }

            /* Dark Mode Media Query */
            @media (prefers-color-scheme: dark) {
                .modal-content {
                    background-color: #212529;
                    color: #fff;
                }

                .alert-info {
                    background-color: #1c1f23;
                    border-color: #0dcaf0;
                    color: #e9ecef;
                }

                .btn-secondary {
                    background-color: #495057;
                    border-color: #495057;
                }
            }
        </style>
    </head>

    <body>
        <div
            class="modal fade"
            id="cleaningDutyModal"
            tabindex="-1"
            aria-labelledby="cleaningDutyModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="cleaningDutyModalLabel">
                            <i class="bi bi-calendar-check cleaning-icon"></i>
                            <span class="title-text font-weight-bold">
                                @if (session('cleaning_duties'))
                                    @php
                                        $types = collect(session('cleaning_duties'))->pluck('type')->unique();
                                    @endphp

                                    @if ($types->count() == 1)
                                        @switch($types->first())
                                            @case('Trực phòng ăn')
                                                Thông Báo Lịch Trực: Phòng Ăn

                                                @break
                                            @case('Đổ rác')
                                                Thông Báo Lịch Trực: Đổ Rác

                                                @break
                                            @case('Trực nhà vệ sinh nữ')
                                                Thông Báo Lịch Trực: Nhà Vệ Sinh
                                                Nữ

                                                @break
                                            @case('Trực nhà vệ sinh nam')
                                                Thông Báo Lịch Trực: Nhà Vệ Sinh
                                                Nam

                                                @break
                                            @default
                                                Thông Báo Lịch Trực
                                        @endswitch
                                    @else
                                            Thông Báo Lịch Trực
                                    @endif
                                @else
                                        Thông Báo Lịch Trực
                                @endif
                            </span>
                        </h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body">
                        @if (session('has_duties'))
                            @foreach (session('cleaning_duties') as $duty)
                                <div class="d-flex align-items-center mb-3">
                                    <i
                                        class="bi bi-exclamation-circle cleaning-icon"
                                    ></i>
                                    <p class="mb-0">
                                        <span class="title-text fw-bold">
                                            @php
                                                // Lấy thứ bằng cách sử dụng Carbon và viết hoa chữ cái đầu
                                                $dayOfWeek = ucfirst($duty['date']->translatedFormat('l'));
                                                $dateString = $duty['date']->isToday()
                                                    ? 'Hôm nay'
                                                    : $dayOfWeek.', '.$duty['date']->format('d/m/Y');
                                            @endphp

                                            @if ($duty['type'] == 'Trực phòng ăn')
                                                {{ $dateString }} là ngày trực
                                                vệ sinh của bạn tại phòng ăn.
                                                Vui lòng hoàn thành nhiệm vụ vệ
                                                sinh đúng thời gian quy định.
                                            @elseif ($duty['type'] == 'Đổ rác')
                                                {{ $dateString }} là ngày trực
                                                vệ sinh của bạn tại khu vực đổ
                                                rác. Vui lòng hoàn thành nhiệm
                                                vụ vệ sinh đúng thời gian quy
                                                định.
                                            @elseif ($duty['type'] == 'Trực nhà vệ sinh nữ')
                                                {{ $dateString }} là ngày trực
                                                vệ sinh của bạn tại nhà vệ sinh
                                                nữ. Vui lòng hoàn thành nhiệm vụ
                                                vệ sinh đúng thời gian quy định.
                                            @elseif ($duty['type'] == 'Trực nhà vệ sinh nam')
                                                {{ $dateString }} là ngày trực
                                                vệ sinh của bạn tại nhà vệ sinh
                                                nam. Vui lòng hoàn thành nhiệm
                                                vụ vệ sinh đúng thời gian quy
                                                định.
                                            @else
                                                {{ $dateString }} là ngày trực
                                                vệ sinh của bạn. Vui lòng hoàn
                                                thành nhiệm vụ vệ sinh đúng thời
                                                gian quy định.
                                            @endif
                                        </span>
                                    </p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-understand"
                            data-bs-dismiss="modal"
                        >
                            Đã hiểu
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <link
            href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css"
            rel="stylesheet"
        />
    </body>
</html>
