<?php

class Mobil
{
    private $merek;
    private $warna;
    private $kecepatan = 0;

    public function getMerek()
    {
        return $this->merek;
    }

    public function setMerek($merek)
    {
        $this->merek = $merek;
    }

    public function getWarna()
    {
        return $this->warna;
    }

    public function setWarna($warna)
    {
        $this->warna = $warna;
    }

    public function getKecepatan()
    {
        return $this->kecepatan;
    }

    public function setKecepatan($kecepatan)
    {
        if ($kecepatan >= 0) {
            $this->kecepatan = $kecepatan;
        } else {
            echo "Kecepatan tidak boleh negatif.";
        }
    }
}

$mobil = new Mobil();

$mobil->setMerek("Toyota");
$mobil->setWarna("Hitam");
$mobil->setKecepatan(100);

echo "Merek: " . $mobil->getMerek() . "<br>";
echo "Warna: " . $mobil->getWarna() . "<br>";
echo "Kecepatan: " . $mobil->getKecepatan();