<?php
session_start();
include "../config/database.php";

$id = $_POST['id'];
$nama = trim($_POST['nama']);
$username = trim($_POST['username']);
$role = $_POST['role'];
$no_hp = trim($_POST['no_hp']);
$email = trim($_POST['email']);
$alamat = trim($_POST['alamat']);
$status = $_POST['status'];

// Ambil data lama
$get = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
$data = mysqli_fetch_assoc($get);

// Cek username baru
if ($username != $data['username']) {
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Username sudah digunakan!'); window.history.back();</script>";
        exit;
    }
}

// Cek password
if (!empty($_POST['password'])) {
    $newPass = password_hash($_POST['password'], PASSWORD_DEFAULT);
} else {
    $newPass = $data['password']; // pakai password lama
}

// Cek foto
$foto = $data['foto'];
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (in_array($ext, $allowed)) {
        if ($data['foto'] != "default.png")
            unlink("../assets/img/profile/" . $data['foto']);
        $foto = time() . "." . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], "../assets/img/profile/" . $foto);
    }
}

// Update
mysqli_query($conn, "UPDATE users SET
nama='$nama',
username='$username',
password='$newPass',
role='$role',
no_hp='$no_hp',
email='$email',
alamat='$alamat',
foto='$foto',
status='$status'
WHERE id='$id'");

echo "<script>alert('Data updated!'); window.location='index.php';</script>";
if ($query) {

    echo "<script>

        alert('Employee berhasil diperbarui.');

        window.location='index.php';

    </script>";

} else {

    echo "<script>

        alert('Gagal memperbarui employee.');

        window.history.back();

    </script>";

}
?>