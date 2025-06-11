<?php
// public/api/fetch_books.php

// デバッグ：環境変数の中身をダンプして即終了
header('Content-Type: text/plain; charset=utf-8');
var_dump(
    'apache_getenv DB_USERNAME:', function_exists('apache_getenv') ? apache_getenv('DB_USERNAME', true) : 'N/A',
    'getenv DB_USERNAME:', getenv('DB_USERNAME'),
    '$_ENV DB_USERNAME:', ($_ENV['DB_USERNAME'] ?? ''),
    '$_SERVER DB_USERNAME:', ($_SERVER['DB_USERNAME'] ?? '')
);
exit;

// 以下、通常の処理…
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../src/fetch_books.php';
