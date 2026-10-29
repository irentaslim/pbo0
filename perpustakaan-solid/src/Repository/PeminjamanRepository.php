<?php
class PeminjamanRepository
{
    public function __construct(private DatabaseConnectionInterface $db) {}

    public function simpan(int $idBuku, int $idAnggota, int $lamaPinjamHari): void
    {
        $pinjam = new DateTimeImmutable();
        $kembali = $pinjam->modify("+{$lamaPinjamHari} days");
        $this->db->eksekusi(
            'INSERT INTO peminjaman (id_buku, id_anggota, tgl_pinjam, tgl_kembali) VALUES (?, ?, ?, ?)',
            [$idBuku, $idAnggota, $pinjam->format('Y-m-d'), $kembali->format('Y-m-d')]
        );
    }
}