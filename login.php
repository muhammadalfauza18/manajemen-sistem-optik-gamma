<?php
session_start();
include "config/database.php";
if (isset($_SESSION['login'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Optik Gamma | Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/login.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>

    <!-- Background image + blob -->
    <div class="page-bg">
        <img src="assets/img/bs-login.png" class="bg-login" alt="">
        <div class="bg-overlay"></div>
        <div class="bg-blob"></div>
    </div>

    <!-- Login content -->
    <div class="login-page">

        <div class="login-wrapper">

            <div class="text-center">
                <img src="assets/img/logo.png" class="logo" alt="Optik Gamma">
                <h2 class="title">Optik Gamma</h2>
                <p class="subtitle">sistem optik gamma</p>
            </div>

            <form action="proses_login.php" method="POST" class="login-form">

                <div class="mb-3">
                    <label class="form-label" for="username">USERNAME</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-user input-icon"></i>
                        <input type="text" class="form-control" id="username" name="username" placeholder="email"
                            required>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center label-row">
                        <label class="form-label mb-0" for="password">PASSWORD</label>
                    </div>
                    <div class="input-wrap">
                        <i class="fa-solid fa-lock input-icon"></i>
                        <input type="password" class="form-control has-toggle" id="password" name="password"
                            placeholder="••••••••" required>
                        <button type="button" class="toggle-eye" onclick="togglePassword()" aria-label="Show password">
                            <i class="fa-regular fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn btn-login w-100">
                    Login <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>

            </form>

        </div>

        <div class="footer-login">
            <div class="status">
                <span class="dot"></span>
                Sistem Optik Gamma
            </div>
            <p>&copy; 2026 Optik Gamma. All rights reserved.</p>
        </div>

    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById("password");
            const icon = document.getElementById("eyeIcon");
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>

</body>

</html>