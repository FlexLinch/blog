<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'blog');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Получаем все посты, созданные текущим залогиненным юзером
$result = $conn->query("SELECT * FROM posts WHERE user_id='$user_id'");

echo '<a href="index.php">Вернуться на главную</a>';
echo "<h2>Ваши посты</h2>";
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
    echo "</small><br>";

    // Кнопки редактирования и удаления
    echo "<form action='delete_post.php' method='POST' style='display:inline;'>";
    echo "<input type='hidden' name='post_id' value='" . $row['id'] . "'>";
    echo "<button type='submit'>Удалить</button>";
    echo "</form>";
    echo "<a href='edit_post.php?id=" . $row['id'] . "'>Редактировать</a>";
    echo "<hr>";
}
?>