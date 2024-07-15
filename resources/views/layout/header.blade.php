<style>
    .custom-navbar-toggler {
        display: none;
        background: none;
        border: none;
        font-size: 1.5rem;
    }

    @media (max-width: 999px) {
        .custom-navbar-toggler {
            display: block;
        }
    }

    @media (max-width:567px) {
        .row p {
            margin: 0;
            font-size: 0.9rem;
        }

        .row h2 {
            font-size: 1.2rem;
            margin: 0;
        }
    }
</style>
<nav class="nav navbar navbar-expand-lg navbar-light iq-navbar">
    <div class="container-fluid navbar-inner">
        <a href="{{ route('admin.home') }}" class="navbar-brand">
            <img src="{{ asset('assets/img/logos/VVP.png') }}" alt="" width="100">
        </a>
        <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
            <i class="icon">
                <svg width="20px" height="20px" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z" />
                </svg>
            </i>
        </div>
        <button class="custom-navbar-toggler" id="navbarToggler">
            <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto  navbar-list mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link py-0 d-flex align-items-center" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary d-flex justify-content-center align-items-center"
                                style="width: 40px; height: 40px; position: relative;">
                                <i class="fa fa-user text-white"></i>
                            </div>
                            <h6 class="mb-0 caption-title ms-2">{{ Auth()->user()->name }}</h6>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.profile') }}">
                                <i class="fas fa-user"></i> Thông Tin Tài Khoản
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}">
                                <i class="fas fa-sign-out-alt"></i> Đăng Xuất
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="iq-navbar-header" style="height: 215px;">
    <div class="container-fluid iq-container">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h2 style="color: white;">Xin Chào {{ Auth()->user()->name }}!</h2>
                        <p style="color: white;">
                            Chúc bạn một ngày làm việc hiệu quả <i class="fas fa-smile"></i>
                        </p>
                    </div>
                    <div>
                        @php
                            $icons = [
                                'Giám đốc' => 'fa-user-tie',
                                'Quản lí sản xuất' => 'fa-cogs',
                                'Kế toán' => 'fa-calculator',
                                'Kho' => 'fa-warehouse',
                                'Khuôn' => 'fa-toolbox',
                                'Bảo trì điện' => 'fa-bolt',
                                'Kỹ thuật' => 'fa-wrench',
                                'QA-QC' => 'fa-clipboard-check',
                                'Ngoại Quan' => 'fa-globe',
                                'Sản xuất' => 'fa-industry',
                                'Quản lý' => 'fa-users',
                                'Tổ trưởng sản xuất' => 'fa-chalkboard-teacher',
                                'admin' => 'fa-user-shield',
                                'IT' => 'fa-laptop-code',
                                'Tổ trưởng ngoại quan' => 'fa-user-check',
                            ];
                            $userRole = Auth::user()->role->role_name;
                            $iconClass = isset($icons[$userRole]) ? $icons[$userRole] : 'fa-user';
                        @endphp
                        <a href="" class="btn btn-link btn-soft-light"
                            style="color: white; text-transform: uppercase; text-decoration: none;">
                            <i class="fas {{ $iconClass }}"></i>
                            {{ $userRole }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="iq-header-img">
        <img src="{{ asset('assets/img/dashboard/top-header.png') }}" alt="header"
            class="theme-color-default-img img-fluid w-100 h-100 animated-scaleX">
    </div>
</div>
<script src="{{ asset('assets/js/libs.min.js') }}"></script>
<script src="{{ asset('assets/js/hope-ui.js') }}"></script>
<script src="{{ asset('assets/js/modelview.js') }}"></script>
<script src="{{ asset('vendor/Leaflet/leaflet.js') }} "></script>
<script>
    // JavaScript to handle navbar and dropdown toggling
    document.addEventListener('DOMContentLoaded', function() {
        var navbarToggler = document.getElementById('navbarToggler');
        var navbarNav = document.getElementById('navbarNav');
        var dropdownMenu = navbarDropdown.nextElementSibling;

        navbarToggler.addEventListener('click', function() {
            navbarNav.classList.toggle('show');
        });

        document.addEventListener('click', function(e) {
            if (!navbarDropdown.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    });
</script>
