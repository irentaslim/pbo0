<?php
require 'fungsi.php';
session_start();

$message = '';
$searchResults = [];

if (!isset($_SESSION['daftarBuku'])) {
    $_SESSION['daftarBuku'] = [
        new Novel('B001', 'Belajar PHP', 2),
        new BukuIT('B002', 'Dasar Pemrograman Java', 3)
    ];
}

if (!isset($_SESSION['daftarAnggota'])) {
    $_SESSION['daftarAnggota'] = [
        'A001' => new MahasiswaBiasa(
            'A001', 'Pierre Tristan LR', 'bob', 'rahasia123', '21051001', 2021
        )
    ];
}

if (!isset($_SESSION['daftarPetugas'])) {
    $_SESSION['daftarPetugas'] = [
        'P001' => new Petugas('P001', 'Iren MT', 'iren', 'irenmaca')
    ];
}

if (!isset($_SESSION['daftarTransaksi'])) {
    $_SESSION['daftarTransaksi'] = [];
}

function cariBukuById($idBuku) {
    foreach ($_SESSION['daftarBuku'] as $buku) {
        if ($buku->getId() === $idBuku) {
            return $buku;
        }
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';

    if ($aksi === 'login_anggota') {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $berhasil = false;

        foreach ($_SESSION['daftarAnggota'] as $id => $anggota) {
            if ($anggota->login($username, $password)) {
                $_SESSION['anggotaLogin'] = $id;
                unset($_SESSION['petugasLogin']);
                $message = 'Login anggota berhasil. Selamat datang, ' . $anggota->getNama() . '.';
                $berhasil = true;
                break;
            }
        }

        if (!$berhasil) {
            $message = 'Username atau password anggota salah.';
        }
    }

    elseif ($aksi === 'login_petugas') {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $berhasil = false;

        foreach ($_SESSION['daftarPetugas'] as $id => $petugas) {
            if ($petugas->login($username, $password)) {
                $_SESSION['petugasLogin'] = $id;
                unset($_SESSION['anggotaLogin']);
                $message = 'Login petugas berhasil.';
                $berhasil = true;
                break;
            }
        }

        if (!$berhasil) {
            $message = 'Username atau password petugas salah.';
        }
    }

    elseif ($aksi === 'logout') {
        unset($_SESSION['anggotaLogin'], $_SESSION['petugasLogin']);
        $message = 'Logout berhasil.';
    }

    elseif ($aksi === 'pinjam_buku') {
        if (!isset($_SESSION['anggotaLogin'])) {
            $message = 'Silakan login sebagai anggota terlebih dahulu.';
        } else {
            $idBuku = trim($_POST['id_buku'] ?? '');
            $idAnggota = $_SESSION['anggotaLogin'];
            $anggota = $_SESSION['daftarAnggota'][$idAnggota];
            $buku = cariBukuById($idBuku);

            if (!$buku) {
                $message = 'Buku dengan ID ' . htmlspecialchars($idBuku) . ' tidak ditemukan.';
            } elseif ($buku->cekStok() <= 0) {
                $message = 'Stok buku sedang habis.';
            } else {
                $transaksi = $anggota->pinjamBuku($buku);

                if ($transaksi !== null) {
                    foreach ($_SESSION['daftarBuku'] as $key => $item) {
                        if ($item->getId() === $idBuku) {
                            $_SESSION['daftarBuku'][$key] = $buku;
                            break;
                        }
                    }

                    $_SESSION['daftarAnggota'][$idAnggota] = $anggota;
                    $_SESSION['daftarTransaksi'][$transaksi->getId()] = $transaksi;

                    $message = 'Peminjaman berhasil. ID transaksi: ' . $transaksi->getId()
                        . ' | Jatuh tempo: ' . $transaksi->getTanggalJatuhTempo();
                }
            }
        }
    }

    elseif ($aksi === 'kembalikan_buku') {
        if (!isset($_SESSION['anggotaLogin'])) {
            $message = 'Silakan login sebagai anggota terlebih dahulu.';
        } else {
            $idTransaksi = trim($_POST['id_transaksi'] ?? '');
            $tanggalKembali = $_POST['tanggal_kembali'] ?? '';
            $transaksi = $_SESSION['daftarTransaksi'][$idTransaksi] ?? null;

            if (!$transaksi) {
                $message = 'ID transaksi tidak ditemukan.';
            } elseif ($tanggalKembali === '') {
                $message = 'Tanggal pengembalian harus diisi.';
            } elseif ($transaksi->getStatus() !== 'dipinjam') {
                $message = 'Transaksi tersebut sudah selesai diproses.';
            } else {
                $anggota = $_SESSION['daftarAnggota'][$_SESSION['anggotaLogin']];
                $anggota->kembalikanBuku($transaksi, $tanggalKembali);
                $bukuTransaksi = $transaksi->getBuku();

                foreach ($_SESSION['daftarBuku'] as $key => $item) {
                    if ($item->getId() === $bukuTransaksi->getId()) {
                        $_SESSION['daftarBuku'][$key] = $bukuTransaksi;
                        break;
                    }
                }

                $_SESSION['daftarAnggota'][$_SESSION['anggotaLogin']] = $anggota;
                $_SESSION['daftarTransaksi'][$idTransaksi] = $transaksi;

                $message = 'Pengembalian berhasil. Status: ' . $transaksi->getStatus()
                    . ' | Denda: Rp' . number_format($transaksi->getDenda(), 0, ',', '.');
            }
        }
    }

    elseif ($aksi === 'tambah_buku') {
        if (!isset($_SESSION['petugasLogin'])) {
            $message = 'Silakan login sebagai petugas terlebih dahulu.';
        } else {
            $id = trim($_POST['id_buku'] ?? '');
            $judul = trim($_POST['judul'] ?? '');
            $stok = (int)($_POST['stok'] ?? 0);
            $jenis = $_POST['jenis_buku'] ?? 'novel';

            if ($id === '' || $judul === '' || $stok <= 0) {
                $message = 'Data buku belum lengkap atau stok tidak valid.';
            } elseif (cariBukuById($id)) {
                $message = 'ID buku sudah digunakan.';
            } else {
                $bukuBaru = ($jenis === 'buku_it')
                    ? new BukuIT($id, $judul, $stok)
                    : new Novel($id, $judul, $stok);

                $petugas = $_SESSION['daftarPetugas'][$_SESSION['petugasLogin']];
                if ($petugas->kelolaBuku('tambah', $bukuBaru)) {
                    $_SESSION['daftarBuku'][] = $bukuBaru;
                    $message = 'Buku berhasil ditambahkan.';
                } else {
                    $message = 'Buku gagal ditambahkan.';
                }
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($_GET['aksi'] ?? '') === 'cari') {
    $kataKunci = trim($_GET['kata_kunci'] ?? '');

    if ($kataKunci === '') {
        $message = 'Masukkan kata kunci pencarian.';
    } else {
        $katalog = new KatalogBuku();
        foreach ($_SESSION['daftarBuku'] as $buku) {
            $katalog->tambahKeKatalog($buku);
        }
        $searchResults = $katalog->cariBuku($kataKunci);
        $message = count($searchResults) . ' buku ditemukan.';
    }
}

$anggotaLogin = null;
if (isset($_SESSION['anggotaLogin'])) {
    $anggotaLogin = $_SESSION['daftarAnggota'][$_SESSION['anggotaLogin']];
}

$petugasLogin = null;
if (isset($_SESSION['petugasLogin'])) {
    $petugasLogin = $_SESSION['daftarPetugas'][$_SESSION['petugasLogin']];
}

function statusBuku($buku) {
    return $buku->cekStok() > 0 ? 'Tersedia' : 'Habis';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistem Perpustakaan</title>
<style>
*{box-sizing:border-box;font-family:Arial,sans-serif}body{margin:0;background:#f4f6f8;color:#222;padding:30px}.container{max-width:1000px;margin:auto}.header{margin-bottom:22px}.header h1{margin-bottom:6px}.header p{color:#666}.card{background:#fff;border-radius:12px;padding:20px;margin:16px 0;box-shadow:0 3px 12px rgba(0,0,0,.08)}h2{margin:0 0 15px;font-size:19px}.grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}.full{grid-column:1/-1}label{display:block;font-weight:bold;font-size:14px;margin-bottom:6px}input,select{width:100%;padding:10px;border:1px solid #ccc;border-radius:8px}button{padding:10px 15px;border:0;border-radius:8px;background:#222;color:#fff;font-weight:bold;cursor:pointer}.message{background:#eef3f7;border-radius:8px;padding:12px;margin:14px 0}table{width:100%;border-collapse:collapse}th,td{text-align:left;padding:10px;border-bottom:1px solid #ddd}th{background:#f7f7f7}.login-state{margin:10px 0;color:#444}.note{font-size:13px;color:#666;margin-top:8px}@media(max-width:700px){.grid{grid-template-columns:1fr}.full{grid-column:auto}body{padding:15px}}
</style>
</head>
<body>
<div class="container">
<div class="header"><h1>Sistem Perpustakaan</h1><p>Mini Project PBO</p></div>

<?php if ($message !== ''): ?><div class="message"><?= htmlspecialchars($message) ?></div><?php endif; ?>

<div class="login-state">
<?php if ($anggotaLogin): ?>Login sebagai anggota: <strong><?= htmlspecialchars($anggotaLogin->getNama()) ?></strong>
<?php elseif ($petugasLogin): ?>Login sebagai petugas: <strong><?= htmlspecialchars($_SESSION['petugasLogin']) ?></strong>
<?php else: ?>Belum ada pengguna yang login.<?php endif; ?>
</div>

<div class="card"><h2>Login Anggota</h2><form method="POST"><input type="hidden" name="aksi" value="login_anggota"><div class="grid"><div><label>Username</label><input type="text" name="username" placeholder="Username" required></div><div><label>Password</label><input type="password" name="password" placeholder="Password" required></div><div class="full"><button type="submit">Login Anggota</button></div></div></form></div>

<div class="card"><h2>Login Petugas</h2><form method="POST"><input type="hidden" name="aksi" value="login_petugas"><div class="grid"><div><label>Username</label><input type="text" name="username" placeholder="Username" required></div><div><label>Password</label><input type="password" name="password" placeholder="Passsword" required></div><div class="full"><button type="submit">Login Petugas</button></div></div></form></div>

<div class="card"><h2>Daftar Buku</h2><table><thead><tr><th>ID</th><th>Judul</th><th>Stok</th><th>Status</th></tr></thead><tbody><?php foreach ($_SESSION['daftarBuku'] as $buku): ?><tr><td><?= htmlspecialchars($buku->getId()) ?></td><td><?= htmlspecialchars($buku->getJudul()) ?></td><td><?= $buku->cekStok() ?></td><td><?= statusBuku($buku) ?></td></tr><?php endforeach; ?></tbody></table></div>

<div class="card"><h2>Cari Buku</h2><form method="GET"><input type="hidden" name="aksi" value="cari"><div class="grid"><div class="full"><label>Kata kunci judul</label><input type="text" name="kata_kunci" placeholder="Contoh: PHP" required></div><div class="full"><button type="submit">Cari Buku</button></div></div></form><?php if (count($searchResults)>0): ?><br><table><thead><tr><th>ID</th><th>Judul</th><th>Stok</th></tr></thead><tbody><?php foreach ($searchResults as $buku): ?><tr><td><?= htmlspecialchars($buku->getId()) ?></td><td><?= htmlspecialchars($buku->getJudul()) ?></td><td><?= $buku->cekStok() ?></td></tr><?php endforeach; ?></tbody></table><?php endif; ?></div>

<div class="card"><h2>Pinjam Buku</h2><form method="POST"><input type="hidden" name="aksi" value="pinjam_buku"><div class="grid"><div class="full"><label>ID Buku</label><input type="text" name="id_buku" placeholder="B001" required></div><div class="full"><button type="submit">Pinjam Buku</button></div></div></form></div>

<div class="card"><h2>Kembalikan Buku</h2><form method="POST"><input type="hidden" name="aksi" value="kembalikan_buku"><div class="grid"><div><label>ID Transaksi</label><input type="text" name="id_transaksi" placeholder="ID transaksi" required></div><div><label>Tanggal Pengembalian</label><input type="date" name="tanggal_kembali" required></div><div class="full"><button type="submit">Kembalikan Buku</button></div></div></form></div>

<div class="card"><h2>Tambah Buku (Petugas)</h2><form method="POST"><input type="hidden" name="aksi" value="tambah_buku"><div class="grid"><div><label>ID Buku</label><input type="text" name="id_buku" placeholder="B003" required></div><div><label>Jenis</label><select name="jenis_buku"><option value="novel">Novel</option><option value="buku_it">Buku IT</option></select></div><div class="full"><label>Judul</label><input type="text" name="judul" placeholder="Judul buku" required></div><div><label>Stok</label><input type="number" name="stok" min="1" placeholder="5" required></div><div class="full"><button type="submit">Tambah Buku</button></div></div></form></div>

<div class="card"><form method="POST"><input type="hidden" name="aksi" value="logout"><button type="submit">Logout</button></form></div>
</div>
</body>
</html>