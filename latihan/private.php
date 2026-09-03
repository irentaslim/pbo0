<?php
class Mobil{
    private $kecepatan=0;
    public function getKecepatan(){
        return $this->kecepatan;
    }
}

$mobil = new Mobil();

echo $mobil->getKecepatan();
?>