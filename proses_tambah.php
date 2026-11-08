<?php
require "config/koneksi.php";
require "config/upload.php";

if (!isset($_SESSION["username"])) {
    header("Location: index.php?pesan=belum_login");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: tambah_buku.php");
    exit;
}

$judul    = trim($_POST["judul"]);
$kategori = $_POST["kategori"];
$penulis  = trim($_POST["penulis"]);
$tahun    = (int) $_POST["tahun_terbit"];
$stok     = (int) $_POST["stok"];

// validasi data
if ($judul == "" || $penulis == "") {
    header("Location: tambah_buku.php?error=" . urlencode("Judul dan penulis tidak boleh kosong."));
    exit;
}
if (!in_array($kategori, $daftar_kategori)) {
    header("Location: tambah_buku.php?error=" . urlencode("Kategori tidak valid."));
    exit;
}
if ($tahun < 1900 || $tahun > date("Y")) {
    header("Location: tambah_buku.php?error=" . urlencode("Tahun terbit tidak valid."));
    exit;
}
if ($stok < 0) {
    header("Location: tambah_buku.php?error=" . urlencode("Stok tidak boleh negatif."));
    exit;
}

// upload sampul
list($ok, $hasil) = uploadSampul($_FILES["sampul"]);
if (!$ok) {
    header("Location: tambah_buku.php?error=" . urlencode($hasil));
    exit;
}
$nama_sampul = $hasil;

$stmt = mysqli_prepare($koneksi, "INSERT INTO buku (judul, kategori, penulis, tahun_terbit, stok, sampul) VALUES (?, ?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "sssiis", $judul, $kategori, $penulis, $tahun, $stok, $nama_sampul);

if (mysqli_stmt_execute($stmt)) {
    header("Location: dashboard.php?notif=tambah");
} else {
    // kalau insert gagal, file yang sudah terupload dihapus lagi
    hapusFileSampul($nama_sampul);
    header("Location: tambah_buku.php?error=" . urlencode("Gagal menyimpan data ke database."));
}
exit;
