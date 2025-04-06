<?php

$mysql = mysqli_connect("localhost", "root", "", "библ");

if (!$mysql) {
    die("Ошибка подключения к базе данных!");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получение и экранирование данных
    $login = mysqli_real_escape_string($mysql, $_POST['login']);
    $password = mysqli_real_escape_string($mysql, $_POST['password']);

    // SQL-запрос для проверки авторизации у посетителей
    $query_visitor = "
        SELECT * FROM посетитель WHERE Логин = '$login' AND Пароль = '$password'
    ";

    $result_visitor = mysqli_query($mysql, $query_visitor);

    if (mysqli_num_rows($result_visitor) > 0) {
        // Авторизация успешна
        session_start();
        $visitor = mysqli_fetch_assoc($result_visitor);
        $_SESSION['user_type'] = 'visitor';
        $_SESSION['user_id'] = $visitor['ID посетителя'];
        header("Location: PersonalCabinetVisitor.php"); // Личный кабинет для посетителей
        exit();
    } else {
        // Неверный логин или пароль
        $error_message = "Неверный логин или пароль!";
    }
}
?>

<!DOCTYPE HTML>
<html lang="ru">
<head>
    <title>Авторизация</title>
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
        input[type="text"], input[type="password"] {
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
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

<?php include 'Head.php'; ?>

<h2 style="text-align: center;">Авторизация</h2>
<?php if(isset($error_message)): ?>
    <div class="error"><?php echo $error_message; ?></div>
<?php endif; ?>
<form method="post">
    <input type="text" name="login" placeholder="Логин" required>
    <input type="password" name="password" placeholder="Пароль" required>
    <input type="submit" value="Войти">
</form>

</body>
</html>
