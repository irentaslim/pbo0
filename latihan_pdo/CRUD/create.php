<?php

require_once '../Catatan.php';

$c = new Catatan();

$hasil = $c->setJudul("Keenam")
           ->setIsi("Belajar Masakphp create")
           ->save();

echo $hasil ? "Berhasil disimpan" : "Gagal";
