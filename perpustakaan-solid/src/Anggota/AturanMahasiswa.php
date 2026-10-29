<?php
class AturanMahasiswa implements AturanPeminjamanInterface
{
    public function lamaPinjamHari(): int { return 7; }
    public function tarifDendaPerHari(): int { return 1000; }
}