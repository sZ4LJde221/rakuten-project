<?php
namespace App;

use Dotenv\Dotenv;

class Config
{
    private static array $env;

    public static function load(string $basePath): void
    {
        // .env 読み込み
        $dotenv = Dotenv::createImmutable($basePath);
        $dotenv->load();

        // 必須項目のバリデート
        $required = ['APPLICATION_ID','DB_HOST','DB_PORT','DB_DATABASE','DB_USERNAME','DB_PASSWORD'];
        $dotenv->required($required)->notEmpty();

        self::$env = $_ENV;
    }

    public static function get(string $key): string
    {
        if (!isset(self::$env[$key])) {
            throw new \RuntimeException("Environment variable {$key} is not set.");
        }
        return self::$env[$key];
    }

    // DB 接続用の DSN を返す例
    public static function getDsn(): string
    {
        // Supabase などで与えられる DATABASE_URL があればそれを使用
        if (getenv('DATABASE_URL')) {
            return getenv('DATABASE_URL');
        }
    
        $conn = self::get('DB_CONNECTION'); // 'pgsql' or 'mysql'
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
