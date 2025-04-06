<?php

$mysql = mysqli_connect("localhost", "root", "", "библ");

if (!$mysql) {
    die("Ошибка подключения к базе данных!");
}

// Поиск по имени автора
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

// Формируем SQL-запрос для получения данных об авторах
$sql = "SELECT автор.`ID автора`,
               автор.`ФИО`,
               автор.`Справочная информация`
        FROM автор";

// Добавление условия поиска, если введен запрос
if ($search_query !== '') {
    $sql .= " WHERE автор.`ФИО` LIKE '%" . mysqli_real_escape_string($mysql, $search_query) . "%'";
}

// Выполнение запроса
$res = mysqli_query($mysql, $sql);
if (!$res) {
    die("Ошибка выполнения запроса: " . mysqli_error($mysql));
}

?>

<!DOCTYPE HTML>
<html lang="ru">
<head>
    <title>Авторы книг</title>
    <meta charset="utf-8">
    <style>
        body {
            background-color: rgb(176, 212, 254);
            font-family: Arial, Helvetica, sans-serif;
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
        .search-form {
            text-align: center;
            margin: 20px;
        }
        input[type="text"] {
            margin: 5px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 300px;
            background-color: #fff;
        }
        input[type="submit"] {
            margin-top: 10px;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            background-color: rgb(176, 176, 247);
            color: rgb(52, 0, 130);
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #4E5B9D;
        }
        .genre-section {
            margin-top: 20px;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 70%; /* Установите нужную ширину */
            margin-left: auto;
            margin-right: auto;
        }
        table {
            width: 100%; /* Таблица будет занимать всю ширину контейнера */
            border-collapse: collapse;
            margin: 0; /* Убираем отступы */
        }
    </style>
</head>
<body>
<?php include 'Head.php'; ?>

    <div class="search-form">
        <form method="GET" action="">
            <label for="search">Поиск по имени автора:</label>
            <input type="text" id="search" name="search" value="<?= htmlspecialchars($search_query) ?>" placeholder="Введите имя автора">
            <input type="submit" value="Поиск">
        </form>
    </div>

    <div class="genre-section">
        <table>
            <tr>
                <th>ID автора</th>
                <th>ФИО</th>
                <th>Справочная информация</th>
            </tr>
            <?php while ($author = mysqli_fetch_array($res)) { ?>
                <tr>
                    <td><?= htmlspecialchars($author['ID автора']) ?></td>
                    <td><?= htmlspecialchars($author['ФИО']) ?></td>
                    <td><?= htmlspecialchars($author['Справочная информация']) ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
