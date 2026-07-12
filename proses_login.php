<?php
session_start();
include "config/database.php";

// Pastikan form dikirim dengan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Cari user berdasarkan username
    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");

    if (mysqli_num_rows($query) > 0) {

        $user = mysqli_fetch_assoc($query);

        // Jika password masih plain text
        if (password_verify($password, $user['password'])) {

            // Cek apakah akun aktif
            if ($user['status'] != "Aktif") {
                echo "<script>
                        alert('Akun tidak aktif!');
                        window.location='index.php';
                      </script>";
                exit;
            }

            $_SESSION['login'] = true;
            $_SESSION['id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['foto'] = $user['foto'];

            header("Location: dashboard.php");
            exit;

        } else {

            echo "<script>
                    alert('Password salah!');
                    window.location='index.php';
                  </script>";
        }

    } else {

        echo "<script>
                alert('Username tidak ditemukan!');
                window.location='index.php';
              </script>";
    }

} else {

    header("Location: index.php");
}
?>