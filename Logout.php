<?php
session_start();
session_destroy(); // Уничтожаем все данные сессии
header("Location: Authorization.php"); // Перенаправление на страницу авторизации
exit();
?>
