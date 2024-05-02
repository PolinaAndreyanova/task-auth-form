<?php
session_start();

include_once("functions.php");

$arDatabaseData = databaseDataHandler();

$arUserData = [
    "login" => "",
    "password" => ""
];

$arValidationMessages = [];

if (isset($_POST["enter"])) {
    $arUserData = postDataHandler();

    $arValidationMessages = handleValidation($arUserData);

    if (isValid($arValidationMessages)) {
        $extra = "authError.php";

        for ($i = 0; $i < count($arDatabaseData); $i++) {
            if ($arUserData["login"] === $arDatabaseData[$i]["login"] && $arUserData["password"] === $arDatabaseData[$i]["password"]) {
                $extra = "authSuccess.php";
                $_SESSION["login"] = $arUserData["login"];
                break;
            }
        }

        redirect($extra);

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
    <form class="content__info" method="post">
        <h1 class="content__header">Авторизация</h1>

        <input class="content__input" type="text" name="login" value="<?= $arUserData["login"] ?>" placeholder="Логин" required />
        <?php
        $arLoginValidationResult = handleError("login", $arValidationMessages);
        echo "<p class='content__feedback content__feedback_type_" . $arLoginValidationResult["type"] . "'>" . $arLoginValidationResult["message"] . "</p>";
        ?>

        <input class="content__input" type="password" name="password" value="<?= $arUserData["password"] ?>" placeholder="********" required />
        <?php
        $arPasswordValidationResult = handleError("password", $arValidationMessages);
        echo "<p class='content__feedback content__feedback_type_" . $arPasswordValidationResult["type"] . "'>" . $arPasswordValidationResult["message"] . "</p>";
        ?>

        <button class="content__button" type="submit" name="enter">Войти</button>
    </form>
</body>

</html>