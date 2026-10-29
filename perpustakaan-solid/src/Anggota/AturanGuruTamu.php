<?php
class AturanGuruTamu implements AturanPeminjamanInterface
{
    public function lamaPinjamHari(): int { return 5; }
    public function tarifDendaPerHari(): int { return 1500; }
}
// didaftarkan di titik perakitan (index.php): 'guru tamu' => new AturanGuruTamu()