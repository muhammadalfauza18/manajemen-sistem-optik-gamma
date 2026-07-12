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
// Cek ID
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

$query = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");

if (mysqli_num_rows($query) == 0) {
    header("Location: index.php");
    exit;
}

$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Employee | Optik Gamma</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/employee.css">

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

                <a href="../medical/index.php" class="nav-item">

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

                <a href="index.php" class="nav-item active">

                    <i class="fa-solid fa-user-tie"></i>

                    Employee

                </a>

            </nav>

        </aside>
        <main class="main">

            <!-- TOPBAR -->

            <header class="topbar">

                <div class="search-box">

                    <i class="fa-solid fa-search"></i>

                    <input type="text" placeholder="Search employee...">

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
            <nav aria-label="breadcrumb">

                <ol class="breadcrumb">

                    <li class="breadcrumb-item">

                        <a href="../dashboard.php">

                            Dashboard

                        </a>

                    </li>

                    <li class="breadcrumb-item">

                        <a href="index.php">

                            Employee Management

                        </a>

                    </li>

                    <li class="breadcrumb-item active">

                        Edit Employee

                    </li>

                </ol>

            </nav>
            <div class="mb-4">

                <h2 class="fw-bold">

                    Edit Employee

                </h2>

                <p class="text-muted">

                    Update employee information.

                </p>

            </div>
            <form action="update.php" method="POST" enctype="multipart/form-data">

                <input type="hidden" name="id" value="<?= $data['id']; ?>">

                <div class="card shadow-sm">

                    <div class="card-body">
                        <h5 class="section-title mb-4">

                            <i class="fa-solid fa-user"></i>

                            Personal Information

                        </h5>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Full Name <span class="text-danger">*</span>

                                </label>

                                <input type="text" name="nama" class="form-control"
                                    value="<?= htmlspecialchars($data['nama']); ?>" required>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Phone Number

                                </label>

                                <input type="text" name="no_hp" class="form-control"
                                    value="<?= htmlspecialchars($data['no_hp']); ?>">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Email Address

                                </label>

                                <input type="email" name="email" class="form-control"
                                    value="<?= htmlspecialchars($data['email']); ?>">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Address

                                </label>

                                <textarea name="alamat" class="form-control"
                                    rows="3"><?= htmlspecialchars($data['alamat']); ?></textarea>

                            </div>

                        </div>
                        <hr class="my-4">

                        <h5 class="section-title mb-4">

                            <i class="fa-solid fa-lock"></i>

                            Account Information

                        </h5>

                        <div class="row">

                            <!-- Username -->

                            <div class="col-md-4 mb-3">

                                <label class="form-label">

                                    Username <span class="text-danger">*</span>

                                </label>

                                <input type="text" name="username" class="form-control"
                                    value="<?= htmlspecialchars($data['username']); ?>" required>

                            </div>

                            <!-- Password Baru -->

                            <div class="col-md-4 mb-3">

                                <label class="form-label">

                                    New Password

                                </label>

                                <div class="input-group">

                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="Kosongkan jika tidak diubah">

                                    <button class="btn btn-outline-secondary" type="button" id="showPassword">

                                        <i class="fa-solid fa-eye"></i>

                                    </button>

                                </div>

                            </div>

                            <!-- Confirm Password -->

                            <div class="col-md-4 mb-3">

                                <label class="form-label">

                                    Confirm Password

                                </label>

                                <div class="input-group">

                                    <input type="password" name="confirm_password" id="confirmPassword"
                                        class="form-control" placeholder="Konfirmasi password">

                                    <button class="btn btn-outline-secondary" type="button" id="showConfirm">

                                        <i class="fa-solid fa-eye"></i>

                                    </button>

                                </div>

                            </div>

                        </div>
                        <hr class="my-4">

                        <h5 class="section-title mb-4">

                            <i class="fa-solid fa-shield-halved"></i>

                            Access & Status

                        </h5>

                        <div class="row">

                            <!-- Role -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Role

                                </label>

                                <select name="role" class="form-select">

                                    <option value="Admin" <?= ($data['role'] == "Admin") ? "selected" : ""; ?>>

                                        Admin

                                    </option>

                                    <option value="Karyawan" <?= ($data['role'] == "Karyawan") ? "selected" : ""; ?>>

                                        Karyawan

                                    </option>

                                </select>

                            </div>

                            <!-- Status -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label d-block">

                                    Status

                                </label>

                                <div class="form-check form-check-inline">

                                    <input class="form-check-input" type="radio" name="status" value="Aktif"
                                        <?= ($data['status'] == "Aktif") ? "checked" : ""; ?>>

                                    <label class="form-check-label">

                                        Active

                                    </label>

                                </div>

                                <div class="form-check form-check-inline">

                                    <input class="form-check-input" type="radio" name="status" value="Nonaktif"
                                        <?= ($data['status'] == "Nonaktif") ? "checked" : ""; ?>>

                                    <label class="form-check-label">

                                        Inactive

                                    </label>

                                </div>

                            </div>

                        </div>
                        <hr class="my-4">

                        <h5 class="section-title mb-4">

                            <i class="fa-solid fa-image"></i>

                            Employee Photo

                        </h5>

                        <div class="row">

                            <div class="col-md-4 text-center">

                                <?php

                                $foto = "../assets/img/profile/default.png";

                                if (!empty($data['foto']) && file_exists("../assets/img/profile/" . $data['foto'])) {

                                    $foto = "../assets/img/profile/" . $data['foto'];

                                }

                                ?>

                                <img src="<?= $foto; ?>" id="preview" class="rounded-circle shadow-sm" width="130"
                                    height="130" style="object-fit:cover;">

                            </div>

                            <div class="col-md-8">

                                <label class="form-label">

                                    Upload New Photo

                                </label>

                                <input type="file" name="foto" id="foto" class="form-control">

                                <small class="text-muted">

                                    Kosongkan jika tidak ingin mengganti foto.

                                </small>

                            </div>

                        </div>
                        <div class="d-flex justify-content-end mt-5">

                            <a href="index.php" class="btn btn-outline-secondary me-2">

                                Cancel

                            </a>

                            <button type="submit" class="btn btn-primary px-4">

                                <i class="fa-solid fa-floppy-disk me-2"></i>

                                Update Employee

                            </button>

                        </div>

                    </div>

                </div>

            </form>
            <script>

                // Preview Foto

                document.getElementById("foto").addEventListener("change", function (e) {

                    const file = e.target.files[0];

                    if (file) {

                        document.getElementById("preview").src = URL.createObjectURL(file);

                    }

                });

                // Show Password

                function togglePassword(id) {

                    const input = document.getElementById(id);

                    input.type = input.type === "password" ? "text" : "password";

                }

                document.getElementById("showPassword").onclick = function () {

                    togglePassword("password");

                }

                document.getElementById("showConfirm").onclick = function () {

                    togglePassword("confirmPassword");

                }

            </script>

        </main>

    </div>

</body>

</html>