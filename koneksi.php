<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "moodies_db"; // Pastikan nama database di phpMyAdmin sama

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>