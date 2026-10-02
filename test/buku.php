<?php
session_start();

// ===============================
// CLASS DEFINITIONS
// (sama persis seperti kode sebelumnya, tidak diubah)
// ===============================

abstract class Anggota {
    private $id;
    private $nama;
    private $username;
    private $password;
    private $denda = 0;

    abstract public function getLamaPeminjaman();

    public function __construct($id, $nama, $username, $password) {
        $this->id = $id;
        $this->nama = $nama;
        $this->username = $username;
        $this->password = $password;
    }

    public function getId() {
        return $this->id;
    }

    public function getNama() {
        return $this->nama;
    }

    public function login($username, $password) {
        if ($this->username === $username && $this->password === $password) {
            echo "Login berhasil. Selamat datang, " . $this->nama . "<br>";
            return true;
        }
        echo "Username atau password salah, silahkan coba lagi.<br>";
        return false;
    }

    public function pinjamBuku(Buku $buku) {
        if ($buku->cekStok() > 0) {
            $buku->kurangiStok();
            $tanggalSekarang = date('Y-m-d');
            $transaksi = new Transaksi(uniqid(), $this, $buku, $tanggalSekarang);
            echo "Buku '" . $buku->getJudul() . "' berhasil dipinjam. Tanggal pinjam: $tanggalSekarang. Tanggal jatuh tempo: " . $transaksi->getTanggalJatuhTempo();
            return $transaksi;
        } else {
            echo "Mohon maaf, stok buku '" . $buku->getJudul() . "' sedang habis.";
            return null;
        }
    }

    public function kembalikanBuku(Transaksi $transaksi, $tanggalDikembalikan) {
        $transaksi->setTanggalDikembalikan($tanggalDikembalikan);

        $jatuhTempo = strtotime($transaksi->getTanggalJatuhTempo());
        $kembali = strtotime($tanggalDikembalikan);

        if ($kembali > $jatuhTempo) {
            $transaksi->setStatus("terlambat");
            $jumlahHariTelat = ceil(($kembali - $jatuhTempo) / (60 * 60 * 24));
            $transaksi->setJumlahHariTelat($jumlahHariTelat);
            $denda = $this->hitungDenda($jumlahHariTelat);
            $transaksi->setDenda($denda);
            echo "Buku '" . $transaksi->getBuku()->getJudul() . "' dikembalikan terlambat. Jumlah hari telat: $jumlahHariTelat. Denda: Rp$denda.";
        } else {
            $transaksi->setStatus("dikembalikan");
            echo "Buku '" . $transaksi->getBuku()->getJudul() . "' dikembalikan tepat waktu. Terima kasih!";
        }
        $transaksi->getBuku()->tambahStok();
    }

    protected function hitungDenda($jumlahHariTelat) {
        $tarifPerHari = 500;
        $this->denda = $jumlahHariTelat * $tarifPerHari;
        return $this->denda;
    }
}

abstract class Mahasiswa extends Anggota {
    private $nim;
    private $angkatan;

    public function __construct($id, $nama, $username, $password, $nim, $angkatan) {
        parent::__construct($id, $nama, $username, $password);
        $this->nim = $nim;
        $this->angkatan = $angkatan;
    }

    public function getNim() {
        return $this->nim;
    }
}

class MahasiswaBiasa extends Mahasiswa {
    public function getLamaPeminjaman() {
        return 7;
    }
}

class MahasiswaTingkatAkhir extends Mahasiswa {
    public function getLamaPeminjaman() {
        return 14;
    }
}

class Dosen extends Anggota {
    private $nip;

    public function __construct($id, $nama, $username, $password, $nip) {
        parent::__construct($id, $nama, $username, $password);
        $this->nip = $nip;
    }

    public function getLamaPeminjaman() {
        return 7;
    }
}

class Petugas {
    private $id;
    private $nama;
    private $username;
    private $password;

    public function __construct($id, $nama, $username, $password) {
        $this->id = $id;
        $this->nama = $nama;
        $this->username = $username;
        $this->password = $password;
    }

    public function login($username, $password) {
        if ($this->username === $username && $this->password === $password) {
            echo "Login berhasil sebagai petugas. Selamat datang, " . $this->nama . "<br>";
            return true;
        }
        echo "Username atau password salah.<br>";
        return false;
    }

    public function kelolaBuku($aksi, Buku $buku) {
        switch ($aksi) {
            case "tambah":
                echo "Buku baru '" . $buku->getJudul() . "' berhasil ditambahkan ke koleksi.<br>";
                break;
            case "edit":
                echo "Data buku '" . $buku->getJudul() . "' berhasil diperbaharui.<br>";
                break;
            case "hapus":
                echo "Buku '" . $buku->getJudul() . "' berhasil dihapus dari koleksi.<br>";
                break;
            default:
                echo "Aksi tidak dikenali.<br>";
        }
    }

