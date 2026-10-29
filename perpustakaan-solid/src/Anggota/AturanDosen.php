<?php
class AturanDosen implements AturanPeminjamanInterface
{
    public function lamaPinjamHari(): int { return 14; }
    public function tarifDendaPerHari(): int { return 500; }
}