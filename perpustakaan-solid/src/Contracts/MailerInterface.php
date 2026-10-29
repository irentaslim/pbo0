<?php
interface MailerInterface
{
    public function kirim(string $kepada, string $subjek, string $isi): void;
}