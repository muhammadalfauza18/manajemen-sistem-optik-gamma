<?php

include "../config/database.php";

$nama = $_POST['nama'];

$jk = $_POST['jenis_kelamin'];

$tgl = $_POST['tanggal_lahir'];

$hp = $_POST['no_hp'];

$email = $_POST['email'];

$alamat = $_POST['alamat'];

$cek = mysqli_query($conn, "SELECT MAX(id) as id FROM patients");

$data = mysqli_fetch_assoc($cek);

$next = $data['id'] + 1;

$kode = "PT-" . str_pad($next, 4, "0", STR_PAD_LEFT);

mysqli_query($conn, "INSERT INTO patients(

kode_pasien,
nama,
jenis_kelamin,
tanggal_lahir,
no_hp,
email,
alamat,
last_visit

)

VALUES(

'$kode',
'$nama',
'$jk',
'$tgl',
'$hp',
'$email',
'$alamat',
NOW()

)");
header("Location:index.php");

?>