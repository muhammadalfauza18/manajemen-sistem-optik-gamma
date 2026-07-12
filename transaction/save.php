<?php
session_start();
include "../config/database.php";


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['id'];

$kode_transaksi = $_POST['kode_transaksi'];
$patient_id = $_POST['patient_id'];
$metode = $_POST['metode_pembayaran'];
$total = $_POST['total'];

$cart = json_decode($_POST['cart'], true);

if (!$patient_id || empty($cart)) {
    die("Data transaksi tidak lengkap.");
}

mysqli_begin_transaction($conn);

try {

    $tanggal = date("Y-m-d");

    // ===============================
    // Simpan Transaksi
    // ===============================
    $query = mysqli_query($conn, "
        INSERT INTO transactions
        (
            kode_transaksi,
            patient_id,
            medical_record_id,
            user_id,
            tanggal,
            metode_pembayaran,
            total
        )
        VALUES
        (
            '$kode_transaksi',
            '$patient_id',
            NULL,
            '$user_id',
            '$tanggal',
            '$metode',
            '$total'
        )
    ");

    if (!$query) {
        throw new Exception(mysqli_error($conn));
    }

    $transaction_id = mysqli_insert_id($conn);

    // ===============================
    // Simpan Detail
    // ===============================
    foreach ($cart as $item) {

        $product_id = intval($item['id']);
        $qty = intval($item['qty']);
        $harga = intval($item['harga']);
        $subtotal = $qty * $harga;

        // ===============================
        // Cek stok
        // ===============================
        $cek = mysqli_query($conn, "
            SELECT stok
            FROM products
            WHERE id='$product_id'
        ");

        if (!$cek || mysqli_num_rows($cek) == 0) {
            throw new Exception("Produk tidak ditemukan.");
        }

        $produk = mysqli_fetch_assoc($cek);

        if ($produk['stok'] < $qty) {
            throw new Exception("Stok produk tidak mencukupi.");
        }

        // ===============================
        // Simpan Detail
        // ===============================
        $detail = mysqli_query($conn, "
            INSERT INTO transaction_details
            (
                transaction_id,
                product_id,
                qty,
                harga,
                subtotal
            )
            VALUES
            (
                '$transaction_id',
                '$product_id',
                '$qty',
                '$harga',
                '$subtotal'
            )
        ");

        if (!$detail) {
            throw new Exception(mysqli_error($conn));
        }

        // ===============================
        // Update Stok
        // ===============================
        $update = mysqli_query($conn, "
            UPDATE products
            SET stok = stok - $qty
            WHERE id='$product_id'
        ");

        if (!$update) {
            throw new Exception(mysqli_error($conn));
        }
    }

    mysqli_commit($conn);

    $_SESSION['success'] = "Transaction Successfully Saved.";

    header("Location: index.php");
    exit;

} catch (Exception $e) {

    mysqli_rollback($conn);

    $_SESSION['error'] = $e->getMessage();

    header("Location: index.php");
    exit;
}