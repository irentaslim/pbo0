<?php
declare(strict_types=1);

// Autoloader sederhana: mencari file <NamaClass>.php di dalam folder src/
spl_autoload_register(function (string $kelas): void {
    $iter = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(__DIR__ . '/src', FilesystemIterator::SKIP_DOTS)
    );
    foreach ($iter as $file) {
        if ($file->getFilename() === $kelas . '.php') {
            require_once $file->getPathname();
            return;
        }
    }
});

// ---------- Perakitan dependency (satu-satunya tempat "new" konkret) ----------
$db = new PdoDatabaseConnection(new PDO('sqlite::memory:'));
// Untuk MySQL: new PdoDatabaseConnection(new PDO('mysql:host=localhost;dbname=perpus', 'root', ''))

foreach ([
    'CREATE TABLE buku (id INTEGER PRIMARY KEY, judul TEXT, jenis TEXT)',
    'CREATE TABLE anggota (id INTEGER PRIMARY KEY, nama TEXT, email TEXT)',
    'CREATE TABLE peminjaman (id INTEGER PRIMARY KEY AUTOINCREMENT, id_buku INTEGER,
        id_anggota INTEGER, tgl_pinjam TEXT, tgl_kembali TEXT)',
    "INSERT INTO buku (id, judul, jenis) VALUES
        (1, 'Pemrograman Berorientasi Objek', 'umum'),
        (2, 'Ensiklopedia Kalimantan', 'referensi')",
    "INSERT INTO anggota (id, nama, email) VALUES
        (1, 'Budi', 'budi@student.untan.ac.id'),
        (2, 'Dr. Siti', 'siti@untan.ac.id')",
] as $sql) {
    $db->eksekusi($sql);
}

$factory = new AturanPeminjamanFactory([
    'mahasiswa' => new AturanMahasiswa(),
    'dosen'     => new AturanDosen(),
    'umum'      => new AturanUmum(),
    'guru tamu' => new AturanGuruTamu(),
]);

$mailer  = new FakeMailer();   // ganti MailFunctionMailer() untuk email sungguhan
$service = new PerpustakaanService(
    new BukuRepository($db),
    new AnggotaRepository($db),
    new PeminjamanRepository($db),
    $factory,
    $mailer,
    new StrukPrinter()
);

// ---------- Skenario ----------
echo "\n[SKENARIO A] Mahasiswa meminjam buku\n";
$service->prosesPeminjaman(1, 1, 'mahasiswa');

echo "\n[SKENARIO B] Dosen meminjam buku\n";
$service->prosesPeminjaman(1, 2, 'dosen');

echo "\n[SKENARIO C] Tipe anggota tidak dikenal\n";
try {
    $service->prosesPeminjaman(1, 1, 'alumni');
} catch (InvalidArgumentException $e) {
    echo "Exception tertangkap: " . $e->getMessage() . "\n";
}

echo "\n[BONUS] Meminjam buku referensi\n";
try {
    $service->prosesPeminjaman(2, 1, 'mahasiswa');
} catch (DomainException $e) {
    echo "Exception tertangkap: " . $e->getMessage() . "\n";
}

echo "\n[FAKE MAILER] Email yang 'terkirim': " . count($mailer->terkirim) . "\n";
foreach ($mailer->terkirim as $m) {
    echo "- ke {$m['kepada']}: {$m['isi']}\n";
}