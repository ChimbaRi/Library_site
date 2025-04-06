<?php
session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'visitor') {
    header("Location: Authorization.php");
    exit();
}

// Подключение к базе данных
$mysql = mysqli_connect("localhost", "root", "", "библ");

if (!$mysql) {
    die("Ошибка подключения к базе данных!");
}

$visitor_id = $_SESSION['user_id'];

// Получаем текущую информацию о пользователе
$query = "SELECT * FROM `посетитель` WHERE `ID посетителя` = $visitor_id";
$result = mysqli_query($mysql, $query);
if (!$result) {
    die("Ошибка выполнения запроса: " . mysqli_error($mysql));
}

$visitor = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получение и экранирование обновлённых данных
    $surname = mysqli_real_escape_string($mysql, $_POST['surname']);
    $name = mysqli_real_escape_string($mysql, $_POST['name']);
    $patronymic = mysqli_real_escape_string($mysql, $_POST['patronymic']);
    $phone = mysqli_real_escape_string($mysql, $_POST['phone']);
    $address = mysqli_real_escape_string($mysql, $_POST['address']);
    $login = mysqli_real_escape_string($mysql, $_POST['login']);
    $password = mysqli_real_escape_string($mysql, $_POST['password']);

    // Подготовка запроса на обновление
    $update_query = "
        UPDATE `посетитель`
        SET 
            `Фамилия` = '$surname',
            `Имя` = '$name',
            `Отчество` = '$patronymic',
            `Номер телефона` = '$phone',
            `Адрес проживания` = '$address',
            `Логин` = '$login'
    ";

    // Если введён новый пароль, его хешируем и добавляем в запрос
    if (!empty($password)) {
        $hashed_password = mysqli_real_escape_string($mysql, password_hash($password, PASSWORD_DEFAULT));
        $update_query .= ", `Пароль` = '$hashed_password'";
    }

    // Добавляем условие для конкретного пользователя
    $update_query .= " WHERE `ID посетителя` = $visitor_id";

    // Выполняем обновление и проверяем на ошибки
    if (mysqli_query($mysql, $update_query)) {
        // Перенаправляем на страницу личного кабинета
        header("Location: PersonalCabinetVisitor.php");
        exit();
    } else {
        echo "Ошибка обновления данных: " . mysqli_error($mysql); // Показать ошибку в случае неудачи
    }
}
?>

<!DOCTYPE HTML>
<html lang="ru">
<head>
    <title>Редактирование профиля</title>
    <meta charset="utf-8">
    <style>
        body {
            background-color: rgb(176, 212, 254);
            font-family: Arial, Helvetica, sans-serif;
            padding: 20px;
        }
        form {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background: white;
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
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            display: inline-block;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<?php include 'Head.php'; ?>
    <h2 style="text-align: center;">Редактирование профиля</h2>
    <form method="post">
        <input type="text" name="surname" placeholder="Фамилия" value="<?php echo htmlspecialchars($visitor['Фамилия']); ?>" required>
        <input type="text" name="name" placeholder="Имя" value="<?php echo htmlspecialchars($visitor['Имя']); ?>" required>
        <input type="text" name="patronymic" placeholder="Отчество" value="<?php echo htmlspecialchars($visitor['Отчество']); ?>" required>
        <input type="text" name="phone" placeholder="Номер телефона" value="<?php echo htmlspecialchars($visitor['Номер телефона']); ?>" required>
        <input type="text" name="address" placeholder="Адрес проживания" value="<?php echo htmlspecialchars($visitor['Адрес проживания']); ?>" required>
        <input type="text" name="login" placeholder="Логин" value="<?php echo htmlspecialchars($visitor['Логин']); ?>" required>
        <input type="password" name="password" placeholder="Новый пароль (не обязательно)">
        <input type="submit" value="Сохранить изменения">
    </form>
</body>
</html>
