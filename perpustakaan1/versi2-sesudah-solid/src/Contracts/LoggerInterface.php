<?php
// src/Contracts/LoggerInterface.php
namespace App\Contracts;

interface LoggerInterface
{
    public function log(string $message): void;
}
