<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
    <title>
        Register
    </title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('assets/css/material-dashboard.css?v=3.1.0') }}" rel="stylesheet" />
    <!-- Nepcha Analytics (nepcha.com) -->
    <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
    {{-- <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script> --}}
</head>

<body class="">
    <main class="main-content  mt-0">
        <section class="login-content">
            <div class="row m-0 align-items-center bg-white vh-100">
                <div class="col-md-6 d-md-block d-none bg-primary p-0 mt-n1 vh-100 overflow-hidden">
                    <img src="{{ asset('images/auth/05.png') }}" class="img-fluid gradient-main animated-scaleX"
                        alt="images">
                </div>
                <div class="col-md-6">
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="card card-transparent auth-card shadow-none d-flex justify-content-center mb-0">
                                <div class="card-body">
                                    <a href="{{ route('dashboard') }}"
                                        class="navbar-brand d-flex align-items-center mb-3">
                                        <svg width="30" class="text-primary" viewBox="0 0 30 30" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect x="-0.757324" y="19.2427" width="28" height="4" rx="2"
                                                transform="rotate(-45 -0.757324 19.2427)" fill="currentColor" />
                                            <rect x="7.72803" y="27.728" width="28" height="4" rx="2"
                                                transform="rotate(-45 7.72803 27.728)" fill="currentColor" />
                                            <rect x="10.5366" y="16.3945" width="16" height="4" rx="2"
                                                transform="rotate(45 10.5366 16.3945)" fill="currentColor" />
                                            <rect x="10.5562" y="-0.556152" width="28" height="4" rx="2"
                                                transform="rotate(45 10.5562 -0.556152)" fill="currentColor" />
                                        </svg>
                                        <h4 class="logo-title ms-3">{{ env('APP_NAME') }}</h4>
                                    </a>
                                    <h2 class="mb-2 text-center">Sign Up</h2>
                                    <p class="text-center">Create your {{ env('APP_NAME') }} account.</p>
                                    <x-auth-session-status class="mb-4" :status="session('status')" />

                                    <!-- Validation Errors -->
                                    <x-auth-validation-errors class="mb-4" :errors="$errors" />
                                    <form method="POST" action="{{ route('register') }}" data-toggle="validator">
                                        {{ csrf_field() }}
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="full-name" class="form-label">Full Name</label>
                                                    <input id="full-name" name="first_name"
                                                        value="{{ old('first_name') }}" class="form-control"
                                                        type="text" placeholder=" " required autofocus>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="last_name" class="form-label">Last Name</label>
                                                    <input class="form-control" type="text" name="last_name"
                                                        id="last_name" placeholder=" " value="{{ old('last_name') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="email">Email <span
                                                            class="text-danger">*</span></label>
                                                    <input class="form-control" type="email" placeholder=" "
                                                        id="email" name="email" value="{{ old('email') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="phone" class="form-label">Phone No.</label>
                                                    <input class="form-control" type="text" name="phone_number"
                                                        placeholder=" " id="phone">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="password" class="form-label">Password</label>
                                                    <input class="form-control" type="password" placeholder=" "
                                                        id="password" name="password" required
                                                        autocomplete="new-password">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="confirm-password" class="form-label">Confirm
                                                        Password</label>
                                                    <input id="password_confirmation" class="form-control"
                                                        type="password" placeholder=" " name="password_confirmation"
                                                        id="confirm-password" required>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <div class="form-check mb-3">
                                                    <label class="form-check-label" for="customCheck1">I agree with
                                                        the terms of use</label>
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="customCheck1" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('sign up') }}</button>
                                        </div>
                                        <p class="text-center my-3">or sign in with other accounts?</p>
                                        <div class="d-flex justify-content-center">
                                            <ul class="list-group list-group-horizontal list-group-flush">
                                                <li class="list-group-item border-0 pb-0">
                                                    <a href="#"><img src="{{ asset('images/brands/fb.svg') }}"
                                                            alt="fb"></a>
                                                </li>
                                                <li class="list-group-item border-0 pb-0">
                                                    <a href="#"><img src="{{ asset('images/brands/gm.svg') }}"
                                                            alt="gm"></a>
                                                </li>
                                                <li class="list-group-item border-0 pb-0">
                                                    <a href="#"><img src="{{ asset('images/brands/im.svg') }}"
                                                            alt="im"></a>
                                                </li>
                                                <li class="list-group-item border-0 pb-0">
                                                    <a href="#"><img src="{{ asset('images/brands/li.svg') }}"
                                                            alt="li"></a>
                                                </li>
                                            </ul>
                                        </div>
                                        <p class="mt-3 text-center">
                                            Already have an Account <a href="{{ route('auth.signin') }}"
                                                class="text-underline">Sign In</a>
                                        </p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sign-bg sign-bg-right">
                        <svg width="280" height="230" viewBox="0 0 431 398" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g opacity="0.05">
                                <rect x="-157.085" y="193.773" width="543" height="77.5714" rx="38.7857"
                                    transform="rotate(-45 -157.085 193.773)" fill="#3B8AFF" />
                                <rect x="7.46875" y="358.327" width="543" height="77.5714" rx="38.7857"
                                    transform="rotate(-45 7.46875 358.327)" fill="#3B8AFF" />
                                <rect x="61.9355" y="138.545" width="310.286" height="77.5714" rx="38.7857"
                                    transform="rotate(45 61.9355 138.545)" fill="#3B8AFF" />
                                <rect x="62.3154" y="-190.173" width="543" height="77.5714" rx="38.7857"
                                    transform="rotate(45 62.3154 -190.173)" fill="#3B8AFF" />
                            </g>
                        </svg>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <!-- <script src="{{ asset('assets/js/material-dashboard.min.js?v=3.1.0') }}"></script> -->
</body>

</html>
