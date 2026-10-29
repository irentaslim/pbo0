<?php
// src/Repositories/InMemory/InMemoryMemberRepository.php
namespace App\Repositories\InMemory;

use App\Contracts\MemberRepositoryInterface;
use App\Models\Member;

class InMemoryMemberRepository implements MemberRepositoryInterface
{
    private array $members = [];

    public function save(Member $member): void
    {
        $this->members[$member->getId()] = $member;
    }

    public function findById(int $id): ?Member
    {
        return $this->members[$id] ?? null;
    }
}