    public function kelolaAnggota($aksi, Anggota $anggota) {
        switch ($aksi) {
            case "tambah":
                echo "Anggota '" . $anggota->getNama() . "' berhasil didaftarkan.<br>";
                break;
            case "edit":
                echo "Data anggota '" . $anggota->getNama() . "' berhasil diperbaharui.<br>";
                break;
            case "hapus":
                echo "Anggota '" . $anggota->getNama() . "' berhasil dihapus dari sistem.<br>";
                break;
            default:
                echo "Aksi tidak dikenali.<br>";
        }
    }
}

abstract class Buku {
    private $id;
    private $judul;
    private $stok;

    public function __construct($id, $judul, $stok) {
        $this->id = $id;
        $this->judul = $judul;
        $this->stok = $stok;
    }

    public function getJudul() {
        return $this->judul;
    }

    public function getId() {
        return $this->id;
    }

    public function cekStok() {
        return $this->stok;
    }

    public function kurangiStok() {
        if ($this->stok > 0) {
            $this->stok--;
            return true;
        }
        return false;
    }

    public function tambahStok() {
        $this->stok++;
    }
}

class Novel extends Buku {
    private $penulis;
    private $genre;
}

class BukuIT extends Buku {
    private $bahasaPemrograman;
    private $edisi;
}

class KatalogBuku {
    private $daftarBuku = [];

    public function tambahKeKatalog(Buku $buku) {
        $this->daftarBuku[] = $buku;
    }

    public function cariBuku($kataKunci) {
        $hasil = [];
        foreach ($this->daftarBuku as $buku) {
            if (stripos($buku->getJudul(), $kataKunci) !== false) {
                $hasil[] = $buku;
            }
        }
        return $hasil;
    }

    public function getSemuaBuku() {
        return $this->daftarBuku;
    }
}

class Transaksi {
    private $idTransaksi;
    private $anggota;
    private $buku;
    private $tanggalPinjam;
    private $tanggalJatuhTempo;
    private $tanggalDikembalikan;
    private $status;
    private $jumlahHariTelat;
    private $denda;

    public function __construct($idTransaksi, Anggota $anggota, Buku $buku, $tanggalPinjam) {
        $this->idTransaksi = $idTransaksi;
        $this->anggota = $anggota;
        $this->buku = $buku;
        $this->tanggalPinjam = $tanggalPinjam;

        $lamaPinjam = $anggota->getLamaPeminjaman();
        $this->tanggalJatuhTempo = date('Y-m-d', strtotime("+$lamaPinjam days", strtotime($tanggalPinjam)));

        $this->status = "dipinjam";
        $this->jumlahHariTelat = 0;
        $this->denda = 0;
    }

    public function getIdTransaksi() { return $this->idTransaksi; }
    public function getTanggalPinjam() { return $this->tanggalPinjam; }
    public function getTanggalJatuhTempo() { return $this->tanggalJatuhTempo; }
    public function getBuku() { return $this->buku; }
    public function getAnggota() { return $this->anggota; }
    public function setStatus($status) { $this->status = $status; }
    public function getStatus() { return $this->status; }
    public function setTanggalDikembalikan($tanggal) { $this->tanggalDikembalikan = $tanggal; }
    public function getTanggalDikembalikan() { return $this->tanggalDikembalikan; }
    public function getDenda() { return $this->denda; }
    public function setDenda($denda) { $this->denda = $denda; }
    public function setJumlahHariTelat($j) { $this->jumlahHariTelat = $j; }
    public function getJumlahHariTelat() { return $this->jumlahHariTelat; }
}

// ===============================
// STATE (disimpan di session, asumsi user sudah login)
// ===============================

if (!isset($_SESSION['inited'])) {
    $_SESSION['buku'] = [
        'B001' => new Novel("B001", "Belajar PHP", 2),
        'B002' => new BukuIT("B002", "Dasar Pemrograman Java", 3),
    ];
    $_SESSION['anggota'] = new MahasiswaBiasa("A001", "Pierre Tristan LR", "bob", "rahasia123", "21051001", 2021);
    $_SESSION['transaksi_aktif'] = []; // key: id buku, value: Transaksi
    $_SESSION['inited'] = true;
}

$katalog = new KatalogBuku();
foreach ($_SESSION['buku'] as $b) {
    $katalog->tambahKeKatalog($b);
}

