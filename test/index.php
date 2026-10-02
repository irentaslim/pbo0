<?php
//superclass: anggota > inhertiance

abstract class Anggota {
    //encapsulation (private, diakses lewat method)
    private $id;
    private $nama;
    private $username;
    private $password;
    private $tanggalPinjam;
    private $tanggalJatuhTempo;
    private $tanggalDikembalikan;
    private $denda = 0;

    //abstraction: method ygg wajib diisi tiap anak class
    abstract public function getLamaPeminjaman(); //polymorphism

    //penjelasan: constructor blm ada sebelumnya, tambahin biar id/nama/username/password bisa keisi
    //soalnya di use case login butuh username & password buat divalidasi
    public function __construct($id, $nama, $username, $password) {
        $this->id = $id;
        $this->nama = $nama;
        $this->username = $username;
        $this->password = $password;
    }

    //penjelasan: getter id & nama, dipake pas nampilin info anggota (misal di petugas->kelolaAnggota)
    public function getId() {
        return $this->id;
    }

    public function getNama() {
        return $this->nama;
    }

    public function login($username, $password) {
        //validasi id & password
        //penjelasan: sesuai use case "Login" -> cek username & password yg diinput sama yg kesimpen di objek
        //kalo ga cocok return false, sistem nampilin pesan e-1 (id/password tidak sesuai)
        if ($this->username === $username && $this->password === $password) {
            echo "Login berhasil. Selamat datang, " . $this->nama . "<br>";
            return true;
        }

        echo "Username atau password salah, silahkan coba lagi.<br>";
        return false;
    }

    public function pinjamBuku(Buku $buku) {
        //abstraction: cek stok, catat transaksi, kurangi stok

        //cek ketersediaan stok buku
        if ($buku->cekStok() > 0 ) {
            $buku->kurangiStok();

            $tanggalSekarang = date('Y-m-d');

            //buat objek transaksi baru
            $transaksi = new Transaksi(uniqid(), $this, $buku, $tanggalSekarang);

            echo "Buku berhasil dipinjam. Tanggal pinjam: $tanggalSekarang. Tanggal jatuh tempo: " . $transaksi->getTanggalJatuhTempo();
            return $transaksi;
        } else {
            echo "Mohon maaf, stok buku sedang habis,";
            return null;
        }
    }

    //kembalikan buku & extend hitung denda kalau terlambat
    public function kembalikanBuku(Transaksi $transaksi, $tanggalDikembalikan) {
        //cek tanggal jatuh tempo, tambah stok, trigger hitungDenda() kalau telat
        $transaksi->setTanggalDikembalikan($tanggalDikembalikan);

        $jatuhTempo = strtotime($transaksi->getTanggalJatuhTempo());
        $kembali = strtotime($tanggalDikembalikan);

        //cek keterlambatan
        if ($kembali > $jatuhTempo) {
            $transaksi->setStatus("terlambat");
            $jumlahHariTelat = ceil(($kembali - $jatuhTempo) / (60 * 60 * 24));

            $transaksi->setJumlahHariTelat($jumlahHariTelat);

            //hitung denda
            $denda = $this->hitungDenda($jumlahHariTelat);
            $transaksi->setDenda($denda);
            echo "Buku dikembalikan terlambat. Jumlah hari telat: $jumlahHariTelat. Denda: Rp$denda.";
        } else {
            $transaksi->setStatus("dikembalikan");
            echo "Buku dikembalikan tepat waktu. Terima kasih!";

        }
        //tambah kembali stok buku
        $transaksi->getBuku()->tambahStok();
    }

    protected function hitungDenda($jumlahHariTelat) {
        $tarifPerHari = 500; //sesuai aturan yaitu Rp 500/hari
        $this->denda = $jumlahHariTelat * $tarifPerHari;
        return $this->denda;
    }
}

abstract class Mahasiswa extends Anggota {
    private $nim;
    private $angkatan;

    //penjelasan: constructor mahasiswa, manggil constructor induk (Anggota) dulu pake parent::__construct
    //terus baru isi nim & angkatan yg emang cuma dipunya mahasiswa (dosen ga punya nim)
    public function __construct($id, $nama, $username, $password, $nim, $angkatan) {
        parent::__construct($id, $nama, $username, $password);
        $this->nim = $nim;
        $this->angkatan = $angkatan;
    }

