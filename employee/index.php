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


// Ambil data employee
$query = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Employee Management | Optik Gamma</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Dashboard CSS -->
    <link rel="stylesheet" href="../assets/css/dashboard.css">

    <!-- Employee CSS -->
    <link rel="stylesheet" href="../assets/css/employee.css">

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
        <main class="main">

            <header class="topbar">

                <div class="search-box">

                    <i class="fa-solid fa-search"></i>

                    <input type="text" id="searchEmployee" placeholder="Search employee...">

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
            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h2 class="fw-bold">

                        Employee Roster

                    </h2>

                    <p class="text-muted">

                        Manage clinical and administrative staff access and details.

                    </p>

                </div>

                <a href="add.php" class="btn btn-primary">

                    <i class="fa-solid fa-user-plus"></i>

                    Add Employee

                </a>

            </div>
            <div class="card shadow-sm border-0">

                <div class="card-body p-0">

                    <table class="table align-middle mb-0">

                        <thead>

                            <tr>

                                <th width="35%">Employee</th>

                                <th width="15%">Role</th>

                                <th width="25%">Contact</th>

                                <th width="10%">Status</th>

                                <th width="15%" class="text-center">Action</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php

                            if (mysqli_num_rows($query) > 0) {

                                while ($row = mysqli_fetch_assoc($query)) {

                                    ?>

                                    <tr>

                                        <!-- Employee -->

                                        <td>

                                            <div class="d-flex align-items-center">

                                                <?php

                                                $foto = "../assets/img/profile/default.png";

                                                if (!empty($row['foto']) && file_exists("../assets/img/profile/" . $row['foto'])) {

                                                    $foto = "../assets/img/profile/" . $row['foto'];

                                                }

                                                ?>

                                                <img src="<?= $foto; ?>" class="rounded-circle me-3" width="50" height="50"
                                                    style="object-fit:cover;">

                                                <div>

                                                    <div class="fw-semibold">

                                                        <?= htmlspecialchars($row['nama']); ?>

                                                    </div>

                                                    <small class="text-muted">

                                                        EMP-<?= str_pad($row['id'], 4, "0", STR_PAD_LEFT); ?>

                                                    </small>

                                                </div>

                                            </div>

                                        </td>
                                        <!-- Role -->

                                        <td>

                                            <?php

                                            if ($row['role'] == "Admin") {

                                                echo '<span class="badge bg-primary px-3 py-2">Admin</span>';

                                            } else {

                                                echo '<span class="badge bg-info text-dark px-3 py-2">Karyawan</span>';

                                            }

                                            ?>

                                        </td>
                                        <!-- Contact -->

                                        <td>

                                            <div>

                                                <i class="fa-solid fa-envelope text-primary me-2"></i>

                                                <?= htmlspecialchars($row['email']); ?>

                                            </div>

                                            <div class="mt-1">

                                                <i class="fa-solid fa-phone text-success me-2"></i>

                                                <?= htmlspecialchars($row['no_hp']); ?>

                                            </div>

                                        </td>
                                        <!-- Status -->

                                        <td>

                                            <?php

                                            if ($row['status'] == "Aktif") {

                                                echo '<span class="badge bg-success">Active</span>';

                                            } else {

                                                echo '<span class="badge bg-secondary">Inactive</span>';

                                            }

                                            ?>

                                        </td>
                                        <!-- Action -->

                                        <td class="text-center">

                                            <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-light border">

                                                <i class="fa-solid fa-pen text-warning"></i>

                                            </a>

                                            <a href="delete.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-light border"
                                                onclick="return confirm('Hapus employee ini?')">

                                                <i class="fa-solid fa-trash text-danger"></i>

                                            </a>

                                        </td>

                                    </tr>

                                    <?php

                                }

                            } else {

                                ?>
                                <tr>

                                    <td colspan="5" class="text-center py-5">

                                        <img src="../assets/img/empty.png" width="120">

                                        <h5 class="mt-3">

                                            Belum ada data employee

                                        </h5>

                                        <p class="text-muted">

                                            Klik tombol <b>Add Employee</b>
                                            untuk menambahkan employee pertama.

                                        </p>

                                    </td>

                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </main>

    </div>

    <script>

        const search = document.getElementById("searchEmployee");

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