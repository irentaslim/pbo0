<?php
// src/Perpustakaan.php
namespace App;

use PDO;

class Perpustakaan
{
    private PDO $pdo;

    public function __construct()
    {
        // ⚠️ Hardcode database
        $this->pdo = new PDO(
            'mysql:host=localhost;dbname=perpustakaan',
            'root',
            'rahasia'
        );
    }

    // ============================================
    // TANGGUNG JAWAB 1: Tambah buku
    // ============================================
    public function tambahBuku(string $judul, string $penulis): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO buku (judul, penulis) VALUES (?, ?)"
        );
        return $stmt->execute([$judul, $penulis]);
    }

    // ============================================
    // TANGGUNG JAWAB 2: Tambah anggota
    // ============================================
    public function tambahAnggota(string $nama, string $email): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO anggota (nama, email) VALUES (?, ?)"
        );
        return $stmt->execute([$nama, $email]);
    }

    // ============================================
    // TANGGUNG JAWAB 3: Peminjaman
    // ============================================
    public function pinjamBuku(int $idBuku, int $idAnggota): bool
    {
        // Cek buku tersedia
        $stmt = $this->pdo->prepare(
            "SELECT stok FROM buku WHERE id = ?"
        );
        $stmt->execute([$idBuku]);
        $buku = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$buku || $buku['stok'] <= 0) {
            return false;
        }

        // Simpan peminjaman
        $stmt = $this->pdo->prepare(
            "INSERT INTO peminjaman (id_buku, id_anggota, tanggal)
             VALUES (?, ?, NOW())"
        );
        $stmt->execute([$idBuku, $idAnggota]);

        // Kurangi stok
        $stmt = $this->pdo->prepare(
            "UPDATE buku SET stok = stok - 1 WHERE id = ?"
        );
        $stmt->execute([$idBuku]);

        // ⚠️ Kirim email langsung di dalam method
        $stmt = $this->pdo->prepare(
            "SELECT email FROM anggota WHERE id = ?"
        );
        $stmt->execute([$idAnggota]);
        $anggota = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($anggota) {
            mail(
                $anggota['email'],
                'Peminjaman Berhasil',
                'Anda telah meminjam buku. Terima kasih!'
            );
        }

        // ⚠️ Log langsung di dalam method
        file_put_contents(
            __DIR__ . '/../logs/perpustakaan.log',
            date('[Y-m-d H:i:s] ') . "Peminjaman buku ID $idBuku\n",
            FILE_APPEND
        );

        return true;
    }

    // ============================================
    // TANGGUNG JAWAB 4: Daftar buku
    // ============================================
    public function daftarBuku(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM buku");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
