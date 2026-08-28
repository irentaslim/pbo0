<?php
class Mobil {
    public $merek;
    public $warna;
    public $kecepatan;
 
    public function __construct($merek, $warna, $kecepatan) {
        $this->merek     = $merek;
        $this->warna     = $warna;
        $this->kecepatan = $kecepatan;
    }
    public function getInfo() {
        echo "Mobil: $this->merek, Warna: $this->warna, Kecepatan: $this->kecepatan km/jam<br>";
    }
    public function jalankan() {
        echo "$this->merek: Mobil berjalan...<br>";
    }
    public function berhenti() {
        echo "$this->merek: Mobil berhenti<br>";
    }
}
 
$mobil1 = new Mobil("Toyota", "Merah", 180);
$mobil2 = new Mobil("Honda", "Hitam", 160);
$mobil3 = new Mobil("Suzuki", "Putih", 150);
 
$mobil1->getInfo();
$mobil2->getInfo();
$mobil3->getInfo();
 
echo "<br>";
 
$mobil1->jalankan();
$mobil1->berhenti();
 
echo "<br>";
 
$mobil2->jalankan();
$mobil2->berhenti();
 
echo "<br>";
 
$mobil3->jalankan();
$mobil3->berhenti();
?>