<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR Code</title>
    <!-- Tambahkan CSS untuk tampilan halaman -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Scan QR Code</div>
                    <div class="card-body">
                        <!-- Tambahkan elemen untuk menampilkan hasil pemindaian QR code -->
                        <div id="qr-result"></div>
                        <!-- Tambahkan elemen untuk kamera -->
                        <div id="qr-video">
                            <video width="100%" height="100%" autoplay muted playsinline></video>
                        </div>
                        <!-- Tambahkan pesan instruksi -->
                        <div id="qr-message" class="mt-3 text-muted">Arahkan kamera ke QR code</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambahkan JavaScript untuk pemindaian QR code -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsQR/1.0.1/jsQR.min.js"></script>
    <script>
        // Fungsi untuk memulai pemindaian QR code
        function startScan() {
            navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: "environment"
                    }
                })
                .then(function(stream) {
                    // Peroleh elemen video
                    var videoElement = document.querySelector("video");
                    // Mulai stream video
                    videoElement.srcObject = stream;
                    videoElement.setAttribute("playsinline", true);
                    videoElement.play();
                    // Tambahkan event listener untuk pemindaian QR code
                    videoElement.addEventListener("canplay", function(ev) {
                        // Setup elemen kanvas untuk merender video
                        var canvasElement = document.createElement("canvas");
                        var canvas = canvasElement.getContext("2d");
                        // Set ukuran kanvas sesuai dengan ukuran video
                        canvasElement.width = videoElement.videoWidth;
                        canvasElement.height = videoElement.videoHeight;
                        // Mencetak video ke kanvas
                        canvas.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);
                        // Mendapatkan data dari kanvas
                        var imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
                        var code = jsQR(imageData.data, imageData.width, imageData.height, {
                            inversionAttempts: "dontInvert",
                        });
                        // Jika QR code ditemukan
                        if (code) {
                            // Hentikan stream video
                            videoElement.srcObject.getTracks().forEach(function(track) {
                                track.stop();
                            });
                            // Tampilkan hasil pemindaian QR code
                            document.getElementById("qr-result").innerHTML = "Hasil: " + code.data;
                        } else {
                            // Jika QR code tidak ditemukan, coba lagi
                            setTimeout(startScan, 100);
                        }
                    });
                })
                .catch(function(err) {
                    // Tangani kesalahan jika akses kamera ditolak
                    console.error("Error accessing camera:", err);
                });
        }
        // Panggil fungsi startScan saat dokumen siap
        document.addEventListener("DOMContentLoaded", startScan);
    </script>
</body>

</html>
