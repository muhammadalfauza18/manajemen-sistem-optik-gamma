<?php
session_start();
include "../config/database.php";

// ==========================
// Cek Login
// ==========================
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit;
}

// ==========================
// Ambil Data User Login
// ==========================
$user_id = $_SESSION['id'];

$qUser = mysqli_query($conn, "
SELECT nama, foto
FROM users
WHERE id='$user_id'
");

$user = mysqli_fetch_assoc($qUser);

// Foto default
$foto = "../assets/img/default.png";

if (
    $user &&
    !empty($user['foto']) &&
    file_exists("../assets/img/profile/" . $user['foto'])
) {
    $foto = "../assets/img/profile/" . $user['foto'];
}

// ==========================
// Statistik
// ==========================

$totalFrame = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) total
FROM products
WHERE kategori='Frame'
"));

$totalLensa = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) total
FROM products
WHERE kategori='Lensa'
"));

$lowStock = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) total
FROM products
WHERE stok <= 10
"));

// ==========================
// Search & Filter
// ==========================

$search = "";
$kategori = "";
$where = "";

if (isset($_GET['search'])) {

    $search = mysqli_real_escape_string($conn, $_GET['search']);

    $where .= "
    AND (
        kode_produk LIKE '%$search%'
        OR nama_produk LIKE '%$search%'
        OR merk LIKE '%$search%'
    )
    ";

}

if (isset($_GET['kategori']) && $_GET['kategori'] != "") {

    $kategori = mysqli_real_escape_string($conn, $_GET['kategori']);

    $where .= " AND kategori='$kategori'";

}

