<?php

require_once __DIR__ . '/vendor/autoload.php';
use App\Services\UserService;

try {
    $service = new UserService();
    $user = $service->createUser(
      'Iren',
      'iren@example.com'
    );
    echo $service->displayUser($user);
} catch (Throwable $e) {
    echo "<h3>Terjadi Error</h3>";
    echo "<p>Pesan: " . $e->getMessage() . "</p>";
    echo "<p>File: " . $e->getFile() . "</p>";
    echo "<p>Line: " . $e->getLine() . "</p>";
}