<?php

trait BisaBayarPakaiQR{
    public function buatQRCodeBelanja(){
        return "QR Code untuk belanja berhasil di buat silahlan scane untuk bayar; Rp " . $this->harga;
    }
}

class Produk { //dipake terus untuk diwariskan
    // properti
    protected $merek; //mempermudah class child berinteraksi/membaca
    protected $harga;
    public function __construct($merek, $harga) {
        $this->merek = $merek;
        $this->harga = $harga;
    }
    public function getInfo() {
        return "Merek: " . $this->merek . "<br>Harga: " . $this->harga;
    }
}

class Makanan extends Produk {
    use BisaBayarPakaiQR; //menggunakan trait
    protected $kadaluarsa;
    public function __construct($merek, $harga, $kadaluarsa) 
    {
        parent::__construct($merek, $harga); //memanggil constructor parent
        $this->kadaluarsa = $kadaluarsa;
    }
    public function getInfo() {
        $info = parent::getInfo();
        return $info . "<br> Expired : " . $this->kadaluarsa;
    }
}

class Elektronik extends Produk {
    use BisaBayarPakaiQR; //menggunakan trait
    protected $garansi;
    public function __construct($merek, $harga, $garansi) 
    {
        parent::__construct($merek, $harga); //memanggil constructor parent
        $this->garansi = $garansi;
    }
    public function getInfo() {
        $info = parent::getInfo();
        return $info . "<br> Garansi : " . $this->garansi;
    }
}

$makanan=new Makanan("Indomie", 3000, "2035-12-31");
$elektronik=new Elektronik("Samsung", 5000000, "2 tahun");

echo $makanan->getInfo();
echo "<br>";
echo $makanan->buatQRCodeBelanja();
echo "<br><br>";
echo $elektronik->getInfo();
echo "<br>";
echo $elektronik->buatQRCodeBelanja();

?>