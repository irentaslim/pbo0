<?php
interface PemberiNotifikasiInterface
{
    public function kirimNotifikasi(string $pesan): void;
}