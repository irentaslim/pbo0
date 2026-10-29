<?php
// src/Loggers/FileLogger.php
namespace App\Loggers;

use App\Contracts\LoggerInterface;

class FileLogger implements LoggerInterface
{
    public function __construct(private string $logFile) {}

    public function log(string $message): void
    {
        $line = date('[Y-m-d H:i:s] ') . $message . PHP_EOL;
        file_put_contents($this->logFile, $line, FILE_APPEND);
    }
}
