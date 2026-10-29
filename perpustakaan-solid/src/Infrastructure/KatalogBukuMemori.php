<?php
class KatalogBukuMemori implements ManajemenKatalogInterface
{
    /** @var array<int, array<string, mixed>> */
    private array $data = [];
    private int $idBerikut = 1;

    public function tambahBuku(array $data): void
    {
        $this->data[$this->idBerikut++] = $data;
    }

    public function hapusBuku(int $id): void
    {
        unset($this->data[$id]);
    }
}