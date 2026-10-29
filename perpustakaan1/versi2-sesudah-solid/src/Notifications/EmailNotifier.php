<?php
// src/Notifications/EmailNotifier.php
namespace App\Notifications;

use App\Contracts\NotifierInterface;

class EmailNotifier implements NotifierInterface
{
    public function send(string $to, string $message): bool
    {
        // Di production: pakai PHPMailer/SMTP
        return mail($to, 'Notifikasi Perpustakaan', $message);
    }
}
