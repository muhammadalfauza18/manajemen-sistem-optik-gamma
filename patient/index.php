<?php
session_start();
include "../config/database.php";
$user_id = $_SESSION['id'];

$qUser = mysqli_query($conn, "
SELECT nama,foto
FROM users
WHERE id='$user_id'
");

$user = mysqli_fetch_assoc($qUser);

// Default
$foto = "../assets/img/default.png";

// Kalau ada foto
if (
    !empty($user['foto']) &&
    file_exists("../assets/img/profile/" . $user['foto'])
) {

    $foto = "../assets/img/profile/" . $user['foto'];

}

// Ambil data pasien
$query = mysqli_query($conn, "SELECT * FROM patients ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Management | Optik Gamma</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Dashboard CSS -->
    <link rel="stylesheet" href="../assets/css/dashboard.css">

    <!-- Patient CSS -->
    <link rel="stylesheet" href="../assets/css/patient.css">
</head>

<body>

    <div class="app-shell">

        <!-- =========================
            SIDEBAR
    ========================== -->

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

                <a href="index.php" class="nav-item active">
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

        <!-- =========================
                MAIN
    ========================== -->

        <main class="main">

            <!-- TOPBAR -->

            <header class="topbar">

                <div class="search-box">

                    <i class="fa-solid fa-search"></i>

                    <input type="text" id="searchPatient" placeholder="Search Patient...">

                </div>

                <div class="top-actions">

                    <div class="admin-chip">

                        <img src="<?= $foto; ?>" alt="Profile">

                        <span>

                            <?= $user['nama']; ?>

                        </span>

                    </div>

                </div>

            </header>

            <!-- PAGE TITLE -->

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h2 class="fw-bold">

                        Manajemen Pasien

                    </h2>

                    <p class="text-muted">

                        Manajemen Semua Pasien

                    </p>

                </div>

                <a href="add.php" class="btn btn-primary">

                    <i class="fa-solid fa-plus"></i>

                    Tambah Pasien

                </a>

            </div>

            <!-- TABLE -->

            <div class="card shadow-sm border-0">

                <div class="card-body p-0">

                    <table class="table align-middle mb-0">

                        <thead>

                            <tr>

                                <th>Patient ID</th>

                                <th>Nama</th>

                                <th>Jenis Kelamin</th>

                                <th>Tanggal Lahir</th>

                                <th>No. Telp</th>

                                <th>Email</th>

                                <th>Action</th>

                            </tr>

                        </thead>

                        <tbody>
                            <?php

                            if (mysqli_num_rows($query) > 0) {

                                while ($row = mysqli_fetch_assoc($query)) {

                                    ?>

                                    <tr>

                                        <td>

                                            <?= htmlspecialchars($row['kode_pasien']); ?>

                                        </td>

                                        <td>

                                            <div class="d-flex align-items-center">

                                                <div class="avatar-initial me-2">

                                                    <?= strtoupper(substr($row['nama'], 0, 1)); ?>

                                                </div>

                                                <?= htmlspecialchars($row['nama']); ?>

                                            </div>

                                        </td>

                                        <td>

                                            <?= htmlspecialchars($row['jenis_kelamin']); ?>

                                        </td>

                                        <td>

                                            <?= date('d M Y', strtotime($row['tanggal_lahir'])); ?>

                                        </td>

                                        <td>

                                            <?= htmlspecialchars($row['no_hp']); ?>

                                        </td>

                                        <td>

                                            <?= htmlspecialchars($row['email']); ?>

                                        </td>

                                        <td>

                                            <a href="../medical/index.php?patient=<?= $row['id']; ?>"
                                                class="btn btn-sm btn-light border" title="Lihat Medical Record">

                                                <i class="fa-solid fa-eye text-primary"></i>

                                            </a>

                                            <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-light border">

                                                <i class="fa-solid fa-pen text-warning"></i>

                                            </a>

                                            <a href="delete.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-light border"
                                                onclick="return confirm('Hapus pasien ini?')">

                                                <i class="fa-solid fa-trash text-danger"></i>

                                            </a>

                                        </td>
                                    </tr>

                                    <?php

                                }

                            } else {

                                ?>

                                <tr>

                                    <td colspan="7" class="text-center py-5">

                                        <img src="../assets/img/empty.png" width="120">

                                        <h5 class="mt-3">

                                            Belum ada data pasien

                                        </h5>

                                        <p class="text-muted">

                                            Klik tombol <b>Add Patient</b> untuk menambahkan pasien pertama.

                                        </p>

                                    </td>

                                </tr>

                                <?php

                            }

                            ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </main>

    </div>

    <script>

        const search = document.getElementById("searchPatient");

        search.addEventListener("keyup", function () {

            let value = this.value.toLowerCase();

            let rows = document.querySelectorAll("tbody tr");

            rows.forEach(function (row) {

                let text = row.innerText.toLowerCase();

                row.style.display = text.indexOf(value) > -1 ? "" : "none";

            });

        });

    </script>

</body>

</html>