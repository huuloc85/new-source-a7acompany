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

    @media (max-width: 768px) {
        .row p {
            margin: 0;
            font-size: 0.9rem;
        }

        .row h2 {
            font-size: 1.2rem;
            margin: 0;
        }

        .text-icons {
            display: inline-block;
            /* Đảm bảo chữ không bị tách ra thành dòng mới */
            justify-content: center;
            color: black;
            font-family: "Playwrite ES Deco Guides", serif;
            font-weight: 400;
            font-style: normal;
            /* Áp dụng font chữ viết tay */
            font-size: 20px;
            /* Kích thước chữ */
            white-space: nowrap;
            /* Đảm bảo chữ không bị xuống dòng */
            overflow: hidden;
            /* Ẩn phần chữ chưa được hiển thị */
            animation: moveText 4s linear infinite;
            /* Chạy chữ từ trái qua phải */
        }

        /* Hiệu ứng chuyển động chữ từ trái qua phải */
        @keyframes moveText {
            0% {
                transform: translateX(-100%);
                /* Bắt đầu từ bên trái ngoài màn hình */
            }

            100% {
                transform: translateX(100%);
                /* Di chuyển đến bên phải ngoài màn hình */
            }
        }

        /* Hiệu ứng sóng cho chữ */
        .text-icons span {
            display: inline-block;
            animation: wave 2s infinite ease-in-out;

            /* Áp dụng hiệu ứng sóng cho chữ */
        }

        /* Hiệu ứng cong lên xuống */
        @keyframes wave {
            0% {
                transform: translateY(0) rotate(0deg);
                /* Vị trí ban đầu */
            }

            25% {
                transform: translateY(-10px) rotate(-10deg);
                /* Di chuyển lên và quay nhẹ */
            }

            50% {
                transform: translateY(0) rotate(0deg);
                /* Vị trí bình thường */
            }

            75% {
                transform: translateY(10px) rotate(10deg);
                /* Di chuyển xuống và quay nhẹ */
            }

            100% {
                transform: translateY(0) rotate(0deg);
                /* Quay lại vị trí ban đầu */
            }
        }

        /* Thêm hiệu ứng cho từng chữ cái để tạo sóng */
        .text-icons span:nth-child(1) {
            animation-delay: 0s;
        }

        .text-icons span:nth-child(2) {
            animation-delay: 0.1s;
        }

        .text-icons span:nth-child(3) {
            animation-delay: 0.2s;
        }

        .text-icons span:nth-child(4) {
            animation-delay: 0.3s;
        }

        /* Tiếp tục cho các chữ cái sau nếu cần */

        /* Cải tiến hiệu ứng cho các SVG */
        .text-icons svg {
            animation: bounce 4s infinite ease-in-out;
            /* margin: 0 5px; */
        }

        /* Hiệu ứng bounce cho SVG */
        @keyframes bounce {
            0% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }

            100% {
                transform: translateY(0);
            }
        }

    }
</style>
<nav class="nav navbar navbar-expand-lg navbar-light iq-navbar no-print">
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
                        <h2 style="color: black;">Xin Chào {{ Auth()->user()->name }}!</h2>
                        <h6 class="text-icons" style="color: black;">
                            Chúc Mừng Giáng Sinh
                            <span class="mx-2">
                                <!-- SVG Cây Thông 1 -->
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 120" width="30"
                                    height="60">
                                    <!-- Tầng dưới -->
                                    <polygon points="50,10 90,50 10,50" fill="#008000" />
                                    <!-- Tầng giữa -->
                                    <polygon points="50,25 80,60 20,60" fill="#007000" />
                                    <!-- Tầng trên -->
                                    <polygon points="50,40 70,70 30,70" fill="#006000" />
                                    <!-- Thân cây -->
                                    <rect x="45" y="70" width="10" height="20" fill="#8B4513" />
                                    <!-- Trang trí -->
                                    <circle cx="40" cy="40" r="2" fill="red" />
                                    <circle cx="60" cy="30" r="2" fill="yellow" />
                                    <circle cx="30" cy="50" r="2" fill="blue" />
                                    <circle cx="70" cy="55" r="2" fill="pink" />
                                    <circle cx="50" cy="60" r="2" fill="white" />
                                </svg>

                                <!-- SVG Mũ -->
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 120" width="25"
                                    height="60">
                                    <!-- Mũ -->
                                    <path d="M20,30 Q60,-10 100,30" fill="red" />
                                    <circle cx="105" cy="35" r="8" fill="white" />
                                    <!-- Mặt -->
                                    <circle cx="60" cy="60" r="30" fill="#FFCC99" />
                                    <!-- Mắt -->
                                    <circle cx="50" cy="55" r="5" fill="black" />
                                    <circle cx="70" cy="55" r="5" fill="black" />
                                    <!-- Mũi -->
                                    <circle cx="60" cy="65" r="4" fill="red" />
                                    <!-- Miệng -->
                                    <path d="M50,75 Q60,85 70,75" fill="none" stroke="black" stroke-width="2" />
                                    <!-- Râu -->
                                    <path d="M40,70 Q60,100 80,70" fill="white" />
                                    <!-- Thân -->
                                    <rect x="40" y="90" width="40" height="20" fill="red" />
                                </svg>

                                <!-- SVG Cây Thông 2 -->
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 120" width="30"
                                    height="60">
                                    <!-- Tầng dưới -->
                                    <polygon points="50,10 90,50 10,50" fill="#008000" />
                                    <!-- Tầng giữa -->
                                    <polygon points="50,25 80,60 20,60" fill="#007000" />
                                    <!-- Tầng trên -->
                                    <polygon points="50,40 70,70 30,70" fill="#006000" />
                                    <!-- Thân cây -->
                                    <rect x="45" y="70" width="10" height="20" fill="#8B4513" />
                                    <!-- Trang trí -->
                                    <circle cx="40" cy="40" r="2" fill="red" />
                                    <circle cx="60" cy="30" r="2" fill="yellow" />
                                    <circle cx="30" cy="50" r="2" fill="blue" />
                                    <circle cx="70" cy="55" r="2" fill="pink" />
                                    <circle cx="50" cy="60" r="2" fill="white" />
                                </svg>
                            </span>
                        </h6>
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
                            style="color: black; text-transform: uppercase; text-decoration: none;">
                            <i class="fas {{ $iconClass }}"></i>
                            {{ $userRole }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="iq-header-img">
        <img src="{{ asset('assets/img/auth/noel-14.jpg') }}" alt="header"
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

        // document.addEventListener('click', function(e) {
        //     if (!navbarDropdown.contains(e.target)) {
        //         dropdownMenu.classList.remove('show');
        //     }
        // });
    });
</script>
