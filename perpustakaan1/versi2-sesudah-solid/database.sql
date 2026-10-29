CREATE DATABASE perpustakaan;
USE perpustakaan;

CREATE TABLE buku (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255),
    penulis VARCHAR(255),
    stok INT DEFAULT 0
);

CREATE TABLE anggota (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255),
    email VARCHAR(255)
);

CREATE TABLE peminjaman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_buku INT,
    id_anggota INT,
    tanggal DATETIME
);

INSERT INTO anggota (nama, email)
VALUES ('Budi', 'budi@example.com');
