<?php

require_once('config.php');
require_once('function.php');

session_start();

// $_SESSIONの中身があるということは、
// ログインしているのに login.php に来ている
// -> ログイン情報を破棄してログイン画面に飛ばす
// -> 次訪れた時はセッションは破棄されている
// -> リダイレクトループに陥らない
if (!empty($_SESSION['id'])) {
    unset($_SESSION['id']);
    header('Location: login.php');
    exit;
}

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

    // バリデーション突破
    if (empty($errors)) {

        $dbh = connectDb();

        $sql = "select * from users where ";
        $sql.= "name = :name and password = :password";

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':password', $password);

        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // var_dump($user);

        // $userが false でない場合
        // -> ログイン成功
        if ($user) {
            $_SESSION['id'] = $user['id'];
            header('Location: index.php');
            exit;
        } else {
            // ログイン失敗
            $errors[] = 'ユーザーネームかパスワードが間違っています';
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
    <h1>ログイン画面です</h1>
    <?php if (isset($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach ?>
        </div>
    <?php endif ?>
    <form action="" method="post">
        ユーザネーム: <input type="text" name="name"><br>
        パスワード: <input type="text" name="password"><br>
        <input type="submit" value="ログイン">
    </form>
    <a href="signup.php">新規ユーザー登録はこちら</a>
</body>
</html>