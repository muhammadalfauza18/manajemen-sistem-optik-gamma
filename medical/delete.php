<?php
session_start();
include "../config/database.php";

// Pastikan user sudah login
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit;
}

// Pastikan parameter id ada
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

// Cek apakah data ada
$cek = mysqli_query($conn, "
SELECT *
FROM medical_records
WHERE id='$id'
");

if (mysqli_num_rows($cek) == 0) {

    header("Location:index.php");
    exit;

}

// Hapus data
$hapus = mysqli_query($conn, "
DELETE FROM medical_records
WHERE id='$id'
");

if ($hapus) {

    header("Location:index.php?success=delete");
    exit;

} else {

    echo "Gagal menghapus data : " . mysqli_error($conn);

}
?>