<?php
session_start();
include "../config/database.php";

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("Location: add.php");
    exit;
}

// =========================
// Ambil Data Form
// =========================

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
    empty($kode_produk) ||
    empty($nama_produk) ||
    empty($kategori) ||
    empty($harga_beli) ||
    empty($harga_jual)
) {

    header("Location: add.php?error=empty");
    exit;

}

// =========================
// Cek Kode Produk
// =========================

$cek = mysqli_query($conn, "
SELECT id
FROM products
WHERE kode_produk='$kode_produk'
");

if (mysqli_num_rows($cek) > 0) {

    header("Location:add.php?error=kode");
    exit;

}

// =========================
// Simpan Data
// =========================

$query = mysqli_query($conn, "

INSERT INTO products
(
    kode_produk,
    nama_produk,
    kategori,
    merk,
    harga_beli,
    harga_jual,
    stok,
    satuan,
    deskripsi
)

VALUES
(
    '$kode_produk',
    '$nama_produk',
    '$kategori',
    '$merk',
    '$harga_beli',
    '$harga_jual',
    '$stok',
    '$satuan',
    '$deskripsi'
)

");

if ($query) {

    header("Location:index.php?success=add");
    exit;

} else {

    echo "

    <h2>Database Error</h2>

    " . mysqli_error($conn);

}