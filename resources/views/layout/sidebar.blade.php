<style>
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

<aside class="sidebar sidebar-default navs-rounded-all sidebar-base no-print">
    <div class="sidebar-header d-flex align-items-center justify-content-center flex-column">
        <a href="{{ route('admin.home') }}" class="navbar-brand text-center d-flex flex-column align-items-center">
            <img src="{{ asset('assets/img/logos/VVP.png') }}" alt="" width="70%"
                title="VINH VINH PHAT ONE MEMBER CO.LTD">
            <div class="logo-text">VINH VINH PHAT ONE MEMBER CO. LTD</div>
        </a>
        <div class="sidebar-toggle" data-toggle="sidebar" data-active="true" style="margin: 150px -7px 0 0;"
            title="Toggle Sidebar">
            <i class="icon" style="width: 30px; height: 30px;">
                <svg width="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </i>
        </div>
    </div>
    <div class="sidebar-body pt-0 data-scrollbar">
        <div class="sidebar-list" id="sidebar">
            <ul class="navbar-nav iq-main-menu" id="sidebar">
                @if (auth()->user()->role->role_name == 'admin' ||
                        auth()->user()->role->role_name == 'manager' ||
                        auth()->user()->role->role_name == 'accountant')
                    @if (auth()->user()->role->role_name == 'admin')
                        <li class="nav-item">
                            <a class="nav-link {{ route('admin.home') }}" aria-current="page"
                                href="{{ route('admin.home') }}" title="Trang Chủ">
                                <i class="icon-svg">
                                    <svg width="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" fill="currentColor" />
                                    </svg>
                                </i>
                                <span class="item-name">Trang chủ</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ route('admin.employee.home') }}"
                                href="{{ route('admin.employee.home') }} "title="Nhân Sự">
                                <i class="icon-svg">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16 13H8c-2.21 0-4-1.79-4-4s1.79-4 4-4h8c2.21 0 4 1.79 4 4s-1.79 4-4 4zm0-6H8c-1.1 0-2 .9-2 2s.9 2 2 2h8c1.1 0 2-.9 2-2s-.9-2-2-2zm1 8H7c-1.1 0-2 .9-2 2v3h14v-3c0-1.1-.9-2-2-2z"
                                            fill="currentColor" />
                                    </svg>
                                </i>
                                <span class="item-name">Nhân sự</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ route('admin.role.home') }}"
                                href="{{ route('admin.role.home') }} "title="Chức Vụ">
                                <i class="icon-svg">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12 4.75C12.9665 4.75 13.75 3.9665 13.75 3C13.75 2.0335 12.9665 1.25 12 1.25C11.0335 1.25 10.25 2.0335 10.25 3C10.25 3.9665 11.0335 4.75 12 4.75ZM6.75 8.75C7.7165 8.75 8.5 7.9665 8.5 7C8.5 6.0335 7.7165 5.25 6.75 5.25C5.7835 5.25 5 6.0335 5 7C5 7.9665 5.7835 8.75 6.75 8.75ZM17.25 8.75C18.2165 8.75 19 7.9665 19 7C19 6.0335 18.2165 5.25 17.25 5.25C16.2835 5.25 15.5 6.0335 15.5 7C15.5 7.9665 16.2835 8.75 17.25 8.75ZM12 14.25C15.85 14.25 19 16.2 19 18V19H5V18C5 16.2 8.15 14.25 12 14.25ZM12 11.25C14.6235 11.25 16.75 9.1235 16.75 6.5C16.75 3.8765 14.6235 1.75 12 1.75C9.3765 1.75 7.25 3.8765 7.25 6.5C7.25 9.1235 9.3765 11.25 12 11.25ZM12 15.75C8.41 15.75 3.75 17.68 3.75 18V19.75H20.25V18C20.25 17.68 15.59 15.75 12 15.75ZM17.25 10.25C15.73 10.25 14.5 9.02 14.5 7.5C14.5 5.98 15.73 4.75 17.25 4.75C18.77 4.75 20 5.98 20 7.5C20 9.02 18.77 10.25 17.25 10.25ZM6.75 10.25C5.23 10.25 4 9.02 4 7.5C4 5.98 5.23 4.75 6.75 4.75C8.27 4.75 9.5 5.98 9.5 7.5C9.5 9.02 8.27 10.25 6.75 10.25Z"
                                            fill="currentColor" />
                                    </svg>
                                </i>
                                <span class="item-name">Chức vụ</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link route('admin.product.home')" href="{{ route('admin.product.home') }}"
                                title="Sản Phẩm">
                                <i class="icon-svg">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12 0C5.37258 0 0 5.37258 0 12C0 18.6274 5.37258 24 12 24C18.6274 24 24 18.6274 24 12C24 5.37258 18.6274 0 12 0ZM12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22Z"
                                            fill="currentColor" />
                                        <path
                                            d="M17 7H11C10.4477 7 10 6.55228 10 6C10 5.44772 10.4477 5 11 5H17C17.5523 5 18 5.44772 18 6C18 6.55228 17.5523 7 17 7Z"
                                            fill="currentColor" />
                                        <path
                                            d="M17 13H7C6.44772 13 6 12.5523 6 12C6 11.4477 6.44772 11 7 11H17C17.5523 11 18 11.4477 18 12C18 12.5523 17.5523 13 17 13Z"
                                            fill="currentColor" />
                                        <path
                                            d="M17 17H13C12.4477 17 12 16.5523 12 16C12 15.4477 12.4477 15 13 15H17C17.5523 15 18 15.4477 18 16C18 16.5523 17.5523 17 17 17Z"
                                            fill="currentColor" />
                                        <path
                                            d="M8 17H7C6.44772 17 6 16.5523 6 16C6 15.4477 6.44772 15 7 15H8C8.55228 15 9 15.4477 9 16C9 16.5523 8.55228 17 8 17Z"
                                            fill="currentColor" />
                                    </svg>
                                </i>
                                <span class="item-name">Sản Phẩm</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.product-plan.index') }}"
                                title="Kế hoạch sản xuất">
                                <i class="icon-svg">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        fill="currentColor" class="bi bi-bar-chart" viewBox="0 0 16 16">
                                        <path
                                            d="M0 0h16v16H0V0zm1 1v14h14V1H1zm2 2v10h2V3H3zm4 4v6h2V7H7zm4-2v8h2V5h-2z" />
                                    </svg>
                                </i>
                                <span class="item-name">Kế hoạch sản xuất</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.material.index') }}" title="Kế hoạch sản xuất">
                                <i class="icon-svg">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        fill="currentColor" class="bi bi-box-seam" viewBox="0 0 16 16">
                                        <path
                                            d="M8.473.018a.5.5 0 0 0-.446 0l-6.5 3.25A.5.5 0 0 0 1.5 4v8a.5.5 0 0 0 .277.447l6.5 3.25a.5.5 0 0 0 .446 0l6.5-3.25A.5.5 0 0 0 14.5 12V4a.5.5 0 0 0-.277-.447l-6.5-3.25zM7.5 1.223l5.036 2.518-2.5 1.25L5 2.473l2.5-1.25zm-1 0l-5.036 2.518 2.5 1.25L11 2.473l-2.5-1.25zm1 13.554V6.223L2 4v8l5.5 2.777zm1 0L14 12V4l-5.5 2.223v8.554z" />
                                    </svg>
                                </i>
                                <span class="item-name">Kế hoạch Nguyên Liệu</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link route('admin.checkemployee.view-employee-todo')"
                                href="{{ route('admin.checkemployee.view-employee-todo') }}"
                                title="Lịch Hoạt Động Trong Ngày">
                                <i class="icon-svg">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20"
                                        height="20" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-calendar">
                                        <rect x="3" y="4" width="18" height="18" rx="2"
                                            ry="2">
                                        </rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                </i>
                                <span class="item-name">
                                    Lịch hoạt động / ngày
                                </span>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Mã Vạch">
                                <i class="icon-svg">
                                    <!-- Biểu tượng máy in hoặc con tem -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                                        <path
                                            d="M2 2a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2v3a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1v-3a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H2zm0 1h12a1 1 0 0 1 1 1v4H1V4a1 1 0 0 1 1-1zm11 10H3v-2h10v2zM2 12a1 1 0 0 1-1-1v-2h14v2a1 1 0 0 1-1 1H2zm9-7H5v2h6V5z" />
                                    </svg>
                                </i>
                                <span class="item-name">Tạo Tem</span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('admin.product.barcode') }}">
                                    <i class="icon-svg me-2">
                                        <!-- Biểu tượng hộp hoặc thùng -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-box-seam" viewBox="0 0 16 16">
                                            <path
                                                d="M9.35 1.176a1 1 0 0 0-.7 0l-6.5 2.7A1 1 0 0 0 2 4.77v6.458a1 1 0 0 0 .65.939l6.5 2.7a1 1 0 0 0 .7 0l6.5-2.7a1 1 0 0 0 .65-.939V4.771a1 1 0 0 0-.65-.938l-6.5-2.7zM8 2.317L13.661 4.6 8 6.884 2.339 4.6 8 2.317zm6 9.84-5.5 2.286v-4.943l5.5-2.286v4.943zm-7-4.943v4.943L1.5 12.157V7.214l5.5 2.285z" />
                                        </svg>
                                    </i>
                                    <span class="item-name">Tạo Tem Thùng</span>
                                </a>
                                <a class="dropdown-item" href="{{ route('admin.product.packing') }}">
                                    <i class="icon-svg me-2">
                                        <!-- Biểu tượng túi hoặc bịch -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-bag-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M5.5 2a3.5 3.5 0 1 1 7 0h1a1 1 0 0 1 1 1v11a1 1 0 0 1-1 1H2.5a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h1zm7 0a2.5 2.5 0 1 0-5 0h5z" />
                                        </svg>
                                    </i>
                                    <span class="item-name">Tạo Tem Bịch</span>
                                </a>
                                <a class="dropdown-item" href="{{ route('admin.product.barcode.history') }}">
                                    <i class="icon-svg me-2">
                                        <!-- Biểu tượng túi hoặc bịch -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-archive-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M12.643 0H3.357C2.4 0 1.643.743 1.643 1.657V3h12.714V1.657C14.357.743 13.6 0 12.643 0zM0 4v10.5C0 15.327.673 16 1.5 16h13c.827 0 1.5-.673 1.5-1.5V4H0zm4.5 4h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1zm0 2h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1z" />
                                        </svg>
                                    </i>
                                    <span class="item-name">Lịch Sử In Tem</span>
                                </a>
                            </div>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.attendence.index') }}" title="Bảng Chấm Công">
                                <i class="icon-svg">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"
                                            fill="currentColor" />
                                        <path d="M13 7h-2v6l5.293 2.707.707-1.707-4-2V7z" fill="currentColor" />
                                    </svg>
                                </i>
                                <span class="item-name">Bảng Chấm Công</span>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link route('admin.checkpo.index')"
                                href="{{ route('admin.checkpo.index') }}" title="Kiểm Tra PO">
                                <i class="icon-svg">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12 1.5C6.20101 1.5 1.5 6.20101 1.5 12C1.5 17.799 6.20101 22.5 12 22.5C17.799 22.5 22.5 17.799 22.5 12C22.5 6.20101 17.799 1.5 12 1.5ZM10.75 17L6.25 12.5L7.66406 11.0859L10.75 14.1641L16.3359 8.66406L17.75 10.0859L10.75 17Z"
                                            fill="currentColor" />
                                    </svg>
                                </i>
                                <span class="item-name">Kiểm tra PO</span>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link route('admin.history.home')" href="{{ route('admin.history.home') }}"
                                title="Lịch Sử">
                                <i class="icon-svg">
                                    <svg width="20" height="20" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512">
                                        <path
                                            d="M504 255.5c.3 136.6-111.2 248.4-247.8 248.5-59 0-113.2-20.5-155.8-54.9-11.1-8.9-11.9-25.5-1.8-35.6l11.3-11.3c8.6-8.6 22.4-9.6 31.9-2C173.1 425.1 212.8 440 256 440c101.7 0 184-82.3 184-184 0-101.7-82.3-184-184-184-48.8 0-93.1 19-126.1 49.9l50.8 50.8c10.1 10.1 2.9 27.3-11.3 27.3H24c-8.8 0-16-7.2-16-16V38.6c0-14.3 17.2-21.4 27.3-11.3l49.4 49.4C129.2 34.1 189.6 8 256 8c136.8 0 247.7 110.8 248 247.5zm-180.9 78.8l9.8-12.6c8.1-10.5 6.3-25.5-4.2-33.7L288 256.3V152c0-13.3-10.7-24-24-24h-16c-13.3 0-24 10.7-24 24v135.7l65.4 50.9c10.5 8.1 25.5 6.3 33.7-4.2z" />
                                    </svg>
                                </i>
                                <span class="item-name">Lịch sử</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->role->role_name == 'manager' || auth()->user()->role->role_name == 'admin')
                        <li class="nav-item">
                            <a class="nav-link route('admin.celender.home')"
                                href="{{ route('admin.celender.home') }}" title="Lịch Làm Việc">
                                <i class="icon-svg"><svg width="20" height="20" id="Layer_1"
                                        data-name="Layer 1" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 122.88 118.34">
                                        <defs>
                                            <style>
                                                .cls-1 {
                                                    fill-rule: evenodd;
                                                }
                                            </style>
                                        </defs>
                                        {{-- <title>work-schedule</title> --}}
                                        <path class="cls-1"
                                            d="M95.53,63.65A27.35,27.35,0,1,1,68.19,91,27.35,27.35,0,0,1,95.53,63.65ZM71.59,4.05c0-2.23,2.21-4,4.94-4s4.94,1.82,4.94,4.05V22.9c0,2.24-2.21,4.05-4.94,4.05s-4.94-1.81-4.94-4.05V4.05Zm-44.26,0c0-2.23,2.21-4,4.94-4s4.95,1.82,4.95,4.05V22.9C37.22,25.14,35,27,32.27,27s-4.94-1.81-4.94-4.05V4.05ZM63.91,111.92H10.24A10.28,10.28,0,0,1,0,101.68V20.54A10.29,10.29,0,0,1,10.24,10.3h9.44V22.9a11.24,11.24,0,0,0,4.26,8.75,13.25,13.25,0,0,0,16.67,0,11.24,11.24,0,0,0,4.26-8.75V10.3H63.94V22.9a11.23,11.23,0,0,0,4.25,8.75,13.26,13.26,0,0,0,16.68,0,11.26,11.26,0,0,0,4.25-8.75V10.3H99a10.28,10.28,0,0,1,10.24,10.24V55.63a38.34,38.34,0,0,0-4.37-1.4V39.94H4.37V99.5a8.08,8.08,0,0,0,8.05,8h49a40.11,40.11,0,0,0,2.5,4.37ZM19.68,56.24l3.46,3.25,7.09-7.21c.73-.75,1.2-1.35,2.11-.41l3,3c1,1,.91,1.52,0,2.42L24.82,67.58c-1.92,1.89-1.59,2-3.55.07l-6.56-6.53a.85.85,0,0,1,.08-1.33l3.43-3.55c.51-.54.93-.51,1.46,0ZM48,51.71H62.68a1.87,1.87,0,0,1,1.87,1.86V65.78a1.89,1.89,0,0,1-1.87,1.87H48a1.88,1.88,0,0,1-1.87-1.87V53.57A1.88,1.88,0,0,1,48,51.71Zm29.59,0H92.27a1.89,1.89,0,0,1,1.81,1.4,37.79,37.79,0,0,0-18.35,5.55V53.57a1.87,1.87,0,0,1,1.87-1.86ZM48,77.66H60A37.81,37.81,0,0,0,57.62,91c0,.87,0,1.74.09,2.6H48a1.88,1.88,0,0,1-1.87-1.87V79.53A1.88,1.88,0,0,1,48,77.66Zm-29.58,0H33.1A1.87,1.87,0,0,1,35,79.53v12.2A1.89,1.89,0,0,1,33.1,93.6H18.43a1.87,1.87,0,0,1-1.87-1.87V79.53a1.87,1.87,0,0,1,1.87-1.87Zm73.31-.43h3.34a1.12,1.12,0,0,1,1.12,1.12V91.23H108a1.12,1.12,0,0,1,1.12,1.11v3.35A1.12,1.12,0,0,1,108,96.8H90.63V78.35a1.12,1.12,0,0,1,1.11-1.12Zm3.79-7.37A21.14,21.14,0,1,1,74.4,91,21.13,21.13,0,0,1,95.53,69.86Z" />
                                    </svg></i>
                                <span class="item-name">Lịch làm việc</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link route('admin.category.home')"
                                href="{{ route('admin.category.home') }}" title="Danh Mục Lịch Làm Việc">
                                <i class="icon-svg"><svg width="20" height="20" version="1.1"
                                        id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                        viewBox="0 0 99.39 122.88" style="enable-background:new 0 0 99.39 122.88"
                                        xml:space="preserve">
                                        <path
                                            d="M65.72,12.72c-0.31,0-0.58-0.04-0.85-0.13c-1.38,0-2.54-1.12-2.54-2.54v-5H37.7v5c0,1.29-1.03,2.41-2.28,2.5 c-0.27,0.09-0.58,0.13-0.89,0.13h-9.28v10.35h48.59V12.67h-8.21L65.72,12.72L65.72,12.72z M22.85,75.03c2.76,0,5,2.24,5,5 s-2.24,5-5,5s-5-2.24-5-5S20.09,75.03,22.85,75.03L22.85,75.03z M17.07,62.7c-0.69-1.03-0.42-2.43,0.62-3.12 c1.03-0.69,2.43-0.42,3.12,0.62l1.23,1.82l4.87-5.92c0.79-0.96,2.21-1.1,3.17-0.31c0.96,0.79,1.1,2.21,0.31,3.17l-6.74,8.2 c-0.15,0.19-0.33,0.36-0.54,0.51c-1.03,0.69-2.43,0.42-3.12-0.62L17.07,62.7L17.07,62.7z M17.07,45.38 c-0.69-1.03-0.42-2.43,0.62-3.12c1.03-0.69,2.43-0.42,3.12,0.62l1.23,1.82l4.87-5.93c0.79-0.96,2.21-1.1,3.17-0.31 c0.96,0.79,1.1,2.21,0.31,3.17l-6.74,8.2c-0.15,0.19-0.33,0.36-0.54,0.51c-1.03,0.69-2.43,0.42-3.12-0.62L17.07,45.38L17.07,45.38z M69.2,122.21c-0.45,0.4-1.07,0.67-1.7,0.67c-0.13,0-0.27,0-0.4-0.04H5.62c-1.52,0-2.94-0.62-3.97-1.65 C0.62,120.16,0,118.78,0,117.21l0-97.36c0-1.56,0.62-2.94,1.65-3.97c1.03-1.03,2.41-1.65,3.97-1.65h14.63v-2.77 c0-1.03,0.4-1.96,1.12-2.68c0.67-0.67,1.61-1.12,2.68-1.12h8.66V4.19c0-1.16,0.49-2.19,1.25-2.94C34.71,0.49,35.74,0,36.9,0h26.37 c1.16,0,2.19,0.49,2.94,1.25c0.76,0.76,1.25,1.78,1.25,2.94v3.48h7.81c1.03,0,1.96,0.45,2.68,1.12c0.67,0.67,1.12,1.65,1.12,2.68 v2.77h14.63c1.56,0,2.94,0.62,3.97,1.65c1.03,1.03,1.65,2.41,1.65,3.97v70.23c0.2,1.01-0.01,1.79-0.76,2.54l-29.05,29.4 c-0.09,0.09-0.13,0.13-0.22,0.18H69.2L69.2,122.21z M64.96,117.79c0-33.62-4.24-29.63,29.22-29.63V19.85c0-0.13-0.04-0.31-0.18-0.4 c-0.09-0.09-0.22-0.18-0.4-0.18l-14.63,0v5.09c0,1.03-0.4,1.96-1.12,2.68c-0.67,0.67-1.61,1.12-2.68,1.12H24 c-1.03,0-2.01-0.45-2.68-1.12c-0.09-0.09-0.13-0.18-0.22-0.27c-0.54-0.67-0.89-1.52-0.89-2.41v-5.09H5.58 c-0.13,0-0.31,0.04-0.4,0.18C5.09,19.54,5,19.72,5,19.85v97.36c0,0.18,0.04,0.31,0.18,0.4c0.09,0.09,0.22,0.18,0.4,0.18h59.34 H64.96L64.96,117.79z M41.23,81.8c-1.38,0-2.54-1.12-2.54-2.54c0-1.38,1.12-2.54,2.54-2.54h24.13c1.38,0,2.54,1.12,2.54,2.54 c0,1.38-1.12,2.54-2.54,2.54H41.23L41.23,81.8z M41.23,45.52c-1.38,0-2.54-1.12-2.54-2.54c0-1.38,1.12-2.54,2.54-2.54h37.16 c1.38,0,2.54,1.12,2.54,2.54c0,1.38-1.12,2.54-2.54,2.54L41.23,45.52L41.23,45.52z M41.23,63.66c-1.38,0-2.54-1.12-2.54-2.54 c0-1.38,1.12-2.54,2.54-2.54h37.16c1.38,0,2.54,1.12,2.54,2.54c0,1.38-1.12,2.54-2.54,2.54H41.23L41.23,63.66z" />
                                    </svg></i>
                                <span class="item-name">Danh mục lịch làm việc</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->role->role_name == 'accountant' || auth()->user()->role->role_name == 'admin')
                        <li class="nav-item">
                            <a class="nav-link route('admin.salary.home')" href="{{ route('admin.salary.home') }}"
                                title="Bảng Lương">
                                <i class="icon-svg"><svg width="20" height="20" id="Layer_1"
                                        data-name="Layer 1" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 122.88 120.1">
                                        <defs>
                                            <style>
                                                .cls-1 {
                                                    fill-rule: evenodd;
                                                }
                                            </style>
                                        </defs>
                                        {{-- <title>payday</title> --}}
                                        <path class="cls-1"
                                            d="M65.82,3.83C65.82,1.73,67.9,0,70.49,0s4.67,1.71,4.67,3.83V20.57c0,2.1-2.07,3.83-4.67,3.83s-4.67-1.71-4.67-3.83V3.83Zm57.06,53L81,120.1,48.52,99.26h-41A7.55,7.55,0,0,1,0,91.72V17.35A7.55,7.55,0,0,1,7.54,9.81h9.1a2.59,2.59,0,0,1,0,5.17H7.54a2.33,2.33,0,0,0-1.66.69,2.36,2.36,0,0,0-.69,1.66V36.61H68.3l-3.41,5.16H5.14V91.69a2.35,2.35,0,0,0,.69,1.66A2.41,2.41,0,0,0,7.49,94H46.41l3.42,0,24.44,15.52.3.21a5,5,0,0,0,6.86-1.58L82.77,106l.07,0L89.15,95.8l28.7-42.22,5,3.22ZM13.56,73.65h10.7a1.24,1.24,0,0,1,1.24,1.23v7.91A1.24,1.24,0,0,1,24.26,84H13.56a1.24,1.24,0,0,1-1.24-1.23V74.88a1.23,1.23,0,0,1,1.24-1.23Zm41-22.54H58.7L53.29,59.3V52.36a1.25,1.25,0,0,1,1.24-1.25ZM34,51.11h10.7A1.25,1.25,0,0,1,46,52.35v7.91a1.24,1.24,0,0,1-1.24,1.23H34a1.23,1.23,0,0,1-1.23-1.23V52.35A1.24,1.24,0,0,1,34,51.11Zm-20.48,0h10.7a1.25,1.25,0,0,1,1.24,1.24v7.91a1.24,1.24,0,0,1-1.24,1.23H13.56a1.24,1.24,0,0,1-1.24-1.23V52.35a1.25,1.25,0,0,1,1.24-1.24ZM34,73.65H43.8l-5.29,8a6.19,6.19,0,0,0-.9,2.38H34a1.23,1.23,0,0,1-1.23-1.23V74.88A1.22,1.22,0,0,1,34,73.65ZM23.9,3.83C23.9,1.73,26,0,28.57,0s4.67,1.71,4.67,3.83V20.57c0,2.1-2.08,3.83-4.67,3.83s-4.67-1.71-4.67-3.83V3.83ZM94,19.78V17.33a2.34,2.34,0,0,0-.68-1.66A2.41,2.41,0,0,0,91.69,15H83.17a2.59,2.59,0,1,1,0-5.17h8.52a7.55,7.55,0,0,1,7.54,7.54v5.76L94,19.78ZM40.7,15a2.59,2.59,0,0,1,0-5.18H58.05a2.59,2.59,0,0,1,0,5.18ZM119,43.09,77.1,106.38,43.71,85,85.57,21.68,119,43.09ZM88.44,60.37a9,9,0,1,1-12.32-3.3,9,9,0,0,1,12.32,3.3ZM107,50.57,78.93,92.33a5.88,5.88,0,0,0-8.1,1.78L57.62,85.66a5.87,5.87,0,0,0-1.78-8.1L83.92,35.78A5.86,5.86,0,0,0,92,34l13.21,8.45A5.88,5.88,0,0,0,107,50.57Z" />
                                    </svg></i>
                                <span class="item-name">Bảng lương</span>
                            </a>
                        </li>
                    @endif
                @endif
                @if (auth()->user()->role->role_name != 'admin' &&
                        auth()->user()->role->role_name != 'manager' &&
                        auth()->user()->role->role_name != 'accountant')
                    <li class="nav-item">
                        <a class="nav-link route('admin.home')" href="{{ route('admin.home') }}" title="Trang Chủ">
                            <i class="icon-svg"><svg width="24px" height="24px" viewBox="0 0 24 24"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M22 22L2 22" stroke="#1C274C" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path
                                        d="M2 11L6.06296 7.74968M22 11L13.8741 4.49931C12.7784 3.62279 11.2216 3.62279 10.1259 4.49931L9.34398 5.12486"
                                        stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                    <path
                                        d="M15.5 5.5V3.5C15.5 3.22386 15.7239 3 16 3H18.5C18.7761 3 19 3.22386 19 3.5V8.5"
                                        stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M4 22V9.5" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M20 9.5V13.5M20 22V17.5" stroke="#1C274C" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path
                                        d="M15 22V17C15 15.5858 15 14.8787 14.5607 14.4393C14.1213 14 13.4142 14 12 14C10.5858 14 9.87868 14 9.43934 14.4393M9 22V17"
                                        stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M14 9.5C14 10.6046 13.1046 11.5 12 11.5C10.8954 11.5 10 10.6046 10 9.5C10 8.39543 10.8954 7.5 12 7.5C13.1046 7.5 14 8.39543 14 9.5Z"
                                        stroke="#1C274C" stroke-width="1.5" />
                                </svg></i>
                            <span class="item-name">Trang Chủ</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link route('admin.employee-show.celender')"
                            href="{{ route('admin.employee-show.celender') }}" title="Lịch Làm Việc">
                            <i class="icon-svg"><svg width="20" height="20" id="Layer_1"
                                    data-name="Layer 1" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 122.88 118.34">
                                    <defs>
                                        <style>
                                            .cls-1 {
                                                fill-rule: evenodd;
                                            }
                                        </style>
                                    </defs>
                                    {{-- <title>work-schedule</title> --}}
                                    <path class="cls-1"
                                        d="M95.53,63.65A27.35,27.35,0,1,1,68.19,91,27.35,27.35,0,0,1,95.53,63.65ZM71.59,4.05c0-2.23,2.21-4,4.94-4s4.94,1.82,4.94,4.05V22.9c0,2.24-2.21,4.05-4.94,4.05s-4.94-1.81-4.94-4.05V4.05Zm-44.26,0c0-2.23,2.21-4,4.94-4s4.95,1.82,4.95,4.05V22.9C37.22,25.14,35,27,32.27,27s-4.94-1.81-4.94-4.05V4.05ZM63.91,111.92H10.24A10.28,10.28,0,0,1,0,101.68V20.54A10.29,10.29,0,0,1,10.24,10.3h9.44V22.9a11.24,11.24,0,0,0,4.26,8.75,13.25,13.25,0,0,0,16.67,0,11.24,11.24,0,0,0,4.26-8.75V10.3H63.94V22.9a11.23,11.23,0,0,0,4.25,8.75,13.26,13.26,0,0,0,16.68,0,11.26,11.26,0,0,0,4.25-8.75V10.3H99a10.28,10.28,0,0,1,10.24,10.24V55.63a38.34,38.34,0,0,0-4.37-1.4V39.94H4.37V99.5a8.08,8.08,0,0,0,8.05,8h49a40.11,40.11,0,0,0,2.5,4.37ZM19.68,56.24l3.46,3.25,7.09-7.21c.73-.75,1.2-1.35,2.11-.41l3,3c1,1,.91,1.52,0,2.42L24.82,67.58c-1.92,1.89-1.59,2-3.55.07l-6.56-6.53a.85.85,0,0,1,.08-1.33l3.43-3.55c.51-.54.93-.51,1.46,0ZM48,51.71H62.68a1.87,1.87,0,0,1,1.87,1.86V65.78a1.89,1.89,0,0,1-1.87,1.87H48a1.88,1.88,0,0,1-1.87-1.87V53.57A1.88,1.88,0,0,1,48,51.71Zm29.59,0H92.27a1.89,1.89,0,0,1,1.81,1.4,37.79,37.79,0,0,0-18.35,5.55V53.57a1.87,1.87,0,0,1,1.87-1.86ZM48,77.66H60A37.81,37.81,0,0,0,57.62,91c0,.87,0,1.74.09,2.6H48a1.88,1.88,0,0,1-1.87-1.87V79.53A1.88,1.88,0,0,1,48,77.66Zm-29.58,0H33.1A1.87,1.87,0,0,1,35,79.53v12.2A1.89,1.89,0,0,1,33.1,93.6H18.43a1.87,1.87,0,0,1-1.87-1.87V79.53a1.87,1.87,0,0,1,1.87-1.87Zm73.31-.43h3.34a1.12,1.12,0,0,1,1.12,1.12V91.23H108a1.12,1.12,0,0,1,1.12,1.11v3.35A1.12,1.12,0,0,1,108,96.8H90.63V78.35a1.12,1.12,0,0,1,1.11-1.12Zm3.79-7.37A21.14,21.14,0,1,1,74.4,91,21.13,21.13,0,0,1,95.53,69.86Z" />
                                </svg></i>
                            <span class="item-name">Lịch làm việc</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link route('admin.employee-show.salary')"
                            href="{{ route('admin.employee-show.salary') }}" title="Bảng Lương">
                            <i class="icon-svg"><svg width="20" height="20" id="Layer_1"
                                    data-name="Layer 1" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 122.88 120.1">
                                    <defs>
                                        <style>
                                            .cls-1 {
                                                fill-rule: evenodd;
                                            }
                                        </style>
                                    </defs>
                                    {{-- <title>payday</title> --}}
                                    <path class="cls-1"
                                        d="M65.82,3.83C65.82,1.73,67.9,0,70.49,0s4.67,1.71,4.67,3.83V20.57c0,2.1-2.07,3.83-4.67,3.83s-4.67-1.71-4.67-3.83V3.83Zm57.06,53L81,120.1,48.52,99.26h-41A7.55,7.55,0,0,1,0,91.72V17.35A7.55,7.55,0,0,1,7.54,9.81h9.1a2.59,2.59,0,0,1,0,5.17H7.54a2.33,2.33,0,0,0-1.66.69,2.36,2.36,0,0,0-.69,1.66V36.61H68.3l-3.41,5.16H5.14V91.69a2.35,2.35,0,0,0,.69,1.66A2.41,2.41,0,0,0,7.49,94H46.41l3.42,0,24.44,15.52.3.21a5,5,0,0,0,6.86-1.58L82.77,106l.07,0L89.15,95.8l28.7-42.22,5,3.22ZM13.56,73.65h10.7a1.24,1.24,0,0,1,1.24,1.23v7.91A1.24,1.24,0,0,1,24.26,84H13.56a1.24,1.24,0,0,1-1.24-1.23V74.88a1.23,1.23,0,0,1,1.24-1.23Zm41-22.54H58.7L53.29,59.3V52.36a1.25,1.25,0,0,1,1.24-1.25ZM34,51.11h10.7A1.25,1.25,0,0,1,46,52.35v7.91a1.24,1.24,0,0,1-1.24,1.23H34a1.23,1.23,0,0,1-1.23-1.23V52.35A1.24,1.24,0,0,1,34,51.11Zm-20.48,0h10.7a1.25,1.25,0,0,1,1.24,1.24v7.91a1.24,1.24,0,0,1-1.24,1.23H13.56a1.24,1.24,0,0,1-1.24-1.23V52.35a1.25,1.25,0,0,1,1.24-1.24ZM34,73.65H43.8l-5.29,8a6.19,6.19,0,0,0-.9,2.38H34a1.23,1.23,0,0,1-1.23-1.23V74.88A1.22,1.22,0,0,1,34,73.65ZM23.9,3.83C23.9,1.73,26,0,28.57,0s4.67,1.71,4.67,3.83V20.57c0,2.1-2.08,3.83-4.67,3.83s-4.67-1.71-4.67-3.83V3.83ZM94,19.78V17.33a2.34,2.34,0,0,0-.68-1.66A2.41,2.41,0,0,0,91.69,15H83.17a2.59,2.59,0,1,1,0-5.17h8.52a7.55,7.55,0,0,1,7.54,7.54v5.76L94,19.78ZM40.7,15a2.59,2.59,0,0,1,0-5.18H58.05a2.59,2.59,0,0,1,0,5.18ZM119,43.09,77.1,106.38,43.71,85,85.57,21.68,119,43.09ZM88.44,60.37a9,9,0,1,1-12.32-3.3,9,9,0,0,1,12.32,3.3ZM107,50.57,78.93,92.33a5.88,5.88,0,0,0-8.1,1.78L57.62,85.66a5.87,5.87,0,0,0-1.78-8.1L83.92,35.78A5.86,5.86,0,0,0,92,34l13.21,8.45A5.88,5.88,0,0,0,107,50.57Z" />
                                </svg></i>
                            <span class="item-name">Bảng Lương</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link route('admin.employee.attendence_record')"
                            href="{{ route('admin.employee.attendence_record') }}" title="Bảng Chấm Công">
                            <i class="icon-svg"><svg width="20" height="20" id="Layer_1"
                                    data-name="Layer 1" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 122.88 120.1">
                                    <defs>
                                        <style>
                                            .cls-1 {
                                                fill-rule: evenodd;
                                            }
                                        </style>
                                    </defs>
                                    {{-- <title>payday</title> --}}
                                    <path class="cls-1"
                                        d="M65.82,3.83C65.82,1.73,67.9,0,70.49,0s4.67,1.71,4.67,3.83V20.57c0,2.1-2.07,3.83-4.67,3.83s-4.67-1.71-4.67-3.83V3.83Zm57.06,53L81,120.1,48.52,99.26h-41A7.55,7.55,0,0,1,0,91.72V17.35A7.55,7.55,0,0,1,7.54,9.81h9.1a2.59,2.59,0,0,1,0,5.17H7.54a2.33,2.33,0,0,0-1.66.69,2.36,2.36,0,0,0-.69,1.66V36.61H68.3l-3.41,5.16H5.14V91.69a2.35,2.35,0,0,0,.69,1.66A2.41,2.41,0,0,0,7.49,94H46.41l3.42,0,24.44,15.52.3.21a5,5,0,0,0,6.86-1.58L82.77,106l.07,0L89.15,95.8l28.7-42.22,5,3.22ZM13.56,73.65h10.7a1.24,1.24,0,0,1,1.24,1.23v7.91A1.24,1.24,0,0,1,24.26,84H13.56a1.24,1.24,0,0,1-1.24-1.23V74.88a1.23,1.23,0,0,1,1.24-1.23Zm41-22.54H58.7L53.29,59.3V52.36a1.25,1.25,0,0,1,1.24-1.25ZM34,51.11h10.7A1.25,1.25,0,0,1,46,52.35v7.91a1.24,1.24,0,0,1-1.24,1.23H34a1.23,1.23,0,0,1-1.23-1.23V52.35A1.24,1.24,0,0,1,34,51.11Zm-20.48,0h10.7a1.25,1.25,0,0,1,1.24,1.24v7.91a1.24,1.24,0,0,1-1.24,1.23H13.56a1.24,1.24,0,0,1-1.24-1.23V52.35a1.25,1.25,0,0,1,1.24-1.24ZM34,73.65H43.8l-5.29,8a6.19,6.19,0,0,0-.9,2.38H34a1.23,1.23,0,0,1-1.23-1.23V74.88A1.22,1.22,0,0,1,34,73.65ZM23.9,3.83C23.9,1.73,26,0,28.57,0s4.67,1.71,4.67,3.83V20.57c0,2.1-2.08,3.83-4.67,3.83s-4.67-1.71-4.67-3.83V3.83ZM94,19.78V17.33a2.34,2.34,0,0,0-.68-1.66A2.41,2.41,0,0,0,91.69,15H83.17a2.59,2.59,0,1,1,0-5.17h8.52a7.55,7.55,0,0,1,7.54,7.54v5.76L94,19.78ZM40.7,15a2.59,2.59,0,0,1,0-5.18H58.05a2.59,2.59,0,0,1,0,5.18ZM119,43.09,77.1,106.38,43.71,85,85.57,21.68,119,43.09ZM88.44,60.37a9,9,0,1,1-12.32-3.3,9,9,0,0,1,12.32,3.3ZM107,50.57,78.93,92.33a5.88,5.88,0,0,0-8.1,1.78L57.62,85.66a5.87,5.87,0,0,0-1.78-8.1L83.92,35.78A5.86,5.86,0,0,0,92,34l13.21,8.45A5.88,5.88,0,0,0,107,50.57Z" />
                                </svg></i>
                            <span class="item-name">Bảng Chấm Công</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link route('admin.employee.check-employee-todo')"
                            href="{{ route('admin.employee.check-employee-todo') }}" title="Chọn Sản Phẩm">
                            <i class="icon-svg"><svg fill="#000000" width="24px" height="24px"
                                    viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="m47.44 61.66a1 1 0 0 1 1 .91v14.37a3.06 3.06 0 0 1 -2.87 3h-20.49a3.06 3.06 0 0 1 -3-2.88v-14.38a1 1 0 0 1 .91-1h24.5zm29.51 0a1 1 0 0 1 1 .91v14.37a3.06 3.06 0 0 1 -2.87 3h-20.49a3.06 3.06 0 0 1 -3-2.88v-14.38a1 1 0 0 1 .91-1h24.5zm-37.36 4.23-.09.11-5.82 6.32-2.63-2.55a.77.77 0 0 0 -1-.08l-.09.08-1.09 1a.62.62 0 0 0 -.07.9l.07.08 3.73 3.54a1.56 1.56 0 0 0 1.08.45 1.43 1.43 0 0 0 1.09-.45l3.14-3.32.63-.67 3.14-3.31a.78.78 0 0 0 .06-.9l-.06-.08-1.09-1a.76.76 0 0 0 -1-.12zm29.51 0-.1.11-5.82 6.32-2.64-2.55a.75.75 0 0 0 -1-.08l-.09.08-1.09 1a.62.62 0 0 0 -.07.9l.07.08 3.73 3.54a1.54 1.54 0 0 0 1.08.45 1.43 1.43 0 0 0 1.09-.45l3.14-3.32.63-.67 3.14-3.31a.78.78 0 0 0 .06-.9l-.06-.08-1.07-1.01a.76.76 0 0 0 -1-.11zm-23.43-14.41a3 3 0 0 1 2.85 2.87v3.24a1 1 0 0 1 -.84 1h-26.68a1 1 0 0 1 -.94-.9v-3.16a3 3 0 0 1 2.69-3.05h23zm31.48 0a3 3 0 0 1 2.85 2.87v3.24a1 1 0 0 1 -.84 1h-26.73a1 1 0 0 1 -1-.9v-3.16a3 3 0 0 1 2.68-3.05h23zm-15-21.29a1 1 0 0 1 1 .91v14.37a3.06 3.06 0 0 1 -2.87 3.05h-20.44a3.06 3.06 0 0 1 -3.05-2.87v-14.44a1 1 0 0 1 .9-1h24.51zm-7.85 4.22-.09.08-5.82 6.32-2.59-2.56a.76.76 0 0 0 -1-.07l-.09.07-1.08 1a.61.61 0 0 0 -.07.9l.07.08 3.72 3.53a1.56 1.56 0 0 0 1.09.45 1.43 1.43 0 0 0 1.08-.45l3.14-3.31.64-.67 3.13-3.32a.78.78 0 0 0 .06-.9l-.06-.07-1.08-1a.77.77 0 0 0 -1-.08zm7.9-14.41a3.06 3.06 0 0 1 3 2.88v3.23a1 1 0 0 1 -.91 1h-28.52a1 1 0 0 1 -1-.91v-3.14a3.06 3.06 0 0 1 2.87-3h24.56z" />
                                </svg></i>
                            <span class="item-name">Chọn Sản Phẩm</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link route('admin.employee-history-check')"
                            href="{{ route('admin.employee-history-check') }}" title="Lịch Sử Hoạt Động">
                            <i class="icon-svg"><svg fill="#000000" width="24px" height="24px"
                                    viewBox="0 0 100 100" version="1.1" xml:space="preserve"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <g>
                                        <path
                                            d="M78.9,40.4C79,40.3,79,40.1,79,40V9c0-2.8-2.2-5-5-5H14c-2.8,0-5,2.2-5,5v60c0,2.8,2.2,5,5,5h16c0.1,0,0.2,0,0.3-0.1    C34.2,86.7,46,96,60,96c17.1,0,31-13.9,31-31C91,55,86.3,46.1,78.9,40.4z M14,6h60c1.7,0,3,1.3,3,3v7H11V9C11,7.3,12.3,6,14,6z     M14,72c-1.7,0-3-1.3-3-3V18h66v21c-3.3-2.1-7-3.7-11-4.5V30c0-0.4-0.2-0.7-0.5-0.9c-0.3-0.2-0.7-0.2-1,0l-13,8    C51.2,37.3,51,37.7,51,38c0,0.3,0.2,0.7,0.5,0.9l13,8c0.3,0.2,0.7,0.2,1,0c0.3-0.2,0.5-0.5,0.5-0.9v-4.2C76.5,44.5,84,54,84,65    c0,13.2-10.8,24-24,24S36,78.2,36,65c0-8.4,4.5-16.3,11.7-20.6c0.4-0.3,0.6-0.8,0.4-1.3l-2-4.6c-0.1-0.3-0.3-0.5-0.6-0.5    c-0.3-0.1-0.6-0.1-0.8,0.1C35,43.5,29,53.9,29,65c0,2.4,0.3,4.7,0.8,7H14z M60,94c-16,0-29-13-29-29c0-10,5.2-19.4,13.7-24.6    l1.2,2.8C38.6,47.9,34,56.2,34,65c0,14.3,11.7,26,26,26s26-11.7,26-26c0-12.3-8.7-23-20.8-25.5c-0.3-0.1-0.6,0-0.8,0.2    c-0.2,0.2-0.4,0.5-0.4,0.8v3.7L53.9,38L64,31.8v3.6c0,0.5,0.4,0.9,0.8,1C78.8,38.8,89,50.8,89,65C89,81,76,94,60,94z" />
                                        <path
                                            d="M60,69c1.9,0,3.4-1.3,3.9-3H79c0.6,0,1-0.4,1-1s-0.4-1-1-1H63.9c-0.4-1.4-1.5-2.5-2.9-2.9V52c0-0.6-0.4-1-1-1s-1,0.4-1,1    v9.1c-1.7,0.4-3,2-3,3.9C56,67.2,57.8,69,60,69z M60,63c1.1,0,2,0.9,2,2s-0.9,2-2,2s-2-0.9-2-2S58.9,63,60,63z" />
                                        <circle cx="71" cy="11" r="2" />
                                        <circle cx="65" cy="11" r="2" />
                                        <circle cx="59" cy="11" r="2" />
                                        <path
                                            d="M23,21h-7c-0.6,0-1,0.4-1,1s0.4,1,1,1h7c0.6,0,1-0.4,1-1S23.6,21,23,21z" />
                                        <path
                                            d="M23,25h-7c-0.6,0-1,0.4-1,1s0.4,1,1,1h7c0.6,0,1-0.4,1-1S23.6,25,23,25z" />
                                        <path
                                            d="M23,29h-7c-0.6,0-1,0.4-1,1s0.4,1,1,1h7c0.6,0,1-0.4,1-1S23.6,29,23,29z" />
                                    </g>
                                </svg></i>
                            <span class="item-name">Lịch Sử Nhập Sản Phẩm</span>
                        </a>
                    </li>

                    @if (Auth()->user()->role->role_name == 'QA-QC')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Mã Vạch">
                                <i class="icon-svg">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        fill="currentColor" class="bi bi-tags-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M2 2a1 1 0 0 1 1-1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 2 6.586zm3.5 4a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3" />
                                        <path
                                            d="M1.293 7.793A1 1 0 0 1 1 7.086V2a1 1 0 0 0-1 1v4.586a1 1 0 0 0 .293.707l7 7a1 1 0 0 0 1.414 0l.043-.043z" />
                                    </svg>
                                </i>
                                <span class="item-name">Tạo Tem</span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('admin.product.barcode') }}">
                                    <i class="icon-svg me-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-qr-code-scan" viewBox="0 0 16 16">
                                            <path
                                                d="M0 .5A.5.5 0 0 1 .5 0h3a.5.5 0 0 1 0 1H1v2.5a.5.5 0 0 1-1 0zm12 0a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0V1h-2.5a.5.5 0 0 1-.5-.5M.5 12a.5.5 0 0 1 .5.5V15h2.5a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1 0-1H15v-2.5a.5.5 0 0 1 .5-.5M4 4h1v1H4z" />
                                            <path d="M7 2H2v5h5zM3 3h3v3H3zm2 8H4v1h1z" />
                                            <path d="M7 9H2v5h5zm-4 1h3v3H3zm8-6h1v1h-1z" />
                                            <path
                                                d="M9 2h5v5H9zm1 1v3h3V3zM8 8v2h1v1H8v1h2v-2h1v2h1v-1h2v-1h-3V8zm2 2H9V9h1zm4 2h-1v1h-2v1h3zm-4 2v-1H8v1z" />
                                            <path d="M12 9h2V8h-2z" />
                                        </svg>
                                    </i>
                                    <span class="item-name">Tạo Tem Thùng</span>
                                </a>
                                <a class="dropdown-item" href="{{ route('admin.product.packing') }}">
                                    <i class="icon-svg me-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-postage-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M4.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z" />
                                            <path
                                                d="M3.5 1a1 1 0 0 0 1-1h1a1 1 0 0 0 2 0h1a1 1 0 0 0 2 0h1a1 1 0 1 0 2 0H15v1a1 1 0 1 0 0 2v1a1 1 0 1 0 0 2v1a1 1 0 1 0 0 2v1a1 1 0 1 0 0 2v1a1 1 0 1 0 0 2v1h-1.5a1 1 0 1 0-2 0h-1a1 1 0 1 0-2 0h-1a1 1 0 1 0-2 0h-1a1 1 0 1 0-2 0H1v-1a1 1 0 1 0 0-2v-1a1 1 0 1 0 0-2V9a1 1 0 1 0 0-2V6a1 1 0 0 0 0-2V3a1 1 0 0 0 0-2V0h1.5a1 1 0 0 0 1 1M3 3v10a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1" />
                                        </svg>
                                    </i>
                                    <span class="item-name">Tạo Tem Bịch</span>
                                </a>

                                <a class="dropdown-item" href="{{ route('admin.product.barcode.history') }}">
                                    <i class="icon-svg me-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-archive-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M12.643 0H3.357C2.4 0 1.643.743 1.643 1.657V3h12.714V1.657C14.357.743 13.6 0 12.643 0zM0 4v10.5C0 15.327.673 16 1.5 16h13c.827 0 1.5-.673 1.5-1.5V4H0zm4.5 4h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1zm0 2h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1z" />
                                        </svg>
                                    </i>
                                    <span class="item-name">Lịch Sử In Tem</span>
                                </a>

                            </div>
                        </li>
                    @endif

                    @if (Auth()->user()->role_id == 4)
                        <li class="nav-item">
                            <a class="nav-link route('admin.barcode.scan')" href="{{ route('admin.barcode.scan') }}"
                                title="Mã Vạch">
                                <i class="icon-svg">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        fill="currentColor" class="bi bi-qr-code-scan" viewBox="0 0 16 16">
                                        <path
                                            d="M0 .5A.5.5 0 0 1 .5 0h3a.5.5 0 0 1 0 1H1v2.5a.5.5 0 0 1-1 0zm12 0a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0V1h-2.5a.5.5 0 0 1-.5-.5M.5 12a.5.5 0 0 1 .5.5V15h2.5a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1 0-1H15v-2.5a.5.5 0 0 1 .5-.5M4 4h1v1H4z" />
                                        <path d="M7 2H2v5h5zM3 3h3v3H3zm2 8H4v1h1z" />
                                        <path d="M7 9H2v5h5zm-4 1h3v3H3zm8-6h1v1h-1z" />
                                        <path
                                            d="M9 2h5v5H9zm1 1v3h3V3zM8 8v2h1v1H8v1h2v-2h1v2h1v-1h2v-1h-3V8zm2 2H9V9h1zm4 2h-1v1h-2v1h3zm-4 2v-1H8v1z" />
                                        <path d="M12 9h2V8h-2z" />
                                    </svg>
                                </i>
                                <span class="item-name">Mã Vạch</span>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link route('admin.profile')" href="{{ route('admin.profile') }}"
                            title="Thông Tin Tài Khoản">
                            <i class="icon-svg"><svg width="24px" height="24px" viewBox="0 0 16 16"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g color="#bebebe" fill="#2e3436">
                                        <path
                                            d="M6 0a3 3 0 100 6 3 3 0 000-6zM4.5 7A4.49 4.49 0 000 11.5v.5c0 1 1 1 1 1h6V8.875c0-.83.587-1.554 1.355-1.79A4.532 4.532 0 007.5 7zM9 9v4h1V9z"
                                            style="marker:none" overflow="visible" />
                                        <path
                                            d="M8.875 8A.863.863 0 008 8.875v6.25c0 .492.383.875.875.875h6.25a.863.863 0 00.875-.875v-6.25A.863.863 0 0015.125 8zM11 9h2v1h-2zm0 2h2v4h-2z"
                                            style="marker:none" overflow="visible" />
                                    </g>
                                </svg></i>
                            <span class="item-name">Thông Tin Tài Khoản</span>
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link route('logout')" href="{{ route('logout') }}">
                        <title>Logout</title>
                        <i class="icon-svg">
                            <svg width="24px" height="24px" viewBox="0 0 15 15" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M1 1L8 1V2L2 2L2 13H8V14H1L1 1ZM10.8536 4.14645L14.1932 7.48614L10.8674 11.0891L10.1326 10.4109L12.358 8L4 8V7L12.2929 7L10.1464 4.85355L10.8536 4.14645Z"
                                    fill="#000000" />
                            </svg>
                        </i>
                        <span class="item-name">Đăng xuất</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="sidebar-footer p-3 left-1 " style="position:absolute;bottom:0; width:100%;">
        <div class="nav-item text-center">
            <a class="nav-link route('logout')" href="{{ route('logout') }}">
                <svg width="20px" height="24px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg"
                    class="icon">
                    <title>Logout</title>
                    <path
                        d="M868 732h-70.3c-4.8 0-9.3 2.1-12.3 5.8-7 8.5-14.5 16.7-22.4 24.5a353.84 353.84 0 0 1-112.7 75.9A352.8 352.8 0 0 1 512.4 866c-47.9 0-94.3-9.4-137.9-27.8a353.84 353.84 0 0 1-112.7-75.9 353.28 353.28 0 0 1-76-112.5C167.3 606.2 158 559.9 158 512s9.4-94.2 27.8-137.8c17.8-42.1 43.4-80 76-112.5s70.5-58.1 112.7-75.9c43.6-18.4 90-27.8 137.9-27.8 47.9 0 94.3 9.3 137.9 27.8 42.2 17.8 80.1 43.4 112.7 75.9 7.9 7.9 15.3 16.1 22.4 24.5 3 3.7 7.6 5.8 12.3 5.8H868c6.3 0 10.2-7 6.7-12.3C798 160.5 663.8 81.6 511.3 82 271.7 82.6 79.6 277.1 82 516.4 84.4 751.9 276.2 942 512.4 942c152.1 0 285.7-78.8 362.3-197.7 3.4-5.3-.4-12.3-6.7-12.3zm88.9-226.3L815 393.7c-5.3-4.2-13-.4-13 6.3v76H488c-4.4 0-8 3.6-8 8v56c0 4.4 3.6 8 8 8h314v76c0 6.7 7.8 10.5 13 6.3l141.9-112a8 8 0 0 0 0-12.6z" />
                </svg> <span class="nav-link-text ms-1">Đăng xuất</span>
            </a>
        </div>
    </div>
</aside>
