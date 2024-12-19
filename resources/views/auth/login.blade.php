<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/vvp.jpg') }}">
    <title>
        Đăng Nhập Vinh Vinh Phát
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
    <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
    {{-- <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script> --}}
    <style>
        .form-check {
            padding-left: 0px !important;
        }

        .snowflake {
            color: #fff;
            font-size: 4em;
            font-family: Arial;
            text-shadow: 0 0 5px #000;
        }

        @-webkit-keyframes snowflakes-fall {
            0% {
                top: -10%
            }

            100% {
                top: 100%
            }
        }

        @-webkit-keyframes snowflakes-shake {
            0% {
                -webkit-transform: translateX(0px);
                transform: translateX(0px)
            }

            50% {
                -webkit-transform: translateX(80px);
                transform: translateX(80px)
            }

            100% {
                -webkit-transform: translateX(0px);
                transform: translateX(0px)
            }
        }

        @keyframes snowflakes-fall {
            0% {
                top: -10%
            }

            100% {
                top: 100%
            }
        }

        @keyframes snowflakes-shake {
            0% {
                transform: translateX(0px)
            }

            50% {
                transform: translateX(80px)
            }

            100% {
                transform: translateX(0px)
            }
        }

        .snowflake {
            position: fixed;
            top: -10%;
            z-index: 9999;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            cursor: default;
            -webkit-animation-name: snowflakes-fall, snowflakes-shake;
            -webkit-animation-duration: 3s, 3s;
            -webkit-animation-timing-function: linear, ease-in-out;
            -webkit-animation-iteration-count: infinite, infinite;
            -webkit-animation-play-state: running, running;
            animation-name: snowflakes-fall, snowflakes-shake;
            animation-duration: 3s, 3s;
            animation-timing-function: linear, ease-in-out;
            animation-iteration-count: infinite, infinite;
            animation-play-state: running, running
        }

        .snowflake:nth-of-type(0) {
            left: 1%;
            -webkit-animation-delay: 0s, 0s;
            animation-delay: 0s, 0s
        }

        .snowflake:nth-of-type(1) {
            left: 10%;
            -webkit-animation-delay: 1s, 1s;
            animation-delay: 1s, 1s
        }

        .snowflake:nth-of-type(2) {
            left: 20%;
            -webkit-animation-delay: 6s, .5s;
            animation-delay: 6s, .5s
        }

        .snowflake:nth-of-type(3) {
            left: 30%;
            -webkit-animation-delay: 4s, 2s;
            animation-delay: 4s, 2s
        }

        .snowflake:nth-of-type(4) {
            left: 40%;
            -webkit-animation-delay: 2s, 2s;
            animation-delay: 2s, 2s
        }

        .snowflake:nth-of-type(5) {
            left: 50%;
            -webkit-animation-delay: 8s, 3s;
            animation-delay: 8s, 3s
        }

        .snowflake:nth-of-type(6) {
            left: 60%;
            -webkit-animation-delay: 6s, 2s;
            animation-delay: 6s, 2s
        }

        .snowflake:nth-of-type(7) {
            left: 70%;
            -webkit-animation-delay: 2.5s, 1s;
            animation-delay: 2.5s, 1s
        }

        .snowflake:nth-of-type(8) {
            left: 80%;
            -webkit-animation-delay: 1s, 0s;
            animation-delay: 1s, 0s
        }

        .snowflake:nth-of-type(9) {
            left: 90%;
            -webkit-animation-delay: 3s, 1.5s;
            animation-delay: 3s, 1.5s
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        snowman .body {
            width: 200px;
            height: 200px;
            background: #ecf0f1;
            box-shadow: -13px -8px 0px rgba(0, 0, 0, 0.1) inset;
            border-radius: 50%;
            margin-top: -100px;
            position: absolute;
            right: 0;
            left: 0;
            margin: 0 auto;
            margin-top: 270px;
        }

        snowman .body:before {
            width: 100px;
            height: 100px;
            background: #ecf0f1;
            box-shadow: -5px 0px 0px rgba(0, 0, 0, 0.1) inset;
            border-radius: 50%;
            display: inline-block;
            content: "";
            position: relative;
            top: -191px;
            left: 46px;
            z-index: 30;
        }

        snowman .body:after {
            width: 160px;
            height: 160px;
            background: #ecf0f1;
            box-shadow: -7px -5px 0px rgba(0, 0, 0, 0.1) inset;
            border-radius: 50%;
            display: inline-block;
            content: "";
            position: relative;
            top: -203px;
            left: 20px;
        }

        snowman .body .head {
            width: 0px;
            height: 0px;
            border-style: solid;
            border-width: 8px 41px 8px 0;
            border-color: transparent #FA9A20 transparent transparent;
            content: "";
            position: relative;
            top: -229px;
            display: inline-block;
            left: -50px;
            -webkit-transform: rotate(10deg);
            -moz-transform: rotate(10deg);
            transform: rotate(10deg);
            box-shadow: 0px 43px rgba(0, 0, 0, 0.2) inset;
            z-index: 30;
        }

        snowman .body .head:before {
            content: "";
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #2c3e50;
            display: inline-block;
            position: absolute;
            top: -23px;
            left: 20px;
        }

        snowman .body .head:after {
            content: "";
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #2c3e50;
            display: inline-block;
            position: absolute;
            top: -26px;
            left: 40px;
        }

        .scarf {
            border-bottom: 21px solid rgb(243, 77, 77);
            border-left: 18px solid transparent;
            border-right: 29px solid transparent;
            height: 0;
            width: 104px;
            position: absolute;
            top: -101px;
            z-index: 30;
            left: 50px;
            border-radius: 0px 100% 5px 10px;
        }

        .scarf:after {
            width: 74px;
            height: 17px;
            -webkit-transform: rotate(86deg);
            -moz-transform: rotate(86deg);
            transform: rotate(86deg);
            background: rgb(243, 77, 77);
            display: inline-block;
            content: "";
            position: absolute;
            top: 34px;
            left: 15px;
            border-radius: 50% 0% 50% 50%;
            box-shadow: -4px 0px rgba(0, 0, 0, 0.1) inset;
        }

        snowman .body .shadow {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 50%;
            width: 190px;
            height: 30px;
            position: absolute;
            bottom: -29px;
            z-index: 1;
            left: 30px;
        }

        .left-hand {
            position: absolute;
            top: -30px;
            left: -7px;
            -webkit-transform: rotate(15deg);
            -moz-transform: rotate(15deg);
            transform: rotate(15deg);
            border-bottom: 6px solid rgba(151, 102, 13, 1);
            border-left: 2px solid transparent;
            border-right: 0 solid transparent;
            height: 0;
            width: 36px;
        }

        .left-hand:before {
            width: 81px;
            left: -82px;
            position: absolute;
            content: "";
            display: inline-block;
            -webkit-transform: rotate(-12deg);
            -moz-transform: rotate(-12deg);
            transform: rotate(-12deg);
            top: 8px;
            border-bottom: 7px solid rgba(128, 84, 6, 1);
            border-left: 3px solid transparent;
            border-right: 3px solid transparent;
            height: 0;
            z-index: 30;
        }

        .left-hand:after {
            width: 47px;
            left: -88px;
            position: absolute;
            content: "";
            display: inline-block;
            -webkit-transform: rotate(17deg);
            -moz-transform: rotate(17deg);
            transform: rotate(17deg);
            top: 3px;
            border-bottom: 4px solid rgba(128, 84, 6, 1);
            border-left: 3px solid transparent;
            border-right: 3px solid transparent;
            height: 0;
            border-radius: 20px 50% 10% 20%;
        }

        .right-hand {
            position: absolute;
            top: -30px;
            right: -37px;
            -webkit-transform: rotate(-12deg);
            -moz-transform: rotate(-12deg);
            transform: rotate(-12deg);
            border-bottom: 6px solid rgba(151, 102, 13, 1);
            border-right: 2px solid transparent;
            border-left: 0 solid transparent;
            height: 0;
            width: 66px;
        }

        .right-hand:before {
            width: 80px;
            right: -82px;
            position: absolute;
            content: "";
            display: inline-block;
            -webkit-transform: rotate(10deg);
            -moz-transform: rotate(10deg);
            transform: rotate(10deg);
            top: 7px;
            border-bottom: 6px solid rgba(128, 84, 6, 1);
            border-right: 3px solid transparent;
            border-left: 3px solid transparent;
            height: 0;
            z-index: 30;
        }

        .right-hand:after {
            width: 47px;
            right: -47px;
            position: absolute;
            content: "";
            display: inline-block;
            -webkit-transform: rotate(-16deg);
            -moz-transform: rotate(-16deg);
            transform: rotate(-16deg);
            top: -6px;
            border-bottom: 4px solid rgba(128, 84, 6, 1);
            border-right: 3px solid transparent;
            border-lrgy: 3px solid transparent;
            height: 0;
            border-radius: 20px 50% 10% 20%;
        }

        snowman .hat {
            top: -253px;
            left: 76px;
            -webkit-transform: rotate(10deg);
            -moz-transform: rotate(10deg);
            transform: rotate(10deg);
            position: absolute;
            border-radius: 0;
            z-index: 20;
            border-top: 56px solid rgb(37, 37, 37);
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
            height: 0;
            width: 60px;
            border-bottom: 27px solid rgb(246, 62, 62);
        }

        snowman .buttons {
            height: 8px;
            width: 8px;
            background: #2c3e50;
            position: absolute;
            border-radius: 50%;
            left: 60px;
            top: -23px;
            z-index: 30;
        }

        snowman .buttons:before {
            height: 10px;
            width: 10px;
            background: #2c3e50;
            position: absolute;
            content: "";
            display: inline-block;
            border-radius: 50%;
            top: -30px;
            left: 5px;
        }

        snowman .buttons:after {
            height: 6px;
            width: 6px;
            background: #2c3e50;
            position: absolute;
            content: "";
            display: inline-block;
            border-radius: 50%;
            top: 30px;
            left: 5px;
        }

        @media (max-width: 767px) {

            /* Nền và nội dung chính */
            .login-content .row.m-0.align-items-center {
                position: relative;
                background-image: url('{{ asset('assets/img/auth/noel-4.jpg') }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                color: white;
            }

            .login-content .row.m-0.align-items-center::before {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.3);
                /* Lớp phủ */
                z-index: 1;
                /* Đảm bảo lớp phủ nằm trên hình nền */
            }

            .login-content .row.m-0.align-items-center * {
                position: relative;
                z-index: 2;
                /* Đảm bảo nội dung nằm trên lớp phủ */
            }

            /* Label */
            .login-content .form-group .form-label {
                color: rgba(255, 255, 255, 0.9);
                /* Màu chữ rõ ràng hơn */
                font-weight: 500;
                /* Giữ kiểu chữ thanh thoát */
            }

            /* Input */
            .login-content .form-group .form-control {
                background: rgba(255, 255, 255, 0.1);
                /* Nền mờ hòa hợp với nền chính */
                color: white;
                border: 1px solid rgba(255, 255, 255, 0.7);
                /* Viền mờ nhẹ */
                border-radius: 5px;
                padding: 10px;
                font-size: 16px;
                transition: border-color 0.3s, box-shadow 0.3s;
                width: 100%;
                box-sizing: border-box;
            }

            /* Input khi focus */
            .login-content .form-group .form-control:focus {
                border-color: rgba(255, 255, 255, 1);
                box-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
                outline: none;
            }

            /* Nút đăng nhập */
            .login-content .col-md-6 .btn-primary {
                background: rgba(255, 255, 255, 0.2);
                /* Nền mờ cho nút */
                color: white;
                border: 2px solid white;
                padding: 12px 20px;
                font-size: 16px;
                font-weight: bold;
                border-radius: 5px;
                transition: all 0.3s;
                width: 100%;
                box-sizing: border-box;
                text-transform: uppercase;
                /* Viết hoa toàn bộ chữ */
            }

            /* Hiệu ứng hover cho nút */
            .login-content .col-md-6 .btn-primary:hover {
                background: rgba(255, 255, 255, 0.3);
                /* Tăng độ sáng khi hover */
                color: black;
                /* Chữ đổi màu đen khi hover */
            }

            /* Biểu tượng mắt */
            .login-content .form-group .position-relative .fa-eye {
                color: rgba(255, 255, 255, 0.8);
            }

            /* Hiệu ứng hover và focus cho input */
            .login-content .form-group .form-control:hover,
            .login-content .col-md-6 .btn-primary:focus {
                border-color: rgba(255, 255, 255, 1);
                box-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
            }

            /* Thêm khoảng cách hợp lý giữa các phần tử */
            .login-content .form-group {
                margin-bottom: 20px;
            }

            .form-control::placeholder {
                color: white;
                /* Màu trắng cho placeholder */
                opacity: 0.7;
                /* Đảm bảo không bị mờ */
            }
        }


        .countdown {
            display: none;
        }

        .text-icons {
            display: none;
        }


        @media (max-width: 768px) {
            :root {
                --d: 0.5s;
                /* Định nghĩa giá trị cho biến */
            }

            @import url(https://fonts.bunny.net/css?family=aclonica:400|economica:400,700);

            h1 {
                font-size: clamp(1rem, 3.5vw - 0.5rem, 2.5rem);
                /* Giảm kích thước chữ */
                font-weight: 1000;
                letter-spacing: 0.05em;
                text-align: center;
            }

            /* Link font chữ từ Google Fonts */
            @import url('https://fonts.googleapis.com/css2?family=Permanent+Marker&family=Playwrite+ES+Deco+Guides&display=swap');

            .text-icons {
                display: inline-block;
                /* Đảm bảo chữ không bị tách ra thành dòng mới */
                justify-content: center;
                color: white;
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


            .countdown {
                width: 100%;
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 0.75rem;
                margin-inline: auto;
            }

            .countdown>div {
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
            }

            .countdown>div>span {
                display: grid;
                place-content: center;
                font-weight: bold;
            }

            .countdown>div::after {
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

        body,
        html {
            overflow: hidden;
            height: 100%;
        }

        /* Đảm bảo phần background ở bên phải không bị cuộn */
        .wrapper {
            height: 100vh;
            overflow: hidden;
        }

        /* Nếu bạn có một class nào đó liên quan đến phần background, ví dụ, .bg-primary */
        .bg-primary {
            height: 100vh;
            /* Chiều cao của phần bên phải */
            overflow: hidden;
            /* Ẩn cuộn */
            position: relative;
        }

        /* Đảm bảo phần mobile background image không cuộn */
        .bg-mobile-image {
            height: 100vh;
            overflow: hidden;
        }
    </style>

</head>

<body>
    <audio id="background-music" autoplay="autoplay" loop>
        <source src="{{ asset('assets/music/noel.mp3') }}" type="audio/mp3">
    </audio>

    <div class="snowflakes" aria-hidden="true">
        <div class="snowflake">
            ❅
        </div>
        <div class="snowflake">
            ❅
        </div>
        <div class="snowflake">
            ❆
        </div>
        <div class="snowflake">
            ❄
        </div>
        <div class="snowflake">
            ❅
        </div>
        <div class="snowflake">
            ❆
        </div>
        <div class="snowflake">
            ❄
        </div>
        <div class="snowflake">
            ❅
        </div>
        <div class="snowflake">
            ❆
        </div>
        <div class="snowflake">
            ❄
        </div>
    </div>
    @include('sweetalert::alert')

    <div class="wrapper">
        <section class="login-content">
            <div class="row m-0 align-items-center bg-white vh-100 position-relative">
                <div class="col-md-6">
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="my-5">
                                <div class="card-body">
                                    <h1 class="text-white ">SỐ NGÀY ĐẾN GIÁNG SINH</h1>
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
                                    <h6 class="text-icons">
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
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 120"
                                                width="25" height="60">
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
                                                <path d="M50,75 Q60,85 70,75" fill="none" stroke="black"
                                                    stroke-width="2" />
                                                <!-- Râu -->
                                                <path d="M40,70 Q60,100 80,70" fill="white" />
                                                <!-- Thân -->
                                                <rect x="40" y="90" width="40" height="20" fill="red" />
                                            </svg>

                                            <!-- SVG Cây Thông 2 -->
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 120"
                                                width="30" height="60">
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
                <div class="col-md-6 d-md-block d-none bg-primary p-0 mt-n1 vh-100 overflow-hidden">
                    <img src="{{ asset('assets/img/auth/noel-4.jpg') }}"
                        class="img-fluid gradient-main animated-scaleX w-100 h-100 object-fit-cover" alt="images">
                </div>

                <!-- Mobile background image -->
                <div class="bg-mobile-image"></div>
            </div>
        </section>



    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const audio = document.getElementById("background-music");
            const isMusicPlaying = localStorage.getItem("musicPlaying");
            document.body.addEventListener("click", () => {
                audio.muted = false;
                audio.play();
                localStorage.setItem("musicPlaying", "true");
            });
        });
        //
        console.clear();
        const today = new Date();
        const currentYear = today.getFullYear();

        // Check if Christmas has passed for the current year
        const christmasThisYear = new Date(`${currentYear}-12-25T00:00:00`);
        const EXP_DATE = today <= christmasThisYear ? christmasThisYear : new Date(`${currentYear + 1}-12-25T00:00:00`);
        const SPEED = 150;

        /***************** COUNTDOWN ********************/
        const panelCountdown = document.querySelector("#panel-countdown");

        // Select elements and spans dynamically
        const countdownElements = ["months", "days", "hours", "minutes", "seconds"];
        const elements = {};
        const currentValues = {};

        countdownElements.forEach((id) => {
            elements[id] = document.querySelectorAll(`#${id} span`);
            currentValues[id] = [];
        });

        function getCurrentDate() {
            const currentDate = new Date();
            const timeDifference = EXP_DATE - currentDate;

            return {
                months: Math.floor((timeDifference % (1000 * 60 * 60 * 24 * 365.25)) / (1000 * 60 * 60 * 24 * 30.44)),
                days: Math.floor((timeDifference % (1000 * 60 * 60 * 24 * 30.44)) / (1000 * 60 * 60 * 24)),
                hours: Math.floor((timeDifference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
                minutes: Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60)),
                seconds: Math.floor((timeDifference % (1000 * 60)) / 1000),
            };
        }

        function updateCountdown() {
            const currentDate = getCurrentDate();

            countdownElements.forEach((unit, index) => {
                const paddedValue = padTo2(currentDate[unit]);

                paddedValue.split("").forEach((digit, i) => {
                    // Only update when digit has changed
                    if (digit !== currentValues[unit][i]) {
                        changeNum(elements[unit][i], digit, SPEED * (countdownElements.length - index));
                    }
                });

                currentValues[unit] = paddedValue.split("");
            });
        }

        function initialLoad() {
            const currentDate = getCurrentDate();

            countdownElements.forEach((unit) => {
                const paddedValue = padTo2(currentDate[unit]);
                currentValues[unit] = paddedValue.split("");

                elements[unit][0].innerText = currentValues[unit][0];
                elements[unit][1].innerText = currentValues[unit][1];
            });
        }

        // Initialize the countdown display
        initialLoad();

        // Interval to update the countdown every second
        let EXP_DATEInterval;

        function startCountdownInterval() {
            clearInterval(EXP_DATEInterval);
            EXP_DATEInterval = setInterval(updateCountdown, 1000);
        }
        startCountdownInterval();

        // change the numbers smoothly
        function changeNum(el, newVal, timing) {
            el.style.transition = `transform ${timing}ms ease`; // Smooth transition for transform
            el.style.transform = 'scale(0)';
            setTimeout(() => {
                el.innerText = newVal;
                el.style.transform = 'scale(1)';
            }, timing);
        }

        function padTo2(num) {
            return num.toString().padStart(2, "0");
        }
    </script>
</body>

</html>
