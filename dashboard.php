<?php
session_start();
include "config/database.php";
// Cek login
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

// Ambil data user yang sedang login
$user_id = $_SESSION['id'];

$queryUser = mysqli_query($conn, "
SELECT nama, foto
FROM users
WHERE id='$user_id'
");

$user = mysqli_fetch_assoc($queryUser);

// Default foto
$foto = "assets/img/default.png";

// Jika user punya foto
if (
    $user &&
    !empty($user['foto']) &&
    file_exists("assets/img/profile/" . $user['foto'])
) {
    $foto = "assets/img/profile/" . $user['foto'];
}

// Total Patients
$qPatients = mysqli_query($conn, "SELECT COUNT(*) as total FROM patients");
$totalPatients = mysqli_fetch_assoc($qPatients);

// Total Products
$qProducts = mysqli_query($conn, "SELECT COUNT(*) as total FROM products");
$totalProducts = mysqli_fetch_assoc($qProducts);

// Total Transactions
$qTransactions = mysqli_query($conn, "SELECT COUNT(*) as total FROM transactions");
$totalTransactions = mysqli_fetch_assoc($qTransactions);

// Total Employees
$qEmployees = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$totalEmployees = mysqli_fetch_assoc($qEmployees);

// ========================
// Monthly Revenue Chart
// ========================

$qChart = mysqli_query($conn, "
SELECT
MONTH(tanggal) AS bulan,
SUM(total) AS total
FROM transactions
GROUP BY MONTH(tanggal)
ORDER BY MONTH(tanggal)
");

$bulan = [];
$pendapatan = [];

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

while ($row = mysqli_fetch_assoc($qChart)) {

    $bulan[] = $namaBulan[$row['bulan']];
    $pendapatan[] = $row['total'];

}

// aktifkan lagi kalau sudah siap
// if(!isset($_SESSION['login'])){
//     header("Location: login.php");
//     exit;
// }
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Optik Gamma</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dashboard.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>

    <div class="app-shell">

        <!-- =========================
         SIDEBAR
    ========================== -->
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-logo">
                    <img src="assets/img/logo.png" alt="Optik Gamma">
                </div>
                <div class="brand-text">
                    <div class="brand-name">Optik Gamma</div>
                    <div class="brand-sub">Clinical Precision</div>
                </div>
            </div>

            <?php
            $isAdmin = (strtolower($_SESSION['role']) == 'admin');
            ?>

            <nav class="side-nav">

                <a href="dashboard.php" class="nav-item active">
                    <i class="fa-solid fa-table-cells-large"></i>
                    Dashboard
                </a>

                <a href="patient/index.php" class="nav-item">
                    <i class="fa-solid fa-user"></i>
                    Patient Management
                </a>

                <a href="medical/index.php" class="nav-item">
                    <i class="fa-solid fa-notes-medical"></i>
                    Medical Records
                </a>

                <?php if ($isAdmin) { ?>

                    <a href="product/index.php" class="nav-item">
                        <i class="fa-solid fa-box"></i>
                        Product Management
                    </a>

                <?php } ?>

                <a href="transaction/index.php" class="nav-item">
                    <i class="fa-solid fa-cash-register"></i>
                    POS Transaction
                </a>

                <?php if ($isAdmin) { ?>

                    <a href="reports/index.php" class="nav-item">
                        <i class="fa-solid fa-chart-column"></i>
                        Reports
                    </a>

                    <a href="employee/index.php" class="nav-item">
                        <i class="fa-solid fa-user-tie"></i>
                        Employee
                    </a>

                <?php } ?>
            </nav>
        </aside>

        <!-- =========================
         MAIN
    ========================== -->
        <main class="main">

            <!-- Top bar -->
            <header class="topbar">

                <div class="top-actions">

                    <div class="admin-chip">

                        <img src="<?= $foto; ?>" alt="Profile" class="rounded-circle">

                        <span>

                            <?= htmlspecialchars($user['nama']); ?>

                        </span>

                    </div>
                    <a href="logout.php" class="btn btn-danger btn-sm ms-3"
                        onclick="return confirm('Yakin ingin logout?')">

                        <i class="fa-solid fa-right-from-bracket"></i>

                        Logout

                    </a>

                </div>
            </header>

            <!-- Overview -->
            <section class="page-head">
                <h1>Ringkasan</h1>
                <p>Ringkasan operasional klinis hari ini.</p>
            </section>

            <!-- Stat cards -->
            <section class="stat-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fa-solid fa-user-group"></i></div>
                    <div class="stat-body">
                        <div class="stat-label">Total Patients</div>
                        <div class="stat-value"><?= $totalPatients['total']; ?></div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon"><i class="fa-solid fa-box"></i></div>
                    <div class="stat-body">
                        <div class="stat-label">Total Products</div>
                        <div class="stat-value"><?= $totalProducts['total']; ?></div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon"><i class="fa-solid fa-receipt"></i></div>
                    <div class="stat-body">
                        <div class="stat-label">Total Transactions</div>
                        <div class="stat-value"><?= $totalTransactions['total']; ?> <span class="delta down"><i
                                    class="fa-solid fa-caret-down"></i></span></div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon"><i class="fa-solid fa-id-badge"></i></div>
                    <div class="stat-body">
                        <div class="stat-label">Total Employees</div>
                        <div class="stat-value"><?= $totalEmployees['total']; ?> <span class="delta flat"></span></div>
                    </div>
                </div>
            </section>

            <!-- Chart + Clinic Status -->
            <section class="mid-grid">
                <?php if ($isAdmin) { ?>

                    <div class="panel chart-panel">

                        <div class="panel-head">

                            <h3>Transaksi Bulanan</h3>

                            <button class="icon-btn small" aria-label="More">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>

                        </div>

                        <div class="chart-wrap">

                            <canvas id="monthlyChart"></canvas>

                        </div>

                    </div>



                    <div class="panel status-panel">
                        <div class="panel-head">
                            <h3>Clinic Status</h3>
                        </div>

                        <div class="status-item">
                            <div class="status-ico green"><i class="fa-solid fa-circle-check"></i></div>
                            <div class="status-text">
                                <div class="s-title">System Online</div>
                            </div>
                            <div class="status-meta">
                                <div class="s-label">Uptime:</div>
                                <div class="s-value">99.9%</div>
                            </div>
                        </div>

                        <div class="status-item">
                            <div class="status-ico blue"><i class="fa-solid fa-clock-rotate-left"></i></div>
                            <div class="status-text">
                                <div class="s-title">Database Status</div>

                                <div class="s-value muted">
                                    Connected
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </section>

            <!-- Recent Patients -->
            <section class="panel patients-panel">
                <div class="panel-head">
                    <h3>Recent Patients</h3>
                    <a href="#" class="link">View All</a>
                </div>

                <div class="table-wrap">
                    <table class="patients-table">
                        <thead>
                            <tr>
                                <th>Patient Name</th>
                                <th>ID</th>
                                <th>Last Visit</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php

                            $data = mysqli_query($conn, "SELECT
patients.*,
medical_records.id AS medical_id
FROM patients
LEFT JOIN medical_records
ON patients.id = medical_records.patient_id
ORDER BY patients.last_visit DESC
LIMIT 5;
                            ");

                            if (mysqli_num_rows($data) == 0) {

                                ?>

                                <tr>

                                    <td colspan="5" style="text-align:center;padding:50px">

                                        Belum ada data pasien

                                    </td>

                                </tr>

                                <?php

                            } else {

                                while ($row = mysqli_fetch_assoc($data)) {

                                    ?>

                                    <tr>

                                        <td><?= $row['nama']; ?></td>

                                        <td><?= $row['kode_pasien']; ?></td>

                                        <td><?= date('d M Y H:i', strtotime($row['last_visit'])); ?></td>


                                        <td>

                                            <a href="medical/index.php?patient=<?= $row['id']; ?>"
                                                class="btn btn-sm btn-primary" title="Lihat Medical Record">

                                                <i class="fa fa-eye"></i>

                                            </a>

                                        </td>

                                    </tr>

                                    <?php

                                }

                            }

                            ?>

                        </tbody>
                    </table>
                </div>
            </section>

        </main>
    </div>
    <script>

        const bulan = <?= json_encode($bulan); ?>;

        const pendapatan = <?= json_encode($pendapatan); ?>;

    </script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('monthlyChart');

        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 320);
        gradient.addColorStop(0, 'rgba(11, 78, 162, 0.28)');
        gradient.addColorStop(1, 'rgba(11, 78, 162, 0.00)');

        new Chart(ctx, {
            type: 'line',
            data: {

                labels: bulan,

                datasets: [{

                    data: pendapatan,

                    borderColor: '#0B4EA2',

                    borderWidth: 3,

                    backgroundColor: gradient,

                    fill: true,

                    tension: .35,

                    pointRadius: 4

                }]

            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0B4EA2',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 10,
                        displayColors: false,
                        callbacks: {
                            label: (c) => 'Rp ' + c.parsed.y.toLocaleString('id-ID')
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#9CA3AF',
                            font: { size: 11 },
                            callback: function (value) {

                                return 'Rp ' + value.toLocaleString('id-ID');

                            }
                        },
                        grid: { color: '#F1F3F7', drawBorder: false }
                    },
                    x: {
                        ticks: { color: '#9CA3AF', font: { size: 11 } },
                        grid: { display: false }
                    }
                }
            }
        });
    </script>

</body>

</html>