<?php
// src/Models/Book.php
namespace App\Models;

class Book
{
    public function __construct(
        private int $id,
        private string $judul,
        private string $penulis,
        private int $stok
    ) {}

    public function getId(): int { return $this->id; }
    public function getJudul(): string { return $this->judul; }
    public function getPenulis(): string { return $this->penulis; }
    public function getStok(): int { return $this->stok; }
    public function isAvailable(): bool { return $this->stok > 0; }
}
