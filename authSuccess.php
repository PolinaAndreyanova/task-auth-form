<?php
session_start();

if (isset($_POST["exit"])) {
    $_SESSION = [];
    unset($_COOKIE[session_name()]);
    session_destroy();

    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

    $extra = 'index.php';

    header("Location: http://$host$uri/$extra");

    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./style.css" />
    <title>Успех</title>
</head>

<body class="content">
    <form class="content__form" method="post">
        <h1 class="content__header">Здравствуйте, <?= $_SESSION["login"] ?> !</h1>

        <p>Вы успешно авторизованы.</p>

        <button class="content__button" type="submit" name="exit">Выйти из системы</button>
    </form>
</body>

</html>