<?php
// src/fetch_books.php
namespace App;

require __DIR__ . '/../vendor/autoload.php';
use App\Repository\BookRepository;

try {
    $databaseUrl = getenv('DATABASE_URL') ?: '';
    if ($databaseUrl === '') {
        throw new \RuntimeException('DATABASE_URL is not set.');
    }

    // URI をパース
    $parts = parse_url($databaseUrl);
    if (!$parts || !isset($parts['host'], $parts['port'], $parts['path'], $parts['user'], $parts['pass'])) {
        throw new \RuntimeException('Invalid DATABASE_URL format.');
    }

    // IPv4 アドレスを解決
    $ipv4 = gethostbyname($parts['host']);
    if (filter_var($ipv4, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
        throw new \RuntimeException("Cannot resolve IPv4 for {$parts['host']}");
    }

    $host     = $ipv4;
    $port     = $parts['port'];
    $dbname   = ltrim($parts['path'], '/');
    $username = $parts['user'];
    $password = $parts['pass'];

    // IPv4 アドレスを使って DSN を組み立て
    $dsn = sprintf('pgsql:host=%s;port=%d;dbname=%s;', $host, $port, $dbname);

    // PDO 接続
    $pdo = new \PDO($dsn, $username, $password, [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
    ]);

    $repo  = new BookRepository($pdo);
    $items = $repo->findAll();

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['items' => $items], JSON_UNESCAPED_UNICODE);

} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
