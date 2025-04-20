<section class="login-content">
    <div class="row m-0 align-items-center bg-white vh-100">
        <div class="col-md-6">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div
                        class="card card-transparent shadow-none d-flex justify-content-center mb-0 auth-card"
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
            <div class="sign-bg">
                <svg
                    width="280"
                    height="230"
                    viewBox="0 0 431 398"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <g opacity="0.05">
                        <rect
                            x="-157.085"
                            y="193.773"
                            width="543"
                            height="77.5714"
                            rx="38.7857"
                            transform="rotate(-45 -157.085 193.773)"
                            fill="#3B8AFF"
                        />
                        <rect
                            x="7.46875"
                            y="358.327"
                            width="543"
                            height="77.5714"
                            rx="38.7857"
                            transform="rotate(-45 7.46875 358.327)"
                            fill="#3B8AFF"
                        />
                        <rect
                            x="61.9355"
                            y="138.545"
                            width="310.286"
                            height="77.5714"
                            rx="38.7857"
                            transform="rotate(45 61.9355 138.545)"
                            fill="#3B8AFF"
                        />
                        <rect
                            x="62.3154"
                            y="-190.173"
                            width="543"
                            height="77.5714"
                            rx="38.7857"
                            transform="rotate(45 62.3154 -190.173)"
                            fill="#3B8AFF"
                        />
                    </g>
                </svg>
            </div>
        </div>
        <div
            class="col-md-6 d-md-block d-none bg-primary p-0 mt-n1 vh-100 overflow-hidden"
        >
            <img
                src="{{ asset('assets/img/auth/01.png') }}"
                class="img-fluid gradient-main animated-scaleX"
                alt="images"
            />
        </div>
    </div>
</section>
