<?php
session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'visitor') {
    header("Location: Authorization.php");
    exit();
}

// Подключение к базе данных
$mysql = mysqli_connect("localhost", "root", "", "библ");

if (!$mysql) {
    die("Ошибка подключения к базе данных: " . mysqli_connect_error());
}

$visitor_id = $_SESSION['user_id'];
$query = "SELECT * FROM `посетитель` WHERE `ID посетителя` = $visitor_id";
$result = mysqli_query($mysql, $query);

if (!$result) {
    die("Ошибка выполнения запроса: " . mysqli_error($mysql));
}

$visitor = mysqli_fetch_assoc($result);

// Проверка, была ли отправлена форма поиска
$search_title = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['book_title'])) {
    $search_title = mysqli_real_escape_string($mysql, $_POST['book_title']);
}

// Модификация запроса для выбора заказов на основе введённого названия книги
$query = "
    SELECT заказ.`ID заказа`, заказ.`Состояние`, заказ.`Дата заказа`, книга.`Название`, книга.`Издательство`, книга.`Год издания`, книга.`Количество страниц`
    FROM `заказ`
    JOIN `детали заказа` ON заказ.`ID заказа` = `детали заказа`.`ID заказа`
    JOIN `книга` ON `детали заказа`.`ID книги` = книга.`ID книги`
    WHERE заказ.`ID посетителя` = $visitor_id";

if (!empty($search_title)) {
    $query .= " AND книга.`Название` LIKE '%$search_title%'";
}

$query .= " ORDER BY заказ.`Дата заказа` DESC";

$result = mysqli_query($mysql, $query);

if (!$result) {
    die("Ошибка выполнения запроса: " . mysqli_error($mysql));
}

$orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE HTML>
<html lang="ru">
<head>
    <title>Личный кабинет посетителя</title>
    <meta charset="utf-8">
    <style>
        body {
            background-color: rgb(176, 212, 254);
            font-family: Arial, Helvetica, sans-serif;
            padding: 20px;
        }
        h2 {
            text-align: center;
        }
        .container {
            display: flex;
            justify-content: space-between;
        }
        .profile, .orders {
            width: 48%;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background: white;
        }
        .profile p, .orders h3 {
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #6C7AE0;
            color: white;
        }
        .edit-button {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 10px -10px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }
        .edit-button:hover {
            background-color: #45a049;
        }
        .search-container {
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <?php include 'Head.php'; ?>
    <h2>Личный кабинет посетителя</h2>

    <div class="container">
        <div class="profile">
            <p><strong>Фамилия:</strong> <?php echo htmlspecialchars($visitor['Фамилия']); ?></p>
            <p><strong>Имя:</strong> <?php echo htmlspecialchars($visitor['Имя']); ?></p>
            <p><strong>Отчество:</strong> <?php echo htmlspecialchars($visitor['Отчество']); ?></p>
            <p><strong>Номер телефона:</strong> <?php echo htmlspecialchars($visitor['Номер телефона']); ?></p>
            <p><strong>Адрес проживания:</strong> <?php echo htmlspecialchars($visitor['Адрес проживания']); ?></p>
            <p><strong>Логин:</strong> <?php echo htmlspecialchars($visitor['Логин']); ?></p>
            <a href="edit_profile.php" class="edit-button">Редактировать данные</a>
        </div>

        <div class="orders">
            <div class="search-container">
                <form method="POST" action="">
                    <input type="text" name="book_title" placeholder="Введите название книги" required>
                    <button type="submit">Поиск</button>
                </form>
            </div>

            <h3>Заказы</h3>
            <?php if (!empty($orders)): ?>
                <table>
                    <tr>
                        <th>Дата заказа</th>
                        <th>Название книги</th>
                        <th>Издательство</th>
                        <th>Год издания</th>
                        <th>Количество страниц</th>
                        <th>Состояние заказа</th>
                    </tr>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['Дата заказа']); ?></td>
                            <td><?php echo htmlspecialchars($order['Название']); ?></td>
                            <td><?php echo htmlspecialchars($order['Издательство']); ?></td>
                            <td><?php echo htmlspecialchars($order['Год издания']); ?></td>
                            <td><?php echo htmlspecialchars($order['Количество страниц']); ?></td>
                            <td><?php echo htmlspecialchars($order['Состояние']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>У вас нет заказов.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
