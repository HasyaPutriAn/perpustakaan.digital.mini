<?php
require "config/koneksi.php";

// proteksi halaman, harus login dulu
if (!isset($_SESSION["username"])) {
    header("Location: index.php?pesan=belum_login");
    exit;
}

// fitur cari sederhana
$cari = isset($_GET["cari"]) ? trim($_GET["cari"]) : "";
if ($cari != "") {
    $like = "%" . $cari . "%";
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM buku WHERE judul LIKE ? OR penulis LIKE ? ORDER BY id DESC");
    mysqli_stmt_bind_param($stmt, "ss", $like, $like);
    mysqli_stmt_execute($stmt);
    $data = mysqli_stmt_get_result($stmt);
} else {
    $data = mysqli_query($koneksi, "SELECT * FROM buku ORDER BY id DESC");
}

// hitung ringkasan
$total = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS jml FROM buku"))["jml"];
$tersedia = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS jml FROM buku WHERE stok > 0"))["jml"];
$dipinjam = $total - $tersedia;

$notif = isset($_GET["notif"]) ? $_GET["notif"] : "";
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard | Mini Digital Library</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav class="navbar navbar-dark bg-dark mb-4">
  <div class="container">
    <span class="navbar-brand">Mini Digital Library</span>
    <div class="d-flex align-items-center text-white">
      <span class="me-3">Halo, <?= htmlspecialchars($_SESSION["nama"]) ?></span>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container pb-5">

  <?php if ($notif == "tambah") { ?>
    <div class="alert alert-success alert-dismissible fade show">Buku baru berhasil ditambahkan.
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  <?php } elseif ($notif == "edit") { ?>
    <div class="alert alert-success alert-dismissible fade show">Data buku berhasil diperbarui.
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  <?php } elseif ($notif == "hapus") { ?>
    <div class="alert alert-success alert-dismissible fade show">Data buku berhasil dihapus.
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  <?php } ?>

  <div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card card-stat shadow-sm"><div class="card-body">
      <div class="text-muted">Total Buku</div><h3 class="mb-0"><?= $total ?></h3></div></div></div>
    <div class="col-md-4"><div class="card card-stat shadow-sm"><div class="card-body">
      <div class="text-muted">Tersedia</div><h3 class="mb-0"><?= $tersedia ?></h3></div></div></div>
    <div class="col-md-4"><div class="card card-stat shadow-sm"><div class="card-body">
      <div class="text-muted">Sedang Dipinjam</div><h3 class="mb-0"><?= $dipinjam ?></h3></div></div></div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h5 class="mb-0">Daftar Buku</h5>
        <div class="d-flex gap-2">
          <form method="GET" class="d-flex gap-2">
            <input type="text" name="cari" class="form-control form-control-sm" placeholder="Cari judul / penulis" value="<?= htmlspecialchars($cari) ?>">
            <button class="btn btn-outline-secondary btn-sm">Cari</button>
          </form>
          <a href="tambah_buku.php" class="btn btn-primary btn-sm">+ Tambah Buku</a>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>No</th><th>Sampul</th><th>Judul</th><th>Kategori</th>
              <th>Penulis</th><th>Tahun</th><th>Stok</th><th>Status</th><th>Aksi</th>
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1;
          if (mysqli_num_rows($data) == 0) {
              echo '<tr><td colspan="9" class="text-center text-muted py-4">Belum ada data buku.</td></tr>';
          }
          while ($b = mysqli_fetch_assoc($data)) {
              // logika status: stok lebih dari 0 berarti tersedia
              if ($b["stok"] > 0) {
                  $status = "Tersedia";
                  $warna = "success";
              } else {
                  $status = "Dipinjam";
                  $warna = "danger";
              }
          ?>
            <tr>
              <td><?= $no++ ?></td>
              <td>
                <?php if ($b["sampul"] != "" && file_exists("uploads/" . $b["sampul"])) { ?>
                  <img src="uploads/<?= htmlspecialchars($b["sampul"]) ?>" class="cover-kecil" alt="sampul">
                <?php } else { ?>
                  <span class="cover-kosong">No cover</span>
                <?php } ?>
              </td>
              <td><?= htmlspecialchars($b["judul"]) ?></td>
              <td><?= htmlspecialchars($b["kategori"]) ?></td>
              <td><?= htmlspecialchars($b["penulis"]) ?></td>
              <td><?= $b["tahun_terbit"] ?></td>
              <td><?= $b["stok"] ?></td>
              <td><span class="badge text-bg-<?= $warna ?>"><?= $status ?></span></td>
              <td class="text-nowrap">
                <a href="edit_buku.php?id=<?= $b["id"] ?>" class="btn btn-warning btn-sm">Edit</a>
                <form action="hapus_buku.php" method="POST" class="d-inline"
                      onsubmit="return confirm('Yakin mau menghapus buku ini?')">
                  <input type="hidden" name="id" value="<?= $b["id"] ?>">
                  <button class="btn btn-danger btn-sm">Hapus</button>
                </form>
              </td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
