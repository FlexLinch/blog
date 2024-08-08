<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'blog');

if (!isset($_GET['id'])) {
    echo "Пост не найден.";
    exit();
}

$post_id = $_GET['id'];

// Получаем пост по ID
$post_result = $conn->query("SELECT * FROM posts WHERE id='$post_id'");
$post = $post_result->fetch_assoc();

if (!$post) {
    echo "Пост не найден.";
    exit();
}

// Получаем имя автора поста
$author_id = $post['user_id'];
$author_result = $conn->query("SELECT username FROM users WHERE id='$author_id'");
$author = $author_result->fetch_assoc();

// Получаем теги для поста
$tags_result = $conn->query("SELECT tags.name FROM tags JOIN post_tags ON tags.id = post_tags.tag_id WHERE post_tags.post_id = " . $post['id']);

// Обработка комментариев
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];
    $conn->query("INSERT INTO comments (post_id, user_id, content) VALUES ('$post_id', '$user_id', '$content')");
}

// Получаем комментарии к посту
$comments_result = $conn->query("SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE post_id='$post_id'");

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
</head>
<body>
    <a href="index.php">Вернуться на главную</a>
    <h2><?php echo htmlspecialchars($post['title']); ?></h2>
    <p><?php echo htmlspecialchars($post['content']); ?></p>
    <small>Автор: <?php echo htmlspecialchars($author['username']); ?></small><br>
    <small>Теги:
    <?php while ($tag = $tags_result->fetch_assoc()): ?>
        <a href="tag_posts.php?tag=<?php echo urlencode($tag['name']); ?>"><?php echo htmlspecialchars($tag['name']); ?></a>
    <?php endwhile; ?>
    </small><br>

    <h3>Комментарии</h3>
    <form action="" method="POST">
        <textarea name="content" placeholder="Ваш комментарий" required></textarea>
        <button type="submit">Добавить комментарий</button>
    </form>

    <h4>Список комментариев:</h4>
    <?php while ($comment = $comments_result->fetch_assoc()): ?>
        <p><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong> <?php echo htmlspecialchars($comment['content']); ?></p>
    <?php endwhile; ?>
</body>
</html>