<?php

/**
 * Class DatabaseConnection (Singleton)
 * Hanya ada SATU objek PDO yang dipakai bersama oleh seluruh class.
 */
class DatabaseConnection
{
    // Menyimpan satu-satunya instance class ini
    private static ?DatabaseConnection $instance = null;

    // Objek PDO yang dibungkus
    private PDO $pdo;

    // Konfigurasi koneksi (sesuaikan dengan XAMPP/Laragon kamu)
    private const HOST     = 'localhost';
    private const DBNAME   = 'materi_pdo';
    private const USERNAME = 'root';
    private const PASSWORD = '';

    /**
     * Constructor dibuat PRIVATE agar tidak bisa dipanggil dengan "new"
     * dari luar class.
     */
    private function __construct()
    {
        try {
            $dsn = "mysql:host=" . self::HOST . ";dbname=" . self::DBNAME . ";charset=utf8mb4";

            $this->pdo = new PDO($dsn, self::USERNAME, self::PASSWORD, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            die("Koneksi gagal! " . $e->getMessage());
        }
    }

    // Cegah objek di-clone
    private function __clone()
    {
    }

    // Cegah objek di-unserialize
    public function __wakeup()
    {
        throw new Exception("Singleton tidak boleh di-unserialize.");
    }

    /**
     * Ambil instance tunggal. Dibuat hanya pada pemanggilan pertama.
     */
    public static function getInstance(): DatabaseConnection
    {
        if (self::$instance === null) {
            self::$instance = new DatabaseConnection();
        }

        return self::$instance;
    }

    /**
     * Ambil objek PDO untuk menjalankan query.
     */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
