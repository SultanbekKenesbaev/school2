<?php
require_once "../includes/db.php";
require_once "../includes/functions.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitize($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Проверка, есть ли уже администратор
    $stmt = $pdo->query("SELECT COUNT(*) FROM admins");
    if ($stmt->fetchColumn() > 0) {
        die("Регистрация закрыта. Уже существует один администратор.");
    }

    $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $password]);

    header("Location: index.php?registered=true");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация администратора</title>
</head>
<body>
    <h2>Регистрация</h2>
    <form method="POST">
        <label>Логин: <input type="text" name="username" required></label><br>
        <label>Пароль: <input type="password" name="password" required></label><br>
        <button type="submit">Зарегистрироваться</button>
    </form>
</body>
</html>
