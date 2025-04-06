<?php

session_start();

// Инициализация данных новостей без подключения к базе данных
$news = [
    [
        'ID новости' => 1,
        'Заголовок' => 'Новая коллекция книг',
        'Текст' => 'Мы рады сообщить о поступлении новой коллекции книг в нашу библиотеку!',
        'Дата' => '2025-03-29 10:00'
    ],
    [
        'ID новости' => 2,
        'Заголовок' => 'График работы на праздники',
        'Текст' => 'В праздники библиотека будет работать с 10:00 до 18:00. Добро пожаловать!',
        'Дата' => '2025-03-20 15:30'
    ],
    [
        'ID новости' => 3,
        'Заголовок' => 'Курс по созданию сайтов',
        'Текст' => 'Записывайтесь на новый курс по созданию сайтов, который начинается в следующую субботу.',
        'Дата' => '2025-03-15 09:00'
    ],
    [
        'ID новости' => 4,
        'Заголовок' => 'Конкурс для юных читателей',
        'Текст' => 'Объявляем конкурс для детей до 14 лет! Победителей ждут призы и подарки.',
        'Дата' => '2025-03-10 14:00'
    ],
    [
        'ID новости' => 5,
        'Заголовок' => 'Виртуальные экскурсии',
        'Текст' => 'Теперь вы можете участвовать в виртуальных экскурсиях по нашей библиотеке прямо из дома!',
        'Дата' => '2025-03-01 12:00'
    ],
];

?>

<!DOCTYPE HTML>
<html lang="ru">
<head>
    <title>Новостная лента</title>
    <meta charset="utf-8">
    <style>
        body {
            background-color: rgb(176, 212, 254);
            font-family: Arial, Helvetica, sans-serif;
            padding: 20px;
        }
        .news-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto;
        }
        .news-item {
            border-bottom: 1px solid #ccc;
            padding: 15px 0;
        }
        h2 {
            margin: 0;
            font-size: 1.5em;
        }
        h1 {
            text-align: center;
        }
        p {
            margin: 5px 0;
        }
        .add-news {
            margin-top: 20px;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            background-color: rgb(176, 176, 247);
            color: white;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .add-news:hover {
            background-color: #4E5B9D;
        }
    </style>
</head>
<body>

<?php include 'Head.php'; ?>

<h1>Новостная лента</h1>

<div class="news-container">

    <?php if (!empty($news)): ?>

        <?php foreach ($news as $item): ?>

            <div class="news-item">
                <h2><?= htmlspecialchars($item['Заголовок']) ?></h2>
                <p><strong>Дата:</strong> <em><?= date('d.m.Y H:i', strtotime($item['Дата'])) ?></em></p>
                <p><?= nl2br(htmlspecialchars($item['Текст'])) ?></p>
            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <p>Нет новостей для отображения.</p>

    <?php endif; ?>

</div>

<?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
    <div>
        <a href="add_news.php" class="add-news">Добавить новость</a>
    </div>
<?php endif; ?>

</body>
</html>
