<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />
        <link
            rel="apple-touch-icon"
            sizes="76x76"
            href="{{ asset('assets/img/apple-icon.png') }}"
        />
        <link
            rel="icon"
            type="image/png"
            href="{{ asset('assets/img/vvp.jpg') }}"
        />
        <title>VINH VINH PHAT</title>
        <!--     Fonts and icons     -->
        <link
            rel="stylesheet"
            type="text/css"
            href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700"
        />
        <!-- Font Awesome Icons -->
        {{--
            <script
            src="https://kit.fontawesome.com/42d5adcbca.js"
            crossorigin="anonymous"
            ></script>
        --}}
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        />
        <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
        {{-- <script defer data-site="127.0.0.1" src="https://api.nepcha.com/js/nepcha-analytics.js"></script> --}}
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        {{-- <script src="https://momentjs.com/downloads/moment.min.js"></script> --}}
        <!-- Nucleo Icons -->
        {{-- <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" /> --}}
        {{-- <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" /> --}}
        {{-- <link id="pagestyle" href="{{ asset('assets/css/material-dashboard.css?v=3.1.0') }}" rel="stylesheet" /> --}}
        <!-- Bootstrap JS (with Popper.js for dropdowns) -->
        {{-- <link rel="stylesheet" href="{{ asset('assets/css/libs.min.css') }}" /> --}}
        {{-- <link rel="stylesheet" href="{{ asset('assets/css/hope-ui.css?v=1.1.0') }}" /> --}}
        {{-- <link rel="stylesheet" href="{{ asset('assets/css/custom.css?v=1.1.0') }}" /> --}}
        {{-- <link rel="stylesheet" href="{{ asset('assets/css/dark.css?v=1.1.0') }}" /> --}}
        {{-- <link rel="stylesheet" href="{{ asset('assets/css/rtl.css?v=1.1.0') }}" /> --}}
        {{-- <link rel="stylesheet" href="{{ asset('assets/css/customizer.css?v=1.1.0') }}" /> --}}
        {{-- <link rel="stylesheet" href="{{ asset('vendor/Leaflet/leaflet.css') }}" /> --}}
        {{-- <link rel="stylesheet" href="{{ asset('assets/css/total.css') }}" /> --}}
        {{-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script> --}}
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
            rel="stylesheet"
        />
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
        {{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script> --}}
        {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" /> --}}
        {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

        <!-- Styling css -->
        @yield('styles')
    </head>

    <body>
        <div id="loading">
            <div
                class="loader simple-loader animate__animated animate__fadeOut"
            >
                <div class="loader-body"></div>
            </div>
        </div>
        <!-- ======= Sidebar ======= -->
        @include('partials.sidebar')
        <main class="main-content">
            <!-- ======= Header ======= -->
            {{--
                <header class="position-relative no-print">
                @include('partials.header')
                </header>
            --}}
            <!-- End Header -->

            <div class="container-fluid">
                @include('sweetalert::alert')
                @yield('content')
            </div>
            <!-- ======= Footer ======= -->
            @include('partials.footer')
            <!-- End Footer -->
        </main>
        <!--   Core JS Files   -->
        <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
        {{-- <script src="{{ asset('assets/js/libs.min.js') }}"></script> --}}
        {{-- <script src="{{ asset('assets/js/hope-ui.js') }}"></script> --}}
        {{-- <script src="{{ asset('assets/js/modelview.js') }}"></script> --}}
        {{-- <script src="{{ asset('assets/js/charts/dashboard.js') }}"></script> --}}
        @yield('scripts')
    </body>
</html>
