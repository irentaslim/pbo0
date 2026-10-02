<?php
if (php_sapi_name() !== 'cli') header('Content-Type: text/plain; charset=utf-8');
//Abstract class Obat
abstract class Obat
{
    protected $id;
    protected $nama;
    protected $hargaDasar;
    public function __construct($id, $nama, $hargaDasar)
    {
        $this->id = $id;
        $this->nama = $nama;
        $this->hargaDasar = $hargaDasar;
    }
    public function getId()         { return $this->id; }
    public function getNama()       { return $this->nama; }
    public function getHargaDasar() { return $this->hargaDasar; }
    abstract public function hitungTotal();
    abstract public function getJenis();
    //bonus keterangan unik tiap child
    abstract public function cetakDetail();
}
//Child class + override
class Tablet extends Obat
{
    private $butir; 
    public function __construct($id, $nama, $hargaDasar, $butir)
    {
        parent::__construct($id, $nama, $hargaDasar);
        $this->butir = $butir;
    }
    public function hitungTotal()
    {
        return $this->hargaDasar + (2000 * $this->butir);
    }
    public function getJenis() { return "Tablet"; }
    public function cetakDetail()
    {
        return "Tablet {$this->butir} butir";
    }
}
class Sirup extends Obat
{
    private $botol;
    public function __construct($id, $nama, $hargaDasar, $botol)
    {
        parent::__construct($id, $nama, $hargaDasar);
        $this->botol = $botol;
    }
    private function dapatDiskon()
    {
        return $this->botol > 3;
    }
    public function hitungTotal()
    {
        $total = $this->hargaDasar * $this->botol;
        if ($this->dapatDiskon()) {
            $total -= $total * 0.05; // diskon 5%
        }
        return $total;
    }
    public function getJenis() { return "Sirup"; }
    public function cetakDetail()
    {
        $diskon = $this->dapatDiskon() ? " (diskon 5%)" : "";
        return "Sirup {$this->botol} botol{$diskon}";
    }
}
class Vitamin extends Obat
{
    private $kapsul;
    public function __construct($id, $nama, $hargaDasar, $kapsul)
    {
        parent::__construct($id, $nama, $hargaDasar);
        $this->kapsul = $kapsul;
    }
    public function hitungTotal()
    {
        return $this->hargaDasar + (5000 * $this->kapsul);
    }
    public function getJenis() { return "Vitamin"; }
    public function cetakDetail()
    {
        return "Vitamin {$this->kapsul} kapsul";
    }
}
//Instansiasi 5 objek
$daftarObat = [
    new Tablet ("O001", "Iren", 10000, 5),
    new Sirup  ("O002", "Riton", 15000, 4), // botol > 3 -> diskon 5%
    new Vitamin("O003", "Aldan", 20000, 2),
    new Tablet ("O004", "Paracetamol",   5000, 10),
    new Sirup  ("O005", "OBH Combi",    12000, 2),
];
//Tampilkan tabel (teks) + total keseluruhan
function rp($angka)
{
    return "Rp " . number_format($angka, 0, ',', '.');
}
$format = "| %-3s | %-6s | %-14s | %-8s | %-13s | %-13s |\n";
$garis  = str_repeat("-", 76) . "\n";
echo $garis;
printf($format, "No", "ID", "Nama", "Jenis", "Harga Dasar", "Total");
echo $garis;
$totalKeseluruhan = 0;
foreach ($daftarObat as $i => $obat) {
    $total = $obat->hitungTotal();
    $totalKeseluruhan += $total;
    printf(
        $format,
        $i + 1,
        $obat->getId(),
        $obat->getNama(),
        $obat->getJenis(),
        rp($obat->getHargaDasar()),
        rp($total)
    );
}
echo $garis;
echo "Total Keseluruhan: " . rp($totalKeseluruhan) . "\n\n";
//bonus detail tiap obat
echo "Detail Obat:\n";
foreach ($daftarObat as $obat) {
    echo "- " . $obat->getNama() . " : " . $obat->cetakDetail() . "\n";
}