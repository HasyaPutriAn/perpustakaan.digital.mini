<?php
require "config/koneksi.php";

if (!isset($_SESSION["username"])) {
    header("Location: index.php?pesan=belum_login");
    exit;
}

$error = isset($_GET["error"]) ? $_GET["error"] : "";
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tambah Buku | Mini Digital Library</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav class="navbar navbar-dark bg-dark mb-4">
  <div class="container">
    <span class="navbar-brand">Mini Digital Library</span>
    <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
  </div>
</nav>

<div class="container pb-5" style="max-width: 720px;">
  <div class="card shadow-sm">
    <div class="card-body p-4">
      <h5 class="mb-3">Tambah Buku Baru</h5>

      <?php if ($error != "") { ?>
        <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
      <?php } ?>

      <form action="proses_tambah.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Judul Buku</label>
          <input type="text" name="judul" class="form-control" required>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori" class="form-select" required>
              <option value="">- Pilih kategori -</option>
              <?php foreach ($daftar_kategori as $k) { ?>
                <option value="<?= $k ?>"><?= $k ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Penulis</label>
            <input type="text" name="penulis" class="form-control" required>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Tahun Terbit</label>
            <input type="number" name="tahun_terbit" class="form-control" min="1900" max="<?= date("Y") ?>" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Stok Buku</label>
            <input type="number" name="stok" id="stok" class="form-control" min="0" value="1" required>
            <div class="form-text mt-2">Status Ketersediaan:
              <span id="labelStatus" class="badge text-bg-success">Tersedia</span>
              (stok lebih dari 0 = Tersedia, stok 0 = Dipinjam)</div>
          </div>
        </div>
        <div class="mb-4">
          <label class="form-label">Sampul Buku (JPG/PNG, maks 2MB)</label>
          <input type="file" name="sampul" id="sampul" class="form-control" accept=".jpg,.jpeg,.png" required>
          <img id="preview" class="cover-preview mt-2 d-none" alt="preview">
        </div>
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary">Simpan</button>
          <a href="dashboard.php" class="btn btn-outline-secondary">Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// preview gambar sebelum diupload + cek ukuran di sisi browser
document.getElementById("sampul").addEventListener("change", function () {
  var file = this.files[0];
  var img = document.getElementById("preview");
  if (!file) return;
  if (file.size > 2 * 1024 * 1024) {
    alert("Ukuran file lebih dari 2MB, pilih file lain.");
    this.value = "";
    img.classList.add("d-none");
    return;
  }
  img.src = URL.createObjectURL(file);
  img.classList.remove("d-none");
});

// label status ketersediaan ikut berubah sesuai isi stok
var inputStok = document.getElementById("stok");
var labelStatus = document.getElementById("labelStatus");
function ubahStatus() {
  if (parseInt(inputStok.value) > 0) {
    labelStatus.textContent = "Tersedia";
    labelStatus.className = "badge text-bg-success";
  } else {
    labelStatus.textContent = "Dipinjam";
    labelStatus.className = "badge text-bg-danger";
  }
}
inputStok.addEventListener("input", ubahStatus);
ubahStatus();
</script>
</body>
</html>
