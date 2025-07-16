<?php
require_once "../includes/db.php";
// require_once "../includes/functions.php"; // Можно убрать, если sanitize() не используется
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username']), ENT_QUOTES, 'UTF-8');
    $password = $_POST['password'];

    // Проверка администратора
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin'] = $admin['id'];
        $_SESSION['role'] = 'admin';
        header("Location: dashboard.php");
        exit();
    }

    // Проверка учителя
    $stmt = $pdo->prepare("SELECT * FROM teacher_user WHERE username = ?");
    $stmt->execute([$username]);
    $teacher = $stmt->fetch();
    if ($teacher && password_verify($password, $teacher['password'])) {
        $_SESSION['teacher'] = $teacher['id'];
        $_SESSION['role'] = 'teacher';
        header("Location: ../teacher/manage_classes.php");
        exit();
    }

    $error = "Неверные данные!";
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход в систему</title>
    <link rel="stylesheet" href="../public/css/admin-vhod.css">
</head>
<body>
    <div class="box">
        <h2>Вход</h2>
        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST">
            <div class="inputs">
                <label>Логин: </label>
                <input type="text" name="username" required>
                <label>Пароль: </label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Войти</button>
        </form>
    </div>
</body>
</html>