    public function getNim() {
        return $this->nim;
    }
}


//polymorphism: override getLamaPeminjaman() beda tiap class
class MahasiswaBiasa extends Mahasiswa {
    public function getLamaPeminjaman() {
        return 7; //hari
    }
}

class MahasiswaTingkatAkhir extends Mahasiswa {
    public function getLamaPeminjaman() {
        return 14; //hari
    }
}

// inheritance: dosen turunan anggota
class Dosen extends Anggota {
    private $nip;

    //penjelassan: dosen ga extends Mahasiswa (beda cabang), jadi constructornya manggil langsung ke Anggota
    public function __construct($id, $nama, $username, $password, $nip) {
        parent::__construct($id, $nama, $username, $password);
        $this->nip = $nip;
    }

    public function getLamaPeminjaman() {
        return 7; //hari
    }
}

//petyugas (aktor terpisah, bukan turunan anggota)
class Petugas {
    private $id;
    private $nama;
    private $username;
    private $password;

    //penjelassan: sama kaya Anggota, petugas jg butuh username/password buat login jd constructornya nyimpen itu semua
    public function __construct($id, $nama, $username, $password) {
        $this->id = $id;
        $this->nama = $nama;
        $this->username = $username;
        $this->password = $password;
    }

    public function login($username, $password) {
        //penjelassan: alur sama kaya login anggota, cuma versi petugas
        if ($this->username === $username && $this->password === $password) {
            echo "Login berhasil sebagai petugas. Selamat datang, " . $this->nama . "<br>";
            return true;
        }

        echo "Username atau password salah.<br>";
        return false;
    }

