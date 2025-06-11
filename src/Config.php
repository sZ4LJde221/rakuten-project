<?php
namespace App;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

class Config
{
    public static function load(string $basePath): void
    {
        try {
            $dotenv = Dotenv::createImmutable($basePath);
            $dotenv->load();
            $required = ['APPLICATION_ID', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'];
            $dotenv->required($required)->notEmpty();
        } catch (InvalidPathException $e) {
            // .env がなくても無視
        }
    }

    public static function get(string $key): string
    {
        $value = false;

        // 1. Apache 環境（apache_getenv）
        if (function_exists('apache_getenv')) {
            $val = apache_getenv($key, true);
            if ($val !== false && $val !== '') {
                $value = $val;
            }
        }

        // 2. getenv()
        if ($value === false) {
            $val = getenv($key);
            if ($val !== false && $val !== '') {
                $value = $val;
            }
        }

        // 3. $_ENV
        if ($value === false && isset($_ENV[$key]) && $_ENV[$key] !== '') {
            $value = $_ENV[$key];
        }

        // 4. $_SERVER
        if ($value === false && isset($_SERVER[$key]) && $_SERVER[$key] !== '') {
            $value = $_SERVER[$key];
        }

        if ($value === false) {
            throw new \RuntimeException("Environment variable {$key} is not set.");
        }

        return $value;
    }

    public static function getDsn(): string
    {
        // Supabase など DATABASE_URL があれば優先
        $dbUrl = false;
        if (function_exists('apache_getenv')) {
            $dbUrl = apache_getenv('DATABASE_URL', true);
        }
        if ($dbUrl === false) {
            $dbUrl = getenv('DATABASE_URL') ?: ($_ENV['DATABASE_URL'] ?? ($_SERVER['DATABASE_URL'] ?? false));
        }
        if ($dbUrl) {
            return $dbUrl;
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
