<?php
namespace Src;

class Pesanan implements HitungTotalInterface
{
    private string $nomorPesanan;
    private string $tanggalPesanan;
    private string $status = 'Menunggu';
    private Pelanggan $pelanggan;
    /** @var DetailPesanan[] */
    private array $detailPesanan = [];

    public function __construct(string $nomorPesanan, string $tanggalPesanan, Pelanggan $pelanggan)
    {
        $this->nomorPesanan = $nomorPesanan;
        $this->tanggalPesanan = $tanggalPesanan;
        $this->pelanggan = $pelanggan;
    }

    public function getNomorPesanan(): string { return $this->nomorPesanan; }
    public function getTanggalPesanan(): string { return $this->tanggalPesanan; }
    public function getStatus(): string { return $this->status; }
    public function getPelanggan(): Pelanggan { return $this->pelanggan; }
    public function getDetailPesanan(): array { return $this->detailPesanan; }

    public function tambahProduk(Produk $produk, int $jumlah): void
    {
        if ($this->status !== 'Menunggu') {
            throw new \LogicException('Pesanan yang diproses tidak dapat diubah.');
        }
        $detail = new DetailPesanan($produk, $jumlah);
        $totalJumlah = $jumlah;
        foreach ($this->detailPesanan as $item) {
            if ($item->getProduk() === $produk) {
                $totalJumlah += $item->getJumlah();
            }
        }
        if ($totalJumlah > $produk->cekStok()) {
            throw new \InvalidArgumentException('Stok ' . $produk->getNamaProduk() . ' tidak cukup.');
        }
        $this->detailPesanan[] = $detail;
    }

    public function hitungTotal(): float
    {
        $total = 0;
        foreach ($this->detailPesanan as $detail) {
            $total += $detail->hitungSubtotal();
        }
        return $total;
    }

    public function ubahStatus(string $status): void
    {
        if (!in_array($status, ['Menunggu', 'Diproses'], true)) {
            throw new \InvalidArgumentException('Status tidak valid.');
        }
        if ($this->status === 'Diproses') {
            throw new \LogicException('Pesanan sudah diproses.');
        }
        $this->status = $status;
    }
}
