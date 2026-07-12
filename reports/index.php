<?php
session_start();
include "../config/database.php";

$start = $_GET['start'] ?? '';
$end = $_GET['end'] ?? '';

$where = "";

if (!empty($start) && !empty($end)) {

    $where = "WHERE tanggal BETWEEN '$start' AND '$end'";

}

if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit;
}

$user_id = intval($_SESSION['id']);

$qUser = mysqli_query($conn, "
SELECT *
FROM users
WHERE id='$user_id'
");

$user = mysqli_fetch_assoc($qUser);

$foto = "../assets/img/default.png";

if (
    !empty($user['foto']) &&
    file_exists("../assets/img/profile/" . $user['foto'])
) {
    $foto = "../assets/img/profile/" . $user['foto'];
}
// ======================================================
// SUMMARY REPORT
// ======================================================

// Total Revenue
$qRevenue = mysqli_query($conn, "
SELECT
IFNULL(SUM(total),0) revenue
FROM transactions
$where
");
$revenue = mysqli_fetch_assoc($qRevenue);


// Total Transaction
$qTransaction = mysqli_query($conn, "
SELECT
COUNT(*) total_transaction
FROM transactions
$where
");

$totalTransaction = mysqli_fetch_assoc($qTransaction);


// Total Product Sold
$qProductSold = mysqli_query($conn, "
SELECT
    IFNULL(SUM(td.qty),0) AS total_product
FROM transaction_details td
JOIN transactions t
    ON td.transaction_id = t.id
$where
");

$totalProduct = mysqli_fetch_assoc($qProductSold);
// ======================================================
// TOP SELLING PRODUCT
// ======================================================

$qTop = mysqli_query($conn, "
SELECT

p.nama_produk,

p.kategori,

SUM(td.qty) qty,

SUM(td.subtotal) revenue

FROM transaction_details td

JOIN products p
ON td.product_id=p.id

JOIN transactions t
ON td.transaction_id=t.id

$where

GROUP BY td.product_id

ORDER BY qty DESC

LIMIT 5
");
// ======================================================
// MONTHLY REVENUE
// ======================================================

$qChart = mysqli_query($conn, "
SELECT

MONTH(tanggal) bulan,

SUM(total) total

FROM transactions

$where

GROUP BY MONTH(tanggal)

ORDER BY MONTH(tanggal)
");

$bulan = [];
$pendapatan = [];

while ($row = mysqli_fetch_assoc($qChart)) {

    $namaBulan = [
        1 => "Jan",
        2 => "Feb",
        3 => "Mar",
        4 => "Apr",
        5 => "Mei",
        6 => "Jun",
        7 => "Jul",
        8 => "Ags",
        9 => "Sep",
        10 => "Okt",
        11 => "Nov",
        12 => "Des"
    ];

    $bulan[] = $namaBulan[$row['bulan']];
    $pendapatan[] = $row['total'];

}
// ======================================================
// SALES BY CATEGORY
// ======================================================

$qCategory = mysqli_query($conn, "
SELECT

p.kategori,

SUM(td.qty) qty

FROM transaction_details td

JOIN products p
ON td.product_id=p.id

JOIN transactions t
ON td.transaction_id=t.id

$where

GROUP BY p.kategori
");
$kategori = [];
$totalKategori = [];

while ($row = mysqli_fetch_assoc($qCategory)) {

    $kategori[] = $row['kategori'];

    $totalKategori[] = $row['qty'];

}
?>
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Reports</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <link rel="stylesheet" href="../assets/css/dashboard.css">

    <link rel="stylesheet" href="report.css">

</head>

<body>

    <div class="app-shell">

        <aside class="sidebar">

            <div class="brand">

                <img src="../assets/img/logo.png" alt="Logo">

                <div>

                    <div class="brand-name">Optik Gamma</div>

                    <div class="brand-sub">Optical Clinic</div>

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

                    <a href="../product/index.php" class="nav-item">
                        <i class="fa-solid fa-box"></i>
                        Product Management
                    </a>

                <?php } ?>

                <a href="../transaction/index.php" class="nav-item">
                    <i class="fa-solid fa-cash-register"></i>
                    POS Transaction
                </a>

                <?php if ($isAdmin) { ?>

                    <a href="index.php" class="nav-item active">
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

                <div class="top-actions ms-auto">

                    <div class="admin-chip">

                        <img src="<?= $foto ?>">

                        <span>

                            <?= htmlspecialchars($user['nama']) ?>

                        </span>

                    </div>

                </div>

            </header>
            <div class="page-header">

                <div>

                    <h2>

                        Reports & Analytics

                    </h2>

                    <p>

                        Comprehensive overview of clinic performance.

                    </p>

                </div>

                <div>

                    <a href="export_pdf.php?start=<?= urlencode($start) ?>&end=<?= urlencode($end) ?>"
                        class="btn btn-danger">

                        <i class="fa-solid fa-file-pdf"></i>
                        Export PDF

                    </a>

                </div>

            </div>
            <form method="GET" class="row g-3 mb-4">

                <div class="col-md-4">

                    <label>Tanggal Awal</label>

                    <input type="date" class="form-control" name="start" value="<?= htmlspecialchars($start) ?>">

                </div>

                <div class="col-md-4">

                    <label>Tanggal Akhir</label>

                    <input type="date" class="form-control" name="end" value="<?= htmlspecialchars($end) ?>">

                </div>

                <div class="col-md-4 d-flex align-items-end">

                    <button class="btn btn-primary">

                        <i class="fa fa-filter"></i>

                        Filter

                    </button>

                    <a href="index.php" class="btn btn-secondary ms-2">

                        Reset

                    </a>

                </div>

            </form>
            <div class="summary-grid">

                <div class="summary-card">

                    <div class="icon blue">

                        <i class="fa-solid fa-money-bill-wave"></i>

                    </div>

                    <h6>

                        Revenue

                    </h6>

                    <h3>

                        Rp
                        <?= number_format($revenue['revenue'], 0, ',', '.') ?>

                    </h3>

                </div>


                <div class="summary-card">

                    <div class="icon green">

                        <i class="fa-solid fa-receipt"></i>

                    </div>

                    <h6>

                        Transactions

                    </h6>

                    <h3>

                        <?= $totalTransaction['total_transaction'] ?>

                    </h3>

                </div>


                <div class="summary-card">

                    <div class="icon orange">

                        <i class="fa-solid fa-box"></i>

                    </div>

                    <h6>

                        Products Sold

                    </h6>

                    <h3>

                        <?= $totalProduct['total_product'] ?>

                    </h3>

                </div>

            </div>
            <div class="report-grid">

                <div class="left-report">

                    <div class="card shadow-sm">

                        <div class="card-header">

                            Monthly Revenue

                        </div>

                        <div class="card-body">

                            <div class="chart-box">

                                <canvas id="revenueChart"></canvas>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="right-report">

                    <div class="card shadow-sm">

                        <div class="card-header">

                            Sales By Category

                        </div>

                        <div class="card-body">

                            <div class="chart-box">

                                <canvas id="categoryChart"></canvas>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
            <div class="card shadow-sm mt-4">

                <div class="card-header">

                    Top Selling Products

                </div>

                <div class="card-body">

                    <table class="table">

                        <thead>

                            <tr>

                                <th>Product</th>

                                <th>Category</th>

                                <th>Sold</th>

                                <th>Revenue</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php

                            if (mysqli_num_rows($qTop) > 0) {

                                while ($row = mysqli_fetch_assoc($qTop)) {

                                    ?>

                                    <tr>

                                        <td>

                                            <?= htmlspecialchars($row['nama_produk']) ?>

                                        </td>

                                        <td>

                                            <?= $row['kategori'] ?>

                                        </td>

                                        <td>

                                            <?= $row['qty'] ?>

                                        </td>

                                        <td>

                                            Rp
                                            <?= number_format($row['revenue'], 0, ',', '.') ?>

                                        </td>

                                    </tr>

                                    <?php

                                }

                            } else {

                                ?>

                                <tr>

                                    <td colspan="4" class="text-center">

                                        No Data

                                    </td>

                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>
        </main>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>

        const bulan =
            <?= json_encode($bulan); ?>;

        const pendapatan =
            <?= json_encode($pendapatan); ?>;

        const kategori =
            <?= json_encode($kategori); ?>;

        const qtyKategori =
            <?= json_encode($totalKategori); ?>;

    </script>
    <script src="report.js"></script>

</body>

</html>