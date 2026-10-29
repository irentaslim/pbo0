<?php
// src/Contracts/MemberRepositoryInterface.php
namespace App\Contracts;

use App\Models\Member;

interface MemberRepositoryInterface
{
    public function save(Member $member): void;
    public function findById(int $id): ?Member;
}
