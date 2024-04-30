<?php
function postDataHandler(): array
{
    return [
        "login" => $_POST["login"],
        "password" => $_POST["password"]
    ];
}

function databaseDataHandler(): array {
    $arDatabaseData = [];

    $fData = fopen("data.csv", "rt") or Die("Ошибка!");

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
    $arValidationErrors = [];

    if (!validateLogin($arUserData["login"])) {
        $arValidationErrors["login"] = ["Логин должен содержать не менее 6 символов, символами могут быть только латинские буквы, цифры и нижнее подчеркивание, при этом первым символом может быть ТОЛЬКО буква", "content__feedback_type_error"];
    } else {
        $arValidationErrors["login"] = ["Валидация пройдена успешно", "content__feedback_type_success"];
    }

    if (!validatePassword($arUserData["password"])) {
        $arValidationErrors["password"] = ["Пароль должен содержать не менее 8 символов: латинские буквы, цифры, символы -, _, *, $, |", "content__feedback_type_error"];
    } else {
        $arValidationErrors["password"] = ["Валидация пройдена успешно", "content__feedback_type_success"];
    }

    return $arValidationErrors;
}

function handleError(string $key, array $arValidationErrors): array
{
    if (array_key_exists($key, $arValidationErrors)) { 
        return $arValidationErrors[$key];
    }
    return ["", ""];
}

function isValid(array $arValidationErrors): bool
{
    $isOk = true;

    foreach ($arValidationErrors as $key => $value) {
        if ($value[0] !== "Валидация пройдена успешно") {
            $isOk = false;
            break;
        }
    }

    return $isOk;
}