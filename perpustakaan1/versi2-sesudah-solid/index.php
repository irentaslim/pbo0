<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Book;
use App\Models\Member;
use App\Repositories\MySQLBookRepository;
use App\Repositories\MySQLMemberRepository;
use App\Repositories\MySQLLoanRepository;
use App\Notifications\EmailNotifier;
use App\Loggers\FileLogger;
use App\Services\LibraryService;

// ============ SETUP DEPENDENSI ============
$pdo = new PDO(
    'mysql:host=localhost;dbname=perpustakaan',
    'root',
    'rahasia'
);

$bookRepo   = new MySQLBookRepository($pdo);
$memberRepo = new MySQLMemberRepository($pdo);
$loanRepo   = new MySQLLoanRepository($pdo);
$notifier   = new EmailNotifier();
$logger     = new FileLogger(__DIR__ . '/logs/perpustakaan.log');

// ============ RANGKAI SERVICE ============
$service = new LibraryService(
    $bookRepo,
    $memberRepo,
    $loanRepo,
    $notifier,
    $logger
);

// ============ JALANKAN ============
try {
    $service->tambahBuku(new Book(0, 'Laskar Pelangi', 'Andrea Hirata', 3));
    $service->tambahBuku(new Book(0, 'Bumi Manusia', 'Pramoedya', 2));

    // (asumsi anggota sudah ada di DB dengan id=1)
    $service->pinjamBuku(1, 1);
    echo "✅ Peminjaman berhasil!\n";

    foreach ($service->daftarBuku() as $buku) {
        echo "- {$buku->getJudul()} (stok: {$buku->getStok()})\n";
    }
} catch (Throwable $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
