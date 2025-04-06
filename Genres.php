<?php

$mysql = mysqli_connect("localhost", "root", "", "библ");

if (!$mysql) {
    die("Ошибка подключения к базе данных!");
}

// Поиск по названию жанра
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

// Формируем SQL-запрос для получения данных о жанрах
$sql = "SELECT жанр.`ID жанра`,
               жанр.`Название`,
               жанр.`Описание`
        FROM жанр";

// Добавление условия поиска, если введен запрос
if ($search_query !== '') {
    $sql .= " WHERE жанр.`Название` LIKE '%" . mysqli_real_escape_string($mysql, $search_query) . "%'";
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
    <title>Жанры книг</title>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: rgb(176, 212, 254); /* фон */
            margin: 0;
            padding: 20px;
        }
        .container {
            text-align: center;
            margin-top: 50px;
        }
        input[type="text"] {
            margin: 5px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 300px; /* Ширина поля для ввода */
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
            background-color: rgb(176, 176, 247);
            color: rgb(52, 0, 130);
        }
        .genre-section {
            margin-top: 20px;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
<?php include 'Head.php'; ?>

    <div class="container">
        <form method="GET" action="">
            <label for="search">Поиск по названию жанра:</label>
            <input type="text" id="search" name="search" value="<?= htmlspecialchars($search_query) ?>" placeholder="Введите название жанра">
            <input type="submit" value="Поиск">
        </form>


        <div class="genre-section">
            <table>
                <tr>
                    <th>ID жанра</th>
                    <th>Название</th>
                    <th>Описание</th>
                </tr>
                <?php while ($genre = mysqli_fetch_array($res)) { ?>
                    <tr>
                        <td><?= htmlspecialchars($genre['ID жанра']) ?></td>
                        <td><?= htmlspecialchars($genre['Название']) ?></td>
                        <td><?= htmlspecialchars($genre['Описание']) ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</body>
</html>
