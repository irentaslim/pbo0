<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Services\UserService;
use App\Services\ProductService;

try {
    // Bagian User (dari Praktikum 2)
    $userService = new UserService();
    $user = $userService->createUser('Budi', 'budi@example.com');

    echo "<h3>Data User</h3>";
    echo $userService->displayUser($user);

    // Bagian Product (Tugas 2)
    $productService = new ProductService();

    $product1 = $productService->createProduct('Laptop Asus', 7500000);
    $product2 = $productService->createProduct('Mouse Wireless', 150000);

    echo "<h3>Data Product</h3>";
    echo $productService->displayProduct($product1);
    echo "<br><br>";
    echo $productService->displayProduct($product2);

} catch (Throwable $e) {
    echo "<h3>Terjadi Error</h3>";
    echo "<p>Pesan: " . $e->getMessage() . "</p>";
    echo "<p>File: " . $e->getFile() . "</p>";
    echo "<p>Line: " . $e->getLine() . "</p>";
}