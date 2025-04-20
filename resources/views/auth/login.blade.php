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
        <title>Đăng Nhập Vinh Vinh Phát</title>
        <!--     Fonts and icons     -->
        <link
            rel="stylesheet"
            type="text/css"
            href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700"
        />
        <!-- Font Awesome Icons -->
        {{-- <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script> --}}
        <!-- CSS Files -->
        <link
            rel="stylesheet"
            href="{{ asset('assets/css/hope-ui.css?v=1.0') }}"
        />
        <link rel="stylesheet" href="{{ asset('assets/css/libs.min.css') }}" />
        <link
            rel="stylesheet"
            href="{{ asset('assets/css/custom.css?v=1.1.0') }}"
        />
        <link
            rel="stylesheet"
            href="{{ asset('assets/css/customizer.css?v=1.1.0') }}"
        />
        <link
            rel="stylesheet"
            href="{{ asset('assets/css/hope-ui.css?v=1.1.0') }}"
        />
        <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
        {{-- <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script> --}}
        <style>
            .form-check {
                padding-left: 0px !important;
            }
        </style>
    </head>

    <body>
        @include('sweetalert::alert')
        <div class="wrapper">
            @include('auth.'.'login_'.$layout)
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#togglePassword').click(function () {
                    const passwordField = $('#password');
                    const passwordFieldType = passwordField.attr('type');
                    const passwordToggleIcon = $('#togglePasswordIcon');
                    if (passwordFieldType === 'password') {
                        passwordField.attr('type', 'text');
                        passwordToggleIcon
                            .removeClass('fa-eye')
                            .addClass('fa-eye-slash');
                    } else {
                        passwordField.attr('type', 'password');
                        passwordToggleIcon
                            .removeClass('fa-eye-slash')
                            .addClass('fa-eye');
                    }
                });
            });
        </script>
    </body>
</html>
