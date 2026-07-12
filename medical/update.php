<?php
session_start();
include "../config/database.php";

// Pastikan user login
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit;
}

// Ambil data
$id = mysqli_real_escape_string($conn, $_POST['id']);
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

$query = mysqli_query($conn, "

UPDATE medical_records SET

patient_id='$patient_id',
tanggal='$tanggal',
keluhan='$keluhan',
diagnosis='$diagnosis',

sph_od='$sph_od',
cyl_od='$cyl_od',
axis_od='$axis_od',
add_od='$add_od',

sph_os='$sph_os',
cyl_os='$cyl_os',
axis_os='$axis_os',
add_os='$add_os',

jenis_lensa='$jenis_lensa',
catatan='$catatan'

WHERE id='$id'

");

if ($query) {

    header("Location:index.php?success=update");
    exit;

} else {

    echo "Update gagal : " . mysqli_error($conn);

}
?>