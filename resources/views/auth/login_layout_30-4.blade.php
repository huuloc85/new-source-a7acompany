<section class="login-content">
    <style>
        .flags-container {
            position: absolute;
            height: 11rem;
            width: 100%;
        }
        .flag {
            height: 25px;
        }
        @media (min-width: 768px) {
            .flags-container {
                width: 50%;
                height: 20rem;
            }
            .flag {
                height: 40px;
            }
        }
    </style>
    <div class="flags-container">
        <img
            src="{{ asset('assets/img/vn-flag-waving.gif') }}"
            alt="flag"
            class="flag position-absolute top-0 start-0 mt-3 ms-3"
        />
        <img
            src="{{ asset('assets/img/vn-flag-waving.gif') }}"
            alt="flag"
            class="flag position-absolute bottom-0 start-0 mb-3 ms-3"
        />
        <img
            src="{{ asset('assets/img/vn-flag-waving.gif') }}"
            alt="flag"
            class="flag position-absolute top-0 end-0 mt-3 me-3"
        />
        <img
            src="{{ asset('assets/img/vn-flag-waving.gif') }}"
            alt="flag"
            class="flag position-absolute bottom-0 end-0 mb-3 me-3"
        />
    </div>
    <div class="flags-container" style="bottom: 0">
        <img
            src="{{ asset('assets/img/vn-flag-waving.gif') }}"
            alt="flag"
            class="flag position-absolute top-0 start-0 mt-3 ms-3"
        />
        <img
            src="{{ asset('assets/img/vn-flag-waving.gif') }}"
            alt="flag"
            class="flag position-absolute bottom-0 start-0 mb-3 ms-3"
        />
        <img
            src="{{ asset('assets/img/vn-flag-waving.gif') }}"
            alt="flag"
            class="flag position-absolute top-0 end-0 mt-3 me-3"
        />
        <img
            src="{{ asset('assets/img/vn-flag-waving.gif') }}"
            alt="flag"
            class="flag position-absolute bottom-0 end-0 mb-3 me-3"
        />
    </div>
    <div class="row m-0 align-items-center bg-white vh-100">
        <div class="col-md-6">
            <div class="logo-30-4 text-center">
                <picture>
                    <source
                        media="(max-width: 767px)"
                        srcset="
                            {{ asset('assets/img/auth/30-4/background.png') }}
                        "
                        type="image/png"
                        height="75"
                    />
                    <img
                        src="{{ asset('assets/img/auth/30-4/background.png') }}"
                        height="150"
                        alt="30-4 background"
                    />
                </picture>
                <p class="text-uppercase text-center fw-bold mt-2 text-30-4">
                    50 năm ngày giải phóng miền nam
                    <br />
                    thống nhất đất nước
                </p>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div
                        class="card card-transparent shadow-none d-flex justify-content-center mb-0 auth-card"
                        style="background-color: inherit"
                    >
                        <div class="card-body">
                            <h2 class="mb-2 text-center">Đăng Nhập</h2>
                            <p class="text-center">Đăng nhập để sử dụng Web.</p>
                            <form
                                method="POST"
                                action="{{ route('handleLogin') }}"
                                data-toggle="validator"
                            >
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label
                                                for="email"
                                                class="form-label"
                                            >
                                                Số điện thoại hoặc tài khoản
                                            </label>
                                            <input
                                                type="text"
                                                id="email"
                                                placeholder="Nhập số điện thoại hoặc tài khoản"
                                                class="form-control"
                                                name="phone"
                                                required
                                            />
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div
                                            class="form-group position-relative"
                                        >
                                            <label
                                                for="password"
                                                class="form-label"
                                            >
                                                Mật khẩu
                                            </label>
                                            <input
                                                type="password"
                                                id="password"
                                                placeholder="*********"
                                                class="form-control"
                                                name="password"
                                            />
                                            <span
                                                class="position-absolute end-0 top-50 pe-3"
                                                id="togglePassword"
                                                style="
                                                    cursor: pointer;
                                                    margin-top: 5px;
                                                "
                                            >
                                                <i
                                                    class="fa fa-eye"
                                                    id="togglePasswordIcon"
                                                ></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-check mb-3">
                                            <input
                                                type="checkbox"
                                                class="form-check-input"
                                                id="customCheck1"
                                            />
                                            <label
                                                class="form-check-label"
                                                for="customCheck1"
                                            >
                                                Ghi nhớ tài khoản
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >
                                        Đăng nhập
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div
            class="col-md-6 d-md-flex justify-content-md-center align-items-md-center d-none p-0 mt-n1 vh-100 overflow-hidden"
            style="background-color: #3c6efc"
        >
            <img
                src="{{ asset('assets/img/auth/30-4/co-viet-nam-waving.gif') }}"
                class="img-fluid gradient-main"
                alt="images"
            />
        </div>
    </div>
</section>
