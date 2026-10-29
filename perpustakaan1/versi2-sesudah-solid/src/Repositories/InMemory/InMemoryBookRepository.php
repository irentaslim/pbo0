<?php
// src/Repositories/InMemory/InMemoryBookRepository.php
namespace App\Repositories\InMemory;

use App\Contracts\BookRepositoryInterface;
use App\Models\Book;

class InMemoryBookRepository implements BookRepositoryInterface
{
    private array $books = [];

    public function save(Book $book): void
    {
        $this->books[$book->getId()] = $book;
    }

    public function findById(int $id): ?Book
    {
        return $this->books[$id] ?? null;
    }

    public function findAll(): array
    {
        return array_values($this->books);
    }

    public function decrementStock(int $id): bool
    {
        if (!isset($this->books[$id])) return false;
        if ($this->books[$id]->getStok() <= 0) return false;

        $old = $this->books[$id];
        $this->books[$id] = new Book(
            $old->getId(),
            $old->getJudul(),
            $old->getPenulis(),
            $old->getStok() - 1
        );
        return true;
    }
}
