<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'blog');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT subscriber_id FROM subscriptions WHERE user_id='$user_id'");

echo '<a href="index.php">Вернуться на главную</a>';
echo "<h2>Посты подписок</h2>";
while ($row = $result->fetch_assoc()) {
    $subscribed_user_id = $row['subscriber_id'];

    // Получаем имя пользователя, на которого подписан текущий пользователь
    $user_result = $conn->query("SELECT username FROM users WHERE id='$subscribed_user_id'");
    $user = $user_result->fetch_assoc();

    // Получаем посты пользователя, на которого подписан текущий пользователь
    $posts = $conn->query("SELECT * FROM posts WHERE user_id='$subscribed_user_id'");

    while ($post = $posts->fetch_assoc()) {
        echo "<h3><a href='post_detail.php?id=" . $post['id'] . "'>" . htmlspecialchars($post['title']) . "</a></h3>";
        echo "<p>" . htmlspecialchars($post['content']) . "</p>";
        echo "<small>Автор: " . htmlspecialchars($user['username']) . "</small><br>";

        // Получаем теги для поста
        $tags_result = $conn->query("SELECT tags.name FROM tags JOIN post_tags ON tags.id = post_tags.tag_id WHERE post_tags.post_id = " . $post['id']);
        echo "<small>Теги: ";
        while ($tag = $tags_result->fetch_assoc()) {
            echo "<a href='tag_posts.php?tag=" . urlencode($tag['name']) . "'>" . htmlspecialchars($tag['name']) . "</a> ";
        }
        echo "</small><br><br>";
    }
}
?>