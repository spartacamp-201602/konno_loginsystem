<?php

require_once('config.php');
require_once('function.php');

session_start();

if (empty($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

// ユーザ情報を取得する
$dbh = connectDb();

$sql = "select * from users where id = :id";

$stmt = $dbh->prepare($sql);
$stmt->bindParam(":id", $_SESSION['id']);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

$name = $user['name'];
$password = $user['password'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $password = $_POST['password'];

    $errors = array();

    // バリデーション
    if (empty($name)) {
        $errors[] = 'ユーザネームが未入力です';
    }

    if (empty($password)) {
        $errors[] = 'パスワードが未入力です';
    }

    if ($name == $user['name'] && $password == $user['password']) {
        $errors[] = '名前, パスワードどちらも変更されていません';
    }

    // バリデーション突破
    if (empty($errors)) {

        $dbh = connectDb();
        $sql = "update users set name = :name, password = :password where id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(":id", $user['id']);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":password", $password);
        $uniqueResult = $stmt->execute();

        if ($uniqueResult) {
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'そのユーザ名は既に登録されています';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン画面</title>
    <style>
        .error{
            color:tomato;
        }
    </style>
</head>
<body>
    <h1>ユーザ情報編集</h1>
    <?php if (isset($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach ?>
        </div>
    <?php endif ?>
    <form action="" method="post">
        ユーザネーム: <input type="text" name="name" value="<?= $name ?>"><br>
        パスワード: <input type="text" name="password" value="<?= $password?>"><br>
        <input type="submit" value="編集する">
    </form>
    <a href="index.php">戻る</a>
</body>
</html>