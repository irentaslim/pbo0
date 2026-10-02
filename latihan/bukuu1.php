<?php

abstract class Anggota {
    private $id;
    private $nama;
    private $username;
    private $password;
    private $tanggalPinjam;
    private $tanggalJatuhTempo;
    private $tanggalDikembalikan;
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
        return $this->username === $username && $this->password === $password;
    }

    public function pinjamBuku(Buku $buku) {
        if ($buku->cekStok() <= 0) {
            return null;
        }

        $buku->kurangiStok();
        $tanggalSekarang = date('Y-m-d');

        return new Transaksi(
            uniqid(),
            $this,
            $buku,
            $tanggalSekarang
        );
    }

    public function kembalikanBuku(Transaksi $transaksi, $tanggalDikembalikan) {
        $transaksi->setTanggalDikembalikan($tanggalDikembalikan);

        $jatuhTempo = strtotime($transaksi->getTanggalJatuhTempo());
        $kembali = strtotime($tanggalDikembalikan);

        if ($kembali > $jatuhTempo) {
            $transaksi->setStatus('terlambat');
            $jumlahHariTelat = ceil(($kembali - $jatuhTempo) / (60 * 60 * 24));
            $transaksi->setJumlahHariTelat($jumlahHariTelat);
            $denda = $this->hitungDenda($jumlahHariTelat);
            $transaksi->setDenda($denda);
        } else {
            $transaksi->setStatus('dikembalikan');
            $transaksi->setJumlahHariTelat(0);
            $transaksi->setDenda(0);
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
        return $this->username === $username && $this->password === $password;
    }

    public function kelolaBuku($aksi, Buku $buku) {
        switch ($aksi) {
            case 'tambah':
            case 'edit':
            case 'hapus':
                return true;
            default:
                return false;
        }
    }

    public function kelolaAnggota($aksi, Anggota $anggota) {
        switch ($aksi) {
            case 'tambah':
            case 'edit':
            case 'hapus':
                return true;
            default:
                return false;
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

    public function manajemen() {}

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
        $this->tanggalJatuhTempo = date(
            'Y-m-d',
            strtotime("+$lamaPinjam days", strtotime($tanggalPinjam))
        );

        $this->status = 'dipinjam';
        $this->jumlahHariTelat = 0;
        $this->denda = 0;
    }

    public function getId() {
        return $this->idTransaksi;
    }

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
        return $this->tanggalDikembalikan ?? null;
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
}