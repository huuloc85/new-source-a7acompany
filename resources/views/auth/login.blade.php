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

    <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
    {{-- <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script> --}}
    {{-- <script type="text/javascript" src="{{ asset('assets/js/fireworks.js') }}"></script> --}}

    <style>
        :root {
            --d: 0.5s;
        }

        canvas {
            position: fixed;
            top: 0;
            left: 0;
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
            z-index: 2;
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
                z-index: 2;
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
    </style>

</head>

<body>
    <audio id="background-music" autoplay="autoplay" loop>
        <source src="{{ asset('assets/music/new-year.mp3') }}" type="audio/mp3">
    </audio>

    <script>
        var SCREEN_WIDTH = window.innerWidth,
            SCREEN_HEIGHT = window.innerHeight,
            mousePos = {
                x: 400,
                y: 300
            },
            canvas = document.createElement('canvas'),
            context = canvas.getContext('2d'),
            particles = [],
            rockets = [],
            MAX_PARTICLES = 1000,
            colorCode = 0;

        // init
        $(document).ready(function() {
            document.body.appendChild(canvas);
            canvas.width = SCREEN_WIDTH;
            canvas.height = SCREEN_HEIGHT;

            canvas.style.position = 'fixed';
            canvas.style.top = '0';
            canvas.style.left = '0';
            canvas.style.zIndex = '1';
            canvas.style.pointerEvents = 'none';
            canvas.style.background = 'transparent';

            setInterval(launch, 400);
            setInterval(loop, 1000 / 60);
        });
        // //bắn khi type
        // $(document).keydown(function(e) {
        //     // Bắn một rocket tại vị trí ngẫu nhiên khi nhấn phím
        //     launchFrom(Math.random() * SCREEN_WIDTH);
        // });
        // update mouse position
        $(document).mousemove(function(e) {
            e.preventDefault();
            mousePos = {
                x: e.clientX,
                y: e.clientY
            };
        });

        // launch more rockets!!!
        $(document).mousedown(function(e) {
            for (var i = 0; i < 8; i++) {
                launchFrom(Math.random() * SCREEN_WIDTH * 2 / 3 + SCREEN_WIDTH / 6);
            }
        });

        function launch() {
            launchFrom(mousePos.x);
            if (Math.random() < 10) {
                launchFrom(Math.random() * SCREEN_WIDTH);
            }
        }

        function launchFrom(x) {
            if (rockets.length < 20) {
                var rocket = new Rocket(x);
                rocket.explosionColor = Math.floor(Math.random() * 360 / 10) * 20;
                rocket.vel.y = Math.random() * -8 - 10;
                rocket.vel.x = Math.random() * 10 - 5;
                rocket.size = 12;
                rocket.shrink = 0.998;
                rocket.gravity = 0.02;
                rockets.push(rocket);
            }
        }

        function loop() {
            if (SCREEN_WIDTH != window.innerWidth) {
                canvas.width = SCREEN_WIDTH = window.innerWidth;
            }
            if (SCREEN_HEIGHT != window.innerHeight) {
                canvas.height = SCREEN_HEIGHT = window.innerHeight;
            }

            context.clearRect(0, 0, SCREEN_WIDTH, SCREEN_HEIGHT);

            var existingRockets = [];

            for (var i = 0; i < rockets.length; i++) {
                rockets[i].update();
                rockets[i].render(context);

                var distance = Math.sqrt(Math.pow(mousePos.x - rockets[i].pos.x, 2) + Math.pow(mousePos.y - rockets[i].pos
                    .y, 2));
                var randomChance = rockets[i].pos.y < (SCREEN_HEIGHT * 2 / 3) ? (Math.random() * 100 <= 1) : false;

                if (rockets[i].pos.y < SCREEN_HEIGHT / 5 || rockets[i].vel.y >= 0 || distance < 50 || randomChance) {
                    rockets[i].explode();
                } else {
                    existingRockets.push(rockets[i]);
                }
            }

            rockets = existingRockets;

            var existingParticles = [];

            for (var i = 0; i < particles.length; i++) {
                particles[i].update();

                if (particles[i].exists()) {
                    particles[i].render(context);
                    existingParticles.push(particles[i]);
                }
            }

            particles = existingParticles;

            while (particles.length > MAX_PARTICLES) {
                particles.shift();
            }
        }

        function Particle(pos) {
            this.pos = {
                x: pos ? pos.x : 0,
                y: pos ? pos.y : 0
            };
            this.vel = {
                x: 0,
                y: 0
            };
            this.shrink = .97;
            this.size = 2;

            this.resistance = 1;
            this.gravity = 0;

            this.flick = false;

            this.alpha = 1;
            this.fade = 0;
            this.color = 0;
        }

        Particle.prototype.update = function() {
            this.vel.x *= this.resistance;
            this.vel.y *= this.resistance;

            this.vel.y += this.gravity;

            this.pos.x += this.vel.x;
            this.pos.y += this.vel.y;

            this.size *= this.shrink;

            this.alpha -= this.fade;
        };

        Particle.prototype.render = function(c) {
            if (!this.exists()) {
                return;
            }

            c.save();
            c.globalCompositeOperation = 'lighter';

            var x = this.pos.x,
                y = this.pos.y,
                r = this.size / 2;

            var gradient = c.createRadialGradient(x, y, 0.1, x, y, r * 2);
            gradient.addColorStop(0, "rgba(255,255,255," + this.alpha + ")");
            gradient.addColorStop(0.4, "hsla(" + this.color + ", 100%, 50%, " + this.alpha + ")");
            gradient.addColorStop(1, "hsla(" + this.color + ", 100%, 50%, 0)");

            c.fillStyle = gradient;

            c.beginPath();
            c.arc(this.pos.x, this.pos.y, this.flick ? Math.random() * this.size * 1.2 : this.size, 0, Math.PI * 2,
                true);
            c.closePath();
            c.fill();

            c.restore();
        };

        Particle.prototype.exists = function() {
            return this.alpha >= 0.1 && this.size >= 1;
        };

        function Rocket(x) {
            Particle.apply(this, [{
                x: x,
                y: SCREEN_HEIGHT
            }]);

            this.explosionColor = 0;
        }

        Rocket.prototype = new Particle();
        Rocket.prototype.constructor = Rocket;

        Rocket.prototype.explode = function() {
            // Tạo cánh hoa chính
            var petalCount = 8; // Số cánh hoa
            var particlesPerPetal = 30; // Số particles trên mỗi cánh
            var centerParticles = 50; // Số particles ở tâm

            // Tạo tâm hoa
            for (var i = 0; i < centerParticles; i++) {
                var particle = new Particle(this.pos);
                var angle = Math.random() * Math.PI * 2;
                var speed = Math.random() * 2 + 1;

                particle.vel.x = Math.cos(angle) * speed;
                particle.vel.y = Math.sin(angle) * speed;
                particle.size = 8;
                particle.gravity = 0.1;
                particle.resistance = 0.98;
                particle.shrink = 0.96;
                particle.fade = 0.02;
                particle.color = this.explosionColor;
                particles.push(particle);
            }

            // Tạo các cánh hoa
            for (var i = 0; i < petalCount; i++) {
                var petalAngle = (i / petalCount) * Math.PI * 2;

                // Tạo particles cho mỗi cánh
                for (var j = 0; j < particlesPerPetal; j++) {
                    var particle = new Particle(this.pos);

                    var spread = (Math.random() - 0.5) * 0.6;
                    var angle = petalAngle + spread;

                    var speed = Math.cos(j / particlesPerPetal * Math.PI) * 15;

                    particle.vel.x = Math.cos(angle) * speed;
                    particle.vel.y = Math.sin(angle) * speed;

                    particle.size = 10 - (j / particlesPerPetal) * 5;

                    particle.gravity = 0.05;
                    particle.resistance = 0.95;
                    particle.shrink = 0.97;
                    particle.fade = 0.015;
                    particle.color = this.explosionColor;

                    particles.push(particle);
                }
            }

            // Tạo hiệu ứng lá
            var leafCount = 12;
            for (var i = 0; i < leafCount; i++) {
                var particle = new Particle(this.pos);
                var angle = (i / leafCount) * Math.PI * 2;
                var speed = Math.random() * 5 + 10;

                particle.vel.x = Math.cos(angle) * speed;
                particle.vel.y = Math.sin(angle) * speed;
                particle.size = 6;
                particle.gravity = 0.2;
                particle.resistance = 0.95;
                particle.shrink = 0.98;
                particle.color = (this.explosionColor + 120) % 360;

                particles.push(particle);
            }
        };

        Rocket.prototype.render = function(c) {
            if (!this.exists()) {
                return;
            }

            c.save();

            c.globalCompositeOperation = 'lighter';

            var x = this.pos.x,
                y = this.pos.y,
                r = this.size / 2;

            // Tạo đuôi pháo sáng
            var gradient = c.createLinearGradient(x, y, x, y + 10);
            gradient.addColorStop(0, "rgba(255, 220, 110, " + this.alpha + ")");
            gradient.addColorStop(0.5, "rgba(255, 140, 0, " + this.alpha * 0.7 + ")");
            gradient.addColorStop(1, "rgba(255, 50, 0, 0)");

            c.fillStyle = gradient;

            c.beginPath();
            c.moveTo(x - r, y);
            c.quadraticCurveTo(x, y + 10, x + r, y);
            c.closePath();
            c.fill();

            // Thêm điểm sáng ở đầu
            var headGradient = c.createRadialGradient(x, y, 0, x, y, r);
            headGradient.addColorStop(0, "rgba(255, 255, 255, " + this.alpha + ")");
            headGradient.addColorStop(0.3, "rgba(255, 220, 110, " + this.alpha * 0.8 + ")");
            headGradient.addColorStop(1, "rgba(255, 140, 0, 0)");

            c.fillStyle = headGradient;
            c.beginPath();
            c.arc(x, y, r * 1.5, 0, Math.PI * 2, true);
            c.fill();

            c.restore();
        };
    </script>
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
                                    <h6 class="text-icons" style="text-align: center; margin-top: 20px;">
                                        <span class="new-year-text">
                                            Happy New Year
                                        </span>
                                        <style>
                                            /* Font style for Happy New Year */
                                            .new-year-text {
                                                font-size: 60px;
                                                font-weight: bold;
                                                font-family: 'Pacifico', cursive;
                                                background: linear-gradient(to right, #e0e0e0, #f0f0f0, #ffffff, #e0e0e0);
                                                -webkit-background-clip: text;
                                                color: transparent;
                                                text-shadow: 0 0 4px #d4d4d4, 0 0 8px #e0e0e0;
                                                animation: text-animation 3s infinite ease-in-out;
                                            }

                                            /* Text animation */
                                            @keyframes text-animation {

                                                0%,
                                                100% {
                                                    transform: scale(1);
                                                    letter-spacing: 0px;
                                                }

                                                50% {
                                                    transform: scale(1.1);
                                                    letter-spacing: 2px;
                                                }
                                            }
                                        </style>
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
