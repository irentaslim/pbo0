<?php
class BukuRepository
{
    public function __construct(private DatabaseConnectionInterface $db) {}

    public function cariById(int $id): Buku
    {
        $baris = $this->db->ambilSatu('SELECT judul, jenis FROM buku WHERE id = ?', [$id]);
        if ($baris === null) {
            throw new RuntimeException("Buku dengan id $id tidak ditemukan");
        }
        return $baris['jenis'] === 'referensi'
            ? new BukuReferensi($baris['judul'])
            : new BukuUmum($baris['judul']);
    }
}