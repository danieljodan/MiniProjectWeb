<?php
// koneksi.php: koneksi ke database MySQL
$host = "localhost";
$user = "root";       // sesuaikan dengan user db Anda
$password = "";       // sesuaikan dengan password db Anda
$database = "sahabat_karier";  // sesuaikan dengan nama database Anda

$conn = mysqli_connect($host, $user, $password, $database) or die("Koneksi gagal: ");


?>
