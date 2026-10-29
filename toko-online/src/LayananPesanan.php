<?php
namespace Src;

class LayananPesanan
{
    public function proses(Pesanan $pesanan): void
    {
        if ($pesanan->getStatus() !== 'Menunggu') {
            throw new \LogicException('Pesanan sudah diproses.');
        }
        $detail = $pesanan->getDetailPesanan();
        if (count($detail) === 0) {
            throw new \LogicException('Pesanan tidak boleh kosong.');
        }
        // Validasi seluruh kebutuhan stok sebelum mengurangi stok mana pun.
        $kebutuhan = [];
        foreach ($detail as $item) {
            $kode = $item->getProduk()->getKodeProduk();
            if (!isset($kebutuhan[$kode])) {
                $kebutuhan[$kode] = ['produk' => $item->getProduk(), 'jumlah' => 0];
            }
            if ($kebutuhan[$kode]['produk'] !== $item->getProduk()) {
                throw new \LogicException('Kode produk duplikat untuk objek berbeda.');
            }
            $kebutuhan[$kode]['jumlah'] += $item->getJumlah();
        }
        foreach ($kebutuhan as $data) {
            if ($data['jumlah'] > $data['produk']->cekStok()) {
                throw new \InvalidArgumentException('Stok ' . $data['produk']->getNamaProduk() . ' tidak mencukupi.');
            }
        }
        foreach ($kebutuhan as $data) {
            $data['produk']->kurangiStok($data['jumlah']);
        }
        $pesanan->ubahStatus('Diproses');
    }
}
