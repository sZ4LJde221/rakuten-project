<?php
// public/index.php

// Composerのオートロード
require __DIR__ . '/../vendor/autoload.php';

use App\Config;

// アプリID取得を試みる（失敗時は null として続行）
$appId = null;
try {
    Config::load(__DIR__ . '/..');
    $appId = Config::get('APPLICATION_ID');
} catch (\Exception $e) {
    // 環境変数未設定時の例外を無視
    //（本番環境ではログ出力など検討）
}
?>
<!DOCTYPE html>

<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>100円以下電子書籍一覧</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <h1>ようこそ！100円以下電子書籍一覧サイトへ</h1>

    <?php if ($appId): ?>

        <p>アプリID: <?= htmlspecialchars($appId, ENT_QUOTES, 'UTF-8') ?></p>

    <?php else: ?>

        <p>※ 現在アプリIDが設定されていません。公開後に設定してください。</p>

    <?php endif; ?>

    <div id="book-list">
        <!-- JSで書籍リストをフェッチして描画 -->
    </div>
    <script>
        window.RAKUTEN_APP_ID = <?= $appId ? json_encode($appId) : 'null' ?>;
    </script>
    <script src="assets/js/app.js"></script>
</body>

</html>