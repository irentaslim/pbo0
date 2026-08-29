<?php
class Mobil {
    public $merek;
    public $warna;
    public $kecepatan;

    public function __construct($merek, $warna, $kecepatan) {
        $this->merek = $merek;
        $this->warna = $warna;
        $this->kecepatan = $kecepatan;
    }

    public function getInfo() {
        return "Merek: " . $this->merek . 
               "<br>Warna: " . $this->warna . 
               "<br>Kecepatan: " . $this->kecepatan . " km/jam";
    }

    public function jalankan() {
        return "Mobil berjalan...";
    }

    public function berhenti() {
        return "Mobil berhenti";
    }
}

$mobil1 = new Mobil("Toyota", "Hitam", 120);
$mobil2 = new Mobil("Honda", "Merah", 100);
$mobil3 = new Mobil("BMW", "Putih", 150);

echo "Mobil 1<br>";
echo $mobil1->getInfo();
echo "<br>" . $mobil1->jalankan();
echo "<br>" . $mobil1->berhenti();
echo "<br><br>";

echo "Mobil 2<br>";
echo $mobil2->getInfo();
echo "<br>" . $mobil2->jalankan();
echo "<br>" . $mobil2->berhenti();
echo "<br><br>";

echo "Mobil 3<br>";
echo $mobil3->getInfo();
echo "<br>" . $mobil3->jalankan();
echo "<br>" . $mobil3->berhenti();
?>