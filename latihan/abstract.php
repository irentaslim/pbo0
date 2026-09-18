<?php
abstract class Hewan{ 
     //kerangka blueprint hewan
    protected $nama;
    public function __construct($nama){
        $this->nama = $nama;
    }
    abstract public function suara();
    public function makan(){ //method makan
        return $this->nama." sedang makan";
    }
}

class Kucing extends Hewan{ //kelas kucing turunan dari hewan
    public function suara(){ //mengimplementasikan method suara
        return "Meow";
    }
} 

class Anjing extends Hewan{ //kelas anjing turunan dari hewan
    public function suara(){ //mengimplementasikan method suara
        return "Guk Guk";
    }
}

$kucing = new Kucing("Kitty"); //membuat objek kucing
echo $kucing->suara(); //menampilkan suara kucing
echo "<br>";
echo $kucing->makan(); //menampilkan bahwa kucing sedang makan


?>