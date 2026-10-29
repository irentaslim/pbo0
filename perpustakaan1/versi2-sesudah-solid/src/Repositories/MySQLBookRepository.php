<?php
// src/Repositories/MySQLBookRepository.php
namespace App\Repositories;

use App\Contracts\BookRepositoryInterface;
use App\Models\Book;
use PDO;

class MySQLBookRepository implements BookRepositoryInterface
{
    public function __construct(private PDO $pdo) {}

    public function save(Book $book): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO buku (judul, penulis, stok) VALUES (?, ?, ?)"
        );
        $stmt->execute([
            $book->getJudul(),
            $book->getPenulis(),
            $book->getStok()
        ]);
    }

    public function findById(int $id): ?Book
    {
        $stmt = $this->pdo->prepare("SELECT * FROM buku WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new Book(
            (int) $row['id'],
            $row['judul'],
            $row['penulis'],
            (int) $row['stok']
        );
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM buku");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($row) => new Book(
                (int) $row['id'],
                $row['judul'],
                $row['penulis'],
                (int) $row['stok']
            ),
            $rows
        );
    }

    public function decrementStock(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE buku SET stok = stok - 1 WHERE id = ? AND stok > 0"
        );
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