$pesan = '';
$hasilCari = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    ob_start();

    if ($action === 'cari') {
        $kw = trim($_POST['keyword'] ?? '');
        $hasilCari = $katalog->cariBuku($kw);
        if (count($hasilCari) === 0) {
            echo "Buku dengan kata kunci '$kw' tidak ditemukan.";
        }
    } elseif ($action === 'pinjam') {
        $id = $_POST['buku_id'] ?? '';
        if (isset($_SESSION['buku'][$id])) {
            $buku = $_SESSION['buku'][$id];
            $transaksi = $_SESSION['anggota']->pinjamBuku($buku);
            if ($transaksi) {
                $_SESSION['transaksi_aktif'][$id] = $transaksi;
            }
        }
    } elseif ($action === 'kembalikan') {
        $id = $_POST['buku_id'] ?? '';
        $tanggal = $_POST['tanggal_kembali'] ?? date('Y-m-d');
        if (isset($_SESSION['transaksi_aktif'][$id])) {
            $transaksi = $_SESSION['transaksi_aktif'][$id];
            $_SESSION['anggota']->kembalikanBuku($transaksi, $tanggal);
            unset($_SESSION['transaksi_aktif'][$id]);
        }
    }

    $pesan = ob_get_clean();
}

$anggota = $_SESSION['anggota'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Sistem Perpustakaan</title>
<style>
    body { font-family: Arial, sans-serif; max-width: 700px; margin: 30px auto; padding: 0 15px; background:#f7f7f8; }
    h2 { margin-bottom: 4px; }
    .sub { color:#666; margin-top:0; }
    .card { background:#fff; border:1px solid #ddd; border-radius:8px; padding:16px; margin-bottom:16px; }
    .card h3 { margin-top:0; }
    .pesan { background:#eef6ff; border-left:4px solid #3b82f6; padding:10px 14px; margin-bottom:16px; border-radius:4px; }
    .buku-item { display:flex; justify-content:space-between; align-items:center; padding:8px 0; border-bottom:1px solid #eee; }
    .buku-item:last-child { border-bottom:none; }
    input[type=text], input[type=date] { padding:6px 8px; border:1px solid #ccc; border-radius:4px; }
    button { padding:6px 14px; border:none; border-radius:4px; background:#3b82f6; color:#fff; cursor:pointer; }
    button:hover { background:#2563eb; }
    .badge { font-size:12px; color:#888; }
</style>
</head>
<body>

<h2>Sistem Perpustakaan</h2>
<p class="sub">Login sebagai: <strong><?= htmlspecialchars($anggota->getNama()) ?></strong> (asumsi sudah login)</p>

<?php if ($pesan): ?>
<div class="pesan"><?= $pesan ?></div>
<?php endif; ?>

<div class="card">
    <h3>Cari Buku</h3>
    <form method="post">
        <input type="hidden" name="action" value="cari">
        <input type="text" name="keyword" placeholder="Contoh: php" required>
        <button type="submit">Cari</button>
    </form>
</div>

<div class="card">
    <h3>Daftar Buku</h3>
    <?php foreach ($_SESSION['buku'] as $id => $b): ?>
        <div class="buku-item">
            <div>
                <?= htmlspecialchars($b->getJudul()) ?>
                <span class="badge">(stok: <?= $b->cekStok() ?>)</span>
            </div>
            <div>
                <?php if (isset($_SESSION['transaksi_aktif'][$id])): ?>
                    <form method="post" style="display:inline-flex; gap:6px;">
                        <input type="hidden" name="action" value="kembalikan">
                        <input type="hidden" name="buku_id" value="<?= $id ?>">
                        <input type="date" name="tanggal_kembali" value="<?= date('Y-m-d') ?>">
                        <button type="submit">Kembalikan</button>
                    </form>
                <?php else: ?>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="action" value="pinjam">
                        <input type="hidden" name="buku_id" value="<?= $id ?>">
                        <button type="submit" <?= $b->cekStok() <= 0 ? 'disabled' : '' ?>>Pinjam</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php if (count($_SESSION['transaksi_aktif']) > 0): ?>
<div class="card">
    <h3>Peminjaman Aktif</h3>
    <?php foreach ($_SESSION['transaksi_aktif'] as $id => $t): ?>
        <div class="buku-item">
            <div>
                <?= htmlspecialchars($t->getBuku()->getJudul()) ?><br>
                <span class="badge">Pinjam: <?= $t->getTanggalPinjam() ?> &middot; Jatuh tempo: <?= $t->getTanggalJatuhTempo() ?></span>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

</body>
</html>