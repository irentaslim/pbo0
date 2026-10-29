<?php
class AnggotaRepository
{
    public function __construct(private DatabaseConnectionInterface $db) {}

    public function cariEmail(int $id): string
    {
        $baris = $this->db->ambilSatu('SELECT email FROM anggota WHERE id = ?', [$id]);
        if ($baris === null) {
            throw new RuntimeException("Anggota dengan id $id tidak ditemukan");
        }
        return $baris['email'];
    }
}