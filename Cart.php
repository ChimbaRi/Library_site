<?php

session_start();

// Проверка типа пользователя
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'visitor') {
    header("Location: Authorization.php");
    exit();
}

// Подключение к базе данных "библ"
$mysql = mysqli_connect("localhost", "root", "", "библ");

if (!$mysql) {
    die("Ошибка подключения к базе данных: " . mysqli_connect_error());
}

$visitor_id = $_SESSION['user_id'];

// Получаем активный заказ пользователя
$query = "SELECT `ID заказа` FROM `заказ` WHERE `ID посетителя` = $visitor_id AND `Состояние` = 'активен'";
$result = mysqli_query($mysql, $query);

if (mysqli_num_rows($result) > 0) {
    $order = mysqli_fetch_assoc($result);
    $order_id = $order['ID заказа'];

    // Получаем книги в корзине
    $query = "
        SELECT книга.`ID книги`, книга.`Название`, книга.`Издательство`, книга.`Год издания`, книга.`Количество страниц`
        FROM `детали заказа`
        JOIN `книга` ON `детали заказа`.`ID книги` = книга.`ID книги`
        WHERE `детали заказа`.`ID заказа` = $order_id
    ";
    $result = mysqli_query($mysql, $query);
    $books = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $books = [];
}

// Проверка отправки формы на удаление записей
if (isset($_POST['delete'])) {
    $ids_to_delete = $_POST['ids'] ?? [];

    if (!empty($ids_to_delete)) {
        $ids = implode(',', array_map('intval', $ids_to_delete));
        $delete_query = "DELETE FROM `детали заказа` WHERE `ID книги` IN ($ids) AND `ID заказа` = $order_id";
        mysqli_query($mysql, $delete_query);

        // Перезагрузка страницы для обновления отображения
        header("Location: cart.php");
        exit();
    }
}

// Проверка отправки формы на подтверждение заказа
if (isset($_POST['confirm'])) {
    // Обновляем состояние заказа для всех книг
    $update_query = "UPDATE `заказ` SET `Состояние` = 'создан' WHERE `ID заказа` = $order_id";
    mysqli_query($mysql, $update_query);

    // Перезагрузка страницы для обновления отображения
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE HTML>
<html lang="ru">
<head>
    <title>Корзина</title>
    <meta charset="utf-8">
    <style>
        body {
            background-color: rgb(176, 212, 254);
            font-family: Arial, Helvetica, sans-serif;
            padding: 20px;
        }
        .cart {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 600px;
            margin: 0 auto;
        }
        h2 {
            text-align: center;
        }
        .book-item {
            margin-bottom: 15px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
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
        .button {
            margin-top: 10px;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            background-color: #6C7AE0;
            color: white;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .button:hover {
            background-color: #4E5B9D;
        }
    </style>
</head>
<body>

<?php include 'Head.php'; ?>

<h2>Корзина</h2>
<div class="cart">
    <?php if (!empty($books)): ?>
        <form method="POST" action="">
            <div style="text-align: center; margin: 20px;">
                <button type="submit" name="delete" class="button">Удалить выбранные книги</button>
                <button type="submit" name="confirm" class="button">Подтвердить заказ</button>
            </div>
            <table>
                <tr>
                    <th>Выбрать</th>
                    <th>Название</th>
                    <th>Издательство</th>
                    <th>Год издания</th>
                    <th>Количество страниц</th>
                </tr>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><input type="checkbox" name="ids[]" value="<?= $book['ID книги'] ?>"></td>
                        <td><?= htmlspecialchars($book['Название']) ?></td>
                        <td><?= htmlspecialchars($book['Издательство']) ?></td>
                        <td><?= htmlspecialchars($book['Год издания']) ?></td>
                        <td><?= htmlspecialchars($book['Количество страниц']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </form>
    <?php else: ?>
        <p>Ваша корзина пуста.</p>
    <?php endif; ?>
</div>
</body>
</html>
