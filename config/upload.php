<?php
// fungsi upload sampul buku, dipakai di proses_tambah dan proses_edit
// return: array [berhasil, nama_file atau pesan_error]
function uploadSampul($file)
{
    $ekstensi_boleh = ["jpg", "jpeg", "png"];
    $maks_ukuran = 2 * 1024 * 1024; // 2MB

    if ($file["error"] !== UPLOAD_ERR_OK) {
        return [false, "File gagal diupload, coba lagi."];
    }

    $ekstensi = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    if (!in_array($ekstensi, $ekstensi_boleh)) {
        return [false, "Sampul harus berformat JPG atau PNG."];
    }

    if ($file["size"] > $maks_ukuran) {
        return [false, "Ukuran sampul maksimal 2MB."];
    }

    // cek isi file memang gambar, bukan cuma ganti ekstensi
    if (getimagesize($file["tmp_name"]) === false) {
        return [false, "File yang diupload bukan gambar."];
    }

    // nama diganti supaya tidak bentrok
    $nama_baru = "sampul_" . time() . "_" . rand(100, 999) . "." . $ekstensi;
    $tujuan = __DIR__ . "/../uploads/" . $nama_baru;

    if (!move_uploaded_file($file["tmp_name"], $tujuan)) {
        return [false, "Gagal menyimpan file ke folder uploads."];
    }

    return [true, $nama_baru];
}

function hapusFileSampul($nama_file)
{
    $path = __DIR__ . "/../uploads/" . $nama_file;
    if ($nama_file != "" && file_exists($path)) {
        unlink($path);
    }
}
