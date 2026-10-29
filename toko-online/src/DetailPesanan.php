<?php
namespace Src;

class DetailPesanan
{
    private Produk $produk;
    private int $jumlah;
    private float $hargaSatuan;

    public function __construct(Produk $produk, int $jumlah)
    {
        if ($jumlah <= 0) {
            throw new \InvalidArgumentException('Jumlah harus lebih dari nol.');
        }
        $this->produk = $produk;
        $this->jumlah = $jumlah;
        $this->hargaSatuan = $produk->getHarga();
    }

    public function getProduk(): Produk { return $this->produk; }
    public function getJumlah(): int { return $this->jumlah; }
    public function getHargaSatuan(): float { return $this->hargaSatuan; }
    public function hitungSubtotal(): float { return $this->hargaSatuan * $this->jumlah; }
}
