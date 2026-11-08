<?php
require "config/koneksi.php";
require "config/upload.php";

if (!isset($_SESSION["username"])) {
    header("Location: index.php?pesan=belum_login");
    exit;
}
// hapus hanya lewat POST supaya tidak kehapus gara-gara klik link
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: dashboard.php");
    exit;
}

$id = (int) $_POST["id"];

// cari nama file sampul dulu
$stmt = mysqli_prepare($koneksi, "SELECT sampul FROM buku WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$buku = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if ($buku) {
    $stmt = mysqli_prepare($koneksi, "DELETE FROM buku WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (mysqli_stmt_execute($stmt)) {
        hapusFileSampul($buku["sampul"]); // hapus file gambarnya juga (unlink)
    }
}

header("Location: dashboard.php?notif=hapus");
exit;
