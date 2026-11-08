# Mini Digital Library

Tugas Terstruktur Pemrograman Web (Sistem Informasi). Aplikasi CRUD data buku dengan login, session, cookie, dan upload sampul. Dibuat dengan PHP Native, MySQL (mysqli), dan Bootstrap 5.

Link video demo: (isi link YouTube di sini)

## Fitur

1. Login dengan form POST, password diverifikasi memakai `password_verify`.
2. Session untuk membatasi akses halaman. Kalau belum login, otomatis diarahkan ke halaman login.
3. Cookie "Ingat saya" untuk menyimpan username selama 7 hari.
4. Logout menghapus session dan cookie lalu kembali ke halaman login.
5. CRUD data buku (judul, kategori, penulis, tahun terbit, stok).
6. Upload sampul buku (JPG/PNG, maksimal 2MB), nama file disimpan di database dan file fisik disimpan di folder `uploads/`.
7. Status buku ditentukan dengan if-else: stok lebih dari 0 berarti "Tersedia", stok 0 berarti "Dipinjam".
8. Hapus buku ikut menghapus file sampul di folder `uploads/`, dan ganti sampul saat edit menghapus file lama.

## Cara Menjalankan

1. Pindahkan folder project ke `htdocs` (XAMPP) atau `www` (Laragon).
2. Nyalakan Apache dan MySQL.
3. Buka phpMyAdmin, lalu import file `db_perpustakaan.sql`.
4. Cek pengaturan di `config/koneksi.php` kalau user atau password MySQL kamu berbeda.
5. Buka `http://localhost/project_perpustakaan/` di browser.
6. Login dengan username `admin` dan password `password`.

## Struktur File

```
project_perpustakaan/
├── assets/style.css
├── config/
│   ├── koneksi.php        koneksi database, session, daftar kategori
│   └── upload.php         fungsi upload dan hapus file sampul
├── uploads/               folder penampung sampul buku
├── index.php              halaman login
├── proses_login.php       verifikasi login, session, cookie
├── logout.php             hapus session dan cookie
├── dashboard.php          tampil data (read)
├── tambah_buku.php        form tambah
├── proses_tambah.php      insert dan upload file
├── edit_buku.php          form edit
├── proses_edit.php        update dan ganti file
├── hapus_buku.php         delete dan unlink file
└── db_perpustakaan.sql    ekspor database
```

## Struktur Tabel Database

Tabel `users`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | INT, PK, auto increment | |
| username | VARCHAR(50), unique | |
| password | VARCHAR(255) | hash bcrypt |
| nama_lengkap | VARCHAR(100) | |

Tabel `buku`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | INT, PK, auto increment | |
| judul | VARCHAR(150) | |
| kategori | VARCHAR(50) | |
| penulis | VARCHAR(100) | |
| tahun_terbit | YEAR | |
| stok | INT | status dihitung dari stok |
| sampul | VARCHAR(255) | nama file di folder uploads |

## Screenshot

Taruh screenshot di folder `screenshots/` lalu sesuaikan nama filenya di bawah ini.

Halaman login

![Login](screenshots/login.png)

Dashboard

![Dashboard](screenshots/dashboard.png)

Tambah buku

![Tambah](screenshots/tambah.png)

Edit buku

![Edit](screenshots/edit.png)

Struktur tabel di phpMyAdmin

![Struktur](screenshots/struktur_tabel.png)
