<?php
class Mobil{
    private $kecepatan = 0;
    public function getKecepatan(){
        return $this->kecepatan;
    }
    public function setKecepatan($kecepatan){
        if ($kecepatan >=0){
            $this->kecepatan = $kecepatan;
        } else{
            echo "Kecepatan tidak boleh negatif";
        }
        //$this->kecepatan = $kecepatan;
    }
}

$mobil = new Mobil();
$mobil->setKecepatan(-60);

$mobil1 = new Mobil();
$mobil1->setKecepatan(60);

echo $mobil->getKecepatan();
echo "<br>";
echo $mobil1->getKecepatan();
?>