    public function kelolaBuku($aksi, Buku $buku) {
        //tambah/edit/hapus
        //penjelassan: use case "kelola buku" ada 3 aksi (tambah, edit, hapus), jadi dibikin switch aja (switch mirip syntax match di py)
        //buat versi ini "edit" cuma simulasi krn ga ada database beneran, yg penting alurnya kebaca
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
        //tambah/edit/hapus
        //penjelassan: sama polanya kaya kelolaBuku, bedanya ini utk data anggota (mahasiswa/dosen)
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

//suuperclass: Buku > inheritance
abstract class Buku {
    //encapsulation
    private $id;
    private $judul;
    private $stok;
    
    public function __construct($id, $judul, $stok) {
    $this->id = $id;
    $this->judul = $judul;
    $this->stok = $stok;
}

    public function manajemen() {
        //tambah, edit, hapus data buku (dipakai Petugas)
    }

    //penjelassan: getter judul & id, dipake buat nampilin hasil pencarian sama pas kelolaBuku
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

// inheritance: kategori buku turunan Buku
class Novel extends Buku {
    private $penulis;
    private $genre;
}

class BukuIT extends Buku {
    private $bahasaPemrograman;
    private $edisi;
}

//penjelassan: class baru buat nanganin use case "mencari buku"
//kubuat simpel, cuma nyari dari array buku bdsrkn keyword yg cocok di judul
//E-1 "buku tidak ditemukan" ditanganin pke return array kosong terus dicek di pemanggil
class KatalogBuku {
    private $daftarBuku = [];

    public function tambahKeKatalog(Buku $buku) {
        $this->daftarBuku[] = $buku;
    }

    public function cariBuku($kataKunci) {
        $hasil = [];

        foreach ($this->daftarBuku as $buku) {
            //penjelassan: stripos biar ga case sensitive, jd "php" ketemu "Belajar PHP"
            if (stripos($buku->getJudul(), $kataKunci) !== false) {
                $hasil[] = $buku;
            }
        }

        if (count($hasil) === 0) {
            echo "Buku dengan kata kunci '$kataKunci' tidak ditemukan.<br>";
        }

        return $hasil;
    }
}

// class transaksi (hasil dari use case pinjam/kembalikan)
class Transaksi {
    // encapsulation
    private $idTransaksi;
    private $anggota; // relasi ke Anggota
    private $buku; // relasi ke Buku
    private $tanggalPinjam;
    private $tanggalJatuhTempo;
    private $tanggalDikembalikan;
    private $status; // "dipinjam", "dikembalikan", "terlambat"
    private $jumlahHariTelat;
    private $denda;
    public function __construct($idTransaksi, Anggota $anggota, Buku $buku, $tanggalPinjam) {
    $this->idTransaksi = $idTransaksi;
    $this->anggota = $anggota;
    $this->buku = $buku;
    $this->tanggalPinjam = $tanggalPinjam;

    $lamaPinjam = $anggota->getLamaPeminjaman();

    $this->tanggalJatuhTempo = date(
        'Y-m-d',
        strtotime("+$lamaPinjam days", strtotime($tanggalPinjam))
    );

    $this->status = "dipinjam";
    $this->jumlahHariTelat = 0;
    $this->denda = 0;
    
}
//getter dll
public function getTanggalJatuhTempo() {
    return $this->tanggalJatuhTempo;
}

public function getBuku() {
    return $this->buku;
}

public function getAnggota() {
    return $this->anggota;
}

public function setStatus($status) {
    $this->status = $status;
}

public function setTanggalDikembalikan($tanggal) {
    $this->tanggalDikembalikan = $tanggal;
}

public function getStatus() {
    return $this->status;
}

public function getTanggalDikembalikan() {
    return $this->tanggalDikembalikan;
}

public function getDenda() {
    return $this->denda;
}

public function setDenda($denda) {
    $this->denda = $denda;
}

public function setJumlahHariTelat($jumlahHariTelat) {
    $this->jumlahHariTelat = $jumlahHariTelat;
}

public function getJumlahHariTelat() {
    return $this->jumlahHariTelat;
}

}//class transaksi closenya dsni


// ===============================
// TESTING PROGRAM PERPUSTAKAAN
// ===============================

echo "<h2>Testing Sistem Perpustakaan</h2>";

$buku = new Novel("B001", "Belajar PHP", 2);
$bukuIT = new BukuIT("B002", "Dasar Pemrograman Java", 3);

//penjelassan: dimasukin ke katalog biar bisa dites fitur cari buku
$katalog = new KatalogBuku();
$katalog->tambahKeKatalog($buku);
$katalog->tambahKeKatalog($bukuIT);

$anggota = new MahasiswaBiasa("A001", "Pierre Tristan LR", "bob", "rahasia123", "21051001", 2021);
$dosen = new Dosen("D001", "Ilhamsyah", "Ilham", "dosen123", "0012345");
$petugas = new Petugas("P001", "Iren MT", "Iren", "Iren123");

echo "<h3>0. Proses Login</h3>";
$anggota->login("bob", "rahasia123");
$anggota->login("bob", "BobSukaMainEpEp");

echo "<h3>0b. Proses Mencari Buku</h3>";
$hasilCari = $katalog->cariBuku("php");
foreach ($hasilCari as $b) {
    echo "Ditemukan: " . $b->getJudul() . " (stok: " . $b->cekStok() . ")<br>";
}

echo "<h3>1. Kondisi Awal</h3>";
echo "Stok buku: " . $buku->cekStok() . "<br>";

echo "<h3>2. Proses Peminjaman</h3>";
$transaksi = $anggota->pinjamBuku($buku);

echo "<br>Stok setelah dipinjam: " . $buku->cekStok() . "<br>";

echo "<h3>3. Proses Pengembalian Terlambat</h3>";

$tanggalKembali = date(
    'Y-m-d',
    strtotime('+10 days')
);

$anggota->kembalikanBuku($transaksi, $tanggalKembali);

echo "<br>Stok setelah dikembalikan: " . $buku->cekStok() . "<br>";
echo "Total denda: Rp" . $transaksi->getDenda() . "<br>";

echo "<h3>4. Proses Kelola Buku & Anggota (Petugas)</h3>";
$petugas->login("Iren", "Iren123");
$petugas->kelolaBuku("tambah", $bukuIT);
$petugas->kelolaAnggota("tambah", $dosen);