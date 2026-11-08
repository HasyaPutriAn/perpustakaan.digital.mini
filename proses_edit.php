<?php
require "config/koneksi.php";
require "config/upload.php";

if (!isset($_SESSION["username"])) {
    header("Location: index.php?pesan=belum_login");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: dashboard.php");
    exit;
}

$id       = (int) $_POST["id"];
$judul    = trim($_POST["judul"]);
$kategori = $_POST["kategori"];
$penulis  = trim($_POST["penulis"]);
$tahun    = (int) $_POST["tahun_terbit"];
$stok     = (int) $_POST["stok"];

$url_balik = "edit_buku.php?id=" . $id . "&error=";

if ($judul == "" || $penulis == "") {
    header("Location: " . $url_balik . urlencode("Judul dan penulis tidak boleh kosong."));
    exit;
}
if (!in_array($kategori, $daftar_kategori)) {
    header("Location: " . $url_balik . urlencode("Kategori tidak valid."));
    exit;
}
if ($tahun < 1900 || $tahun > date("Y")) {
    header("Location: " . $url_balik . urlencode("Tahun terbit tidak valid."));
    exit;
}
if ($stok < 0) {
    header("Location: " . $url_balik . urlencode("Stok tidak boleh negatif."));
    exit;
}

// ambil data lama dulu, butuh nama file sampul lama
$stmt = mysqli_prepare($koneksi, "SELECT sampul FROM buku WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$lama = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$lama) {
    header("Location: dashboard.php");
    exit;
}
$sampul_lama = $lama["sampul"];
$sampul_final = $sampul_lama;
$ganti_sampul = false;

// kalau user pilih file baru, proses upload
if (isset($_FILES["sampul"]) && $_FILES["sampul"]["error"] !== UPLOAD_ERR_NO_FILE) {
    list($ok, $hasil) = uploadSampul($_FILES["sampul"]);
    if (!$ok) {
        header("Location: " . $url_balik . urlencode($hasil));
        exit;
    }
    $sampul_final = $hasil;
    $ganti_sampul = true;
}

$stmt = mysqli_prepare($koneksi, "UPDATE buku SET judul=?, kategori=?, penulis=?, tahun_terbit=?, stok=?, sampul=? WHERE id=?");
mysqli_stmt_bind_param($stmt, "sssiisi", $judul, $kategori, $penulis, $tahun, $stok, $sampul_final, $id);

if (mysqli_stmt_execute($stmt)) {
    // file lama baru dihapus setelah update berhasil
    if ($ganti_sampul) {
        hapusFileSampul($sampul_lama);
    }
    header("Location: dashboard.php?notif=edit");
} else {
    if ($ganti_sampul) {
        hapusFileSampul($sampul_final);
    }
    header("Location: " . $url_balik . urlencode("Gagal memperbarui data."));
}
exit;
