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
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Employee | Optik Gamma</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- CSS -->
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

                    <a href="../reports/index.php" class="nav-item">
                        <i class="fa-solid fa-chart-column"></i>
                        Reports
                    </a>

                    <a href="index.php" class="nav-item active">
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

            <!-- Breadcrumb -->

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

                        Add Employee

                    </li>

                </ol>

            </nav>

            <!-- Title -->

            <div class="mb-4">

                <h2 class="fw-bold">

                    Add Employee

                </h2>

                <p class="text-muted">

                    Create a new employee account and assign system access.

                </p>

            </div>

            <!-- Form -->

            <form action="save.php" method="POST" enctype="multipart/form-data">

                <div class="card shadow-sm">

                    <div class="card-body p-4">
                        <!-- =========================
        PERSONAL INFORMATION
========================= -->

                        <h5 class="section-title mb-4">

                            <i class="fa-solid fa-user me-2 text-primary"></i>

                            Personal Information

                        </h5>

                        <div class="row">

                            <!-- Full Name -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Full Name <span class="text-danger">*</span>

                                </label>

                                <input type="text" name="nama" class="form-control" placeholder="Enter full name"
                                    required>

                            </div>

                            <!-- Phone -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Phone Number

                                </label>

                                <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx">

                            </div>

                            <!-- Email -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Email Address

                                </label>

                                <input type="email" name="email" class="form-control" placeholder="example@gmail.com">

                            </div>

                            <!-- Address -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Address

                                </label>

                                <textarea name="alamat" class="form-control" rows="3"
                                    placeholder="Enter address"></textarea>

                            </div>

                        </div>
                        <hr class="my-4">

                        <h5 class="section-title mb-4">

                            <i class="fa-solid fa-lock me-2 text-primary"></i>

                            Account Information

                        </h5>

                        <div class="row">

                            <!-- Username -->

                            <div class="col-md-4 mb-3">

                                <label class="form-label">

                                    Username <span class="text-danger">*</span>

                                </label>

                                <input type="text" name="username" class="form-control" placeholder="Username" required>

                            </div>

                            <!-- Password -->

                            <div class="col-md-4 mb-3">

                                <label class="form-label">

                                    Password <span class="text-danger">*</span>

                                </label>

                                <div class="input-group">

                                    <input type="password" name="password" id="password" class="form-control" required>

                                    <button class="btn btn-outline-secondary" type="button" id="showPassword">

                                        <i class="fa-solid fa-eye"></i>

                                    </button>

                                </div>

                            </div>

                            <!-- Confirm Password -->

                            <div class="col-md-4 mb-3">

                                <label class="form-label">

                                    Confirm Password <span class="text-danger">*</span>

                                </label>

                                <div class="input-group">

                                    <input type="password" name="confirm_password" id="confirmPassword"
                                        class="form-control" required>

                                    <button class="btn btn-outline-secondary" type="button" id="showConfirm">

                                        <i class="fa-solid fa-eye"></i>

                                    </button>

                                </div>

                            </div>

                        </div>
                        <hr class="my-4">

                        <h5 class="section-title mb-4">

                            <i class="fa-solid fa-shield-halved me-2 text-primary"></i>

                            Access & Status

                        </h5>

                        <div class="row">

                            <!-- Role -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Role <span class="text-danger">*</span>

                                </label>

                                <select name="role" class="form-select" required>

                                    <option value="">-- Select Role --</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Karyawan">Karyawan</option>

                                </select>

                            </div>

                            <!-- Status -->

                            <div class="col-md-6 mb-3">

                                <label class="form-label d-block">

                                    Status

                                </label>

                                <div class="form-check form-check-inline">

                                    <input class="form-check-input" type="radio" name="status" value="Aktif" checked>

                                    <label class="form-check-label">

                                        Active

                                    </label>

                                </div>

                                <div class="form-check form-check-inline">

                                    <input class="form-check-input" type="radio" name="status" value="Nonaktif">

                                    <label class="form-check-label">

                                        Inactive

                                    </label>

                                </div>

                            </div>

                        </div>
                        <div class="mb-4">

                            <label class="form-label">

                                Employee Photo

                            </label>

                            <input type="file" name="foto" class="form-control" id="foto">

                        </div>


                        <div class="d-flex justify-content-end mt-4">

                            <a href="index.php" class="btn btn-outline-secondary me-2">

                                Cancel

                            </a>

                            <button type="submit" class="btn btn-primary px-4">

                                <i class="fa-solid fa-floppy-disk me-2"></i>

                                Save Employee

                            </button>

                        </div>
                    </div>
                </div>

    </div>

    </form>
    <script>

        document.getElementById("foto").addEventListener("change", function (e) {

            const file = e.target.files[0];

            if (file) {

                document.getElementById("preview").src =
                    URL.createObjectURL(file);

            }

        });

    </script>
    <script>

        function togglePassword(id) {

            let input = document.getElementById(id);

            if (input.type === "password") {

                input.type = "text";

            } else {

                input.type = "password";

            }

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