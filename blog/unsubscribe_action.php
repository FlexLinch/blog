<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'blog');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $subscriber_id = $_POST['subscriber_id'];

    // Удаляем подписку
    $conn->query("DELETE FROM subscriptions WHERE user_id='$user_id' AND subscriber_id='$subscriber_id'");

    header('Location: subscribe.php'); // Перенаправляем обратно на страницу отписки-подписки
}
?>