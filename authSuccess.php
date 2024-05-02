<?php
session_start();

include_once("functions.php");

if (isset($_POST["exit"])) {
    $_SESSION = [];
    unset($_COOKIE[session_name()]);
    session_destroy();
    
    redirect("index.php");

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
    <form class="content__info" method="post">
        <h1 class="content__header">Здравствуйте, <?= $_SESSION["login"] ?> !</h1>

        <p>Вы успешно авторизованы.</p>

        <button class="content__button" type="submit" name="exit">Выйти из системы</button>
    </form>
</body>

</html>