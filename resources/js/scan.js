import { Html5Qrcode } from "html5-qrcode";

document.addEventListener("DOMContentLoaded", function () {
    const reader = new Html5Qrcode("reader");

    Html5Qrcode.getCameras().then((devices) => {
        if (devices && devices.length) {
            reader.start(
                { facingMode: "environment" },
                {
                    fps: 10,
                    qrbox: 250,
                },
                (decodedText, decodedResult) => {
                    console.log("✅ Mã quét:", decodedText);
                    console.log("📦 Kết quả chi tiết:", decodedResult);
                },
                (errorMessage) => {
                    console.warn("Lỗi quét:", errorMessage);
                },
            );
        }
    });
});
