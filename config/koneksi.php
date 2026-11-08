<?php
// koneksi ke database MySQL pakai mysqli
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_perpustakaan";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// daftar kategori disimpan di array supaya dropdown tidak ditulis berulang
$daftar_kategori = ["Fiksi", "Non-Fiksi", "Teknologi", "Pendidikan", "Sejarah", "Hukum", "Lainnya"];
