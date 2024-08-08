<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'blog');

// Получаем все публичные посты
$result = $conn->query("SELECT * FROM posts WHERE is_private = 0");

echo '<a href="index.php">Вернуться на главную</a>';
echo "<h2>Посты</h2>";
while ($row = $result->fetch_assoc()) {
    // Получаем имя автора поста
    $author_id = $row['user_id'];
    $author_result = $conn->query("SELECT username FROM users WHERE id='$author_id'");
    $author = $author_result->fetch_assoc();

    // Выводим заголовок поста как ссылку на post_detail.php
    echo "<h3><a href='post_detail.php?id=" . $row['id'] . "'>" . htmlspecialchars($row['title']) . "</a></h3>";
    echo "<p>" . htmlspecialchars($row['content']) . "</p>";
    echo "<small>Автор: " . htmlspecialchars($author['username']) . "</small><br>";

    // Получаем теги для поста
    $tags_result = $conn->query("SELECT tags.name FROM tags JOIN post_tags ON tags.id = post_tags.tag_id WHERE post_tags.post_id = " . $row['id']);
    echo "<small>Теги: ";
    while ($tag = $tags_result->fetch_assoc()) {
        echo "<a href='tag_posts.php?tag=" . urlencode($tag['name']) . "'>" . htmlspecialchars($tag['name']) . "</a> ";
    }
    echo "</small><br><br>";
}
?>