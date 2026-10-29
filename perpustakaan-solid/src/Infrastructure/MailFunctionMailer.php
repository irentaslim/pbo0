<?php
class MailFunctionMailer implements MailerInterface
{
    public function kirim(string $kepada, string $subjek, string $isi): void
    {
        if (!mail($kepada, $subjek, $isi)) {
            throw new RuntimeException("Gagal mengirim email ke $kepada");
        }
    }
}