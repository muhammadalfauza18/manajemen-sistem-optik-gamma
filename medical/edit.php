<?php
session_start();
include "../config/database.php";

if (!isset($_GET['id'])) {
    header("Location:index.php");
    exit;
}

$id = (int) $_GET['id'];

$query = mysqli_query($conn, "
SELECT *
FROM medical_records
WHERE id='$id'
");

if (mysqli_num_rows($query) == 0) {

    header("Location:index.php");
    exit;

}

$data = mysqli_fetch_assoc($query);

// Ambil daftar pasien
$patients = mysqli_query($conn, "
SELECT id,nama
FROM patients
ORDER BY nama ASC
");
?>
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>

        New Medical Record

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

                <a href="../product/index.php" class="nav-item">

                    <i class="fa-solid fa-box"></i>

                    Product Management

                </a>

                <a href="../transaction/index.php" class="nav-item">

                    <i class="fa-solid fa-cash-register"></i>

                    POS Transaction

                </a>

                <a href="../reports/index.php" class="nav-item">

                    <i class="fa-solid fa-chart-column"></i>

                    Reports

                </a>

                <a href="../empolyeeindex.php" class="nav-item">

                    <i class="fa-solid fa-user-tie"></i>

                    Employee

                </a>

            </nav>

        </aside>
        <main class="main">

            <header class="topbar">

                <div class="top-actions ms-auto">

                    <div class="admin-chip">

                        <img src="../assets/img/default.png">

                        <span>

                            Administrator

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

                            Medical Records

                        </a>

                    </li>

                    <li class="breadcrumb-item active">

                        New Medical Record

                    </li>

                </ol>

            </nav>
            <div class="page-header">

                <div>

                    <h2>

                        New Medical Record

                    </h2>

                    <p>

                        Create a patient examination record.

                    </p>

                </div>

            </div>
            <form action="update.php" method="POST">

                <input type="hidden" name="id" value="<?= $data['id']; ?>">

                <div class="card medical-card">

                    <div class="card-body">
                        <h5 class="section-title">

                            <i class="fa-solid fa-user me-2"></i>

                            Patient Information

                        </h5>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Patient *

                                </label>

                                <select name="patient_id" class="form-select" required>

                                    <option value="">

                                        -- Select Patient --

                                    </option>

                                    <?php

                                    while ($p = mysqli_fetch_assoc($patients)) {

                                        ?>

                                        <option value="<?= $p['id']; ?>" <?= ($data['patient_id'] == $p['id']) ? 'selected' : ''; ?>>

                                            <?= $p['nama']; ?>

                                        </option>

                                    <?php } ?>

                                </select>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Visit Date

                                </label>

                                <input type="date" name="tanggal" class="form-control" value="<?= $data['tanggal']; ?>"
                                    required>

                            </div>

                        </div>
                        <hr>

                        <h5 class="section-title">

                            <i class="fa-solid fa-notes-medical me-2"></i>

                            Clinical Information

                        </h5>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Complaint

                                </label>

                                <textarea name="keluhan" class="form-control"><?= $data['keluhan']; ?></textarea>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Diagnosis

                                </label>

                                <textarea name="diagnosis" class="form-control"><?= $data['diagnosis']; ?></textarea>

                            </div>

                        </div>
                        <hr>

                        <h5 class="section-title">

                            <i class="fa-solid fa-eye me-2"></i>

                            Refraction Data

                        </h5>

                        <div class="row">

                            <!-- ===================== -->
                            <!-- RIGHT EYE -->
                            <!-- ===================== -->

                            <div class="col-md-6">

                                <div class="card border-0 bg-light mb-4">

                                    <div class="card-header bg-transparent fw-semibold">

                                        RIGHT EYE (OD)

                                    </div>

                                    <div class="card-body">

                                        <div class="row">

                                            <div class="col-3">

                                                <label class="form-label">

                                                    SPH

                                                </label>

                                                <input type="text" name="sph_od" class="form-control"
                                                    value="<?= $data['sph_od']; ?>">

                                            </div>

                                            <div class="col-3">

                                                <label class="form-label">

                                                    CYL

                                                </label>

                                                <input type="text" name="cyl_od" class="form-control"
                                                    value="<?= $data['cyl_od']; ?>">

                                            </div>

                                            <div class="col-3">

                                                <label class="form-label">

                                                    AXIS

                                                </label>

                                                <input type="text" name="axis_od" class="form-control"
                                                    value="<?= $data['axis_od']; ?>">

                                            </div>

                                            <div class="col-3">

                                                <label class="form-label">

                                                    ADD

                                                </label>

                                                <input type="text" name="add_od" class="form-control"
                                                    value="<?= $data['add_od']; ?>">

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- ===================== -->
                            <!-- LEFT EYE -->
                            <!-- ===================== -->

                            <div class="col-md-6">

                                <div class="card border-0 bg-light mb-4">

                                    <div class="card-header bg-transparent fw-semibold">

                                        LEFT EYE (OS)

                                    </div>

                                    <div class="card-body">

                                        <div class="row">

                                            <div class="col-3">

                                                <label class="form-label">

                                                    SPH

                                                </label>

                                                <input type="text" name="sph_os" class="form-control"
                                                    value="<?= $data['sph_os']; ?>">
                                            </div>

                                            <div class="col-3">

                                                <label class="form-label">

                                                    CYL

                                                </label>

                                                <input type="text" name="cyl_os" class="form-control"
                                                    value="<?= $data['cyl_os']; ?>">

                                            </div>

                                            <div class="col-3">

                                                <label class="form-label">

                                                    AXIS

                                                </label>

                                                <input type="text" name="axis_os" class="form-control"
                                                    value="<?= $data['axis_os']; ?>">

                                            </div>

                                            <div class="col-3">

                                                <label class="form-label">

                                                    ADD

                                                </label>

                                                <input type="text" name="add_os" class="form-control"
                                                    value="<?= $data['add_os']; ?>">

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>
                        <hr>

                        <h5 class="section-title">

                            <i class="fa-solid fa-glasses me-2"></i>

                            Prescription

                        </h5>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Lens Type

                                </label>

                                <select name="jenis_lensa" class="form-select">

                                    <option value="Single Vision" <?= ($data['jenis_lensa'] == "Single Vision") ? "selected" : ""; ?>>
                                        Single Vision
                                    </option>

                                    <option value="Bifocal" <?= ($data['jenis_lensa'] == "Bifocal") ? "selected" : ""; ?>>
                                        Bifocal
                                    </option>

                                    <option value="Progressive" <?= ($data['jenis_lensa'] == "Progressive") ? "selected" : ""; ?>>
                                        Progressive
                                    </option>

                                    <option value="Blue Cut" <?= ($data['jenis_lensa'] == "Blue Cut") ? "selected" : ""; ?>>
                                        Blue Cut
                                    </option>

                                    <option value="Photochromic" <?= ($data['jenis_lensa'] == "Photochromic") ? "selected" : ""; ?>>
                                        Photochromic
                                    </option>

                                </select>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Notes

                                </label>

                                <textarea name="catatan" class="form-control"><?= $data['catatan']; ?></textarea>

                            </div>

                        </div>
                        <hr>

                        <div class="d-flex justify-content-end">

                            <a href="index.php" class="btn btn-light me-2">

                                Cancel

                            </a>

                            <button type="submit" class="btn btn-primary">

                                <i class="fa-solid fa-floppy-disk me-2"></i>

                                update Medical Record

                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </main>

    </div>

</body>

</html>