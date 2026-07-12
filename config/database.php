<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "optik_gamma";
$port = 3307; // Sesuaikan dengan port MySQL kamu

$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    die("Koneksi database gagal : " . mysqli_connect_error());
}

?>