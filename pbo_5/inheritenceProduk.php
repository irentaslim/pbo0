<?php

class Produk {
    protected $namaProduk;
    protected $merek;
    protected $harga;

    public function __construct($namaProduk, $merek, $harga) {
        // Validasi harga: harus angka dan lebih besar dari 0
        if (!is_numeric($harga) || $harga <= 0) {
            throw new InvalidArgumentException("Harga tidak valid! Harga harus berupa angka lebih besar dari 0.");
        }
        $this->namaProduk = $namaProduk;
        $this->merek = $merek;
        $this->harga = $harga;
    }

    public function getInfo() {
        return "Merek: " . $this->merek . "<br>Harga: Rp " . number_format($this->harga, 0, ',', '.');
    }
}

class Makanan extends Produk {
    protected $tanggalKadaluarsa;
    // Tanggal acuan pengecekan status (tanggal produk diperiksa), bukan tanggal sistem,
    // supaya status yang dihasilkan konsisten setiap kali dijalankan
    private $tanggalCek = "2025-01-01";

    public function __construct($namaProduk, $merek, $harga, $tanggalKadaluarsa) {
        parent::__construct($namaProduk, $merek, $harga); // memanggil constructor parent (ikut divalidasi)
        $this->tanggalKadaluarsa = $tanggalKadaluarsa;
    }

    private function cekStatus() {
        return ($this->tanggalKadaluarsa >= $this->tanggalCek) ? "Segar" : "Kadaluarsa";
    }

    // Override getInfo(), tetap manfaatkan getInfo() milik parent
    public function getInfo() {
        $info = "Produk: Makanan - " . $this->namaProduk . "<br>" . parent::getInfo();
        $info .= "<br>Tanggal Kadaluarsa: " . $this->tanggalKadaluarsa;
        $info .= "<br>Status: " . $this->cekStatus();
        return $info;
    }
}

class Elektronik extends Produk {
    protected $garansi;

    public function __construct($namaProduk, $merek, $harga, $garansi) {
        parent::__construct($namaProduk, $merek, $harga);
        $this->garansi = $garansi;
    }

    // Override getInfo(), tetap manfaatkan getInfo() milik parent
    public function getInfo() {
        $info = "Produk: Elektronik - " . $this->namaProduk . "<br>" . parent::getInfo();
        $info .= "<br>Garansi: " . $this->garansi . " bulan";
        return $info;
    }
}

// ================== TESTING ==================
$makanan = new Makanan("Mie Instan", "Indomie", 3500, "2025-06-30");
$elektronik = new Elektronik("Smart TV", "Samsung", 5000000, 12);

echo $makanan->getInfo();
echo "<br><br>";
echo $elektronik->getInfo();
?>