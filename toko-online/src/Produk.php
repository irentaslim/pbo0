<?php
namespace Src;

use InvalidArgumentException;

class Produk
{
    private string $kodeProduk;
    private string $namaProduk;
    private float $harga;
    private int $stok;

    public function __construct(string $kodeProduk, string $namaProduk, float $harga, int $stok)
    {
        if ($harga < 0 || $stok < 0) {
            throw new InvalidArgumentException('Harga dan stok tidak boleh negatif.');
        }
        $this->kodeProduk = $kodeProduk;
        $this->namaProduk = $namaProduk;
        $this->harga = $harga;
        $this->stok = $stok;
    }

    public function getKodeProduk(): string { return $this->kodeProduk; }
    public function getNamaProduk(): string { return $this->namaProduk; }
    public function getHarga(): float { return $this->harga; }
    public function cekStok(): int { return $this->stok; }

    public function kurangiStok(int $jumlah): void
    {
        if ($jumlah <= 0 || $jumlah > $this->stok) {
            throw new InvalidArgumentException('Jumlah pembelian tidak valid atau stok tidak cukup untuk ' . $this->namaProduk);
        }
        $this->stok -= $jumlah;
    }
}
