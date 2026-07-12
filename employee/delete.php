<?php
session_start();
include "../config/database.php";

// Cek apakah ID dikirim
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

// Ambil data employee
$query = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");

if (mysqli_num_rows($query) == 0) {
    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);

// Hapus foto jika bukan default
if ($data['foto'] != "default.png") {

    $file = "../assets/img/profile/" . $data['foto'];

    if (file_exists($file)) {
        unlink($file);
    }
}

// Hapus data dari database
$delete = mysqli_query($conn, "DELETE FROM users WHERE id='$id'");

// Redirect
if ($delete) {

    header("Location: index.php?success=delete");
    exit;

} else {

    echo "Gagal menghapus data : " . mysqli_error($conn);

}