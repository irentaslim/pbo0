<?php
interface ManajemenKatalogInterface
{
    public function tambahBuku(array $data): void;
    public function hapusBuku(int $id): void;
}