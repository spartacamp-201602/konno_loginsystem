<?php

// 設定ファイルを読み込む
require_once('config.php');
require_once('function.php');

//

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

    // バリデーションを突破
    if (empty($errors)) {
        $dbh = connectDb();

        $sql = "insert into users (name, password, created_at) ";
        $sql.= "values (:name, :password, now())";

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':password', $password);

        $result = $stmt->execute();

        if ($result) {
            header('Location: login.php');
            exit;
        } else {
            $errors[] = 'その名前は既に登録されています';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>新規登録画面</title>
</head>
<style>
    .error {
        color: red;
        list-style: none;
    }
</style>
<body>
    <h1>新規ユーザー登録</h1>

    <?php if (isset($errors)) : ?>
        <div class='error'>
            <?php foreach ($errors as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach ?>
        </div>
    <?php endif; ?>

    <form action="" method="post">
        ユーザネーム: <input type="text" name="name"><br>
        パスワード: <input type="text" name="password"><br>
        <input type="submit" value="新規登録">
    </form>
    <a href="login.php">ログインはこちら</a>
</body>
</html>