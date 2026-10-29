# Perpustakaan SOLID — perbaikan

## Cara menjalankan di Laragon

1. Ekstrak folder `perpustakaan-solid` ke `C:\laragon\www\`.
2. Timpa file proyek lama dengan file dari ZIP ini. Jangan menaruhnya dalam folder `perpustakaan-solid/perpustakaan-solid`.
3. Gunakan PHP 8.0 atau lebih baru dan aktifkan ekstensi `pdo_sqlite` melalui menu ekstensi PHP Laragon. Restart Laragon setelah mengubah ekstensi.
4. Buka `http://localhost/perpustakaan-solid/` atau jalankan `php index.php` dari terminal pada folder proyek.

Proyek ini tidak menggunakan Composer dan tidak membutuhkan `vendor/autoload.php`.
Semua class berada pada namespace global (tanpa baris `namespace`). Folder tetap bernama `src/`; jangan menambahkan awalan `Src\` atau `App\` pada pemanggilan class. Autoloader dalam index.php sudah sesuai dengan struktur ini.

## Penyebab error dan perbaikan

- **src/Infrastructure/FakeMailer.php:** contoh PRODUKSI dan PENGUJIAN di luar class ikut dijalankan ketika autoload memuat FakeMailer. Akibatnya kode mencoba membuka koneksi MySQL `perpus`, memakai variabel `$factory` dari konteks pemuatan, serta membuat PerpustakaanService dengan tiga argumen padahal constructor membutuhkan enam argumen dengan tipe berbeda. Contoh tersebut dihapus; file sekarang hanya mendefinisikan class FakeMailer. Perakitan service yang benar sudah ada di index.php.
- **src/Buku/BukuReferensi.php:** definisi fungsi contoh `pinjam()` dan pemanggilannya berada di luar class, sehingga ikut berjalan saat class dimuat. Bagian contoh dihapus; class BukuReferensi tetap tidak dapat dipinjam.
- **src/Service/PerpustakaanService.php:** pencarian email/validasi keberadaan anggota dipindah sebelum INSERT peminjaman, agar ID anggota yang tidak ada tidak meninggalkan catatan peminjaman.
- **index.php:** AturanGuruTamu didaftarkan dengan kunci `guru tamu`, sehingga implementasinya dapat dipakai melalui factory.

## Hasil yang diharapkan

- Skenario A: mahasiswa berhasil meminjam, durasi 7 hari, tarif denda Rp 1000/hari.
- Skenario B: dosen berhasil meminjam, durasi 14 hari, tarif denda Rp 500/hari.
- Skenario C: pesan tipe anggota alumni tidak dikenal, ditangkap oleh try/catch.
- BONUS: pesan buku referensi tidak dapat dipinjam, ditangkap oleh try/catch.
- FakeMailer mencatat 2 email. Tidak ada email sungguhan yang dikirim.

Pesan “Exception tertangkap” pada skenario C dan BONUS adalah hasil pengujian yang disengaja.

## Catatan database

Index.php tetap menggunakan SQLite in-memory: data hanya untuk demonstrasi dan dibuat ulang setiap eksekusi. MySQL tidak diperlukan untuk menjalankan versi ini. Mengganti baris koneksi saja ke MySQL belum cukup, karena skema saat ini memakai sintaks SQLite (`AUTOINCREMENT`) dan inisialisasi tabel/data dijalankan setiap kali. Migrasi MySQL memerlukan penyesuaian skema dan pemisahan inisialisasi data.

Jika muncul `could not find driver`, pastikan `pdo_sqlite` aktif pada versi PHP yang benar-benar digunakan Laragon. Periksa `php --ri pdo_sqlite` di terminal Laragon; versi PHP terminal dan web dapat berbeda.

## Verifikasi

Diuji dengan PHP 8.3 dan PDO SQLite: seluruh file PHP lolos pemeriksaan sintaks; skenario A/B berhasil; skenario C/BONUS ditolak sesuai harapan; tercatat dua peminjaman dan dua email. ID buku/anggota yang tidak ada ditolak tanpa menambah catatan peminjaman. Seluruh class dapat dimuat tanpa mengeksekusi kode contoh.
