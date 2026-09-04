<?php
class Mahasiswa {
    private $name;

    public function getName() {
        return $this->name;
    }
    public function setName($name)//parameter name sebagai wadah untuk menerima nilai dari luar 
    {
        $this->name = $name;
    }
}
$mhs= new Mahasiswa();
$mhs->setName("Iren");
echo $mhs->getName();
?>