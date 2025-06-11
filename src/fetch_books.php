<?php
// src/fetch_books.php

// Namespaces and classes
namespace App;

require __DIR__ . '/../vendor/autoload.php';
use App\Config;
use App\Repository\BookRepository;

// Load env
Config::load(__DIR__ . '/..');

// Connect DB
$dsn  = Config::getDsn();
$user = Config::get('DB_USERNAME');
$pass = Config::get('DB_PASSWORD');
try {
    $pdo = new \PDO($dsn, $user, $pass, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    $repo = new BookRepository($pdo);
    $items = $repo->findAll();
    echo json_encode(['items' => $items], JSON_UNESCAPED_UNICODE);
} catch (\Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}