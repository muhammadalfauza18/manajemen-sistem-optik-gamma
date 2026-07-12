<?php
session_start();
include "../config/database.php";

// Pastikan form dikirim dengan metode POST
if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("Location: index.php");
    exit;
}

// =========================
// Ambil Data Form
// =========================

$id = intval($_POST['id']);
$kode_produk = mysqli_real_escape_string($conn, $_POST['kode_produk']);
$nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
$kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
$merk = mysqli_real_escape_string($conn, $_POST['merk']);
$harga_beli = mysqli_real_escape_string($conn, $_POST['harga_beli']);
$harga_jual = mysqli_real_escape_string($conn, $_POST['harga_jual']);
$stok = mysqli_real_escape_string($conn, $_POST['stok']);
$satuan = mysqli_real_escape_string($conn, $_POST['satuan']);
$deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

// =========================
// Validasi
// =========================

if (
    empty($nama_produk) ||
    empty($kategori) ||
    empty($harga_beli) ||
    empty($harga_jual)
) {

    header("Location: edit.php?id=$id&error=empty");
    exit;

}

// =========================
// Cek Kode Produk
// (Kode boleh sama milik dirinya sendiri)
// =========================

$cek = mysqli_query($conn, "
SELECT id
FROM products
WHERE kode_produk='$kode_produk'
AND id != '$id'
");

if (mysqli_num_rows($cek) > 0) {

    header("Location: edit.php?id=$id&error=kode");
    exit;

}

// =========================
// Update Data
// =========================

$query = mysqli_query($conn, "
UPDATE products
SET
    kode_produk = '$kode_produk',
    nama_produk = '$nama_produk',
    kategori    = '$kategori',
    merk        = '$merk',
    harga_beli  = '$harga_beli',
    harga_jual  = '$harga_jual',
    stok        = '$stok',
    satuan      = '$satuan',
    deskripsi   = '$deskripsi'
WHERE id = '$id'
");

// =========================
// Redirect
// =========================

if ($query) {

    header("Location: index.php?success=update");
    exit;

} else {

    echo "<h3>Update Gagal</h3>";
    echo mysqli_error($conn);

}