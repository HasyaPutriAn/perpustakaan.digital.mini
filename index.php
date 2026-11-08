<?php
session_start();
// kalau sudah login langsung ke dashboard
if (isset($_SESSION["username"])) {
    header("Location: dashboard.php");
    exit;
}

// isi username otomatis kalau tadi centang "Ingat saya"
$username_cookie = isset($_COOKIE["remember_username"]) ? $_COOKIE["remember_username"] : "";
$pesan = isset($_GET["pesan"]) ? $_GET["pesan"] : "";
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | Mini Digital Library</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="login-wrap">
  <div class="card login-card shadow">
    <div class="card-body p-4">
      <h4 class="text-center mb-1">Mini Digital Library</h4>
      <p class="text-center text-muted mb-4">Silakan login untuk mengelola data buku</p>

      <?php if ($pesan == "gagal") { ?>
        <div class="alert alert-danger py-2">Username atau password salah.</div>
      <?php } elseif ($pesan == "belum_login") { ?>
        <div class="alert alert-warning py-2">Kamu harus login dulu.</div>
      <?php } elseif ($pesan == "logout") { ?>
        <div class="alert alert-success py-2">Kamu sudah logout.</div>
      <?php } ?>

      <form action="proses_login.php" method="POST">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($username_cookie) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" name="remember" id="remember" <?= $username_cookie != "" ? "checked" : "" ?>>
          <label class="form-check-label" for="remember">Ingat saya</label>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>
