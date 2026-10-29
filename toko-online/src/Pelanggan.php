<?php
namespace Src;

class Pelanggan
{
    private string $idPelanggan;
    private string $nama;
    private string $email;
    private string $alamat;

    public function __construct(string $idPelanggan, string $nama, string $email, string $alamat)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Email pelanggan tidak valid.');
        }
        $this->idPelanggan = $idPelanggan;
        $this->nama = $nama;
        $this->email = $email;
        $this->alamat = $alamat;
    }

    public function getIdPelanggan(): string { return $this->idPelanggan; }
    public function getNama(): string { return $this->nama; }
    public function getEmail(): string { return $this->email; }
    public function getAlamat(): string { return $this->alamat; }

    public function buatPesanan(string $nomorPesanan): Pesanan
    {
        return new Pesanan($nomorPesanan, date('Y-m-d'), $this);
    }
}