$query = mysqli_query($conn, "
SELECT *
FROM products
WHERE 1
$where
ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>

        Product Management | Optik Gamma

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

        <!-- ======================
     Main
====================== -->

        <main class="main">

            <header class="topbar">

                <div class="top-actions ms-auto">

                    <div class="admin-chip">

                        <img src="<?= $foto; ?>" alt="Profile">

                        <span>

                            <?= htmlspecialchars($user['nama']); ?>

                        </span>

                    </div>

                </div>

            </header>
            <!-- ======================
     Breadcrumb
====================== -->

            <nav aria-label="breadcrumb">

                <ol class="breadcrumb">

                    <li class="breadcrumb-item">

                        <a href="../dashboard.php">

                            Dashboard

                        </a>

                    </li>

                    <li class="breadcrumb-item active">

                        Product Management

                    </li>

                </ol>

            </nav>

            <!-- ======================
     Header
====================== -->

            <div class="page-header">

                <div>

                    <h2>

                        Product Management

                    </h2>

                    <p>

                        Manage inventory across frames, lenses and accessories.

                    </p>

                </div>

                <a href="add.php" class="btn btn-primary">

                    <i class="fa-solid fa-plus me-2"></i>

                    Add Product

                </a>

            </div>
            <?php

            if (isset($_GET['success'])) {

                if ($_GET['success'] == "add") {

                    ?>

                    <div class="alert alert-success alert-dismissible fade show">

                        <i class="fa-solid fa-circle-check me-2"></i>

                        Product berhasil ditambahkan.

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                    </div>

                    <?php

                }

            }

            ?>
            <?php
            if (isset($_GET['success'])) {

                if ($_GET['success'] == "update") {
                    ?>

                    <div class="alert alert-success alert-dismissible fade show">

                        <i class="fa-solid fa-circle-check me-2"></i>

                        Product berhasil diperbarui.

                        <button type="button" class="btn-close" data-bs-dismiss="alert">
                        </button>

                    </div>

                    <?php
                }
            }
            ?>

            <!-- ======================
     Statistics
====================== -->

            <div class="row g-4 mb-4">

                <div class="col-lg-4">

                    <div class="stat-card">

                        <div class="icon blue">

                            <i class="fa-solid fa-glasses"></i>

                        </div>

                        <div>

                            <h6>Total Frame</h6>

                            <h2>
                                <?= $totalFrame['total']; ?>
                            </h2>

                        </div>

                    </div>

                </div>

                <div class="col-lg-4">

                    <div class="stat-card">

                        <div class="icon green">

                            <i class="fa-solid fa-eye"></i>

                        </div>

                        <div>

                            <h6>Total Lensa</h6>

                            <h2>
                                <?= $totalLensa['total']; ?>
                            </h2>

                        </div>

                    </div>

                </div>

                <div class="col-lg-4">

                    <div class="stat-card">

                        <div class="icon red">

                            <i class="fa-solid fa-triangle-exclamation"></i>

                        </div>

                        <div>

                            <h6>Low Stock</h6>

                            <h2>
                                <?= $lowStock['total']; ?>
                            </h2>

                        </div>

                    </div>

                </div>

            </div>

            <!-- ======================
     Product Table Card
====================== -->

            <div class="card">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <div>

                            <h5 class="mb-1">

                                Stock Management

                            </h5>

                            <small class="text-muted">

                                Monitor and manage your inventory.

                            </small>

                        </div>

                        <form method="GET" class="d-flex gap-2 flex-wrap">

                            <input type="text" name="search" class="form-control" placeholder="Search product..."
                                value="<?= htmlspecialchars($search); ?>">

                            <select name="kategori" class="form-select">

                                <option value="">

                                    All Categories

                                </option>

                                <option value="Frame" <?= ($kategori == "Frame") ? "selected" : ""; ?>>

                                    Frame

                                </option>

                                <option value="Lensa" <?= ($kategori == "Lensa") ? "selected" : ""; ?>>

                                    Lensa

                                </option>

                                <option value="Aksesoris" <?= ($kategori == "Aksesoris") ? "selected" : ""; ?>>

                                    Aksesoris

                                </option>

                            </select>

                            <button class="btn btn-primary">

                                <i class="fa-solid fa-search me-2"></i>

                                Search

                            </button>

                        </form>

                    </div>

                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table align-middle">

                            <thead>

                                <tr>

                                    <th>Product</th>

                                    <th>Code</th>

                                    <th>Category</th>

                                    <th>Brand</th>

                                    <th>Stock</th>

                                    <th>Price</th>

                                    <th width="120">

                                        Action

                                    </th>

                                </tr>

                            </thead>

                            <tbody>
                                <?php if (mysqli_num_rows($query) > 0) { ?>

                                    <?php while ($row = mysqli_fetch_assoc($query)) { ?>

                                        <tr>

                                            <!-- Product -->

                                            <td>

                                                <div class="d-flex flex-column">

                                                    <span class="fw-semibold">

                                                        <?= htmlspecialchars($row['nama_produk']); ?>

                                                    </span>

                                                    <small class="text-muted">

                                                        <?= htmlspecialchars($row['deskripsi']); ?>

                                                    </small>

                                                </div>

                                            </td>

                                            <!-- Code -->

                                            <td>

                                                <span class="badge bg-light text-dark border">

                                                    <?= htmlspecialchars($row['kode_produk']); ?>

                                                </span>

                                            </td>

                                            <!-- Category -->

                                            <td>

                                                <?php

                                                if ($row['kategori'] == "Frame") {

                                                    echo '<span class="badge bg-primary">Frame</span>';

                                                } elseif ($row['kategori'] == "Lensa") {

                                                    echo '<span class="badge bg-success">Lensa</span>';

                                                } else {

                                                    echo '<span class="badge bg-secondary">' . $row['kategori'] . '</span>';

                                                }

                                                ?>

                                            </td>

                                            <!-- Brand -->

                                            <td>

                                                <?= htmlspecialchars($row['merk']); ?>

                                            </td>

                                            <!-- Stock -->

                                            <td>

                                                <?php if ($row['stok'] <= 10) { ?>

                                                    <span class="badge bg-danger">

                                                        Low Stock (
                                                        <?= $row['stok']; ?>)

                                                    </span>

                                                <?php } else { ?>

                                                    <span class="badge bg-success">

                                                        In Stock (
                                                        <?= $row['stok']; ?>)

                                                    </span>

                                                <?php } ?>

                                            </td>

                                            <!-- Price -->

                                            <td class="fw-bold text-success">

                                                Rp
                                                <?= number_format($row['harga_jual'], 0, ",", "."); ?>

                                            </td>

                                            <!-- Action -->

                                            <td>

                                                <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-light" title="Edit">

                                                    <i class="fa-solid fa-pen text-warning"></i>

                                                </a>

                                                <a href="delete.php?id=<?= $row['id']; ?>" class="btn btn-light" title="Delete"
                                                    onclick="return confirm('Hapus produk ini?')">

                                                    <i class="fa-solid fa-trash text-danger"></i>

                                                </a>

                                            </td>

                                        </tr>

                                    <?php } ?>

                                <?php } else { ?>

                                    <tr>

                                        <td colspan="7">

                                            <div class="empty-state">

                                                <i class="fa-solid fa-box-open"></i>

                                                <h5>

                                                    No Product Available

                                                </h5>

                                                <p>

                                                    There are no products in the inventory.

                                                </p>

                                                <a href="add.php" class="btn btn-primary">

                                                    <i class="fa-solid fa-plus me-2"></i>

                                                    Add Product

                                                </a>

                                            </div>

                                        </td>

                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
</tbody>