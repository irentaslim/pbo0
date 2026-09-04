<?php
class Mahasiswa {
    protected $name;
}
class MahasiswaAktif extends Mahasiswa{
    public function getName() {
        return $this->name;
    }
    public function setName($name)//parameter name sebagai wadah untuk menerima nilai dari luar 
    {
        $this->name = $name;
    }
}
$mhs= new MahasiswaAktif();
$mhs->setName("Iren Taslim");
echo $mhs->getName();
?>