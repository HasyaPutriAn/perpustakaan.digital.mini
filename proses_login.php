<?php
require "config/koneksi.php";

// halaman ini cuma boleh diakses lewat form (POST)
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$username = trim($_POST["username"]);
$password = $_POST["password"];

$stmt = mysqli_prepare($koneksi, "SELECT id, username, nama_lengkap, password FROM users WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($hasil);

if ($user && password_verify($password, $user["password"])) {
    session_regenerate_id(true);
    $_SESSION["id_user"]  = $user["id"];
    $_SESSION["username"] = $user["username"];
    $_SESSION["nama"]     = $user["nama_lengkap"];

    // fitur remember me: simpan username di cookie selama 7 hari
    if (isset($_POST["remember"])) {
        setcookie("remember_username", $user["username"], time() + (7 * 24 * 60 * 60), "/", "", false, true);
    } else {
        setcookie("remember_username", "", time() - 3600, "/");
    }

    header("Location: dashboard.php");
    exit;
}

header("Location: index.php?pesan=gagal");
exit;
