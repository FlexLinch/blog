<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'blog');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = $_POST['post_id'];

    // Удаляем пост
    $conn->query("DELETE FROM posts WHERE id='$post_id'");
    // Удаляем связи с тегами
    $conn->query("DELETE FROM post_tags WHERE post_id='$post_id'");

    header('Location: my_posts.php'); // Перенаправляем обратно на страницу с постами
}
?>