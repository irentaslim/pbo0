<?php
// src/Models/Member.php
namespace App\Models;

class Member
{
    public function __construct(
        private int $id,
        private string $nama,
        private string $email
    ) {}

    public function getId(): int { return $this->id; }
    public function getNama(): string { return $this->nama; }
    public function getEmail(): string { return $this->email; }
}
