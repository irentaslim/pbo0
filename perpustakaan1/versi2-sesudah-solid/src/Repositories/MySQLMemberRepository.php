<?php
// src/Repositories/MySQLMemberRepository.php
namespace App\Repositories;

use App\Contracts\MemberRepositoryInterface;
use App\Models\Member;
use PDO;

class MySQLMemberRepository implements MemberRepositoryInterface
{
    public function __construct(private PDO $pdo) {}

    public function save(Member $member): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO anggota (nama, email) VALUES (?, ?)"
        );
        $stmt->execute([$member->getNama(), $member->getEmail()]);
    }

    public function findById(int $id): ?Member
    {
        $stmt = $this->pdo->prepare("SELECT * FROM anggota WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new Member((int) $row['id'], $row['nama'], $row['email']);
    }
}
