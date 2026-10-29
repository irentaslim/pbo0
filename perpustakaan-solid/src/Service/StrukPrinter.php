<?php
class StrukPrinter
{
    public function cetak(string $judul, int $lamaPinjam, int $tarifDenda): void
    {
        echo "=== STRUK PEMINJAMAN ===\n";
        echo "Judul: {$judul}\n";
        echo "Lama pinjam: {$lamaPinjam} hari\n";
        echo "Tarif denda: Rp {$tarifDenda} / hari\n";
    }
}