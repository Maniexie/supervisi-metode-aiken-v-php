<?php
$username = "root";
$password = "";
$server = "localhost";
$database = "supervisi";


$koneksi = mysqli_connect($server, $username, $password, $database) or die("koneksi gagal");
define('BASE_URL', 'http://192.168.100.5/supervisi/');
?>