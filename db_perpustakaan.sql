-- Database untuk Mini Digital Library
CREATE DATABASE IF NOT EXISTS db_perpustakaan;
USE db_perpustakaan;

DROP TABLE IF EXISTS buku;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  nama_lengkap VARCHAR(100) NOT NULL
);

CREATE TABLE buku (
  id INT AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(150) NOT NULL,
  kategori VARCHAR(50) NOT NULL,
  penulis VARCHAR(100) NOT NULL,
  tahun_terbit YEAR NOT NULL,
  stok INT NOT NULL DEFAULT 0,
  sampul VARCHAR(255) DEFAULT NULL
);

-- akun login: username admin, password "password" (disimpan dalam bentuk hash bcrypt)
INSERT INTO users (username, password, nama_lengkap) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator');

INSERT INTO buku (judul, kategori, penulis, tahun_terbit, stok, sampul) VALUES
('Laskar Pelangi', 'Fiksi', 'Andrea Hirata', 2005, 3, NULL),
('Bumi Manusia', 'Fiksi', 'Pramoedya Ananta Toer', 1980, 0, NULL),
('Pemrograman Web dengan PHP', 'Teknologi', 'Budi Raharjo', 2019, 2, NULL);
