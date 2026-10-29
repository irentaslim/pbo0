<?php
// src/Models/Loan.php
namespace App\Models;

class Loan
{
    public function __construct(
        private int $idBook,
        private int $idMember,
        private string $tanggal
    ) {}

    public function getIdBook(): int { return $this->idBook; }
    public function getIdMember(): int { return $this->idMember; }
    public function getTanggal(): string { return $this->tanggal; }
}
