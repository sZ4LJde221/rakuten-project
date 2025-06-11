<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // hostを localhost から rakuten_db に変更
    $pdo = new PDO('pgsql:host=rakuten_db;port=5432;dbname=rakuten', 'rakuten', 'rakutenpass');
    echo "DB接続成功\n";
} catch (PDOException $e) {
    echo "DB接続失敗: " . $e->getMessage();
}
