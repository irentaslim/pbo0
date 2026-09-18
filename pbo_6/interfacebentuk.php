<?php
// Interface
interface Bentuk {
    public function hitungLuas();
    public function namaBentuk();
}

class Persegi implements Bentuk {
    private $sisi;

    public function __construct($sisi) {
        $this->sisi = $sisi;
    }

    public function hitungLuas() {
        return $this->sisi * $this->sisi;
    }

    public function namaBentuk() {
        return "Persegi (sisi=" . $this->sisi . ")";
    }
}

class Lingkaran implements Bentuk {
    private $jari;

    public function __construct($jari) {
        $this->jari = $jari;
    }

    public function hitungLuas() {
        return 3.14 * $this->jari * $this->jari;
    }

    public function namaBentuk() {
        return "Lingkaran (radius=" . $this->jari . ")";
    }
}

// Polymorphism dengan interface
function cetakLuas(Bentuk $bentuk) {
    echo "Luas " . $bentuk->namaBentuk() . ": " . $bentuk->hitungLuas() . "<br>";
}

// Menampung objek Persegi dan Lingkaran dalam satu array
$daftarBentuk = [
    new Persegi(5),
    new Lingkaran(7)
];

// Melakukan loop untuk mencetak luas setiap bentuk
foreach ($daftarBentuk as $bentuk) {
    cetakLuas($bentuk);
}
?>