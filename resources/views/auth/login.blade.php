<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/vvp.jpg') }}">
    <title>
        Vinh Vinh Phát
    </title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/hope-ui.css?v=1.0') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/libs.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css?v=1.1.0') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css?v=1.1.0') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/hope-ui.css?v=1.1.0') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <style>
        :root {
            --d: 0.5s;
        }

        canvas {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 2;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 1);
        }

        .btn.btn-primary {
            background: rgba(255, 255, 255, 0.2) !important;
            color: white !important;
            border: 2px solid white !important;
            padding: 12px 20px !important;
            font-size: 16px !important;
            font-weight: bold !important;
            border-radius: 5px !important;
            width: 100% !important;
            transition: all 0.3s !important;
        }

        .btn.btn-primary:hover {
            background: rgba(255, 255, 255, 0.3) !important;
            color: black !important;
        }

        .btn.btn-primary:focus {
            border-color: rgba(255, 255, 255, 1) !important;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.8) !important;
        }

        /* Ensures full height for the wrapper and main content */
        .wrapper,
        .bg-primary,
        .bg-mobile-image {
            height: 100vh;
            overflow: hidden;
        }

        /* Background styling for login section */
        .login-content .row.m-0.align-items-center {
            background-image: url('{{ asset('assets/img/auth/new-year-4.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: white;
            position: relative;
            justify-content: center;
            /* Center content horizontally */
        }

        /* Overlay for the background */
        .login-content .row.m-0.align-items-center::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            /* Dark overlay */
            z-index: 1;
        }

        /* Ensure content appears above overlay */
        .login-content .row.m-0.align-items-center * {
            position: relative;
            z-index: 50;
        }

        /* Form content and its layout */
        .login-content .col-md-6 {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            /* Full height */
        }


        /* Đảm bảo các input hòa trộn vào background */
        .form-control {
            background: rgba(255, 255, 255, 0.2);
            /* Nền mờ cho input */
            border: 1px solid rgba(255, 255, 255, 0.5);
            /* Viền input mờ */
            color: white;
            box-shadow: none;
            /* Loại bỏ bóng mặc định của input */
        }

        /* Center the title and other text elements */
        h1.text-icons,
        h2,
        h6.text-icons {
            text-align: center;
        }

        /* Style for inputs and labels */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-size: 1.1rem;
            font-weight: bold;
        }

        /* Button styling */
        .btn-primary {
            padding: 0.75rem 2rem;
            font-size: 1.2rem;
            border-radius: 5px;
            width: 100%;
            /* Make the button full width */
        }


        /* Hide countdown and text icons */
        .countdown,
        .text-icons {
            display: none;
        }

        /* Mobile styles */
        @media (max-width: 767px) {

            /* Login container styles */
            .login-content .row.m-0.align-items-center {
                position: relative;
                background-image: url('{{ asset('assets/img/auth/new-year-4.jpg') }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                color: white;
            }

            .login-content .row.m-0.align-items-center::before {
                content: "";
                position: absolute;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                /* Màu đen với độ mờ 50% */
                z-index: 1;
            }


            .login-content .row.m-0.align-items-center * {
                position: relative;
                z-index: 50;
            }

            /* Form elements */
            .login-content {
                .form-group {
                    margin-bottom: 20px;

                    .form-label {
                        color: rgba(255, 255, 255, 0.9);
                        font-weight: 500;
                    }

                    .form-control {
                        background: rgba(255, 255, 255, 0.1);
                        color: white;
                        border: 1px solid rgba(255, 255, 255, 0.7);
                        border-radius: 5px;
                        padding: 10px;
                        font-size: 16px;
                        width: 100%;
                        box-sizing: border-box;
                        transition: border-color 0.3s, box-shadow 0.3s;

                        &:focus,
                        &:hover {
                            border-color: rgba(255, 255, 255, 1);
                            box-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
                            outline: none;
                        }

                        &::placeholder {
                            color: white;
                            opacity: 0.7;
                        }
                    }

                    .position-relative .fa-eye {
                        color: rgba(255, 255, 255, 0.8);
                    }
                }

                .btn-primary {
                    background: rgba(255, 255, 255, 0.2);
                    color: white;
                    border: 2px solid white;
                    padding: 12px 20px;
                    font-size: 16px;
                    font-weight: bold;
                    border-radius: 5px;
                    width: 100%;
                    box-sizing: border-box;
                    text-transform: uppercase;
                    transition: all 0.3s;

                    &:hover {
                        background: rgba(255, 255, 255, 0.3);
                        color: black;
                    }

                    &:focus {
                        border-color: rgba(255, 255, 255, 1);
                        box-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
                    }
                }
            }

            /* Text animation styles */
            .text-icons {
                display: block;
                justify-content: center;
                color: white;
                text-align: center;
            }

            /* Countdown container styles */
            .countdown {
                width: 100%;
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 0.75rem;
                margin-inline: auto;

                >div {
                    background: transparent;
                    color: white;
                    padding: 0.75rem 0.75rem 2.5rem;
                    border-radius: 0.5rem;
                    position: relative;
                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                    overflow: hidden;
                    transition: all 0.2s;
                    flex: 1;
                    text-align: center;

                    >span {
                        display: grid;
                        place-content: center;
                        font-weight: bold;
                    }

                    &::after {
                        content: attr(data-desc);
                        font-size: 0.8rem;
                        position: absolute;
                        text-transform: capitalize;
                        bottom: 1rem;
                        left: 50%;
                        transform: translateX(-50%);
                        transition: 300ms ease-in-out var(--d);
                    }
                }
            }

            h1 {
                font-size: clamp(1rem, 3.5vw - 0.5rem, 2.5rem);
                font-weight: 1000;
                letter-spacing: 0.05em;
                text-align: center;
            }
        }

        /* Animation keyframes */
        @keyframes moveText {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        @keyframes wave {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            25% {
                transform: translateY(-10px) rotate(-10deg);
            }

            50% {
                transform: translateY(0) rotate(0deg);
            }

            75% {
                transform: translateY(10px) rotate(10deg);
            }
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        /* Import font từ Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Pacifico&display=swap');

        .new-year-text {
            /* Kích thước font */
            font-size: clamp(40px, 6vw, 80px);
            font-weight: bold;
            font-family: 'Pacifico', sans-serif;

            padding: 15px 8px;
            line-height: 1.4;
            overflow: visible;
            margin: 8px 0;

            /* Gradient màu Tết - kết hợp đỏ và vàng */
            background: linear-gradient(45deg,
                    #FFD700 0%,
                    /* Vàng */
                    #FF4D4D 25%,
                    /* Đỏ tươi */
                    #FFD700 50%,
                    /* Vàng */
                    #FF4D4D 75%,
                    /* Đỏ tươi */
                    #FFD700 100%
                    /* Vàng */
                );

            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;

            /* Giảm độ sáng của text-shadow */
            text-shadow:
                0 0 3px rgba(255, 77, 77, 0.3),
                0 0 6px rgba(255, 215, 0, 0.2);

            /* Animations */
            animation:
                tetShine 4s linear infinite,
                tetBounce 2s ease-in-out infinite,
                tetGlow 2s ease-in-out infinite;

            transform-origin: center;
            transform-style: preserve-3d;
            -webkit-font-smoothing: antialiased;

            /* Giảm độ sáng tổng thể */
            filter: brightness(1.05);
        }

        .new-year-container {
            padding: 15px;
            overflow: visible;
            text-align: center;
            min-height: 100px;
        }

        /* Hiệu ứng gradient di chuyển */
        @keyframes tetShine {
            0% {
                background-position: 100% center;
            }

            100% {
                background-position: -100% center;
            }
        }

        /* Hiệu ứng nảy nhẹ nhàng */
        @keyframes tetBounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-5px);
            }
        }

        /* Giảm độ sáng trong animation glow */
        @keyframes tetGlow {

            0%,
            100% {
                text-shadow:
                    0 0 3px rgba(255, 77, 77, 0.3),
                    0 0 6px rgba(255, 215, 0, 0.2);
                filter: brightness(1);
            }

            50% {
                text-shadow:
                    0 0 5px rgba(255, 77, 77, 0.4),
                    0 0 8px rgba(255, 215, 0, 0.3),
                    0 0 10px rgba(255, 77, 77, 0.2);
                filter: brightness(1.1);
            }
        }

        @media screen and (max-width: 768px) {
            .new-year-text {
                padding: 18px 10px;
                line-height: 1.5;
            }

            .new-year-container {
                padding: 18px;
                min-height: 120px;
            }

            @keyframes tetBounce {

                0%,
                100% {
                    transform: translateY(0);
                }

                50% {
                    transform: translateY(-3px);
                }
            }
        }
    </style>

