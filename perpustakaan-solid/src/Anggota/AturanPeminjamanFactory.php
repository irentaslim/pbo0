<?php
class AturanPeminjamanFactory
{
    /** @param array<string, AturanPeminjamanInterface> $daftar */
    public function __construct(private array $daftar) {}

    public function buat(string $tipe): AturanPeminjamanInterface
    {
        return $this->daftar[strtolower($tipe)]
            ?? throw new InvalidArgumentException("Tipe anggota tidak dikenal: $tipe");
    }
}