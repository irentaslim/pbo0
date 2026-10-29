<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Perpustakaan;

$pustaka = new Perpustakaan();

$pustaka->tambahBuku('Laskar Pelangi', 'Andrea Hirata');
$pustaka->tambahAnggota('Budi', 'budi@example.com');
$pustaka->pinjamBuku(1, 1);

echo "Selesai!";
