<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'blog');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

echo '<a href="index.php">Вернуться на главную</a>';

// ПОДПИСКА

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subscriber_username = $_POST['subscriber_username'];
    $user_id = $_SESSION['user_id'];
    
    // получаю id юзера для подписки
    $subscriber_result = $conn->query("SELECT id FROM users WHERE username='$subscriber_username'");
    $subscriber = $subscriber_result->fetch_assoc();
    $subscriber_id = $subscriber['id'];
    
    // проверяю, что юзер существует
    if ($subscriber_id) {
        // проверяю, не подписан ли уже залогиненный юзер на указанного юзера
        $check_subscription = $conn->query("SELECT * FROM subscriptions WHERE user_id='$user_id' AND subscriber_id='$subscriber_id'");
        
        if ($check_subscription->num_rows == 0) {
            // Если подписки нет, добавляем новую
            $conn->query("INSERT INTO subscriptions (user_id, subscriber_id) VALUES ('$user_id', '$subscriber_id')");
            echo "Вы подписались на пользователя!";
        } else {
            echo "Вы уже подписаны на этого пользователя.";
        }
    } else {
        echo "Пользователь не найден.";
    }
}

// ОТПИСКА

$user_id = $_SESSION['user_id'];

// Получаем список пользователей, на которых подписан текущий пользователь
$result = $conn->query("SELECT users.id, users.username FROM subscriptions JOIN users ON subscriptions.subscriber_id = users.id WHERE subscriptions.user_id='$user_id'");

if ($result->num_rows > 0) {
    echo "<h2>Вы подписаны на следующих пользователей:</h2>";
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<span>" . htmlspecialchars($row['username']) . "</span>";
        echo " <form action='unsubscribe_action.php' method='POST' style='display:inline;'>";
        echo "<input type='hidden' name='subscriber_id' value='" . $row['id'] . "'>";
        echo "<button type='submit'>Отписаться</button>";
        echo "</form>";
        echo "</div>";
    }
} else {
    echo "<p>Вы не подписаны ни на одного пользователя.</p>";
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Форма подписки</title>
</head>
<body>
    <h2>Подпиcаться на пользователя:</h2>
    <form action="" method="POST">
        <input type="text" name="subscriber_username" placeholder="Имя пользователя для подписки" required>
        <button type="submit">Подписаться</button>
    </form>
</body>
</html>
