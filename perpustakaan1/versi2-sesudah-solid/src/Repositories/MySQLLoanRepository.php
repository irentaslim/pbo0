<?php
// src/Repositories/MySQLLoanRepository.php
namespace App\Repositories;

use App\Contracts\LoanRepositoryInterface;
use App\Models\Loan;
use PDO;

class MySQLLoanRepository implements LoanRepositoryInterface
{
    public function __construct(private PDO $pdo) {}

    public function save(Loan $loan): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO peminjaman (id_buku, id_anggota, tanggal)
             VALUES (?, ?, ?)"
        );
        $stmt->execute([
            $loan->getIdBook(),
            $loan->getIdMember(),
            $loan->getTanggal()
        ]);
    }
}
