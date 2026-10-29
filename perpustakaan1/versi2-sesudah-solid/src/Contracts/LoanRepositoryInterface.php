<?php
// src/Contracts/LoanRepositoryInterface.php
namespace App\Contracts;

use App\Models\Loan;

interface LoanRepositoryInterface
{
    public function save(Loan $loan): void;
}
