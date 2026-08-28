<?php
class Persegipanjang{
    public $p; 
    public $l;
    public function luas(){
        return $this->p * $this->l;
    }
}
$kotak = new Persegipanjang();
$kotak->p = 100;
$kotak->l = 10;

$kotak1 = new Persegipanjang();
$kotak1->p = 10;
$kotak1->l = 10;

echo $kotak->luas();
echo "<br>";
echo $kotak1->luas();
?>