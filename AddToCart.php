<?php

session_start();

// Проверяем, авторизован ли пользователь как посетитель
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'visitor') {
    header("Location: Authorization.php");
    exit();
}

$mysql = mysqli_connect("localhost", "root", "", "библ");

if (!$mysql) {
    die("Ошибка подключения к базе данных!");
}

$visitor_id = $_SESSION['user_id'];
$book_id = (int)$_POST['book_id'];

// Проверяем, существует ли уже заказ для пользователя
$query = "SELECT `ID заказа` FROM `заказ` WHERE `ID посетителя` = $visitor_id AND `Состояние` = 'активен'";
$result = mysqli_query($mysql, $query);

if (mysqli_num_rows($result) > 0) {
    // Если заказ существует, получаем его ID
    $order = mysqli_fetch_assoc($result);
    $order_id = $order['ID заказа'];
} else {
    // Если заказа нет, создаем новый
    $query = "INSERT INTO `заказ` (`ID посетителя`, `Дата заказа`, `Состояние`) VALUES ($visitor_id, NOW(), 'активен')";
    mysqli_query($mysql, $query);

    // Получаем ID нового заказа
    $order_id = mysqli_insert_id($mysql);
}

// Добавляем книгу в детали заказа
$query = "INSERT INTO `детали заказа` (`ID заказа`, `ID книги`) VALUES ($order_id, $book_id)";
mysqli_query($mysql, $query);

// Перенаправляем на страницу корзины
header("Location: Cart.php");
exit();
?>
