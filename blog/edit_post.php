<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'blog');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];
    $post_result = $conn->query("SELECT * FROM posts WHERE id='$post_id'");
    $post = $post_result->fetch_assoc();

    if (!$post || $post['user_id'] != $_SESSION['user_id']) {
        echo "Пост не найден или у вас нет прав для редактирования.";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $tags = $_POST['tags']; // Получаем теги из формы
    $is_private = isset($_POST['is_private']) ? 1 : 0; // Получаем значение флага "Скрытый пост"

    // Обновляем пост
    $conn->query("UPDATE posts SET title='$title', content='$content', is_private='$is_private' WHERE id='$post_id'");

    // Удаляем старые теги
    $conn->query("DELETE FROM post_tags WHERE post_id='$post_id'");

    // Обработка новых тегов
    $tags_array = explode(',', $tags); // Предполагается, что теги вводятся через запятую
    foreach ($tags_array as $tag) {
        $tag = trim($tag);

        // Проверяем, существует ли тег в таблице tags
        $tag_result = $conn->query("SELECT id FROM tags WHERE name='$tag'");
        if ($tag_row = $tag_result->fetch_assoc()) {
            $tag_id = $tag_row['id']; // Получаем ID существующего тега
        } else {
            // Если тег не существует, добавляем его
            $conn->query("INSERT INTO tags (name) VALUES ('$tag')");
            $tag_id = $conn->insert_id; // Получаем ID нового тега
        }

        // Создаем связь между постом и тегом
        $conn->query("INSERT INTO post_tags (post_id, tag_id) VALUES ('$post_id', '$tag_id')");
    }

    header('Location: my_posts.php'); // Перенаправляем обратно на страницу с постами
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать пост</title>
</head>
<body>
    <a href="index.php">Вернуться на главную</a>
    <h2>Редактировать пост</h2>
    <form action="" method="POST">
        <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
        <br>
        <textarea name="content" required><?php echo htmlspecialchars($post['content']); ?></textarea>
        <br>

        <?php
        // Получаем текущие теги для поста
        $tags_result = $conn->query("SELECT tags.name FROM tags JOIN post_tags ON tags.id = post_tags.tag_id WHERE post_tags.post_id = '$post_id'");
        $tags = [];
        while ($tag = $tags_result->fetch_assoc()) {
            $tags[] = $tag['name'];
        }
        ?>

        <input type="text" name="tags" value="<?php echo htmlspecialchars(implode(', ', $tags)); ?>" placeholder="Введите теги через запятую" required>
        <br>
        <!-- Добавляем чекбокс для редактирования параметра "Скрытый пост" -->
        <input type="checkbox" name="is_private" <?php echo $post['is_private'] ? 'checked' : ''; ?>> Скрытый пост
        <br>
        <button type="submit">Сохранить изменения</button>
    </form>
</body>
</html>