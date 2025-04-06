<?php

$mysql = mysqli_connect("localhost", "root", "", "библ");

if (!$mysql) {
    die("Ошибка подключения к базе данных!");
}

// Устанавливаем значения сортировки по умолчанию
$order_by = '`Количество страниц`';
$order_direction = 'ASC';

// Фильтры
$filter_genre = isset($_GET['genre']) ? $_GET['genre'] : '';
$filter_author = isset($_GET['author']) ? $_GET['author'] : '';
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

// Проверяем, была ли отправлена форма сортировки
if (isset($_GET['sort']) && isset($_GET['direction'])) {
    $order_by = $_GET['sort'] === 'Количество страниц' ? '`Количество страниц`' : 'книга.`Название`';
    $order_direction = $_GET['direction'] === 'desc' ? 'DESC' : 'ASC';
}

// Запрос для получения данных о жанрах
$genres_result = mysqli_query($mysql, "SELECT DISTINCT жанр.Название FROM жанр");
$genres = [];
while ($row = mysqli_fetch_array($genres_result)) {
    $genres[] = $row['Название'];
}

// Запрос для получения данных об авторах
$authors_result = mysqli_query($mysql, "SELECT DISTINCT автор.ФИО FROM автор");
$authors = [];
while ($row = mysqli_fetch_array($authors_result)) {
    $authors[] = $row['ФИО'];
}

// Начинаем формировать SQL-запрос
$sql = "
    SELECT книга.`ID книги`,
           книга.`Название`,
           книга.`Издательство`,
           книга.`Год издания`,
           книга.`Обложка`,
           книга.`Количество страниц`,
           GROUP_CONCAT(DISTINCT жанр.`Название` SEPARATOR ', ') AS Жанры,
           GROUP_CONCAT(DISTINCT автор.`ФИО` SEPARATOR ', ') AS Авторы
    FROM книга
    LEFT JOIN жанр ON книга.`ID жанра` = жанр.`ID жанра`
    LEFT JOIN автор ON книга.`ID автора` = автор.`ID автора`
";

// Фильтрация по жанру
if ($filter_genre !== '') {
    $sql .= " WHERE жанр.`Название` = '" . mysqli_real_escape_string($mysql, $filter_genre) . "'";
}

// Фильтрация по автору
if ($filter_author !== '') {
    $sql .= ($filter_genre !== '') ? " AND " : " WHERE ";
    $sql .= " автор.`ФИО` = '" . mysqli_real_escape_string($mysql, $filter_author) . "'";
}

// Поиск по названию
if ($search_query !== '') {
    $sql .= ($filter_genre !== '' || $filter_author !== '') ? " AND " : " WHERE ";
    $sql .= " книга.`Название` LIKE '%" . mysqli_real_escape_string($mysql, $search_query) . "%'";
}

// Добавление GROUP BY и ORDER BY
$sql .= " GROUP BY книга.`ID книги` ORDER BY $order_by $order_direction";

// Выполнение запроса
$res = mysqli_query($mysql, $sql);
if (!$res) {
    die("Ошибка выполнения запроса: " . mysqli_error($mysql));
}
?>

<!DOCTYPE HTML>
<html lang="ru">
<head>
    <title>Управление книгами</title>
    <meta charset="utf-8">
    <style>
        body {
            background-color: rgb(176, 212, 254);
            font-family: Arial, Helvetica, sans-serif;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 0;
        }
        th, td {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: rgb(176, 176, 247);
            color: rgb(52, 0, 130);
        }
        img {
            width: 100px;
            height: auto;
            object-fit: fill;
        }
        select, input[type="text"] {
            margin: 5px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 150px;
            background-color: #fff;
        }
        input[type="submit"], .button {
            margin-top: 10px;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            background-color: rgb(176, 176, 247);
            color: rgb(52, 0, 130);
            cursor: pointer;
            display: inline-block;
        }
        input[type="submit"]:hover, .button:hover {
            background-color: #4E5B9D;
        }
        .genre-section {
            margin-top: 20px;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 70%;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
<?php include 'Head.php'; ?>

<form method="GET" action="">
    <label for="sort">Сортировка по:</label>
    <select name="sort" id="sort">
        <option value="Количество страниц" <?= $order_by === '`Количество страниц`' ? 'selected' : '' ?>>Количество страниц</option>
        <option value="Название" <?= $order_by === 'книга.`Название`' ? 'selected' : '' ?>>Название</option>
        <option value="Год издания" <?= $order_by === '`Год издания`' ? 'selected' : '' ?>>Год издания</option>
    </select>

    <label for="direction">Порядок:</label>
    <select name="direction" id="direction">
        <option value="asc" <?= $order_direction === 'ASC' ? 'selected' : '' ?>>По возрастанию</option>
        <option value="desc" <?= $order_direction === 'DESC' ? 'selected' : '' ?>>По убыванию</option>
    </select>

    <label for="genre">Жанр:</label>
    <select name="genre" id="genre">
        <option value="">Все жанры</option>
        <?php foreach ($genres as $genre): ?>
            <option value="<?= htmlspecialchars($genre) ?>" <?= $filter_genre === $genre ? 'selected' : '' ?>><?= htmlspecialchars($genre) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="author">Автор:</label>
    <select name="author" id="author">
        <option value="">Все авторы</option>
        <?php foreach ($authors as $author): ?>
            <option value="<?= htmlspecialchars($author) ?>" <?= $filter_author === $author ? 'selected' : '' ?>><?= htmlspecialchars($author) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="search">Поиск по названию:</label>
    <input type="text" id="search" name="search" value="<?= htmlspecialchars($search_query) ?>" placeholder="Введите название">
    <input type="submit" value="Применить фильтры">
</form>

<div class="genre-section">
    <form method="POST" action="AddToCart2.php">
        <input type="submit" value="Добавить в корзину" style="margin-bottom: 10px;"> <!-- Кнопка перенесена выше таблицы -->
        <table>
            <tr>
                <th>Выбрать</th>
                <th>Название</th>
                <th>Издательство</th>
                <th>Год издания</th>
                <th>Обложка</th>
                <th>Количество страниц</th>
                <th>Жанры</th>
                <th>Авторы</th>
            </tr>
            <?php while ($Book = mysqli_fetch_array($res)) { ?>
                <tr>
                    <td>
                        <input type="checkbox" name="selected_books[]" value="<?= htmlspecialchars($Book['ID книги']) ?>">
                    </td>
                    <td>
                        <a href="Book_details.php?id=<?= htmlspecialchars($Book['ID книги']) ?>">
                            <?= htmlspecialchars($Book['Название']) ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($Book['Издательство']) ?></td>
                    <td><?= htmlspecialchars($Book['Год издания']) ?></td>
                    <td>
                        <?php if ($Book['Обложка']): ?>
                            <img src="data:image/jpeg;base64,<?= base64_encode($Book['Обложка']) ?>" alt="Обложка">
                        <?php else: ?>
                            Нет обложки
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($Book['Количество страниц']) ?></td>
                    <td><?= htmlspecialchars($Book['Жанры']) ?></td>
                    <td><?= htmlspecialchars($Book['Авторы']) ?></td>
                </tr>
            <?php } ?>
        </table>
    </form>
</div>

</body>
</html>
