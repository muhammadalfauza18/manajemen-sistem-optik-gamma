<?php
session_start();
include "../config/database.php";

// ========================
// Ambil Data Form
// ========================

$nama = trim($_POST['nama']);
$username = trim($_POST['username']);
$password = $_POST['password'];
$confirm = $_POST['confirm_password'];
$role = $_POST['role'];
$no_hp = trim($_POST['no_hp']);
$email = trim($_POST['email']);
$alamat = trim($_POST['alamat']);
$status = $_POST['status'];

// ========================
// Validasi Password
// ========================

if ($password != $confirm) {

    echo "<script>
            alert('Konfirmasi password tidak sesuai!');
            window.history.back();
          </script>";
    exit;
}

// ========================
// Username Sudah Ada?
// ========================

$cek = mysqli_query($conn, "SELECT * FROM users
WHERE username='$username'");

if (mysqli_num_rows($cek) > 0) {

    echo "<script>
            alert('Username sudah digunakan!');
            window.history.back();
          </script>";
    exit;
}

// ========================
// Upload Foto
// ========================

$foto = "default.png";

if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {

    $namaFile = $_FILES['foto']['name'];
    $tmpFile = $_FILES['foto']['tmp_name'];
    $ukuran = $_FILES['foto']['size'];

    $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (in_array($ext, $allowed)) {

        if ($ukuran <= 2097152) {

            $foto = time() . "_" . $namaFile;

            move_uploaded_file(
                $tmpFile,
                "../assets/img/profile/" . $foto
            );

        }

    }

}

// ========================
// Enkripsi Password
// ========================

$password = password_hash($password, PASSWORD_DEFAULT);

// ========================
// Simpan Database
// ========================

$query = mysqli_query($conn, "INSERT INTO users(

nama,
username,
password,
role,
no_hp,
email,
alamat,
foto,
status

)

VALUES(

'$nama',
'$username',
'$password',
'$role',
'$no_hp',
'$email',
'$alamat',
'$foto',
'$status'

)");


// ========================
// Redirect
// ========================

if ($query) {

    echo "<script>

        alert('Employee berhasil ditambahkan.');

        window.location='index.php';

    </script>";

} else {

    echo "<script>

        alert('Employee gagal ditambahkan.');

        window.history.back();

    </script>";

}
?>