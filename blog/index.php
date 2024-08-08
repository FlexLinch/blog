<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мой учебный Блог</title>
</head>
<body>
    <h1>Добро пожаловать в мой блог!</h1>
    <p>Это тестовый блог, созданный исключительно в учебных целях.
        <br> Тут демонстрируется логика поведения кода и отсутствует какой-либо дизайн.
        <br> Блог заполнен небольшим количеством постов, создано три пользователя:
        <br> User1 (пароль pass1)
        <br> User2 (пароль pass2)
        <br> User3 (пароль pass3)
        <br> Кроме того, можно зарегистрировать своего пользователя для проверки функционала.</p>
        <br><br>
    <nav>
        <a href="register.php">Регистрация</a>
        <br><br>
        <a href="login.php">Вход</a>
        <br><br>
        <a href="create_post.php">Создать пост</a>
        <br><br>
        <a href="my_posts.php">Мои посты</a>
        <br><br>
        <a href="posts.php">Все посты</a>
        <br><br>
        <a href="search_tag.php">Поиск по тегу</a>
        <br><br>
        <a href="hidden_post.php">Запрос скрытого поста</a>
        <br><br>
        <a href="subscribe.php">Подписаться на пользователей</a>
        <br><br>
        <a href="subscriptions.php">Посты подписок</a>
    </nav>
</body>
</html>