import { Html5Qrcode } from "html5-qrcode";

document.addEventListener("DOMContentLoaded", function () {
    const reader = document.getElementById("reader");
    if (!reader) {
        console.error("Không tìm thấy phần tử reader");
        return;
    }

    const url = reader.dataset.url;
    const html5QrCode = new Html5Qrcode("reader");
    let startApi = true;

    const onScanSuccess = (decodedText, decodedResult) => {
        if (decodedText && decodedText !== "*!" && decodedText !== "U8'48*(") {
            console.log("✅ Mã quét:", decodedText);

            if (startApi) {
                fetch(url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                    body: JSON.stringify({
                        barcode: decodedText,
                    }),
                })
                    .then((res) => res.json())
                    .then((response) => {
                        switch (response.status) {
                            case 200:
                                alert("✅ Cập nhật thành công. Đợi 5 giây...");
                                break;
                            case 400:
                                alert("⚠️ Mã đã được quét.");
                                break;
                            case 404:
                                alert("❌ Không tìm thấy sản phẩm.");
                                break;
                            default:
                                alert("⚠️ Lỗi không xác định.");
                                break;
                        }
                    })
                    .catch((err) => console.error("Lỗi gửi mã:", err));

                startApi = false;
                setTimeout(() => {
                    startApi = true;
                }, 5000);
            }
        }
    };

    Html5Qrcode.getCameras()
        .then((devices) => {
            if (devices && devices.length) {
                const cameraId = devices[0].id;
                html5QrCode.start(
                    { deviceId: { exact: cameraId } },
                    { fps: 10, qrbox: 250 },
                    onScanSuccess,
                );
            }
        })
        .catch((err) => {
            console.error("Không thể truy cập camera:", err);
        });
});
