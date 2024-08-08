<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'blog');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tag_name = $_POST['tag_name'];

    // Получаем ID тега
    $tag_result = $conn->query("SELECT id FROM tags WHERE name='$tag_name'");
    $tag = $tag_result->fetch_assoc();

    if ($tag) {
        $tag_id = $tag['id'];

        // Получаем посты с этим тегом
        $posts_result = $conn->query("SELECT posts.* FROM posts JOIN post_tags ON posts.id = post_tags.post_id WHERE post_tags.tag_id = '$tag_id'");

        echo "<h2>Посты с тегом: " . htmlspecialchars($tag_name) . "</h2>";
        while ($post = $posts_result->fetch_assoc()) {
            // Получаем имя автора поста
            $author_result = $conn->query("SELECT username FROM users WHERE id='" . $post['user_id'] . "'");
            $author = $author_result->fetch_assoc();
            
            echo "<h3><a href='post_detail.php?id=" . $post['id'] . "'>" . htmlspecialchars($post['title']) . "</a></h3>";
            echo "<p>" . htmlspecialchars($post['content']) . "</p>";
            echo "<small>Автор: " . htmlspecialchars($author['username']) . "</small><br>";
            
            // Получаем теги для поста
            $tags_result = $conn->query("SELECT tags.name FROM tags JOIN post_tags ON tags.id = post_tags.tag_id WHERE post_tags.post_id = " . $post['id']);
            echo "<small>Теги: ";
            while ($tag = $tags_result->fetch_assoc()) {
                echo "<a href='search_tag.php?tag=" . urlencode($tag['name']) . "'>" . htmlspecialchars($tag['name']) . "</a> ";
            }
            echo "</small><br><br>";
        }
    } else {
        echo "<p>Тег не найден.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Поиск по тегу</title>
</head>
<body>
    <a href="index.php">Вернуться на главную</a>
    <h2>Поиск постов по тегу</h2>
    <form action="" method="POST">
        <input type="text" name="tag_name" placeholder="Введите Тег" required>
        <button type="submit">Найти</button>
    </form>
</body>
</html>