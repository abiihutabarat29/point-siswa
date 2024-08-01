<!DOCTYPE html>

<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>SISKO APP</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/smk-n-air-putih.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>

</head>

<body>
    <!-- Content -->
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="justify-content-center">
                            <a href="#" class="app-brand-link gap-2">
                                <img src="{{ asset('assets/img/favicon/smk-n-air-putih.png') }}"
                                    alt="SMK Negeri Air Putih" style="width: 50px; height: 50px;" class="me-3">
                                <h4 class="text-body fw-bold mt-3">SMK Negeri 1 Air Putih</h4>
                            </a>
                        </div>
                        <hr>
                        <!-- /Logo -->
                        <p class="mb-3" id="typing-text"></p>
                        <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    placeholder="Masukkan email anda" autofocus />
                                @error('email')
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-4 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label">Password</label>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="Masukkan password anda" aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    @error('password')
                                        <span class="text-danger text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit">Masuk</button>
                            </div>
                        </form>
                    </div>
                </div>
                <footer class="footer mt-2"
                    style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
                    <small>Copyright ©2024 SMK Negeri 1 Air Putih. All rights reserved.</small>
                    <br>
                    <small>Versi 1.0.0</small>
                </footer>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const texts = [
                "Selamat Datang di Aplikasi SISKO",
                "Silahkan masuk ke akun anda!"
            ];
            const typingTextElement = document.getElementById("typing-text");
            let textIndex = 0;
            let charIndex = 0;

            function type() {
                if (charIndex < texts[textIndex].length) {
                    typingTextElement.innerHTML += texts[textIndex].charAt(charIndex);
                    charIndex++;
                    setTimeout(type, 100);
                } else {
                    setTimeout(resetAndType, 10000);
                }
            }

            function resetAndType() {
                typingTextElement.innerHTML = "";
                charIndex = 0;
                textIndex = (textIndex + 1) % texts.length;
                type();
            }

            type();
        });

        document.addEventListener("DOMContentLoaded", function() {
            const emailInput = document.getElementById("email");
            const passwordInput = document.getElementById("password");
            const submitButton = document.querySelector("button[type='submit']");

            function checkFormValidity() {
                const emailValue = emailInput.value.trim();
                const passwordValue = passwordInput.value.trim();
                // Aktifkan tombol jika kedua input diisi, nonaktifkan sebaliknya
                submitButton.disabled = !(emailValue && passwordValue);
            }

            // Periksa validitas form saat ada perubahan di input
            emailInput.addEventListener("input", checkFormValidity);
            passwordInput.addEventListener("input", checkFormValidity);

            // Inisialisasi dengan memeriksa form saat pertama kali dimuat
            checkFormValidity();
        });
    </script>
</body>

</html>
