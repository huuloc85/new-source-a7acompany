<style>
    .nav-item .dropdown-menu {
        max-height: 400px;
        overflow-y: auto;
    }

    .nav-item.dropdown:hover .dropdown-menu {
        display: block;
    }

    /* .dropdown-menu {
        max-height: 100vh;
        overflow-y: auto;
        padding: 0.5rem;
    } */

    @media (max-width: 1200px) {
        .sidebar .sidebar-toggle {
            right: -10px;
        }
    }

    .logo-text {
        font-size: 12px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
        text-align: center;
    }
</style>

@php
    $roleId = Auth()->user()->role_id;

    $isAdmin = Auth()->user()->role->role_name == 'admin';
    $isQA = in_array($roleId, [8, 13]);
    $isStorage = $roleId == 4;
    // Ngoại quan + sản suất
    $isReqRole = in_array($roleId, [9, 14, 18, 19]);

    $isEmployee = ! $isAdmin && ! $isQA && ! $isStorage && ! $isReqRole;

    $navConfig = [
        [
            'label' => 'Trang chủ',
            'icon' => 'fas fa-home fa-lg',
            'path' => 'admin.home',
        ],
    ];

    $navAdmin = [
        [
            'label' => 'Nhân Sự',
            'icon' => 'fas fa-user-group fa-lg',
            'children' => [
                [
                    'label' => 'Danh sách',
                    'icon' => 'fas fa-list-alt fa-lg',
                    'path' => 'admin.employee.home',
                ],
                [
                    'label' => 'Chức vụ',
                    'icon' => 'fas fa-user-tie fa-lg',
                    'path' => 'admin.role.home',
                ],
            ],
        ],
        [
            'label' => 'Sản Phẩm',
            'icon' => 'fas fa-boxes fa-lg',
            'path' => 'admin.product.home',
        ],
        [
            'label' => 'Kế hoạch',
            'icon' => 'fas fa-calendar-alt fa-lg',
            'children' => [
                [
                    'label' => 'Kế hoạch sản xuất',
                    'icon' => 'fas fa-chart-bar fa-lg',
                    'path' => 'admin.product-plan.index',
                ],
                [
                    'label' => 'Kế hoạch nguyên liệu',
                    'icon' => 'fas fa-box fa-lg',
                    'path' => 'admin.material.index',
                ],
            ],
        ],
        [
            'label' => 'Lịch hoạt động / ngày',
            'icon' => 'fas fa-calendar-day fa-lg',
            'path' => 'admin.checkemployee.view-employee-todo',
        ],
        [
            'label' => 'Tạo Tem',
            'icon' => 'fas fa-print fa-lg',
            'children' => [
                [
                    'label' => 'Tạo Tem Thùng',
                    'icon' => 'fas fa-box fa-lg',
                    'path' => 'admin.product.barcode',
                ],
                [
                    'label' => 'Tạo Tem Bịch',
                    'icon' => 'fas fa-sheet-plastic fa-lg',
                    'path' => 'admin.product.packing',
                ],
                [
                    'label' => 'Lịch Sử In Tem',
                    'icon' => 'fas fa-print fa-lg',
                    'path' => 'admin.checkstamp',
                ],
            ],
        ],
        [
            'label' => 'Chấm Công',
            'icon' => 'fas fa-calendar-check fa-lg',
            'children' => [
                [
                    'label' => 'Lịch Sử Chấm Công',
                    'icon' => 'fas fa-history fa-lg',
                    'path' => 'admin.attendence.index',
                ],
                [
                    'label' => 'Bảng Tính Công',
                    'icon' => 'fas fa-file-invoice fa-lg',
                    'path' => 'admin.attendence.records',
                ],
            ],
        ],
        [
            'label' => 'Kiểm tra PO',
            'icon' => 'fas fa-check-square fa-lg',
            'path' => 'admin.checkpo.index',
        ],
        [
            'label' => 'Lịch sử',
            'icon' => 'fas fa-history fa-lg',
            'path' => 'admin.history.home',
        ],
        [
            'label' => 'Lịch làm việc',
            'icon' => 'fas fa-calendar-alt fa-lg',
            'path' => 'admin.celender.home',
        ],
        [
            'label' => 'Danh mục lịch làm việc',
            'icon' => 'fas fa-briefcase fa-lg',
            'path' => 'admin.category.home',
        ],
        [
            'label' => 'Bảng lương',
            'icon' => 'fas fa-money-check-alt fa-lg',
            'path' => 'admin.salary.home',
        ],
    ];

    $navEmployee = [
        [
            'label' => 'Lịch làm việc',
            'icon' => 'fas fa-calendar-alt fa-lg',
            'path' => 'admin.employee-show.celender',
        ],
        [
            'label' => 'Bảng lương',
            'icon' => 'fas fa-money-check-alt fa-lg',
            'path' => 'admin.employee-show.salary',
        ],
        [
            'label' => 'Chấm công',
            'icon' => 'fas fa-calendar-check fa-lg',
            'children' => [
                [
                    'label' => 'Lịch Sử Chấm Công',
                    'icon' => 'fas fa-history fa-lg',
                    'path' => 'admin.employee.attendence',
                ],
                [
                    'label' => 'Bảng Tính Công',
                    'icon' => 'fas fa-file-invoice fa-lg',
                    'path' => 'admin.employee.attendence_caculate_records',
                ],
            ],
        ],
    ];

    $navQA = [
        [
            'label' => 'Tạo Tem',
            'icon' => 'fas fa-print fa-lg',
            'children' => [
                [
                    'label' => 'Tạo Tem Thùng',
                    'icon' => 'fas fa-box fa-lg',
                    'path' => 'admin.product.barcode',
                ],
                [
                    'label' => 'Tạo Tem Bịch',
                    'icon' => 'fas fa-sheet-plastic fa-lg',
                    'path' => 'admin.product.packing',
                ],
                [
                    'label' => 'Lịch Sử In Tem',
                    'icon' => 'fas fa-print fa-lg',
                    'path' => 'admin.checkstamp',
                ],
            ],
        ],
    ];

    $navNotQA = [
        [
            'label' => 'Chọn Sản Phẩm',
            'icon' => 'fas fa-boxes fa-lg',
            'path' => 'admin.employee.check-employee-todo',
        ],
        [
            'label' => 'Lịch Sử Hoạt Động',
            'icon' => 'fas fa-history fa-lg',
            'path' => 'admin.employee-history-check',
        ],
    ];

    $navReqRole = [
        [
            'label' => 'Yêu Cầu In Tem',
            'icon' => 'fas fa-envelopes-bulk fa-lg',
            'path' => 'admin.send-stamp',
        ],
    ];
    if (in_array($roleId, [14, 18, 19])) {
        array_push($navReqRole, [
            'label' => 'Lịch làm việc nhân viên',
            'icon' => 'fas fa-calendar-alt fa-lg',
            'path' => 'admin.celender.home',
        ]);
        array_push($navReqRole, [
            'label' => 'Lịch hoạt động / ngày',
            'icon' => 'fas fa-calendar-day fa-lg',
            'path' => 'admin.checkemployee.view-employee-todo',
        ]);
    }

    $navStorage = [
        [
            'label' => 'Kho Đã Xuất Hàng',
            'icon' => 'fas fa-box fa-lg',
            'path' => 'admin.storage.index',
        ],
        [
            'label' => 'Quét QR code',
            'icon' => 'fas fa-qrcode fa-lg',
            'path' => 'admin.barcode.scanQr',
        ],
        [
            'label' => 'Quét Barcode',
            'icon' => 'fas fa-barcode fa-lg',
            'path' => 'admin.barcode.scan',
        ],
    ];

    $navProfile = [
        [
            'label' => 'Thông Tin Tài Khoản',
            'icon' => 'fas fa-user fa-lg',
            'path' => 'admin.profile',
        ],
    ];

    $isAdmin && array_push($navConfig, ...$navAdmin);
    $isQA && array_push($navConfig, ...$navEmployee, ...$navQA, ...$navProfile);
    $isStorage && array_push($navConfig, ...$navEmployee, ...$navStorage, ...$navProfile);
    $isReqRole && array_push($navConfig, ...$navEmployee, ...$navNotQA, ...$navReqRole, ...$navProfile);
    $isEmployee && array_push($navConfig, ...$navEmployee, ...$navNotQA, ...$navProfile);

    $isActive = function ($path) {
        return request()->routeIs($path) ? 'active' : '';
    };
