<?php
session_start();
include "../config/database.php";

$id = $_GET['id'];

$data = mysqli_query($conn, "SELECT * FROM patients WHERE id='$id'");

$row = mysqli_fetch_assoc($data);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Pasien</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/dashboard.css">

    <link rel="stylesheet" href="../assets/css/patient.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

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

        <main class="main">

            <header class="topbar">

                <div>

                    <h3 class="fw-bold">

                        Edit Pasien

                    </h3>

                    <p class="text-muted">

                        Update Informasi Pasien

                    </p>

                </div>

            </header>

            <div class="card p-4 shadow-sm">

                <form action="update.php" method="POST">

                    <input type="hidden" name="id" value="<?= $row['id']; ?>">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label>Nama</label>

                            <input type="text" class="form-control" name="nama" value="<?= $row['nama']; ?>" required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Gender</label>

                            <select class="form-select" name="jenis_kelamin">

                                <option <?= $row['jenis_kelamin'] == "Laki-laki" ? "selected" : ""; ?>>

                                    Laki-laki

                                </option>

                                <option <?= $row['jenis_kelamin'] == "Perempuan" ? "selected" : ""; ?>>

                                    Perempuan

                                </option>

                            </select>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Tanggal Lahir</label>

                            <input type="date" class="form-control" name="tanggal_lahir"
                                value="<?= $row['tanggal_lahir']; ?>">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>No. Telepon</label>

                            <input type="text" class="form-control" name="no_hp" value="<?= $row['no_hp']; ?>">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Email</label>

                            <input type="email" class="form-control" name="email" value="<?= $row['email']; ?>">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>Alamat</label>

                            <textarea class="form-control" rows="3" name="alamat"><?= $row['alamat']; ?></textarea>

                        </div>

                    </div>

                    <div class="text-end">

                        <a href="index.php" class="btn btn-secondary">

                            Cancel

                        </a>

                        <button class="btn btn-primary">

                            Update Pasien

                        </button>

                    </div>

                </form>

            </div>

        </main>

    </div>

</body>

</html>