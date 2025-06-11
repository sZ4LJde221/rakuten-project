<?php
// public/index.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// JSONリクエストならこのファイル自身で処理
if (isset($_GET['json']) && $_GET['json'] === '1') {
    header('Content-Type: application/json; charset=utf-8');
    require __DIR__ . '/api/fetch_books.php';
    exit;
}

// HTML レンダリング
require __DIR__ . '/../vendor/autoload.php';
use App\Config;

$appId = null;
try {
    Config::load(__DIR__ . '/..');
    $appId = Config::get('APPLICATION_ID');
} catch (\Exception $e) {
    // 環境変数未設定は無視
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
    <p>※ アプリIDが設定されていません。公開後に設定してください。</p>
  <?php endif; ?>

  <div id="book-list">
    <p>Loading...</p>
  </div>
  <script>
    window.RAKUTEN_APP_ID = <?= $appId ? json_encode($appId) : 'null' ?>;
  </script>
  <script src="assets/js/app.js"></script>
</body>
</html>