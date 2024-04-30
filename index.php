<?php
session_start();

include_once("functions.php");

$arDatabaseData = databaseDataHandler();

$arUserData = [
    "login" => "",
    "password" => ""
];

$arValidationErrors = [];

if (isset($_POST["enter"])) {
    $arUserData = postDataHandler();

    $arValidationErrors = handleValidation($arUserData);

    if (isValid($arValidationErrors)) {
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'authError.php';

        for ($i = 0; $i < count($arDatabaseData); $i++) {
            if ($arUserData["login"] === $arDatabaseData[$i]["login"] && $arUserData["password"] === $arDatabaseData[$i]["password"]) {
                $extra = 'authSuccess.php';
                $_SESSION["login"] = $arUserData["login"];
                break;
            }
        }

        header("Location: http://$host$uri/$extra");

        exit;
    }

}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./style.css" />
    <title>Форма</title>
</head>

<body class="content">
    <form class="content__form" method="post">
        <h1 class="content__header">Авторизация</h1>

        <input class="content__input" type="text" name="login" value="<?= $arUserData["login"] ?>" placeholder="Логин" required />
        <?php
        $arLoginValidationResult = handleError("login", $arValidationErrors);
        echo "<p class='content__feedback $arLoginValidationResult[1]'>$arLoginValidationResult[0]</p>";
        ?>

        <input class="content__input" type="password" name="password" value="<?= $arUserData["password"] ?>" placeholder="********" required />
        <?php
        $arPasswordValidationResult = handleError("password", $arValidationErrors);
        echo "<p class='content__feedback $arPasswordValidationResult[1]'>$arPasswordValidationResult[0]</p>";
        ?>

        <button class="content__button" type="submit" name="enter">Войти</button>
    </form>
</body>

</html>