@endphp

<aside class="sidebar sidebar-default navs-rounded-all sidebar-base no-print">
    <div
        class="sidebar-header d-flex align-items-center justify-content-center flex-column"
    >
        <a
            href="{{ route('admin.home') }}"
            class="navbar-brand text-center d-flex flex-column align-items-center"
        >
            <img
                src="{{ asset('assets/img/logos/VVP.png') }}"
                alt=""
                width="70%"
                title="VINH VINH PHAT ONE MEMBER CO.LTD"
            />
            <div class="logo-text">VINH VINH PHAT ONE MEMBER CO. LTD</div>
        </a>
        <div
            class="sidebar-toggle"
            data-toggle="sidebar"
            data-active="true"
            style="margin: 150px -7px 0 0"
            title="Toggle Sidebar"
        >
            <i class="icon" style="width: 30px; height: 30px">
                <svg
                    width="40"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M4.25 12.2744L19.25 12.2744"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    ></path>
                    <path
                        d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    ></path>
                </svg>
            </i>
        </div>
    </div>
    <div class="sidebar-body pt-0 data-scrollbar">
        <div class="sidebar-list" id="sidebar">
            <ul class="navbar-nav iq-main-menu" id="sidebar">
                @foreach ($navConfig as $keyNav => $navItem)
                    @if (empty($navItem['children']))
                        <li class="nav-item">
                            <a
                                href="{{ route($navItem['path']) }}"
                                class="nav-link {{ $isActive($navItem['path']) }}"
                                title="{{ $navItem['label'] }}"
                            >
                                <i
                                    class="{{ $navItem['icon'] }}"
                                    style="width: 1.5rem"
                                ></i>
                                <span class="ms-3 flex-grow-1">
                                    {{ $navItem['label'] }}
                                </span>
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a
                                class="nav-link dropdown-toggle {{ collect($navItem['children'])->pluck('path')->contains(fn ($path) => $isActive($path)) ? 'active' : '' }}"
                                href="#"
                                role="button"
                                data-toggle="dropdown"
                                aria-expanded="false"
                                title="{{ $navItem['label'] }}"
                            >
                                <i
                                    class="{{ $navItem['icon'] }}"
                                    style="width: 1.5rem"
                                ></i>
                                <span class="ms-3 flex-grow-1">
                                    {{ $navItem['label'] }}
                                </span>
                            </a>
                            <div class="dropdown-menu">
                                @foreach ($navItem['children'] as $keyChild => $child)
                                    <a
                                        class="dropdown-item {{ $isActive($child['path']) }}"
                                        href="{{ route($child['path']) }}"
                                        title="{{ $child['label'] }}"
                                    >
                                        <i
                                            class="{{ $child['icon'] }}"
                                            style="width: 1.5rem"
                                        ></i>
                                        <span class="flex-grow-1">
                                            {{ $child['label'] }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
    <div
        class="sidebar-footer left-1 mb-3"
        style="position: absolute; bottom: 0; width: 100%"
    >
        <div class="nav-item text-center">
            <a class="nav-link" href="{{ route('logout') }}">
                <i
                    class="fas fa-right-from-bracket fa-lg"
                    style="width: 1.5rem"
                ></i>
                <span class="nav-link-text ms-1">Đăng xuất</span>
            </a>
        </div>
    </div>
</aside>
