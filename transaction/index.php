<?php
session_start();
include "../config/database.php";

// ======================================================
// AUTH LOGIN
// ======================================================
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit;
}

$user_id = intval($_SESSION['id']);


// ======================================================
// DATA USER LOGIN
// ======================================================
$queryUser = mysqli_query($conn, "
    SELECT *
    FROM users
    WHERE id = '$user_id'
    LIMIT 1
");

$user = mysqli_fetch_assoc($queryUser);


// ======================================================
// FOTO PROFILE
// ======================================================
$foto = "../assets/img/default.png";

if (
    !empty($user['foto']) &&
    file_exists("../assets/img/profile/" . $user['foto'])
) {
    $foto = "../assets/img/profile/" . $user['foto'];
}


// ======================================================
// DATA PASIEN
// ======================================================
$patients = mysqli_query($conn, "
    SELECT *
    FROM patients
    ORDER BY nama ASC
");


// ======================================================
// DATA PRODUK
// ======================================================
$products = mysqli_query($conn, "
    SELECT *
    FROM products
    WHERE stok > 0
    ORDER BY nama_produk ASC
");


// ======================================================
// GENERATE KODE TRANSAKSI
// ======================================================
$queryKode = mysqli_query($conn, "
    SELECT MAX(id) AS last_id
    FROM transactions
");

$dataKode = mysqli_fetch_assoc($queryKode);

$nextID = ($dataKode['last_id'] ?? 0) + 1;

$kodeTransaksi = "TRX" . str_pad($nextID, 4, "0", STR_PAD_LEFT);

?>
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>POS Transaction</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/transaction.css">

</head>

<body>

    <div class="app-shell">

        <!-- ================= SIDEBAR ================= -->
        <aside class="sidebar">

            <div class="brand">

                <div class="brand-logo">
                    <img src="../assets/img/logo.png" alt="Logo">
                </div>

                <div class="brand-text">
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

                <a href="index.php" class="nav-item active">
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
        <!-- END SIDEBAR -->


        <!-- ================= MAIN ================= -->
        <main class="main">
            <?php if (isset($_SESSION['success'])) { ?>

                <div class="alert alert-success alert-dismissible fade show">

                    <?= $_SESSION['success']; ?>

                    <button class="btn-close" data-bs-dismiss="alert"></button>

                </div>

                <?php unset($_SESSION['success']);
            } ?>


            <?php if (isset($_SESSION['error'])) { ?>

                <div class="alert alert-danger alert-dismissible fade show">

                    <?= $_SESSION['error']; ?>

                    <button class="btn-close" data-bs-dismiss="alert"></button>

                </div>

                <?php unset($_SESSION['error']);
            } ?>
            <!-- TOPBAR -->
            <header class="topbar">

                <div class="top-actions ms-auto">

                    <div class="admin-chip">

                        <img src="<?= $foto ?>" alt="Profile">

                        <span>
                            <?= htmlspecialchars($user['nama']) ?>
                        </span>

                    </div>

                </div>

            </header>


            <!-- PAGE HEADER -->
            <div class="page-header">

                <div>

                    <h2>POS Transaction</h2>

                    <p>Create new sales transaction.</p>

                </div>

                <div class="transaction-code">
                    <?= $kodeTransaksi ?>
                </div>

            </div>

            <form id="transactionForm" method="POST" action="save.php">
                <!-- CONTENT -->
                <div class="transaction-grid">

                    <div class="left-panel">

                        <div class="card shadow-sm">

                            <div class="card-header bg-primary text-white">
                                <i class="fa fa-user"></i>
                                Patient Information
                            </div>

                            <div class="card-body">

                                <label class="form-label">Select Patient</label>

                                <select class="form-select" name="patient_id" id="patient" required>

                                    <option value="">-- Select Patient --</option>

                                    <?php while ($p = mysqli_fetch_assoc($patients)) { ?>

                                        <option value="<?= $p['id']; ?>">
                                            <?= htmlspecialchars($p['nama']); ?>
                                        </option>

                                    <?php } ?>

                                </select>

                            </div>

                        </div>

                    </div>

                    <div class="center-panel">

                        <div class="card shadow-sm">

                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fa-solid fa-box"></i>
                                    Product List
                                </h5>
                            </div>

                            <div class="card-body">

                                <input type="text" class="form-control mb-3" id="searchProduct"
                                    placeholder="Search Product...">

                                <table class="table table-hover align-middle">

                                    <thead>

                                        <tr>

                                            <th>Product</th>
                                            <th width="100">Stock</th>
                                            <th width="150">Price</th>
                                            <th width="100">Action</th>

                                        </tr>

                                    </thead>

                                    <tbody id="productTable">

                                        <?php while ($prd = mysqli_fetch_assoc($products)) { ?>

                                            <tr>

                                                <td>
                                                    <?= htmlspecialchars($prd['nama_produk']) ?>
                                                </td>

                                                <td>
                                                    <?= $prd['stok'] ?>
                                                </td>

                                                <td>
                                                    Rp
                                                    <?= number_format($prd['harga_jual'], 0, ',', '.') ?>
                                                </td>

                                                <td>

                                                    <button type="button" class="btn btn-primary btn-sm btn-add"
                                                        data-id="<?= $prd['id'] ?>"
                                                        data-name="<?= htmlspecialchars($prd['nama_produk']) ?>"
                                                        data-price="<?= $prd['harga_jual'] ?>">

                                                        <i class="fa-solid fa-plus"></i>

                                                    </button>

                                                </td>

                                            </tr>

                                        <?php } ?>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                    <div class="right-panel">

                        <div class="card shadow-sm">

                            <!-- Header -->
                            <div class="card-header">

                                <h5 class="mb-0">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                    Shopping Cart
                                </h5>

                            </div>

                            <!-- Body -->
                            <div class="card-body">

                                <table class="table table-hover align-middle">

                                    <thead>

                                        <tr>

                                            <th>Item</th>
                                            <th width="110">Qty</th>
                                            <th width="130">Subtotal</th>
                                            <th width="60">Action</th>

                                        </tr>

                                    </thead>

                                    <tbody id="cartBody">

                                        <tr>

                                            <td colspan="4" class="text-center text-muted">

                                                No Product

                                            </td>

                                        </tr>

                                    </tbody>

                                </table>

                                <hr>

                                <div class="d-flex justify-content-between align-items-center mb-3">

                                    <h5 class="mb-0">Total</h5>

                                    <h4 class="text-success mb-0" id="grandTotal">

                                        Rp 0

                                    </h4>

                                </div>

                                <!-- Payment -->
                                <div class="mb-3">

                                    <label class="form-label fw-semibold">

                                        Payment Method

                                    </label>

                                    <select class="form-select" name="metode_pembayaran" required>

                                        <option value="Cash">Cash</option>
                                        <option value="Transfer">Transfer</option>
                                        <option value="QRIS">QRIS</option>

                                    </select>

                                </div>

                                <!-- Save -->
                                <button type="submit" class="btn btn-success w-100">

                                    <i class="fa-solid fa-floppy-disk"></i>

                                    Save Transaction

                                </button>

                                <!-- Hidden -->
                                <input type="hidden" name="kode_transaksi" value="<?= $kodeTransaksi ?>">

                                <input type="hidden" name="total" id="totalInput">

                                <input type="hidden" name="cart" id="cartInput">

                            </div>

                        </div>

                    </div>
                </div>

            </form>
        </main>

    </div>
    <script>

        // ===============================
        // SEARCH PRODUCT
        // ===============================
        document.getElementById("searchProduct").addEventListener("keyup", function () {

            let keyword = this.value.toLowerCase();

            let rows = document.querySelectorAll("#productTable tr");

            rows.forEach(function (row) {

                let nama = row.cells[0].innerText.toLowerCase();

                row.style.display = nama.includes(keyword) ? "" : "none";

            });

        });


        // ===============================
        // SHOPPING CART
        // ===============================
        let cart = [];

        const cartBody = document.getElementById("cartBody");
        const grandTotal = document.getElementById("grandTotal");

        document.querySelectorAll(".btn-add").forEach(function (btn) {

            btn.addEventListener("click", function () {

                let id = this.dataset.id;
                let nama = this.dataset.name;
                let harga = parseInt(this.dataset.price);

                let item = cart.find(p => p.id == id);

                if (item) {

                    item.qty++;

                } else {

                    cart.push({

                        id: id,
                        nama: nama,
                        harga: harga,
                        qty: 1

                    });

                }

                renderCart();

            });

        });


        // ===============================
        // RENDER CART
        // ===============================
        function renderCart() {

            cartBody.innerHTML = "";

            let grand = 0;

            if (cart.length === 0) {

                cartBody.innerHTML = `
        <tr>
            <td colspan="4" class="text-center text-muted">
                No Product
            </td>
        </tr>
        `;

                grandTotal.innerHTML = "Rp 0";

                document.getElementById("totalInput").value = 0;
                document.getElementById("cartInput").value = "";

                return;

            }

            cart.forEach(function (item, index) {

                let subtotal = item.qty * item.harga;

                grand += subtotal;

                cartBody.innerHTML += `

        <tr>

            <td>${item.nama}</td>

            <td>

                <button
                    type="button"
                    class="btn btn-sm btn-danger"
                    onclick="minusQty(${index})">

                    -

                </button>

                <span class="mx-2">${item.qty}</span>

                <button
                    type="button"
                    class="btn btn-sm btn-success"
                    onclick="plusQty(${index})">

                    +

                </button>

            </td>

            <td>

                Rp ${subtotal.toLocaleString('id-ID')}

            </td>

            <td>

                <button
                    type="button"
                    class="btn btn-sm btn-outline-danger"
                    onclick="hapusItem(${index})">

                    <i class="fa-solid fa-trash"></i>

                </button>

            </td>

        </tr>

        `;

            });

            grandTotal.innerHTML = "Rp " + grand.toLocaleString("id-ID");

            document.getElementById("totalInput").value = grand;

            document.getElementById("cartInput").value = JSON.stringify(cart);

        }



        // ===============================
        // TAMBAH QTY
        // ===============================
        function plusQty(index) {

            cart[index].qty++;

            renderCart();

        }



        // ===============================
        // KURANG QTY
        // ===============================
        function minusQty(index) {

            cart[index].qty--;

            if (cart[index].qty <= 0) {

                cart.splice(index, 1);

            }

            renderCart();

        }



        // ===============================
        // HAPUS ITEM
        // ===============================
        function hapusItem(index) {

            cart.splice(index, 1);

            renderCart();

        }



        // ===============================
        // VALIDASI SEBELUM SUBMIT
        // ===============================
        document.getElementById("transactionForm").addEventListener("submit", function (e) {

            if (document.getElementById("patient").value == "") {

                alert("Silakan pilih pasien.");

                e.preventDefault();

                return;

            }

            if (cart.length == 0) {

                alert("Keranjang masih kosong.");

                e.preventDefault();

                return;

            }

        });

    </script>
</body>

</html>