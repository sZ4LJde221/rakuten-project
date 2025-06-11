<?php
// src/fetch_books.php

// Namespaces and classes
namespace App;

require __DIR__ . '/../vendor/autoload.php';
use App\Config;

// Load env
Config::load(__DIR__ . '/..');

try {
    // DATABASE_URL があればそれを使う
    $databaseUrl = getenv('DATABASE_URL') ?: ($_ENV['DATABASE_URL'] ?? '');
    if ($databaseUrl) {
        // SQLAlchemy 等と同じ形式の URL ("pgsql://user:pass@host:port/db") ならパースが必要ですが、
        // Render に設定した DATABASE_URL が "pgsql:host=...;port=...;dbname=...;user=...;password=...;"
        // の形式なら直接渡せます。
        $pdo = new \PDO($databaseUrl, null, null, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ]);
    } else {
        // 従来の DB_* 環境変数
        $dsn  = Config::getDsn();
        $user = Config::get('DB_USERNAME');
        $pass = Config::get('DB_PASSWORD');
        $pdo = new \PDO($dsn, $user, $pass, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ]);
    }

    $repo = new Repository\BookRepository($pdo);
    $items = $repo->findAll();
    echo json_encode(['items' => $items], JSON_UNESCAPED_UNICODE);
} catch (\Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
