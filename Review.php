<?php

session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'visitor') {
    header("Location: Authorization.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));
    $subject = "Новый отзыв от " . $email;
    $body = "Сообщение:\n" . $message;
    $headers = "From: $email\r\n";
    mail("your_email@example.com", $subject, $body, $headers);
}

?>

<!DOCTYPE HTML>
<html lang="ru">
<head>
    <title>Оставить отзыв</title>
    <meta charset="utf-8">
    <style>
        body {
            background-color: rgb(176, 212, 254);
            font-family: Arial, Helvetica, sans-serif;
            padding: 20px;
        }
        .review-form {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 600px;
            margin: auto;
        }
        h2 {
            text-align: center;
            color: rgb(176, 212, 254);
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px -10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            padding: 10px 15px;
            background-color: rgb(176, 176, 247);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #4E5B9D;
        }
    </style>
</head>
<body>

<?php include 'Head.php'; ?>

<h2> ОТЗЫВ </h2>

<div class="review-form">
    <form method="post" action="">
        <label for="email">Ваш Email:</label>
        <input type="email" name="email" required>

        <label for="message">Ваш отзыв:</label>
        <textarea name="message" rows="5" required></textarea>

        <button type="submit">Отправить отзыв</button>
    </form>
</div>

</body>
</html>
