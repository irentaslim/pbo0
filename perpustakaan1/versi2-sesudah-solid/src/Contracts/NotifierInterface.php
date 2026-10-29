<?php
// src/Contracts/NotifierInterface.php
namespace App\Contracts;

interface NotifierInterface
{
    public function send(string $to, string $message): bool;
}
