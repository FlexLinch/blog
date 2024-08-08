<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'blog');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];
    $is_private = isset($_POST['is_private']) ? 1 : 0;
    $conn->query("INSERT INTO posts (user_id, title, content, is_private) VALUES ('$user_id', '$title', '$content', '$is_private')");
    //$conn->query("INSERT INTO posts (user_id, title, content) VALUES ('$user_id', '$title', '$content')");
    $post_id = $conn->insert_id; // Получаем ID созданного поста
    echo "Пост создан!";
}

/*$is_private = isset($_POST['is_private']) ? 1 : 0;
$conn->query("INSERT INTO posts (user_id, title, content, is_private) VALUES ('$user_id', '$title', '$content', '$is_private')");*/

$tags = explode(',', $_POST['tags']); // Предполагается, что теги вводятся через запятую
foreach ($tags as $tag) {
    $tag = trim($tag);
    // Добавляем тег в таблицу tags, если его там нет
    $conn->query("INSERT INTO tags (name) VALUES ('$tag') ON DUPLICATE KEY UPDATE id=id");
    $tag_id = $conn->insert_id; // Получаем ID тега
    // Создаем связь между постом и тегом
    $conn->query("INSERT INTO post_tags (post_id, tag_id) VALUES ('$post_id', '$tag_id')");
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Создать пост</title>
</head>
<body>
    <a href="index.php">Вернуться на главную</a>
    <h2>Создать пост</h2>
    <form action="" method="POST">
        <input type="text" name="title" placeholder="Заголовок" required>
        <br>
        <textarea name="content" placeholder="Содержание" required></textarea>
        <br>
        <input type="text" name="tags" placeholder="Добавьте теги (через запятую)">
        <br>
        <input type="checkbox" name="is_private"> Скрытый пост
        <br>
        <button type="submit">Создать пост</button>
    </form>
</body>
</html>