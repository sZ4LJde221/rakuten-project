<?php
namespace App;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

class Config
{
    /**
     * .env を読み込む。ファイルがなくても無視。
     */
    public static function load(string $basePath): void
    {
        try {
            $dotenv = Dotenv::createImmutable($basePath);
            $dotenv->load();
            // 必須チェック（.env があれば）
            $required = ['APPLICATION_ID', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'];
            $dotenv->required($required)->notEmpty();
        } catch (InvalidPathException $e) {
            // .env がなくても OK（Render の環境変数を使う場合）
        }
    }

    /**
     * 環境変数を取得。getenv→$_ENV→$_SERVER の順で探し、
     * いずれにもなければ例外。
     */
    public static function get(string $key): string
    {
        $value = getenv($key);
        if ($value === false) {
            if (isset($_ENV[$key]) && $_ENV[$key] !== '') {
                $value = $_ENV[$key];
            } elseif (isset($_SERVER[$key]) && $_SERVER[$key] !== '') {
                $value = $_SERVER[$key];
            }
        }
        if ($value === false || $value === '') {
            throw new \RuntimeException("Environment variable {$key} is not set.");
        }
        return $value;
    }

    /**
     * DSN を組み立てて返す。
     */
    public static function getDsn(): string
    {
        // DATABASE_URL（Heroku/Supabase など）があれば優先
        $databaseUrl = getenv('DATABASE_URL') ?: ($_ENV['DATABASE_URL'] ?? ($_SERVER['DATABASE_URL'] ?? false));
        if ($databaseUrl) {
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
