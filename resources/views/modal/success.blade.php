<!-- resources/views/modal/birthday.blade.php -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <style>
                /* CSS cho modal */
                .birthday-cake-img {
                    width: 80%;
                    /* Đặt kích thước hình ảnh cake */
                    border-radius: 10px;
                    /* Bo góc cho hình ảnh */
                    margin-top: 20px;
                    /* Tạo khoảng cách phía trên */
                    animation: bounce 1.5s infinite, glow 1.5s infinite;
                    /* Hiệu ứng nhảy và phát sáng */
                }

                /* Hiệu ứng phát sáng nhẹ cho hình ảnh */
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
                    background: #ffffff;
                    /* Đặt nền trắng */
                    border: 2px solid #000000;
                    border-radius: 15px;
                    box-shadow: 0 0 40px rgba(0, 0, 0, 0.5);
                    animation: slideIn 0.7s forwards;
                }


                .modal-header {
                    border-bottom: 2px solid #000000;
                    /* Đường viền dưới nổi bật */
                    background-color: #ffffff;
                    /* Màu vàng tươi sáng */
                    animation: glow-header 2s infinite alternate;
                    /* Hiệu ứng phát sáng cho header */
                }

                .modal-title {
                    font-weight: bold;
                    /* Chữ đậm */
                    color: #d32f2f;
                    /* Màu đỏ tươi */
                    font-size: 1.8rem;
                    /* Tăng kích thước chữ tiêu đề */
                    text-align: center;
                }

                @keyframes glow-header {
                    from {
                        box-shadow: 0 0 10px #ffffff;
                    }

                    to {
                        box-shadow: 0 0 20px #ffffff;
                    }
                }

                .modal-footer {
                    border-top: 2px solid #000000;
                    /* Đường viền trên nổi bật */
                    background-color: #ffccbc;
                    /* Màu nền footer dịu nhẹ */
                }

                .modal-body {
                    font-family: 'Comic Sans MS', cursive, sans-serif;
                    /* Font vui tươi */
                    color: #424242;
                    /* Màu chữ */
                    text-align: center;
                    /* Canh giữa chữ */
                    animation: pulse 1.5s infinite;
                    /* Hiệu ứng nhịp tim cho nội dung */
                }

                /* Hiệu ứng nhịp tim cho nội dung */
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

                /* Hiệu ứng nhảy */
                @keyframes bounce {

                    0%,
                    20%,
                    50%,
                    80%,
                    100% {
                        transform: translateY(0);
                    }

                    40% {
                        transform: translateY(-15px);
                    }

                    60% {
                        transform: translateY(-7px);
                    }
                }

                /* Hiệu ứng trượt vào */
                @keyframes slideIn {
                    0% {
                        transform: translateY(-100px);
                        opacity: 0;
                    }

                    100% {
                        transform: translateY(0);
                        opacity: 1;
                    }
                }

                /* Hiệu ứng đổ bóng */
                .modal-backdrop.show {
                    opacity: 0.8;
                    /* Độ mờ cho backdrop */
                }

                /* Hiệu ứng mờ dần khi hiện lên */
                @keyframes fadeIn {
                    0% {
                        opacity: 0;
                    }

                    100% {
                        opacity: 1;
                    }
                }
            </style>
            <div class="modal-header">
                <h5 class="modal-title" id="birthdayModalLabel">🎉 Chúc Mừng Sinh Nhật! 🎉</h5>
            </div>
            <div class="modal-body">
                <h3>Công Ty Vinh Vinh Phát chúc mừng sinh nhật!</h3>
                <p>Chúc bạn có một ngày sinh nhật thật vui vẻ và hạnh phúc!</p>
                <img src="{{ asset('assets/img/birthdaycake.png') }}" alt="Birthday Cake" class="birthday-cake-img">
            </div>
        </div>
    </div>
</div>
<script>
    // Hiển thị modal khi trang load
    document.addEventListener("DOMContentLoaded", function() {
        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show(); // Hiển thị modal

        // Tự động tắt modal sau 10 giây
        setTimeout(function() {
            successModal.hide(); // Ẩn modal sau 10 giây
        }, 5000); // 10000 milliseconds = 10 seconds
    });
</script>
