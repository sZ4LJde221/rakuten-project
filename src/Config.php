<?php
namespace App;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

class Config
{
    public static function load(string $basePath): void
    {
        // .env ファイルの読み込みは任意にしてエラーを握りつぶす
        try {
            $dotenv = Dotenv::createImmutable($basePath);
            $dotenv->load();

            // 必須項目のバリデート
            $required = ['APPLICATION_ID', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'];
            $dotenv->required($required)->notEmpty();
        } catch (InvalidPathException $e) {
            // .env がなくても無視（Renderなど環境変数が設定されている場合）
        }
    }

    public static function get(string $key): string
    {
        $value = getenv($key);
        if ($value === false) {
            throw new \RuntimeException("Environment variable {$key} is not set.");
        }
        return $value;
    }

    public static function getDsn(): string
    {
        $databaseUrl = getenv('DATABASE_URL');
        if ($databaseUrl !== false) {
            return $databaseUrl;
        }

        $conn = self::get('DB_CONNECTION');
        $host = self::get('DB_HOST');
        $port = self::get('DB_PORT');
        $db   = self::get('DB_DATABASE');

        if ($conn === 'pgsql') {
            return sprintf('pgsql:host=%s;port=%s;dbname=%s;', $host, $port, $db);
        }

        return sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            $host,
            $port,
            $db
        );
    }
}
