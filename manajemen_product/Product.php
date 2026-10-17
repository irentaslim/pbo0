<?php

require_once __DIR__ . '/DatabaseConnection.php';

/**
 * Class Product
 * Berisi operasi CRUD ke tabel "products" memakai PDO + prepared statement.
 */
class Product
{
    public ?int $id;
    public string $nama;
    public float $harga;
    public int $stok;

    public function __construct(string $nama = '', float $harga = 0, int $stok = 0, ?int $id = null)
    {
        $this->id    = $id;
        $this->nama  = $nama;
        $this->harga = $harga;
        $this->stok  = $stok;
    }

    /** Ambil koneksi dari Singleton */
    private static function db(): PDO
    {
        return DatabaseConnection::getInstance()->getConnection();
    }

    /** Ubah satu baris hasil query menjadi objek Product */
    private static function fromRow(array $row): Product
    {
        return new Product($row['nama'], (float) $row['harga'], (int) $row['stok'], (int) $row['id']);
    }

    /** CREATE - tambah data produk baru */
    public function save(): bool
    {
        $sql  = "INSERT INTO products (nama, harga, stok) VALUES (:nama, :harga, :stok)";
        $stmt = self::db()->prepare($sql);

        $ok = $stmt->execute([
            ':nama'  => $this->nama,
            ':harga' => $this->harga,
            ':stok'  => $this->stok,
        ]);

        if ($ok) {
            $this->id = (int) self::db()->lastInsertId();
        }

        return $ok;
    }

    /** READ - lihat semua produk */
    public static function findAll(): array
    {
        $stmt = self::db()->query("SELECT * FROM products ORDER BY id DESC");

        $hasil = [];
        foreach ($stmt->fetchAll() as $row) {
            $hasil[] = self::fromRow($row);
        }

        return $hasil;
    }

    /** READ - lihat satu produk berdasarkan id (null jika tidak ada) */
    public static function findById(int $id): ?Product
    {
        $stmt = self::db()->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row ? self::fromRow($row) : null;
    }

    /** UPDATE - ubah data produk dengan id tertentu memakai nilai pada objek ini */
    public function update(int $id): bool
    {
        $sql  = "UPDATE products SET nama = :nama, harga = :harga, stok = :stok WHERE id = :id";
        $stmt = self::db()->prepare($sql);

        return $stmt->execute([
            ':nama'  => $this->nama,
            ':harga' => $this->harga,
            ':stok'  => $this->stok,
            ':id'    => $id,
        ]);
    }

    /** DELETE - hapus produk dengan id tertentu */
    public static function delete(int $id): bool
    {
        $stmt = self::db()->prepare("DELETE FROM products WHERE id = :id");

        return $stmt->execute([':id' => $id]);
    }
}
