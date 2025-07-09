<?php
require_once "../includes/db.php";
session_start();

// Проверяем авторизацию
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Ошибка: не указан ID новости.");
}

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
$stmt->execute([$id]);

header("Location: manage_news.php");
exit;
?>
