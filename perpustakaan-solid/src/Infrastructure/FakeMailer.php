<?php
final class FakeMailer implements MailerInterface
{
    /** @var array<int, array{kepada: string, subjek: string, isi: string}> */
    public array $terkirim = [];

    public function kirim(string $kepada, string $subjek, string $isi): void
    {
        $this->terkirim[] = ['kepada' => $kepada, 'subjek' => $subjek, 'isi' => $isi];
    }
}
