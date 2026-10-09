<?php

require_once __DIR__ . '/app/Models/User.php';
require_once __DIR__ . '/app/Services/UserService.php';

use App\Services\UserService;

$service = new UserService();

$user = $service->createUser(
    'Budi',
    'budi@example.com'
);

echo $service->displayUser($user);