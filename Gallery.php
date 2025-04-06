<?php

$mysql = mysqli_connect("localhost", "root", "", "библ");

if (!$mysql) {
    die("Ошибка подключения к базе данных!");
}

// Начинаем формировать SQL-запрос
$sql = "
    SELECT книга.`ID книги`,
           книга.`Название`,
           книга.`Обложка`
    FROM книга
";

// Выполнение запроса
$res = mysqli_query($mysql, $sql);
if (!$res) {
    die("Ошибка выполнения запроса: " . mysqli_error($mysql));
}
?>

<!DOCTYPE HTML>
<html lang="ru">
<head>
    <title>Галерея обложек книг</title>
    <meta charset="utf-8">
    <style>
        body {
            background-color: rgb(176, 212, 254);
            font-family: Arial, Helvetica, sans-serif;
        }
        .gallery {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .book-card {
            margin: 10px;
            border-radius: 5px;
            width: 150px;
            text-align: center;
        }
        img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-bottom: 1px solid #ccc;
        }
        h3 {
            margin: 5px;
            font-size: 1em;
        }
    </style>
</head>
<body>

    <?php include 'Head.php'; ?>

    <div class="gallery">
    <?php while ($Book = mysqli_fetch_array($res)) { ?>
        <div class="book-card">
            <a href="Book_details.php?id=<?= $Book['ID книги'] ?>">
                <?php if ($Book['Обложка']): ?>
                    <img src="data:image/jpeg;base64,<?= base64_encode($Book['Обложка']) ?>" alt="<?= htmlspecialchars($Book['Название']) ?>">
                <?php else: ?>
                    <img src="placeholder.jpg" alt="Нет обложки">
                <?php endif; ?>
                <h3><?= htmlspecialchars($Book['Название']) ?></h3>
            </a>
        </div>
    <?php } ?>
    </div>

</body>
</html>
