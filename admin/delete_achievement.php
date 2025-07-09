<?php
require_once "../includes/db.php";
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Ошибка: не указан ID.");
}

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM achievements WHERE id = ?");
$stmt->execute([$id]);

header("Location: manage_achievements.php");
exit;
?>

