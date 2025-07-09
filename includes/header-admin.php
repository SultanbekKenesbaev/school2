<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Школа</title>
    <link rel="stylesheet" href="../public/css/styles.css">
    <link rel="stylesheet" href="../public/css/header.css">
    <link rel="stylesheet" href="../public/css/sidebar.css">
</head>

<body>

    <header>
        <div class="container">
            <div class="menu">
                <h1>Добро пожаловать на сайт школы</h1>

                <nav>
                    <ul class="menu-list">
                        <li><a href="dashboard.php">Главная</a></li>
                        <li><a href="manage_achievements.php">Достижения</a></li>
                        <li><a href="manage_news.php">Новости</a></li>
                        <li><a href="manage_students.php">Лучшие студенты</a></li>
                        <li><a href="manage_students.php">Учителя</a></li>
                        <?php if (isset($_SESSION['admin'])): ?>
                            <li><a href="../admin/logout.php">Выход</a></li>
                        <?php else: ?>
                            <li><a href="admin/index.php">Вход</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
    </header>