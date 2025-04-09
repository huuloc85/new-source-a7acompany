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
            // 👉 Tách mã cần lấy, ví dụ như "00K9P"
            const match = decodedText.match(/\s+([A-Z0-9]{5})\s+/);
            const code = match ? match[1] : null;

            if (code && startApi) {
                console.log("✅ Mã đã tách:", code);
                alert("🎉 Quét mã thành công!\nMã: " + code);

                fetch(url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                    body: JSON.stringify({
                        qr_code: code,
                    }),
                });

                // 👉 Cho nghỉ 5 giây để tránh gửi liên tục
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
