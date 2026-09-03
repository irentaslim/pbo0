<?php
class Mobil{
    private $kecepatan = 0;
    public function getKecepatan(){
        return $this->kecepatan;
    }
    public function setKecepatan($kecepatan){
        $this->kecepatan = $kecepatan;
    }
}

$mobil = new Mobil();
$mobil->setKecepatan(60);

echo $mobil->getKecepatan();
?>