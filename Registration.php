<?php

// Подключение к базе данных
$mysql = mysqli_connect("localhost", "root", "", "библ");

if (!$mysql) {
    die("Ошибка подключения к базе данных!");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получение и экранирование данных
    $surname = mysqli_real_escape_string($mysql, $_POST['surname']);
    $name = mysqli_real_escape_string($mysql, $_POST['name']);
    $patronymic = mysqli_real_escape_string($mysql, $_POST['patronymic']);
    $phone = mysqli_real_escape_string($mysql, $_POST['phone']);
    $address = mysqli_real_escape_string($mysql, $_POST['address']);
    $login = mysqli_real_escape_string($mysql, $_POST['login']);
    $password = mysqli_real_escape_string($mysql, $_POST['password']);

    // SQL-запрос
    $query = "
        INSERT INTO посетитель (
            `Фамилия`,
            `Имя`,
            `Отчество`,
            `Номер телефона`,
            `Адрес проживания`,
            `Логин`,
            `Пароль`
        ) VALUES (
            '$surname',
            '$name',
            '$patronymic',
            '$phone',
            '$address',
            '$login',
            '$password'  -- Сохраняем пароль в открытом виде
        )";

    // Выполнение запроса
    if (mysqli_query($mysql, $query)) {
        // После успешной регистрации перенаправляем на Authorization.php
        header("Location: Authorization.php");
        exit();
    } else {
        echo "Ошибка: " . mysqli_error($mysql);
    }
}
?>

<!DOCTYPE HTML>
<html lang="ru">

<head>
    <title>Регистрация</title>
    <meta charset="utf-8">
    <style>
        body {
            background-color: rgb(176, 212, 254);
            font-family: Arial, Helvetica, sans-serif;
        }

        form {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        input[type="text"], 
        input[type="password"] {
            margin: 5px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: calc(100% - 22px);
            background-color: #fff;
        }

        input[type="submit"] {
            margin-top: 10px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: rgb(176, 176, 247);
            color: rgb(52, 0, 130);
            cursor: pointer;
            display: inline-block;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #4E5B9D;
        }
    </style>
</head>

<body>
    <?php include 'Head.php'; ?>

    <h2 style="text-align: center;">Регистрация</h2>
    <form method="post">
        <input type="text" name="surname" placeholder="Фамилия" required>
        <input type="text" name="name" placeholder="Имя" required>
        <input type="text" name="patronymic" placeholder="Отчество" required>
        <input type="text" name="phone" placeholder="Номер телефона" required>
        <input type="text" name="address" placeholder="Адрес проживания" required>
        <input type="text" name="login" placeholder="Логин" required>
        <input type="password" name="password" placeholder="Пароль" required>
        <input type="submit" value="Зарегистрироваться">
    </form>
</body>

</html>
