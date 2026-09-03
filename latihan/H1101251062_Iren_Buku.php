<?php //menandai bahwa ini kode program php
class Buku { //cetakan blueprint buku
    private $judul;
    private $penulis;
    private $harga = 0; //=0 sebagai nilai awal dan agar tidal null jk validasi gagal
    private $stock = 0;

    // properti tambahan buat nyimpan pesan error (bukan ditampilkan langsung)
    private $hargaerror;
    private $stockerror;

    //inisialisasi properti saat objek dibuat setiap new buku dipanggil
    public function __construct($judul, $penulis, $harga, $stock) {
        $this->setJudul($judul);
        $this->setPenulis($penulis);
        $this->setHarga($harga);
        $this->setStock($stock);
    }

    //getter membaca dan mengambil properti
    public function getJudul() {
        return $this->judul;
    }
    public function getPenulis() {
        return $this->penulis;
    }
    public function getHarga() {
        if ($this->harga >= 10000) { //ngecek apakah harga lebih dari 10.000
            return $this->harga; //jika iya, tampilkan harga
        } else {
            return $this->hargaerror; //jika tidak, tampilkan pesan error yang tersimpan
        }
    }
    public function getStock() {
        if ($this->stock > 0) { //ngecek apakah stock lebih dari 0
            return $this->stock; //jika iya, tampilkan stock
        } else {
            return $this->stockerror; //jika tidak, tampilkan pesan error yang tersimpan
        }
    }

    //Setter memberikan properti nilai baru, sekaligus validasi
    public function setJudul($judul) {
        $this->judul = $judul;
    } 
    public function setPenulis($penulis) {
        $this->penulis = $penulis;
    }
    public function setHarga($harga) {
        if ($harga >=10000) { //ngecek apakah harga lebih dari 10.000
            $this->harga = $harga; //jika iya, simpan harga
        } else {
            $this->hargaerror = "Harga tidak valid. Minimal harga buku adalah Rp10.000";
        } //jika tidak, simpan pesan error ke properti hargaerror
    } 
    public function setStock($stock) {
        if ($stock >= 0) { //ngecek apakah stock lebih dari 0
            $this->stock = $stock; //jika iya, simpan stock
        } else {
            $this->stockerror = "Stock tidak valid. Stock tidak boleh negatif";
        } //jika tidak, simpan pesan error ke properti stockerror
    }
} 

//membuat objek buku melalui "New = Buku" yang otomatis memicucu
//__construct() dan mengisi properti dengan parameter judul, penulis, harga, dan stock

$buku = new Buku("Jane Eyre", "Charlotte Bronte", 150000, 10);
$buku1 = new Buku("Pride and Prejudice", "Jane Austen", 120000, 5);
$buku2 = new Buku("To Kill a Mockingbird", "Harper Lee", 180000, 8); 
$buku3 = new Buku("Agnes Grey", "Anna Bronte", 3000, -10);

//output
echo "Buku 1". "<br>";
echo "Judul Buku :" . $buku->getJudul() . "<br>";
echo "Penulis Buku :" . $buku->getPenulis() . "<br>";
echo "Harga Buku :" . $buku->getHarga() . "<br>";
echo "Stock Buku :" . $buku->getStock() . "<br><br>";

echo "Buku 2". "<br>";
echo "Judul Buku :" . $buku1->getJudul() . "<br>";
echo "Penulis Buku :" . $buku1->getPenulis() . "<br>";
echo "Harga Buku :" . $buku1->getHarga() . "<br>";
echo "Stock Buku :" . $buku1->getStock() . "<br><br>";

echo "Buku 3". "<br>";
echo "Judul Buku :" . $buku2->getJudul() . "<br>";
echo "Penulis Buku :" . $buku2->getPenulis() . "<br>";
echo "Harga Buku :" . $buku2->getHarga() . "<br>";
echo "Stock Buku :" . $buku2->getStock() . "<br><br>";

echo "Buku 4". "<br>";
echo "Judul Buku :" . $buku3->getJudul() . "<br>";
echo "Penulis Buku :" . $buku3->getPenulis() . "<br>";
echo "Harga Buku :" . $buku3->getHarga() . "<br>";      
echo "Stock Buku :" . $buku3->getStock() . "<br><br>";

?>