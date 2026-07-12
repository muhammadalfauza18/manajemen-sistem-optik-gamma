<?php
session_start();
include "../config/database.php";
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Patient</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/dashboard.css">

    <link rel="stylesheet" href="../assets/css/patient.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body>

    <div class="app-shell">

        <!-- SIDEBAR -->

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

        <!-- MAIN -->

        <main class="main">

            <header class="topbar">

                <div>

                    <h3 class="fw-bold">

                        Tambah Pasien Baru

                    </h3>

                    <nav aria-label="breadcrumb">

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item">

                                <a href="../dashboard.php">

                                    Dashboard

                                </a>

                            </li>

                            <li class="breadcrumb-item">

                                <a href="index.php">

                                    Manajemen Pasien

                                </a>

                            </li>

                            <li class="breadcrumb-item active">

                                Tambah Pasien

                            </li>

                        </ol>

                    </nav>

                </div>

            </header>


            <div class="card p-4 shadow-sm">

                <form action="save.php" method="POST">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label>

                                Nama

                            </label>

                            <input type="text" class="form-control" name="nama" required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>

                                Gender

                            </label>

                            <select class="form-select" name="jenis_kelamin" required>

                                <option value="">

                                    Pilih Gender

                                </option>

                                <option>

                                    Laki-laki

                                </option>

                                <option>

                                    Perempuan

                                </option>

                            </select>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>

                                Tanggal Lahir

                            </label>

                            <input type="date" class="form-control" name="tanggal_lahir" required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>

                                Nomor Telepon

                            </label>

                            <input type="text" class="form-control" name="no_hp" required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>

                                Alamat Email

                            </label>

                            <input type="email" class="form-control" name="email">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label>

                                Alamat

                            </label>

                            <textarea class="form-control" rows="3" name="alamat" required></textarea>

                        </div>

                    </div>

                    <div class="d-flex justify-content-end mt-4">

                        <a href="index.php" class="btn btn-light me-2">

                            Cancel

                        </a>

                        <button class="btn btn-primary">

                            <i class="fa-solid fa-floppy-disk"></i>

                            Save Pasien

                        </button>

                    </div>

                </form>

            </div>

        </main>

    </div>

</body>

</html>