<?php

require_once('config.php');
require_once('function.php');

session_start();

if (empty($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

// ユーザネームを表示したい
// -> $_SESSION['id'] の情報を取得して
// -> select 文でユーザ情報を取ってきて
// -> ユーザネームを表示する

$dbh = connectDb();

$sql = "select * from users where id = :id";

$stmt = $dbh->prepare($sql);
$stmt->bindParam(":id", $_SESSION['id']);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>会員限定画面</title>
</head>
<body>
    <h1>登録したユーザーのみ閲覧可能です!</h1>
    <h2><?= h($user['name']) ?>さん ようこそ！</h2>
    <a href="edit.php">ユーザー情報編集</a><br>
    <a href="logout.php">ログアウト</a>
</body>
</html>