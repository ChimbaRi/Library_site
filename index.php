<?php

session_start();

// Инициализация данных филиалов с большим количеством записей и измененными названиями
$branches = [
    [
        'Название' => 'Библиотека для всех',
        'Город' => 'Москва',
        'Улица' => 'Тверская улица',
        'Дом' => '10'
    ],
    [
        'Название' => 'Книжный мир',
        'Город' => 'Санкт-Петербург',
        'Улица' => 'Невский проспект',
        'Дом' => '15'
    ],
    [
        'Название' => 'Знания для всех',
        'Город' => 'Казань',
        'Улица' => 'Баумана',
        'Дом' => '25'
    ],
    [
        'Название' => 'Чтение — это жизнь',
        'Город' => 'Екатеринбург',
        'Улица' => 'Карломаркса',
        'Дом' => '5'
    ],
    [
        'Название' => 'Мир книги',
        'Город' => 'Нижний Новгород',
        'Улица' => 'Большая Покровская',
        'Дом' => '8'
    ],
    [
        'Название' => 'Творческая библиотека',
        'Город' => 'Уфа',
        'Улица' => 'Ленина',
        'Дом' => '12'
    ],
    [
        'Название' => 'Литературный уголок',
        'Город' => 'Омск',
        'Улица' => 'Ленина',
        'Дом' => '18'
    ],
    [
        'Название' => 'Библиотечный центр',
        'Город' => 'Челябинск',
        'Улица' => 'Труда',
        'Дом' => '22'
    ],
    [
        'Название' => 'Чудеса книги',
        'Город' => 'Ростов-на-Дону',
        'Улица' => 'Северный',
        'Дом' => '30'
    ],
    [
        'Название' => 'Книги для сердца',
        'Город' => 'Краснодар',
        'Улица' => 'Красная',
        'Дом' => '40'
    ],
];

?>

<!DOCTYPE HTML>
<html lang="ru">
<head>
    <title>Главная страница библиотеки</title>
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
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .library-section, .branch-section {
            margin-top: 20px;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .library-title, .branch-title {
            font-size: 24px;
            margin-bottom: 15px;
            font-weight: bold;
            color: #333;
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
    </style>
</head>
<body>

    <?php include 'Head.php'; ?>

    <div class="container">

        <div class="library-section">
            <div class="library-title">Библиотека</div>
            <div class="library-content">
                <p>Добро пожаловать в нашу библиотеку! Здесь вы найдете множество книг, журналов и других материалов для чтения и исследования. Наша библиотека предлагает уютную атмосферу для учебы и отдыха.</p>
                <p>Посетите нас, чтобы узнать больше о наших услугах и мероприятиях.</p>
            </div>
        </div>

        <div class="branch-section">
            <div class="branch-title">Филиалы библиотеки</div>
            <?php if (!empty($branches)): ?>
                <table>
                    <tr>
                        <th>Название</th>
                        <th>Город</th>
                        <th>Улица</th>
                        <th>Дом</th>
                    </tr>
                    <?php foreach ($branches as $branch): ?>
                        <tr>
                            <td><?= htmlspecialchars($branch['Название']) ?></td>
                            <td><?= htmlspecialchars($branch['Город']) ?></td>
                            <td><?= htmlspecialchars($branch['Улица']) ?></td>
                            <td><?= htmlspecialchars($branch['Дом']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>Нет доступных филиалов.</p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
