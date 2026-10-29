<?php
class PdoDatabaseConnection implements DatabaseConnectionInterface
{
    public function __construct(private PDO $pdo)
    {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    public function ambilSatu(string $sql, array $params = []): ?array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $baris = $stmt->fetch();
        return $baris === false ? null : $baris;
    }

    public function eksekusi(string $sql, array $params = []): void
    {
        $this->pdo->prepare($sql)->execute($params);
    }
}