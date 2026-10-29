<?php
interface DatabaseConnectionInterface
{
    /** @param array<int, mixed> $params */
    public function ambilSatu(string $sql, array $params = []): ?array;

    /** @param array<int, mixed> $params */
    public function eksekusi(string $sql, array $params = []): void;
}