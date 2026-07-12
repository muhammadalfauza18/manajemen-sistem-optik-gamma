<?php
session_start();
include "../config/database.php";

// Cek Login
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit;
}

// Cek ID
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

// Pastikan produk ada
$cek = mysqli_query($conn, "
SELECT *
FROM products
WHERE id='$id'
");

if (mysqli_num_rows($cek) == 0) {

    header("Location:index.php?error=notfound");
    exit;

}

// Hapus data
$delete = mysqli_query($conn, "
DELETE FROM products
WHERE id='$id'
");

if ($delete) {

    header("Location:index.php?success=delete");
    exit;

} else {

    echo "<h3>Gagal Menghapus Data</h3>";
    echo mysqli_error($conn);

}