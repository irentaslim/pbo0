<?php
class Product {
    public $nama;
    public $harga;
    public $kategori;

    public function __construct($nama, $harga, $kategori) {
        $this->nama     = $nama;
        $this->harga    = $harga;
        $this->kategori = $kategori;
    }

    public function getInfo() {
        return "Produk: $this->nama, Kategori: $this->kategori, Harga: Rp" . number_format($this->harga, 0, ',', '.');
    }

    public function applyDiskon($persen) {
        $potongan = $this->harga * ($persen / 100);
        $this->harga = $this->harga - $potongan;
    }
}

$produk1 = new Product("Laptop", 8000000, "Elektronik");
$produk2 = new Product("Sepatu", 500000, "Fashion");

echo "Sebelum diskon:" . "<br>";
echo $produk1->getInfo() . "<br>";
echo $produk2->getInfo() . "<br>";

echo "<br>";

$produk1->applyDiskon(10);
$produk2->applyDiskon(20);

echo "Sesudah diskon:" . "<br>";
echo $produk1->getInfo() . "<br>";
echo $produk2->getInfo() . "<br>";

?>