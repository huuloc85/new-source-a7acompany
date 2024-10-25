<!-- resources/views/modal/birthday.blade.php -->
@php
    // Đường dẫn hình ảnh ngẫu nhiên
    $images = [
        asset('assets/img/birthdaycake.png'),
        asset('assets/img/birthdaycake1.jpg'),
        asset('assets/img/birthdaycake3.jpg'),
        asset('assets/img/birthdaycake5.jpg'),
    ];

    // Chọn ngẫu nhiên câu chúc và hình ảnh
    // $randomWish = $wishes[array_rand($wishes)];
    $randomImage = $images[array_rand($images)];
@endphp

<!-- resources/views/modal/birthday.blade.php -->
<!-- resources/views/modal/birthday.blade.php -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <style>
                /* CSS cho modal */
                .birthday-cake-img {
                    width: 80%;
                    border-radius: 10px;
                    margin-top: 20px;
                }

                @keyframes glow {
                    0% {
                        box-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
                    }

                    50% {
                        box-shadow: 0 0 20px rgba(255, 223, 0, 1);
                    }

                    100% {
                        box-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
                    }
                }

                .modal-content {
                    background: linear-gradient(135deg, #f0f8ff 30%, #ffffff 70%);
                    border: 2px solid #000000;
                    border-radius: 15px;
                    box-shadow: 0 0 40px rgba(0, 0, 0, 0.5);
                    animation: slideIn 0.7s forwards;
                }

                .modal-header {
                    border-bottom: 2px solid #000000;
                    background-color: #ffffff;
                    animation: glow-header 2s infinite alternate;
                }

                .modal-title {
                    font-weight: bold;
                    color: #d32f2f;
                    font-size: 1.8rem;
                    text-align: center;
                    margin: 0;
                    /* Không có khoảng cách trên và dưới */
                }

                @keyframes glow-header {
                    from {
                        box-shadow: 0 0 10px #ffffff;
                    }

                    to {
                        box-shadow: 0 0 20px #ffffff;
                    }
                }

                .modal-body {
                    font-family: 'Comic Sans MS', cursive, sans-serif;
                    color: #424242;
                    text-align: center;
                    animation: pulse 1.5s infinite;
                }

                @keyframes pulse {
                    0% {
                        transform: scale(1);
                    }

                    50% {
                        transform: scale(1.05);
                    }

                    100% {
                        transform: scale(1);
                    }
                }

                .employee-list {
                    list-style-type: none;
                    /* Không có dấu chấm đầu dòng */
                    padding: 0;
                    /* Không có khoảng cách bên trong */
                    margin: 20px 0;
                    /* Khoảng cách trên và dưới */
                }

                .employee-list li {
                    margin: 10px 0;
                    /* Khoảng cách giữa các tên */
                    font-weight: bold;
                    /* Chữ đậm */
                }

                .pyro>.before,
                .pyro>.after {
                    position: absolute;
                    width: 7px;
                    height: 7px;
                    pointer-events: none;
                    z-index: 99999999;
                    border-radius: 50%;
                    box-shadow: -120px -218.66667px blue, 248px -16.66667px #00ff84, 190px 16.33333px #002bff, -113px -308.66667px #ff009d, -109px -287.66667px #ffb300, -50px -313.66667px #ff006e, 226px -31.66667px #ff4000, 180px -351.66667px #ff00d0, -12px -338.66667px #00f6ff, 220px -388.66667px #99ff00, -69px -27.66667px #ff0400, -111px -339.66667px #6200ff, 155px -237.66667px #00ddff, -152px -380.66667px #00ffd0, -50px -37.66667px #00ffdd, -95px -175.66667px #a6ff00, -88px 10.33333px #0d00ff, 112px -309.66667px #005eff, 69px -415.66667px #ff00a6, 168px -100.66667px #ff004c, -244px 24.33333px #ff6600, 97px -325.66667px #ff0066, -211px -182.66667px #00ffa2, 236px -126.66667px #b700ff, 140px -196.66667px #9000ff, 125px -175.66667px #00bbff, 118px -381.66667px #ff002f, 144px -111.66667px #ffae00, 36px -78.66667px #f600ff, -63px -196.66667px #c800ff, -218px -227.66667px #d4ff00, -134px -377.66667px #ea00ff, -36px -412.66667px #ff00d4, 209px -106.66667px #00fff2, 91px -278.66667px #000dff, -22px -191.66667px #9dff00, 139px -392.66667px #a6ff00, 56px -2.66667px #0099ff, -156px -276.66667px #ea00ff, -163px -233.66667px #00fffb, -238px -346.66667px #00ff73, 62px -363.66667px #0088ff, 244px -170.66667px #0062ff, 224px -142.66667px #b300ff, 141px -208.66667px #9000ff, 211px -285.66667px #ff6600, 181px -128.66667px #1e00ff, 90px -123.66667px #c800ff, 189px 70.33333px #00ffc8, -18px -383.66667px #00ff33, 100px -6.66667px #ff008c;
                    -moz-animation: 1s bang ease-out infinite backwards, 1s gravity ease-in infinite backwards, 5s position linear infinite backwards;
                    -webkit-animation: 1s bang ease-out infinite backwards, 1s gravity ease-in infinite backwards, 5s position linear infinite backwards;
                    -o-animation: 1s bang ease-out infinite backwards, 1s gravity ease-in infinite backwards, 5s position linear infinite backwards;
                    -ms-animation: 1s bang ease-out infinite backwards, 1s gravity ease-in infinite backwards, 5s position linear infinite backwards;
                    animation: 1s bang ease-out infinite backwards, 1s gravity ease-in infinite backwards, 5s position linear infinite backwards;
                }

                .pyro>.after {
                    -moz-animation-delay: 1.25s, 1.25s, 1.25s;
                    -webkit-animation-delay: 1.25s, 1.25s, 1.25s;
                    -o-animation-delay: 1.25s, 1.25s, 1.25s;
                    -ms-animation-delay: 1.25s, 1.25s, 1.25s;
                    animation-delay: 1.25s, 1.25s, 1.25s;
                    -moz-animation-duration: 1.25s, 1.25s, 6.25s;
                    -webkit-animation-duration: 1.25s, 1.25s, 6.25s;
                    -o-animation-duration: 1.25s, 1.25s, 6.25s;
                    -ms-animation-duration: 1.25s, 1.25s, 6.25s;
                    animation-duration: 1.25s, 1.25s, 6.25s;
                }

                @-webkit-keyframes bang {
                    from {
                        box-shadow: 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white;
                    }
                }

                @-moz-keyframes bang {
                    from {
                        box-shadow: 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white;
                    }
                }

                @-o-keyframes bang {
                    from {
                        box-shadow: 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white;
                    }
                }

                @-ms-keyframes bang {
                    from {
                        box-shadow: 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white;
                    }
                }

                @keyframes bang {
                    from {
                        box-shadow: 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white;
                    }
                }

                @-webkit-keyframes gravity {
                    to {
                        transform: translateY(200px);
                        -moz-transform: translateY(200px);
                        -webkit-transform: translateY(200px);
                        -o-transform: translateY(200px);
                        -ms-transform: translateY(200px);
                        opacity: 0;
                    }
                }

                @-moz-keyframes gravity {
                    to {
                        transform: translateY(200px);
                        -moz-transform: translateY(200px);
                        -webkit-transform: translateY(200px);
                        -o-transform: translateY(200px);
                        -ms-transform: translateY(200px);
                        opacity: 0;
                    }
                }

                @-o-keyframes gravity {
                    to {
                        transform: translateY(200px);
                        -moz-transform: translateY(200px);
                        -webkit-transform: translateY(200px);
                        -o-transform: translateY(200px);
                        -ms-transform: translateY(200px);
                        opacity: 0;
                    }
                }

                @-ms-keyframes gravity {
                    to {
                        transform: translateY(200px);
                        -moz-transform: translateY(200px);
                        -webkit-transform: translateY(200px);
                        -o-transform: translateY(200px);
                        -ms-transform: translateY(200px);
                        opacity: 0;
                    }
                }

                @keyframes gravity {
                    to {
                        transform: translateY(200px);
                        -moz-transform: translateY(200px);
                        -webkit-transform: translateY(200px);
                        -o-transform: translateY(200px);
                        -ms-transform: translateY(200px);
                        opacity: 0;
                    }
                }

                @-webkit-keyframes position {

                    0%,
                    19.9% {
                        margin-top: 10%;
                        margin-left: 40%;
                    }

                    20%,
                    39.9% {
                        margin-top: 40%;
                        margin-left: 30%;
                    }

                    40%,
                    59.9% {
                        margin-top: 20%;
                        margin-left: 70%;
                    }

                    60%,
                    79.9% {
                        margin-top: 30%;
                        margin-left: 20%;
                    }

                    80%,
                    99.9% {
                        margin-top: 30%;
                        margin-left: 80%;
                    }
                }

                @-moz-keyframes position {

                    0%,
                    19.9% {
                        margin-top: 10%;
                        margin-left: 40%;
                    }

                    20%,
                    39.9% {
                        margin-top: 40%;
                        margin-left: 30%;
                    }

                    40%,
                    59.9% {
                        margin-top: 20%;
                        margin-left: 70%;
                    }

                    60%,
                    79.9% {
                        margin-top: 30%;
                        margin-left: 20%;
                    }

                    80%,
                    99.9% {
                        margin-top: 30%;
                        margin-left: 80%;
                    }
                }

                @-o-keyframes position {

                    0%,
                    19.9% {
                        margin-top: 10%;
                        margin-left: 40%;
                    }

                    20%,
                    39.9% {
                        margin-top: 40%;
                        margin-left: 30%;
                    }

                    40%,
                    59.9% {
                        margin-top: 20%;
                        margin-left: 70%;
                    }

                    60%,
                    79.9% {
                        margin-top: 30%;
                        margin-left: 20%;
                    }

                    80%,
                    99.9% {
                        margin-top: 30%;
                        margin-left: 80%;
                    }
                }

                @-ms-keyframes position {

                    0%,
                    19.9% {
                        margin-top: 10%;
                        margin-left: 40%;
                    }

                    20%,
                    39.9% {
                        margin-top: 40%;
                        margin-left: 30%;
                    }

                    40%,
                    59.9% {
                        margin-top: 20%;
                        margin-left: 70%;
                    }

                    60%,
                    79.9% {
                        margin-top: 30%;
                        margin-left: 20%;
                    }

                    80%,
                    99.9% {
                        margin-top: 30%;
                        margin-left: 80%;
                    }
                }

                @keyframes position {

                    0%,
                    19.9% {
                        margin-top: 10%;
                        margin-left: 40%;
                    }

                    20%,
                    39.9% {
                        margin-top: 40%;
                        margin-left: 30%;
                    }

                    40%,
                    59.9% {
                        margin-top: 20%;
                        margin-left: 70%;
                    }

                    60%,
                    79.9% {
                        margin-top: 30%;
                        margin-left: 20%;
                    }

                    80%,
                    99.9% {
                        margin-top: 30%;
                        margin-left: 80%;
                    }
                }
            </style>
            <div class="modal-header">
                <h5 class="modal-title" id="birthdayModalLabel">🎉 Chúc Mừng Sinh Nhật! 🎉</h5>
            </div>
            <div class="modal-body">
                <h3>Công Ty Vinh Vinh Phát Chúc Mừng Sinh Nhật!</h3>
                <div class="pyro">
                    <div class="before"></div>
                    <img src="{{ $randomImage }}" alt="Birthday Cake" class="birthday-cake-img">
                    <div class="after"></div>
                </div>
                <ul class="employee-list">
                    @foreach (session('birthday_employees', []) as $employee)
                        @php
                            $wishes = [
                                "Chúc bạn, {$employee} có một tuổi mới tràn đầy sức khỏe và thành công!",
                                "Hy vọng tuổi mới sẽ mang lại nhiều may mắn và niềm vui cho bạn, {$employee}!",
                                "Chúc bạn, {$employee} luôn hạnh phúc, thành đạt và ngày càng xinh đẹp!",
                                "Chúc bạn, {$employee} luôn vui vẻ, đạt được mọi điều mong muốn trong cuộc sống!",
                                "Một tuổi mới đầy niềm vui và năng lượng mới đang chờ đón bạn, {$employee}!",
                                "Chúc bạn, {$employee} thành công trong mọi dự định và ước mơ trong năm tới!",
                                "Mong rằng bạn, {$employee} sẽ luôn nhận được sự hỗ trợ và đồng hành từ đồng nghiệp trong công việc!",
                                "Chúc bạn, {$employee} có nhiều cơ hội để phát triển bản thân và thăng tiến trong sự nghiệp!",
                                "Hy vọng mỗi ngày của bạn, {$employee} đều tràn đầy hạnh phúc và niềm vui trong công việc!",
                                "Chúc bạn luôn giữ vững tinh thần làm việc và không ngừng phấn đấu, {$employee}!",
                                "Mong rằng năm mới sẽ mang đến cho bạn, {$employee} nhiều dự án thành công và đạt được mọi mục tiêu!",
                                "Chúc bạn, {$employee} có một ngày sinh nhật thật đáng nhớ bên những người bạn yêu thương!",
                                "Mong rằng công ty sẽ luôn là một nơi làm việc vui vẻ và ý nghĩa đối với bạn, {$employee}!",
                                "Chúc bạn, {$employee} gặt hái nhiều thành công và niềm vui trong cả công việc lẫn cuộc sống!",
                                "Hy vọng bạn, {$employee} sẽ luôn là nguồn cảm hứng cho mọi người xung quanh!",
                                "Chúc bạn, {$employee} sẽ có nhiều kỷ niệm đẹp trong năm mới và những mối quan hệ tốt đẹp!",
                            ];
                            $randomWish = $wishes[array_rand($wishes)];
                        @endphp
                        <li>{{ $randomWish }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    // Hiển thị modal khi trang load
    document.addEventListener("DOMContentLoaded", function() {
        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show(); // Hiển thị modal

        // Tự động tắt modal sau 5 giây
        setTimeout(function() {
            successModal.hide(); // Ẩn modal sau 5 giây
        }, 10000); // 5000 milliseconds = 5 seconds
    });
</script>
