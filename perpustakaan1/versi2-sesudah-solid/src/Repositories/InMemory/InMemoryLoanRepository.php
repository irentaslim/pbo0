<?php
// src/Repositories/InMemory/InMemoryLoanRepository.php
namespace App\Repositories\InMemory;

use App\Contracts\LoanRepositoryInterface;
use App\Models\Loan;

class InMemoryLoanRepository implements LoanRepositoryInterface
{
    private array $loans = [];

    public function save(Loan $loan): void
    {
        $this->loans[] = $loan;
    }

    public function all(): array
    {
        return $this->loans;
    }
}
