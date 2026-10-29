<?php
class AturanUmum implements AturanPeminjamanInterface
{
    public function lamaPinjamHari(): int { return 3; }
    public function tarifDendaPerHari(): int { return 2000; }
}