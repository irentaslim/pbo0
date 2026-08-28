<?php
class Mobil {
    public $angka1;
    public $angka2; 

    public function penjumlahan(){
        return $this->angka1 + $this->angka2; 
    }
}

$perhitungan = new Mobil(); 
$perhitungan->angka1=10; 
$perhitungan->angka2=10; 

echo $perhitungan->penjumlahan();
?>