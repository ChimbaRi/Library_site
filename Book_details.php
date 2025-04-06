<?php

session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$mysql = mysqli_connect("localhost", "root", "", "библ");

if (!$mysql) {
    die("Ошибка подключения к базе данных!");
}

$book_id = (int)$_GET['id'];

$sql = "
    SELECT 
        книга.`ID книги`,
        книга.`Название`,
        книга.`Издательство`,
        книга.`Год издания`,
        книга.`Количество страниц`,
        книга.`Описание`,
        GROUP_CONCAT(DISTINCT автор.`ФИО` SEPARATOR ', ') AS автора,
        GROUP_CONCAT(DISTINCT жанр.`Название` SEPARATOR ', ') AS жанры
    FROM книга
    LEFT JOIN автор ON книга.`ID автора` = автор.`ID автора`
    LEFT JOIN жанр ON книга.`ID жанра` = жанр.`ID жанра`
    WHERE книга.`ID книги` = $book_id
    GROUP BY книга.`ID книги`
";

$res = mysqli_query($mysql, $sql);

if (!$res) {
    die("Ошибка выполнения запроса: " . mysqli_error($mysql));
}

$book = mysqli_fetch_assoc($res);
$book_in_cart = false;

foreach ($_SESSION['cart'] as $cart_item) {
    if (isset($cart_item['book_id']) && $cart_item['book_id'] == $book_id) {
        $book_in_cart = true;
        break;
    }
}

echo "<!-- Cart contents: " . print_r($_SESSION['cart'], true) . " -->";
echo "<!-- Book in cart: " . ($book_in ? 'Yes' : 'No') . " -->";
?>

<!DOCTYPE HTML>
<html lang="ru">
<head>
    <title>Детали книги: <?= htmlspecialchars($book['Название']) ?></title>
    <meta charset="utf-8">
    <style>
        body {
            background-color: rgb(176, 212, 254);
            font-family: Arial, Helvetica, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        .book-details {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: auto;
            max-width: 600px;
            margin-bottom: 20px;
        }
        h1 {
            color: #333;
        }
        h2 {
            text-align: center;
            color: rgb(176, 212, 254);
        }
        p {
            margin: 5px 0;
        }
        .back-button {
            margin-top: 20px;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            background-color: rgb(176, 176, 247);
            color: rgb(52, 0, 130);
            cursor: pointer;
            text-decoration: none;
        }
        .back-button:hover {
            background-color: #4E5B9D;
        }
    </style>
</head>

<body>
<?php include 'Head.php'; ?>

<h2> КНИГА </h2>
<div class="book-details">
    <h1><?= htmlspecialchars($book['Название']) ?></h1>
    <?php if (!empty($book['Описание'])): ?>
        <p><strong>Описание:</strong> <?= nl2br(htmlspecialchars($book['Описание'])) ?></p>
    <?php else: ?>
        <p><strong>Описание:</strong> Нет описания</p>
    <?php endif; ?>
    <p><strong>Издательство:</strong> <?= htmlspecialchars($book['Издательство']) ?></p>
    <p><strong>Год издания:</strong> <?= htmlspecialchars($book['Год издания']) ?></p>
    <p><strong>Количество страниц:</strong> <?= htmlspecialchars($book['Количество страниц']) ?></p>
    <p><strong>Авторы:</strong> <?= htmlspecialchars($book['автора']); ?></p>
    <p><strong>Жанры:</strong> <?= htmlspecialchars($book['жанры']); ?></p>

    <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'visitor'): ?>
        <?php if ($book_in_cart): ?>
            <p>Эта книга уже в вашей корзине.</p>
        <?php else: ?>
            <form action="AddToCart.php" method="post">
                <input type="hidden" name="book_id" value="<?= $book['ID книги'] ?>">
                <button type="submit" class="back-button">Добавить в корзину</button>
            </form>
        <?php endif; ?>
    <?php else: ?>
        <p>Пожалуйста, <a href="Authorization.php">войдите как пользователь</a>, чтобы добавить книгу в корзину.</p>
    <?php endif; ?>
</div>

<a href="javascript:history.back()" class="back-button">Назад</a>
</body>
</html>
