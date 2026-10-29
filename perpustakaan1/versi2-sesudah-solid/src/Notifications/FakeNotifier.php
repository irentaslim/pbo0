<?php
// src/Notifications/FakeNotifier.php
namespace App\Notifications;

use App\Contracts\NotifierInterface;

class FakeNotifier implements NotifierInterface
{
    private array $sent = [];

    public function send(string $to, string $message): bool
    {
        $this->sent[] = ['to' => $to, 'message' => $message];
        return true;
    }

    public function getSent(): array
    {
        return $this->sent;
    }
}
