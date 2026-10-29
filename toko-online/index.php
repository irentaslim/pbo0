<?php
spl_autoload_register(function (string $class): void {
    $prefix = 'Src\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    $namaFile = __DIR__ . '/src/' . substr($class, strlen($prefix)) . '.php';
    if (is_file($namaFile)) {
        require_once $namaFile;
    }
});

use Src\Produk;
use Src\Pelanggan;
use Src\LayananPesanan;

function aman(string $teks): string
{
    return htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
}
function rupiah(float $nilai): string
{
    return 'Rp ' . number_format($nilai, 0, ',', '.');
}

$produk1 = new Produk('PRD001', 'Keyboard', 150000, 10);
$produk2 = new Produk('PRD002', 'Mouse', 75000, 15);
$produk3 = new Produk('PRD003', 'Headset', 200000, 8);
$pelanggan = new Pelanggan('PLG001', 'Andi', 'andi@example.com', 'Pontianak');
$pesanan = $pelanggan->buatPesanan('PSN001');
$pesanan->tambahProduk($produk1, 2);
$pesanan->tambahProduk($produk2, 1);
$pesanan->tambahProduk($produk3, 1);
$layanan = new LayananPesanan();
$pesanError = '';
try {
    $layanan->proses($pesanan);
} catch (Throwable $error) {
    $pesanError = $error->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simulasi Toko Online</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6fa; color: #222; padding: 25px; }
        main { max-width: 850px; margin: auto; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 12px #0001; }
        h1 { color: #173e68; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #173e68; color: white; }
        .total { font-weight: bold; text-align: right; margin-top: 20px; }
        .status { color: #176b37; font-weight: bold; }
        .error { color: #a32424; }
    </style>
</head>
<body>
<main>
    <h1>Simulasi Penjualan Toko Online</h1>
    <h2>Informasi Pelanggan</h2>
    <p>ID: <?= aman($pelanggan->getIdPelanggan()) ?></p>
    <p>Nama: <?= aman($pelanggan->getNama()) ?></p>
    <p>Email: <?= aman($pelanggan->getEmail()) ?></p>
    <p>Alamat: <?= aman($pelanggan->getAlamat()) ?></p>
    <h2>Detail Pesanan</h2>
    <p>Nomor: <?= aman($pesanan->getNomorPesanan()) ?></p>
    <p>Tanggal: <?= aman($pesanan->getTanggalPesanan()) ?></p>
    <p>Status: <span class="status"><?= aman($pesanan->getStatus()) ?></span></p>
    <?php if ($pesanError !== ''): ?>
        <p class="error">Gagal memproses: <?= aman($pesanError) ?></p>
    <?php endif; ?>
    <table>
        <tr><th>Produk</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th></tr>
        <?php foreach ($pesanan->getDetailPesanan() as $detail): ?>
            <tr>
                <td><?= aman($detail->getProduk()->getNamaProduk()) ?></td>
                <td><?= rupiah($detail->getHargaSatuan()) ?></td>
                <td><?= $detail->getJumlah() ?></td>
                <td><?= rupiah($detail->hitungSubtotal()) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <p class="total">Total: <?= rupiah($pesanan->hitungTotal()) ?></p>
    <h2>Stok Setelah Transaksi</h2>
    <?php foreach ([$produk1, $produk2, $produk3] as $produk): ?>
        <p><?= aman($produk->getNamaProduk()) ?>: <?= $produk->cekStok() ?> unit</p>
    <?php endforeach; ?>
</main>
</body>
</html>
