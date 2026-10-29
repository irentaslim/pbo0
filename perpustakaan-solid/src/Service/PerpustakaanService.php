<?php
class PerpustakaanService
{
    public function __construct(
        private BukuRepository $bukuRepo,
        private AnggotaRepository $anggotaRepo,
        private PeminjamanRepository $peminjamanRepo,
        private AturanPeminjamanFactory $aturanFactory,
        private MailerInterface $mailer,
        private StrukPrinter $printer,
    ) {}

    public function prosesPeminjaman(int $idBuku, int $idAnggota, string $tipe): void
    {
        $aturan = $this->aturanFactory->buat($tipe);   // OCP: lolos jika tipe terdaftar
        $buku = $this->bukuRepo->cariById($idBuku);

        if (!$buku instanceof DapatDipinjam) {          // LSP: aturan bisnis eksplisit
            throw new DomainException("Buku '{$buku->getJudul()}' adalah buku referensi dan tidak dapat dipinjam");
        }

        $email = $this->anggotaRepo->cariEmail($idAnggota);
        $this->peminjamanRepo->simpan($idBuku, $idAnggota, $aturan->lamaPinjamHari());
        $this->mailer->kirim($email, 'Konfirmasi Peminjaman', "Buku '{$buku->getJudul()}' berhasil dipinjam");

        $this->printer->cetak($buku->getJudul(), $aturan->lamaPinjamHari(), $aturan->tarifDendaPerHari());
    }
}