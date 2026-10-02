<?php
abstract class Pustaka {
    protected $judul;
    protected $id;
    private $stok;

    public function __construct($judul, $id, $stok) {
        $this->judul = $judul;
        $this->id = $id;
        $this->stok = $stok;
    }

    public function isTersedia() {
        return $this->stok > 0;
    }
    public function kurangiStok() {
        if ($this->stok > 0) $this->stok--;
    }

    abstract public function tampilkanInfo();
}

class Buku extends Pustaka {
    private $pengarang;
    public function __construct($judul, $id, $stok, $pengarang) {
        parent::__construct($judul, $id, $stok);
        $this->pengarang = $pengarang;
    }
    public function tampilkanInfo() {
        return "{$this->judul} oleh {$this->pengarang}";
    }
}
?>