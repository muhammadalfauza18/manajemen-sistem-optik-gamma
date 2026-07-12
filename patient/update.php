<?php

include "../config/database.php";

$id = $_POST['id'];

$nama = $_POST['nama'];

$jk = $_POST['jenis_kelamin'];

$tgl = $_POST['tanggal_lahir'];

$hp = $_POST['no_hp'];

$email = $_POST['email'];

$alamat = $_POST['alamat'];

mysqli_query($conn, "UPDATE patients SET

nama='$nama',

jenis_kelamin='$jk',

tanggal_lahir='$tgl',

no_hp='$hp',

email='$email',

alamat='$alamat',

last_visit=NOW()

WHERE id='$id'

");

header("Location:index.php");

?>