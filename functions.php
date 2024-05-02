<?php
function redirect(string $extra): void
{
    $host = $_SERVER["HTTP_HOST"];
    $uri = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
    
    header("Location: http://$host$uri/$extra");
}

function postDataHandler(): array
{
    return [
        "login" => $_POST["login"],
        "password" => $_POST["password"]
    ];
}

function databaseDataHandler(): array {
    $arDatabaseData = [];

    $fData = fopen("data.csv", "rt") or die("Ошибка!");

    for ($i = 0; $arData = fgetcsv($fData, 100); $i++) {
        $arDatabaseData[] = [
            "login" => $arData[0],
            "password" => $arData[1]
        ];
    }

    fclose($fData);

    return $arDatabaseData;
}

function validateLogin(string $login): bool
{
    $arOptions = [
        "options" => [
            "regexp" => '/^[a-zA-Z][a-zA-Z0-9_]{5,}$/s'
        ]
    ];

    return filter_var($login, FILTER_VALIDATE_REGEXP, $arOptions);
}

function validatePassword(string $password): bool
{
    $arOptions = [
        "options" => [
            "regexp" => '/^[a-zA-Z0-9-_*$|]{8,}$/s'
        ]
    ];

    return filter_var($password, FILTER_VALIDATE_REGEXP, $arOptions) && strpbrk($password, "-_*$|") && strpbrk($password, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ") && strpbrk($password, "0123456789");
}

function handleValidation(array $arUserData): array
{
    $arValidationMessages = [];

    if (!validateLogin($arUserData["login"])) {
        $arValidationMessages["login"] = [
            "message" => "Логин должен содержать не менее 6 символов, символами могут быть только латинские буквы, цифры и нижнее подчеркивание, при этом первым символом может быть ТОЛЬКО буква",
            "type" => "error"
        ];
    } else {
        $arValidationMessages["login"] = [
            "message" => "Валидация пройдена успешно",
            "type" => "success"
        ];
    }

    if (!validatePassword($arUserData["password"])) {
        $arValidationMessages["password"] = [
            "message" => "Пароль должен содержать не менее 8 символов: латинские буквы, цифры, символы -, _, *, $, |",
            "type" => "error"
        ];
    } else {
        $arValidationMessages["password"] = [
            "message" => "Валидация пройдена успешно",
            "type" => "success"
        ];
    }

    return $arValidationMessages;
}

function handleError(string $key, array $arValidationErrors): array
{
    if (array_key_exists($key, $arValidationErrors)) { 
        return $arValidationErrors[$key];
    }

    return [
        "type" => "",
        "message" => ""
    ];
}

function isValid(array $arValidationMessages): bool
{
    $isOk = true;

    foreach ($arValidationMessages as $key => $value) {
        if ($value["type"] !== "success") {
            $isOk = false;
            break;
        }
    }

    return $isOk;
}