<?php
session_start();
include "../config/database.php";

// Pastikan user sudah login
if (!isset($_SESSION['id'])) {

    header("Location: ../index.php");
    exit;
}

// Ambil user login
$user_id = $_SESSION['id'];

// Ambil data form
$patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']);
$tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
$keluhan = mysqli_real_escape_string($conn, $_POST['keluhan']);
$diagnosis = mysqli_real_escape_string($conn, $_POST['diagnosis']);

$sph_od = mysqli_real_escape_string($conn, $_POST['sph_od']);
$cyl_od = mysqli_real_escape_string($conn, $_POST['cyl_od']);
$axis_od = mysqli_real_escape_string($conn, $_POST['axis_od']);
$add_od = mysqli_real_escape_string($conn, $_POST['add_od']);

$sph_os = mysqli_real_escape_string($conn, $_POST['sph_os']);
$cyl_os = mysqli_real_escape_string($conn, $_POST['cyl_os']);
$axis_os = mysqli_real_escape_string($conn, $_POST['axis_os']);
$add_os = mysqli_real_escape_string($conn, $_POST['add_os']);

$jenis_lensa = mysqli_real_escape_string($conn, $_POST['jenis_lensa']);
$catatan = mysqli_real_escape_string($conn, $_POST['catatan']);

// Simpan ke database
$query = mysqli_query($conn, "

INSERT INTO medical_records(

patient_id,
user_id,
tanggal,
keluhan,
diagnosis,

sph_od,
cyl_od,
axis_od,
add_od,

sph_os,
cyl_os,
axis_os,
add_os,

jenis_lensa,
catatan

)

VALUES(

'$patient_id',
'$user_id',
'$tanggal',
'$keluhan',
'$diagnosis',

'$sph_od',
'$cyl_od',
'$axis_od',
'$add_od',

'$sph_os',
'$cyl_os',
'$axis_os',
'$add_os',

'$jenis_lensa',
'$catatan'

)

");

if ($query) {

    header("Location:index.php?success=add");
    exit;

} else {

    echo "Gagal menyimpan data : " . mysqli_error($conn);

}
?>