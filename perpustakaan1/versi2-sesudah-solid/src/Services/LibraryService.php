<?php
// src/Services/LibraryService.php
namespace App\Services;

use App\Contracts\BookRepositoryInterface;
use App\Contracts\MemberRepositoryInterface;
use App\Contracts\LoanRepositoryInterface;
use App\Contracts\NotifierInterface;
use App\Contracts\LoggerInterface;
use App\Models\Book;
use App\Models\Loan;
use InvalidArgumentException;
use RuntimeException;

class LibraryService
{
    public function __construct(
        private BookRepositoryInterface $bookRepo,
        private MemberRepositoryInterface $memberRepo,
        private LoanRepositoryInterface $loanRepo,
        private NotifierInterface $notifier,
        private LoggerInterface $logger
    ) {}

    public function tambahBuku(Book $book): void
    {
        $this->bookRepo->save($book);
        $this->logger->log("Buku ditambahkan: {$book->getJudul()}");
    }

    public function pinjamBuku(int $idBuku, int $idAnggota): void
    {
        // 1. Cek buku
        $book = $this->bookRepo->findById($idBuku);
        if ($book === null) {
            throw new InvalidArgumentException("Buku tidak ditemukan");
        }
        if (!$book->isAvailable()) {
            throw new RuntimeException("Stok buku habis");
        }

        // 2. Cek anggota
        $member = $this->memberRepo->findById($idAnggota);
        if ($member === null) {
            throw new InvalidArgumentException("Anggota tidak ditemukan");
        }

        // 3. Simpan peminjaman
        $loan = new Loan($idBuku, $idAnggota, date('Y-m-d H:i:s'));
        $this->loanRepo->save($loan);

        // 4. Kurangi stok
        $this->bookRepo->decrementStock($idBuku);

        // 5. Kirim notifikasi
        $this->notifier->send(
            $member->getEmail(),
            "Halo {$member->getNama()}, peminjaman buku '{$book->getJudul()}' berhasil."
        );

        // 6. Log
        $this->logger->log(
            "Peminjaman: '{$book->getJudul()}' oleh {$member->getNama()}"
        );
    }

    public function daftarBuku(): array
    {
        return $this->bookRepo->findAll();
    }
}
