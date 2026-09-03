<?php
class Buku {
    private $judul;
    private $penulis;
    private $harga;
    private $stock;

    // properti tambahan buat nyimpan pesan error (bukan ditampilkan langsung)
    private $hargaError;
    private $stockError;

    public function __construct($judul, $penulis, $harga, $stock) {
        $this->setJudul($judul);
        $this->setPenulis($penulis);
        $this->setHarga($harga);
        $this->setStock($stock);
    }

    //Getter
    public function getJudul() {
        return $this->judul;
    }
    public function getPenulis() {
        return $this->penulis;
    }
    public function getHarga() {
        // kalau harga valid (lebih dari 0), tampilkan angkanya
        // kalau tidak, tampilkan pesan error yang tersimpan
        if ($this->harga > 0) {
            return $this->harga;
        } else {
            return $this->hargaError;
        }
    }
    public function getStock() {
        if ($this->stock > 0) {
            return $this->stock;
        } else {
            return $this->stockError;
        }
    }

    //Setter
    public function setJudul($judul) {
        $this->judul = $judul;
    }
    public function setPenulis($penulis) {
        $this->penulis = $penulis;
    }
    public function setHarga($harga) {
        if ($harga >= 10000) {
            $this->harga = $harga;
        } else {
            // pesan disimpan dulu, TIDAK di-echo di sini
            $this->hargaError = "Harga tidak valid. Minimal harga buku adalah Rp10.000.";
        }
    }
    public function setStock($stock) {
        if ($stock >= 0) {
            $this->stock = $stock;
        } else {
            $this->stockError = "Stock tidak valid. Stock tidak boleh negatif.";
        }
    }
}

//objek
$buku  = new Buku("Jane Eyre", "Charlotte Bronte", 150000, 10);
$buku1 = new Buku("Pride and Prejudice", "Jane Austen", 120000, 5);
$buku2 = new Buku("To Kill a Mockingbird", "Harper Lee", 180000, 8);
$buku3 = new Buku("Agnes Grey", "Anna Bronte", 3000, -10);

//output
echo "Buku 1<br>";
echo "Judul Buku :" . $buku->getJudul() . "<br>";
echo "Penulis Buku :" . $buku->getPenulis() . "<br>";
echo "Harga Buku :" . $buku->getHarga() . "<br>";
echo "Stock Buku :" . $buku->getStock() . "<br><br>";

echo "Buku 2<br>";
echo "Judul Buku :" . $buku1->getJudul() . "<br>";
echo "Penulis Buku :" . $buku1->getPenulis() . "<br>";
echo "Harga Buku :" . $buku1->getHarga() . "<br>";
echo "Stock Buku :" . $buku1->getStock() . "<br><br>";

echo "Buku 3<br>";
echo "Judul Buku :" . $buku2->getJudul() . "<br>";
echo "Penulis Buku :" . $buku2->getPenulis() . "<br>";
echo "Harga Buku :" . $buku2->getHarga() . "<br>";
echo "Stock Buku :" . $buku2->getStock() . "<br><br>";

echo "Buku 4<br>";
echo "Judul Buku :" . $buku3->getJudul() . "<br>";
echo "Penulis Buku :" . $buku3->getPenulis() . "<br>";
echo "Harga Buku :" . $buku3->getHarga() . "<br>";
echo "Stock Buku :" . $buku3->getStock() . "<br><br>";