</head>

<body>
    <audio id="background-music" autoplay="autoplay" loop>
        <source src="{{ asset('assets/music/new-year.mp3') }}" type="audio/mp3">
    </audio>

    @include('sweetalert::alert')

    <div class="wrapper">
        <section class="login-content">
            <div class="row m-0 align-items-center bg-white vh-100 position-relative">
                <div class="col-md-6">
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="my-5">
                                <div class="card-body">
                                    <h1 class="text-white text-icons">SỐ NGÀY ĐẾN TẾT NGUYÊN ĐÁN</h1>
                                    <div class="countdown">
                                        <div id="months" data-desc="Tháng" class="animate-in " style="--d:1800ms">
                                            <span>2</span>
                                            <span>0</span>
                                        </div>
                                        <div id="days" data-desc="Ngày" class="animate-in " style="--d:1500ms">
                                            <span>2</span>
                                            <span>0</span>
                                        </div>
                                        <div id="hours" data-desc="Giờ" class="animate-in " style="--d:1200ms">
                                            <span>2</span>
                                            <span>3</span>
                                        </div>
                                        <div id="minutes" data-desc="Phút" class="animate-in " style="--d:800ms">
                                            <span>1</span>
                                            <span>2</span>
                                        </div>
                                        <div id="seconds" data-desc="Giây" class="animate-in " style="--d:500ms">
                                            <span>0</span>
                                            <span>1</span>
                                        </div>
                                    </div>
                                    <h2 class="mb-2 text-center text-white">Đăng Nhập</h2>
                                    <h6 class="text-icons" style="text-align: center; margin-top: 10px;">
                                        <div class="new-year-text">Happy New Year 2025!</div>
                                    </h6>
                                    <form method="POST" action="{{ route('handleLogin') }}" data-toggle="validator">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="email" class="form-label">Số điện thoại hoặc tài
                                                        khoản</label>
                                                    <input type="text" id="email"
                                                        placeholder="Nhập số điện thoại hoặc tài khoản"
                                                        class="form-control" name="phone" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group position-relative">
                                                    <label for="password" class="form-label">Mật khẩu</label>
                                                    <input type="password" id="password" placeholder="*********"
                                                        class="form-control " name="password">
                                                    <span class="position-absolute end-0 top-50 pe-3"
                                                        id="togglePassword" style="cursor: pointer; margin-top: 5px;">
                                                        <i class="fa fa-eye" id="togglePasswordIcon"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            {{-- <div class="col-lg-6">
                                                <div class="form-check mb-3">
                                                    <input type="checkbox" class="form-check-input" id="customCheck1">
                                                    <label class="form-check-label" for="customCheck1">Ghi nhớ tài khoản
                                                    </label>
                                                </div>
                                            </div> --}}
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary">Đăng nhập</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden for mobile -->
                {{-- <div class="col-md-6 d-md-block d-none bg-primary p-0 mt-n1 vh-100 overflow-hidden">
                    <img src="{{ asset('assets/img/auth/new-year-4.jpg') }}"
                        class="img-fluid gradient-main animated-scaleX w-100 h-100 object-fit-cover" alt="images">
                </div> --}}

                <!-- Mobile background image -->
                <div class="bg-mobile-image"></div>
            </div>
        </section>
    </div>

    {{-- Fireworks --}}
    <canvas id="fireworksCanvas"></canvas>

    <script src="{{ asset('assets/js/firework/MyMath.js') }}"></script>
    <script src="{{ asset('assets/js/firework/Stage.js') }}"></script>
    <script src="{{ asset('assets/js/firework/fireworksCanvas.js') }}"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script>
        // Audio handling
        document.addEventListener("DOMContentLoaded", () => {
            const audio = document.getElementById("background-music");

            document.body.addEventListener("click", () => {
                audio.muted = false;
                audio.play();
                localStorage.setItem("musicPlaying", "true");
            });
        });

        // Constants and utilities
        const padTo2 = num => num.toString().padStart(2, "0");

        const getCurrentYear = () => new Date().getFullYear();
        const getExpirationDate = () => {
            const today = new Date();
            const year = getCurrentYear();
            const christmas = new Date(`${year}-01-29T00:00:00`);
            return today <= christmas ? christmas : new Date(`${year + 1}-01-29T00:00:00`);
        };

        const EXPIRATION_DATE = getExpirationDate();
        const ANIMATION_SPEED = 150;
        const TIME_UNITS = ["months", "days", "hours", "minutes", "seconds"];

        // DOM elements cache
        const elements = TIME_UNITS.reduce((acc, unit) => {
            acc[unit] = document.querySelectorAll(`#${unit} span`);
            return acc;
        }, {});

        // State management
        const currentValues = TIME_UNITS.reduce((acc, unit) => {
            acc[unit] = [];
            return acc;
        }, {});

        // Time calculations
        const getTimeRemaining = () => {
            const now = new Date();
            const timeDiff = EXPIRATION_DATE - now;

            return {
                months: Math.floor((timeDiff % (1000 * 60 * 60 * 24 * 365.25)) / (1000 * 60 * 60 * 24 * 30.44)),
                days: Math.floor((timeDiff % (1000 * 60 * 60 * 24 * 30.44)) / (1000 * 60 * 60 * 24)),
                hours: Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
                minutes: Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60)),
                seconds: Math.floor((timeDiff % (1000 * 60)) / 1000)
            };
        };

        // Animation
        const animateNumber = (element, newValue, duration) => {
            element.style.transition = `transform ${duration}ms ease`;
            element.style.transform = 'scale(0)';

            setTimeout(() => {
                element.innerText = newValue;
                element.style.transform = 'scale(1)';
            }, duration);
        };

        // Update logic
        const updateCountdown = () => {
            const timeRemaining = getTimeRemaining();

            TIME_UNITS.forEach((unit, index) => {
                const newValue = padTo2(timeRemaining[unit]);
                const digits = newValue.split("");

                digits.forEach((digit, i) => {
                    if (digit !== currentValues[unit][i]) {
                        animateNumber(
                            elements[unit][i],
                            digit,
                            ANIMATION_SPEED * (TIME_UNITS.length - index)
                        );
                    }
                });

                currentValues[unit] = digits;
            });
        };

        // Initialization
        const initialize = () => {
            const timeRemaining = getTimeRemaining();

            TIME_UNITS.forEach(unit => {
                const value = padTo2(timeRemaining[unit]);
                currentValues[unit] = value.split("");

                elements[unit].forEach((el, i) => {
                    el.innerText = currentValues[unit][i];
                });
            });

            setInterval(updateCountdown, 500);
        };

        // Start the countdown
        initialize();
    </script>
</body>

</html>
