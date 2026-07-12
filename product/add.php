<?php
session_start();
include "../config/database.php";

// ===============================
// Cek Login
// ===============================
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit;
}

// ===============================
// Ambil Data User Login
// ===============================
$user_id = $_SESSION['id'];

$qUser = mysqli_query($conn, "
SELECT nama, foto
FROM users
WHERE id='$user_id'
");

$user = mysqli_fetch_assoc($qUser);

// Foto Default
$foto = "../assets/img/default.png";

if (
    $user &&
    !empty($user['foto']) &&
    file_exists("../assets/img/profile/" . $user['foto'])
) {
    $foto = "../assets/img/profile/" . $user['foto'];
}

// ===============================
// Generate Kode Produk
// ===============================

$qKode = mysqli_query($conn, "
SELECT MAX(id) as last_id
FROM products
");

$dataKode = mysqli_fetch_assoc($qKode);

$next = ($dataKode['last_id'] ?? 0) + 1;

$kodeProduk = "PRD" . str_pad($next, 3, "0", STR_PAD_LEFT);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>

        Add Product

    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <link rel="stylesheet" href="../assets/css/dashboard.css">

    <link rel="stylesheet" href="../assets/css/product.css">

</head>

<body>

    <div class="app-shell">
        <aside class="sidebar">

            <div class="brand">

                <div class="brand-logo">

                    <img src="../assets/img/logo.png">

                </div>

                <div class="brand-text">

                    <div class="brand-name">
                        Optik Gamma
                    </div>

                    <div class="brand-sub">
                        Optical Clinic
                    </div>

                </div>

            </div>

            <?php
            $isAdmin = (strtolower($_SESSION['role']) == 'admin');
            ?>

            <nav class="side-nav">

                <a href="../dashboard.php" class="nav-item">
                    <i class="fa-solid fa-table-cells-large"></i>
                    Dashboard
                </a>

                <a href="../patient/index.php" class="nav-item">
                    <i class="fa-solid fa-user"></i>
                    Patient Management
                </a>

                <a href="../medical/index.php" class="nav-item">
                    <i class="fa-solid fa-notes-medical"></i>
                    Medical Records
                </a>

                <?php if ($isAdmin) { ?>

                    <a href="index.php" class="nav-item active">
                        <i class="fa-solid fa-box"></i>
                        Product Management
                    </a>

                <?php } ?>

                <a href="../transaction/index.php" class="nav-item">
                    <i class="fa-solid fa-cash-register"></i>
                    POS Transaction
                </a>

                <?php if ($isAdmin) { ?>

                    <a href="../reports/index.php" class="nav-item">
                        <i class="fa-solid fa-chart-column"></i>
                        Reports
                    </a>

                    <a href="../employee/index.php" class="nav-item">
                        <i class="fa-solid fa-user-tie"></i>
                        Employee
                    </a>

                <?php } ?>
            </nav>

        </aside>
        <main class="main">

            <header class="topbar">

                <div class="search-box">

                    <i class="fa-solid fa-search"></i>

                    <input type="text" placeholder="Search...">

                </div>

                <div class="top-actions ms-auto">

                    <div class="admin-chip">

                        <img src="<?= $foto; ?>" alt="Profile">

                        <span>

                            <?= htmlspecialchars($user['nama']); ?>

                        </span>

                    </div>

                </div>

            </header>
            <nav aria-label="breadcrumb">

                <ol class="breadcrumb">

                    <li class="breadcrumb-item">

                        <a href="../dashboard.php">

                            Dashboard

                        </a>

                    </li>

                    <li class="breadcrumb-item">

                        <a href="index.php">

                            Product Management

                        </a>

                    </li>

                    <li class="breadcrumb-item active">

                        Add Product

                    </li>

                </ol>

            </nav>
            <?php

            if (isset($_GET['error'])) {

                if ($_GET['error'] == "empty") {

                    ?>

                    <div class="alert alert-warning">

                        Lengkapi semua data terlebih dahulu.

                    </div>

                    <?php

                }

            }

            ?>
            <div class="page-header">

                <div>

                    <h2>

                        Add Product

                    </h2>

                    <p>

                        Create a new product for your inventory.

                    </p>

                </div>

            </div>
            <form action="save.php" method="POST">

                <div class="card">

                    <div class="card-body">
                        <!-- =========================
     Product Information
========================= -->

                        <h5 class="section-title">

                            <i class="fa-solid fa-box-open me-2"></i>

                            Product Information

                        </h5>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Product Name <span class="text-danger">*</span>

                                </label>

                                <input type="text" name="nama_produk" class="form-control"
                                    placeholder="Enter product name" required>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Category <span class="text-danger">*</span>

                                </label>

                                <select name="kategori" class="form-select" required>

                                    <option value="">-- Select Category --</option>

                                    <option value="Frame">Frame</option>

                                    <option value="Lensa">Lensa</option>

                                    <option value="Aksesoris">Aksesoris</option>

                                </select>

                            </div>

                            <div class="col-md-6 mb-4">

                                <label class="form-label">

                                    Brand

                                </label>

                                <input type="text" name="merk" class="form-control" placeholder="Example : Rayban">

                            </div>

                        </div>

                        <hr>

                        <!-- =========================
     Inventory & Pricing
========================= -->

                        <h5 class="section-title">

                            <i class="fa-solid fa-warehouse me-2"></i>

                            Inventory & Pricing

                        </h5>

                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <label class="form-label">

                                    Product Code

                                </label>

                                <input type="text" name="kode_produk" class="form-control" value="<?= $kodeProduk; ?>"
                                    readonly>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">

                                    Stock

                                </label>

                                <input type="number" name="stok" class="form-control" value="0" min="0" required>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label">

                                    Unit

                                </label>

                                <select name="satuan" class="form-select" required>

                                    <option value="">-- Pilih Satuan --</option>

                                    <option value="pcs">pcs</option>

                                    <option value="botol">botol</option>


                                </select>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Purchase Price

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">

                                        Rp

                                    </span>

                                    <input type="number" name="harga_beli" class="form-control" required>

                                </div>

                            </div>

                            <div class="col-md-6 mb-4">

                                <label class="form-label">

                                    Selling Price

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">

                                        Rp

                                    </span>

                                    <input type="number" name="harga_jual" class="form-control" required>

                                </div>

                            </div>

                        </div>

                        <hr>

                        <!-- =========================
     Additional Information
========================= -->

                        <h5 class="section-title">

                            <i class="fa-solid fa-file-lines me-2"></i>

                            Additional Information

                        </h5>

                        <div class="mb-4">

                            <label class="form-label">

                                Description

                            </label>

                            <textarea name="deskripsi" class="form-control" rows="5"
                                placeholder="Product description..."></textarea>

                        </div>

                        <!-- =========================
     Action Buttons
========================= -->

                        <div class="d-flex justify-content-between mt-4">

                            <a href="index.php" class="btn btn-light">

                                <i class="fa-solid fa-arrow-left me-2"></i>

                                Cancel

                            </a>

                            <button type="submit" class="btn btn-primary px-4">

                                <i class="fa-solid fa-save me-2"></i>

                                Save Product

                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </main>

    </div>

</body>

</html>