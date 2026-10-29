<?php
interface SirkulasiBukuInterface
{
    public function pinjamBuku(int $id): void;
    public function kembalikanBuku(int $id): void;
}