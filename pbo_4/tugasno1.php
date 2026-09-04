<?php
class Mobil
{
    //Properti private
    private $merek;
    private $warna;
    private $kecepatan;

    //Constructor
    public function __construct($merek, $warna, $kecepatan)
    {
        $this->merek = $merek;
        $this->setWarna($warna);
        $this->setKecepatan($kecepatan);
    }

    //Getter 
    public function getMerek()
    {
        return $this->merek;
    }
    public function getWarna()
    {
        return $this->warna;
    }
    public function getKecepatan()
    {
        return $this->kecepatan;
    }

    //Setter dengan validasi
    public function setWarna($warna)
    {
        if (empty($warna)) {
            echo "Warna tidak boleh kosong.<br>";
        } elseif (strlen($warna) < 3) {
            echo "Warna minimal 3 karakter.<br>";
        } else {
            $this->warna = $warna;
        }
    }
    public function setKecepatan($kecepatan)
    {
        if ($kecepatan < 0) {
            echo "Kecepatan tidak boleh negatif.<br>";
        } elseif ($kecepatan > 200) {
            echo "Kecepatan maksimal 200 km/jam.<br>";
        } else {
            $this->kecepatan = $kecepatan;
        }
    }

    //Method 
    public function getInfo()
    {
        return "Merek: " . $this->merek .
               "<br>Warna: " . $this->warna .
               "<br>Kecepatan: " . $this->kecepatan . " km/jam<br>";
    }
    public function jalankan()
    {
        return "Mobil berjalan...<br>";
    }
    public function berhenti()
    {
        return "Mobil berhenti<br>";
    }
}

//Membuat 3 objek Mobil
$mobil1 = new Mobil("Toyota", "Merah",100);
$mobil2 = new Mobil("Honda", "Hitam", 120);
$mobil3 = new Mobil("BMW", "Putih", 150);

//Menampilkan informasi mobil 
echo "Mobil 1 <br>";
echo $mobil1->getInfo();
echo $mobil1->jalankan();
echo $mobil1->berhenti();

echo "<br>";

echo "Mobil 2 <br>";
echo $mobil2->getInfo();
echo $mobil2->jalankan();
echo $mobil2->berhenti();

echo "<br>";

echo "Mobil 3 <br>";
echo $mobil3->getInfo();
echo $mobil3->jalankan();
echo $mobil3->berhenti();

?>