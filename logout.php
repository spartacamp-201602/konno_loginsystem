<?php

session_start();

unset($_SESSION['id']);

// unset($_SESSION['key']) or session_destroy();
// 違いはすべて消すか, 変数ひとつだけ消すか

session_destroy();

header('Location: login.php');

