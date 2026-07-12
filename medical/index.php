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

// Ambil semua pasien
$patients = mysqli_query($conn, "
SELECT id,nama
FROM patients
ORDER BY nama ASC
");

// Patient yang dipilih
$patient_id = isset($_GET['patient'])
    ? (int) $_GET['patient']
    : 0;

// Data Patient
$patient = null;

// Data Medical
$medical = null;

// History
$history = [];

if ($patient_id) {

    $qPatient = mysqli_query($conn, "
        SELECT *
        FROM patients
        WHERE id='$patient_id'
    ");

    $patient = mysqli_fetch_assoc($qPatient);

    $qMedical = mysqli_query($conn, "
        SELECT

        medical_records.*,

        users.nama AS employee_name

        FROM medical_records

        JOIN users
        ON medical_records.user_id=users.id

        WHERE patient_id='$patient_id'

        ORDER BY tanggal DESC

        LIMIT 1
    ");

    $medical = mysqli_fetch_assoc($qMedical);

    $qHistory = mysqli_query($conn, "
        SELECT *

        FROM medical_records

        WHERE patient_id='$patient_id'

        ORDER BY tanggal DESC
    ");

    while ($row = mysqli_fetch_assoc($qHistory)) {

        $history[] = $row;

    }

}
?>
<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title>

        Medical Record

    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <link rel="stylesheet" href="../assets/css/dashboard.css">

    <link rel="stylesheet" href="../assets/css/medical.css">

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

                <a href="../patient/index.php" class="nav-item">
                    <i class="fa-solid fa-user"></i>
                    Patient Management
                </a>

                <a href="index.php" class="nav-item active">
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

                <div class="top-actions ms-auto">


                    <div class="top-actions ms-auto">

                        <div class="admin-chip">

                            <img src="<?= $foto; ?>" alt="Profile">

                            <span>

                                <?= $user['nama']; ?>

                            </span>

                        </div>

                    </div>

                </div>

            </header>
            <nav class="breadcrumb-wrap">

                <ol class="breadcrumb">

                    <li class="breadcrumb-item">

                        <a href="../dashboard.php">

                            Dashboard

                        </a>

                    </li>

                    <li class="breadcrumb-item active">

                        Medical Records

                    </li>

                </ol>

            </nav>
            <?php
            if (isset($_GET['success'])) {

                if ($_GET['success'] == "add") {
                    ?>

                    <div class="alert alert-success">

                        Medical Record berhasil ditambahkan.

                    </div>

                    <?php
                } elseif ($_GET['success'] == "update") {
                    ?>

                    <div class="alert alert-success">

                        Medical Record berhasil diperbarui.

                    </div>

                    <?php
                } elseif ($_GET['success'] == "delete") {
                    ?>

                    <div class="alert alert-danger">

                        Medical Record berhasil dihapus.
                        <button class="btn-close" data-bs-dismiss="alert">
                        </button>

                    </div>

                    <?php
                }

            }
            ?>

            <div class="page-header">

                <div>

                    <h2>

                        Medical Records

                    </h2>

                    <p>

                        Patient examination overview

                    </p>

                </div>

                <a href="add.php" class="btn btn-primary">

                    <i class="fa-solid fa-plus me-2"></i>

                    New Medical Record

                </a>


            </div>
            <div class="card mb-4">

                <div class="card-body">

                    <form method="GET">

                        <label class="form-label">

                            Select Patient

                        </label>

                        <div class="row">

                            <div class="col-md-6">

                                <select name="patient" class="form-select" onchange="this.form.submit()">

                                    <option value="">

                                        Choose Patient

                                    </option>

                                    <?php

                                    mysqli_data_seek($patients, 0);

                                    while ($p = mysqli_fetch_assoc($patients)) {

                                        ?>

                                        <option value="<?= $p['id']; ?>" <?= $patient_id == $p['id'] ? "selected" : ""; ?>>

                                            <?= $p['nama']; ?>

                                        </option>

                                    <?php } ?>

                                </select>

                            </div>

                        </div>

                    </form>

                </div>

            </div>
            <?php if ($patient) { ?>

                <div class="row">

                    <!-- ========================= -->
                    <!-- PATIENT INFORMATION -->
                    <!-- ========================= -->

                    <div class="col-lg-4">

                        <div class="card medical-card mb-4">

                            <div class="card-header">

                                <i class="fa-solid fa-user me-2"></i>

                                Patient Information

                            </div>

                            <div class="card-body">

                                <div class="d-flex justify-content-between align-items-start mb-3">

                                    <div>

                                        <h4 class="fw-bold mb-2">

                                            <?= $patient['nama']; ?>

                                        </h4>

                                        <span class="badge bg-success">

                                            Active Patient

                                        </span>

                                    </div>

                                    <?php if ($medical) { ?>

                                        <div>

                                            <a href="edit.php?id=<?= $medical['id']; ?>" class="btn btn-warning btn-sm me-1"
                                                title="Edit">

                                                <i class="fa-solid fa-pen"></i>

                                            </a>

                                            <a href="delete.php?id=<?= $medical['id']; ?>" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin ingin menghapus medical record ini?')"
                                                title="Delete">

                                                <i class="fa-solid fa-trash"></i>

                                            </a>

                                        </div>

                                    <?php } ?>

                                </div>
                                <div class="info-item">

                                    <span>Date of Birth</span>

                                    <strong>

                                        <?= date("d F Y", strtotime($patient['tanggal_lahir'])); ?>

                                    </strong>

                                </div>

                                <div class="info-item">

                                    <span>Gender</span>

                                    <strong>

                                        <?= $patient['jenis_kelamin']; ?>

                                    </strong>

                                </div>

                                <div class="info-item">

                                    <span>Phone</span>

                                    <strong>

                                        <?= $patient['no_hp']; ?>

                                    </strong>

                                </div>

                                <div class="info-item">

                                    <span>Email</span>

                                    <strong>

                                        <?= $patient['email']; ?>

                                    </strong>

                                </div>

                                <div class="info-item">

                                    <span>Address</span>

                                    <strong>

                                        <?= $patient['alamat']; ?>

                                    </strong>

                                </div>

                            </div>

                        </div>

                    </div>
                    <!-- ========================= -->
                    <!-- LATEST REFRACTION -->
                    <!-- ========================= -->

                    <div class="col-lg-8">

                        <div class="card medical-card mb-4">

                            <div class="card-header d-flex justify-content-between">

                                <span>

                                    <i class="fa-solid fa-eye me-2"></i>

                                    Latest Refraction Data

                                </span>

                                <?php if ($medical) { ?>

                                    <small>

                                        <?= date("d M Y", strtotime($medical['tanggal'])); ?>

                                    </small>

                                <?php } ?>

                            </div>

                            <div class="card-body">

                                <?php if ($medical) { ?>

                                    <table class="table table-bordered text-center">

                                        <thead>

                                            <tr>

                                                <th>Eye</th>

                                                <th>SPH</th>

                                                <th>CYL</th>

                                                <th>AXIS</th>

                                                <th>ADD</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            <tr>

                                                <td><strong>OD</strong></td>

                                                <td><?= $medical['sph_od']; ?></td>

                                                <td><?= $medical['cyl_od']; ?></td>

                                                <td><?= $medical['axis_od']; ?></td>

                                                <td><?= $medical['add_od']; ?></td>

                                            </tr>

                                            <tr>

                                                <td><strong>OS</strong></td>

                                                <td><?= $medical['sph_os']; ?></td>

                                                <td><?= $medical['cyl_os']; ?></td>

                                                <td><?= $medical['axis_os']; ?></td>

                                                <td><?= $medical['add_os']; ?></td>

                                            </tr>

                                        </tbody>

                                    </table>
                                    <div class="row mt-3">

                                        <div class="col-md-4">

                                            <div class="summary-box">

                                                <span>Lens Type</span>

                                                <h5>

                                                    <?= $medical['jenis_lensa']; ?>

                                                </h5>

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="summary-box">

                                                <span>Examined By</span>

                                                <h5>

                                                    <?= $medical['employee_name']; ?>

                                                </h5>

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="summary-box">

                                                <span>Visit Date</span>

                                                <h5>

                                                    <?= date("d M Y", strtotime($medical['tanggal'])); ?>

                                                </h5>

                                            </div>

                                        </div>

                                    </div>

                                <?php } else { ?>

                                    <div class="alert alert-warning mb-0">

                                        Belum ada pemeriksaan untuk pasien ini.

                                    </div>

                                <?php } ?>

                            </div>

                        </div>

                    </div>

                </div>
                <div class="row mt-4">

                    <!-- ========================= -->
                    <!-- VISIT HISTORY -->
                    <!-- ========================= -->

                    <div class="col-lg-4">

                        <div class="card medical-card h-100">

                            <div class="card-header">

                                <i class="fa-solid fa-clock-rotate-left me-2"></i>

                                Visit History

                            </div>

                            <div class="card-body">

                                <?php

                                if (count($history) > 0) {

                                    foreach ($history as $visit) {

                                        ?>

                                        <div class="history-item">

                                            <div class="history-date">

                                                <?= date("d M Y", strtotime($visit['tanggal'])); ?>

                                            </div>

                                            <div class="history-title">

                                                Eye Examination

                                            </div>

                                            <div class="history-desc">

                                                <?= $visit['diagnosis']; ?>

                                            </div>

                                            <hr>

                                        </div>

                                        <?php

                                    }

                                } else {

                                    ?>

                                    <div class="text-center py-4">

                                        <i class="fa-solid fa-folder-open fa-2x text-secondary mb-3"></i>

                                        <p class="text-muted">

                                            Belum ada riwayat pemeriksaan.

                                        </p>

                                    </div>

                                <?php } ?>

                            </div>

                        </div>

                    </div>
                    <!-- ========================= -->
                    <!-- DIAGNOSIS -->
                    <!-- ========================= -->

                    <div class="col-lg-4">

                        <div class="card medical-card h-100">

                            <div class="card-header">

                                <i class="fa-solid fa-notes-medical me-2"></i>

                                Clinical Diagnosis

                            </div>

                            <div class="card-body">

                                <?php if ($medical) { ?>

                                    <h6>Complaint</h6>

                                    <p>

                                        <?= nl2br($medical['keluhan']); ?>

                                    </p>

                                    <hr>

                                    <h6>Diagnosis</h6>

                                    <p>

                                        <?= nl2br($medical['diagnosis']); ?>

                                    </p>

                                    <hr>

                                    <h6>Notes</h6>

                                    <p>

                                        <?= nl2br($medical['catatan']); ?>

                                    </p>

                                <?php } else { ?>

                                    <p class="text-muted">

                                        Belum ada diagnosis.

                                    </p>

                                <?php } ?>

                            </div>

                        </div>

                    </div>
                    <!-- ========================= -->
                    <!-- PRESCRIPTION -->
                    <!-- ========================= -->

                    <div class="col-lg-4">

                        <div class="card medical-card h-100">

                            <div class="card-header">

                                <i class="fa-solid fa-glasses me-2"></i>

                                Active Prescription

                            </div>

                            <div class="card-body">

                                <?php if ($medical) { ?>

                                    <table class="table table-borderless">

                                        <tr>

                                            <td>

                                                Lens Type

                                            </td>

                                            <td>

                                                <strong>

                                                    <?= $medical['jenis_lensa']; ?>

                                                </strong>

                                            </td>

                                        </tr>

                                        <tr>

                                            <td>

                                                Examined By

                                            </td>

                                            <td>

                                                <?= $medical['employee_name']; ?>

                                            </td>

                                        </tr>

                                        <tr>

                                            <td>

                                                Date

                                            </td>

                                            <td>

                                                <?= date("d M Y", strtotime($medical['tanggal'])); ?>

                                            </td>

                                        </tr>

                                    </table>

                                <?php } else { ?>

                                    <p class="text-muted">

                                        Belum ada data resep.

                                    </p>

                                <?php } ?>

                            </div>

                        </div>

                    </div>

                </div>




            <?php } ?>

        </main>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>