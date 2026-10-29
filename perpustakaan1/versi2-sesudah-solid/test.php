<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Book;
use App\Models\Member;
use App\Repositories\InMemory\InMemoryBookRepository;
use App\Repositories\InMemory\InMemoryMemberRepository;
use App\Repositories\InMemory\InMemoryLoanRepository;
use App\Notifications\FakeNotifier;
use App\Loggers\InMemoryLogger;
use App\Services\LibraryService;

// ============ SETUP DEPENDENSI TESTING ============
$bookRepo   = new InMemoryBookRepository();
$memberRepo = new InMemoryMemberRepository();
$loanRepo   = new InMemoryLoanRepository();
$notifier   = new FakeNotifier();
$logger     = new InMemoryLogger();

$service = new LibraryService(
    $bookRepo, $memberRepo, $loanRepo, $notifier, $logger
);

// ============ SETUP DATA ============
$bookRepo->save(new Book(1, 'Laskar Pelangi', 'Andrea Hirata', 2));
$memberRepo->save(new Member(1, 'Budi', 'budi@example.com'));

// ============ JALANKAN TEST ============
$service->pinjamBuku(1, 1);

// ============ ASSERT ============
$book = $bookRepo->findById(1);
assert($book->getStok() === 1, 'Stok harus berkurang dari 2 menjadi 1');

$sent = $notifier->getSent();
assert(count($sent) === 1, 'Harus ada 1 notifikasi terkirim');
assert($sent[0]['to'] === 'budi@example.com', 'Email tujuan harus benar');

$logs = $logger->getLogs();
assert(count($logs) === 1, 'Harus ada 1 log tercatat');

echo "✅ Semua test lulus!\n";
echo "- Stok buku sekarang: " . $book->getStok() . "\n";
echo "- Notifikasi terkirim ke: " . $sent[0]['to'] . "\n";
echo "- Log: " . $logs[0] . "\n";
