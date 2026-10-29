<?php
abstract class Buku
{
    public function __construct(protected string $judul) {}

    public function getJudul(): string
    {
        return $this->judul;
    }
}