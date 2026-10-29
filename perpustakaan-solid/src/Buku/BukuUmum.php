<?php
class BukuUmum extends Buku implements DapatDipinjam
{
    public function hitungDenda(int $hariTerlambat): int
    {
        return $hariTerlambat * 1000;
    }
}
