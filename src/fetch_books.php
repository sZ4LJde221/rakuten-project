<?php
// src/fetch_books.php
namespace App;

require __DIR__ . '/../vendor/autoload.php';
use App\Config;
use App\Repository\BookRepository;

// 環境変数読み込み
Config::load(__DIR__ . '/..');

try {
    // DATABASE_URL または DB_* で PDO 接続
    $databaseUrl = getenv('DATABASE_URL') ?: '';
    if ($databaseUrl) {
        // Render の DATABASE_URL が URI 形式なら parse_url 後に組み立て
        $parts = parse_url($databaseUrl);
        $dsn      = sprintf('pgsql:host=%s;port=%s;dbname=%s;', $parts['host'], $parts['port'], ltrim($parts['path'], '/'));
        $user     = $parts['user'];
        $pass     = $parts['pass'];
    } else {
        $dsn  = Config::getDsn();
        $user = Config::get('DB_USERNAME');
        $pass = Config::get('DB_PASSWORD');
    }
    $pdo  = new \PDO($dsn, $user, $pass, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    $repo = new BookRepository($pdo);
    $items = $repo->findAll();

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['items' => $items], JSON_UNESCAPED_UNICODE);
} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
