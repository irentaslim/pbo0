<?php
session_start();

require_once __DIR__ . '/Product.php';

/* ---------- Helper ---------- */
function e(string $teks): string
{
    return htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
}

function redirect(string $pesan, string $tipe = 'ok'): void
{
    $_SESSION['flash'] = ['pesan' => $pesan, 'tipe' => $tipe];
    header('Location: index.php');
    exit;
}

/* ---------- Proses form (Create, Update, Delete) ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    $id   = (int) ($_POST['id'] ?? 0);

    if ($aksi === 'hapus') {
        Product::delete($id);
        redirect('Produk berhasil dihapus.');
    }

    // Validasi input untuk tambah / ubah
    $nama  = trim($_POST['nama'] ?? '');
    $harga = $_POST['harga'] ?? '';
    $stok  = $_POST['stok'] ?? '';

    if ($nama === '' || !is_numeric($harga) || $harga < 0 || !ctype_digit((string) $stok)) {
        redirect('Data tidak valid. Pastikan nama terisi, harga dan stok berupa angka.', 'error');
    }

    $produk = new Product($nama, (float) $harga, (int) $stok);

    if ($aksi === 'tambah') {
        $produk->save();
        redirect('Produk berhasil ditambahkan.');
    }

    if ($aksi === 'ubah') {
        $produk->update($id);
        redirect('Produk berhasil diperbarui.');
    }
}

/* ---------- Siapkan data tampilan ---------- */
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$editId   = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;
$diedit   = $editId ? Product::findById($editId) : null;   // findById dipakai di sini
$daftar   = Product::findAll();                             // findAll dipakai di sini
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Produk (PDO)</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f3f5f9; margin: 0; padding: 24px; color: #222; }
        .wrap { max-width: 860px; margin: auto; }
        h1 { margin-top: 0; }
        .card { background: #fff; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
        label { display: block; font-size: 14px; margin: 10px 0 4px; }
        input[type=text], input[type=number] { width: 100%; padding: 9px; border: 1px solid #ccd; border-radius: 6px; }
        .row { display: flex; gap: 12px; }
        .row > div { flex: 1; }
        button, .btn { display: inline-block; padding: 8px 14px; border: 0; border-radius: 6px; cursor: pointer;
                       font-size: 14px; text-decoration: none; color: #fff; }
        .primary { background: #1a3cff; } .warn { background: #e69500; } .danger { background: #d62d2d; } .gray { background: #777; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border-bottom: 1px solid #e5e8ef; text-align: left; font-size: 14px; }
        th { background: #f0f2f8; }
        .aksi form { display: inline; }
        .flash { padding: 10px 14px; border-radius: 6px; margin-bottom: 16px; }
        .flash.ok { background: #dff5e3; color: #1d6b2e; } .flash.error { background: #fde1e1; color: #8f1d1d; }
    </style>
</head>
<body>
<div class="wrap">
    <h1>Sistem Manajemen Produk</h1>

    <?php if ($flash): ?>
        <div class="flash <?= e($flash['tipe']) ?>"><?= e($flash['pesan']) ?></div>
    <?php endif; ?>

    <!-- FORM TAMBAH / UBAH -->
    <div class="card">
        <h2><?= $diedit ? 'Ubah Produk' : 'Tambah Produk' ?></h2>
        <form method="post" action="index.php">
            <input type="hidden" name="aksi" value="<?= $diedit ? 'ubah' : 'tambah' ?>">
            <?php if ($diedit): ?>
                <input type="hidden" name="id" value="<?= $diedit->id ?>">
            <?php endif; ?>

            <label for="nama">Nama Produk</label>
            <input type="text" id="nama" name="nama" required value="<?= $diedit ? e($diedit->nama) : '' ?>">

            <div class="row">
                <div>
                    <label for="harga">Harga (Rp)</label>
                    <input type="number" id="harga" name="harga" min="0" step="100" required
                           value="<?= $diedit ? $diedit->harga : '' ?>">
                </div>
                <div>
                    <label for="stok">Stok</label>
                    <input type="number" id="stok" name="stok" min="0" required
                           value="<?= $diedit ? $diedit->stok : '' ?>">
                </div>
            </div>

            <p>
                <button type="submit" class="primary"><?= $diedit ? 'Simpan Perubahan' : 'Tambah' ?></button>
                <?php if ($diedit): ?><a href="index.php" class="btn gray">Batal</a><?php endif; ?>
            </p>
        </form>
    </div>

    <!-- TABEL DATA -->
    <div class="card">
        <h2>Daftar Produk</h2>
        <table>
            <thead>
                <tr><th>ID</th><th>Nama</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            <?php if (!$daftar): ?>
                <tr><td colspan="5">Belum ada data produk.</td></tr>
            <?php endif; ?>
            <?php foreach ($daftar as $p): ?>
                <tr>
                    <td><?= $p->id ?></td>
                    <td><?= e($p->nama) ?></td>
                    <td>Rp <?= number_format($p->harga, 0, ',', '.') ?></td>
                    <td><?= $p->stok ?></td>
                    <td class="aksi">
                        <a href="index.php?edit=<?= $p->id ?>" class="btn warn">Ubah</a>
                        <form method="post" action="index.php" onsubmit="return confirm('Hapus produk ini?');">
                            <input type="hidden" name="aksi" value="hapus">
                            <input type="hidden" name="id" value="<?= $p->id ?>">
                            <button type="submit" class="danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
