<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'blog');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];

    // Получаем скрытый пост по заголовку
    $result = $conn->query("SELECT * FROM posts WHERE title='$title' AND is_private = 1");

    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();

        // Получаем имя автора поста
        $author_id = $post['user_id'];
        $author_result = $conn->query("SELECT username FROM users WHERE id='$author_id'");
        $author = $author_result->fetch_assoc();

        echo "<h2><a href='post_detail.php?id=" . $post['id'] . "'>" . htmlspecialchars($post['title']) . "</a></h2>";
        echo "<p>" . htmlspecialchars($post['content']) . "</p>";
        echo "<small>Автор: " . htmlspecialchars($author['username']) . "</small><br>";

        // Получаем теги для поста
        $tags_result = $conn->query("SELECT tags.name FROM tags JOIN post_tags ON tags.id = post_tags.tag_id WHERE post_tags.post_id = " . $post['id']);
        echo "<small>Теги: ";
        while ($tag = $tags_result->fetch_assoc()) {
            echo "<a href='tag_posts.php?tag=" . urlencode($tag['name']) . "'>" . htmlspecialchars($tag['name']) . "</a> ";
        }
        echo "</small><br>";
    } else {
        echo "<p>Скрытый пост с таким заголовком не найден.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Скрытый пост</title>
</head>
<body>
    <a href="index.php">Вернуться на главную</a>
    <h2>Запрос скрытого поста</h2>
    <form action="hidden_post.php" method="POST">
        <input type="text" name="title" placeholder="Введите заголовок скрытого поста" required>
        <button type="submit">Запросить пост</button>
    </form>
</body>
</html>
