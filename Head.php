<?php
session_start(); // Необходимо для работы сессий
?>

<style>
    nav {
        display: block;
        border-radius: 10px;
        padding-top: 10px;
        padding-bottom: 10px;
        background: rgb(176, 176, 247);
        margin-left: -10px;
        margin-right: -10px;
        text-align: center;
        opacity: 90%;
        width: auto;
        margin-left: auto;
        margin-right: auto;
    }
    .a_nav {
        margin: 7px;
        color: rgb(52, 0, 130);
        text-decoration: none;
    }
</style>

<nav>
    <div>
        <a class="a_nav" href="index.php">Главная страница</a>
        <a class="a_nav" href="News.php">Новости</a>
        <a class="a_nav" href="Gallery.php">Галерея</a>
        <a class="a_nav" href="Genres.php">Жанры</a>
        <a class="a_nav" href="Authors.php">Авторы</a>
        <a class="a_nav" href="Book.php">Книги</a>

        <?php if (isset($_SESSION['user_type'])): ?>
            <!-- Если пользователь авторизован как посетитель, показываем кнопку корзины -->
            <?php if ($_SESSION['user_type'] === 'visitor'): ?>
                <a class="a_nav" href="Review.php">Написать отзыв</a>
                <a class="a_nav" href="Cart.php">Корзина</a>
                <a class="a_nav" href="PersonalCabinetVisitor.php">Личный кабинет</a>
            <?php else: ?>
                <a class="a_nav" href="PersonalCabinetEmployee.php">Личный кабинет</a>
            <?php endif; ?>
            <!-- Если пользователь авторизован, показываем кнопку выхода -->
            <a class="a_nav" href="Logout.php">Выйти</a>
        <?php else: ?>
            <!-- Если пользователь не авторизован, показываем кнопки регистрации и авторизации -->
            <a class="a_nav" href="Registration.php">Регистрация</a>
            <a class="a_nav" href="Authorization.php">Авторизация</a>
        <?php endif; ?>
    </div>
</nav>
