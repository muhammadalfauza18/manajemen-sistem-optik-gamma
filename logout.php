<?php
session_start();

// Hapus semua data session
$_SESSION = [];

// Hancurkan session
session_destroy();

// Arahkan ke halaman login
header("Location: index.php");
exit;
?>