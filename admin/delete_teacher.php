<?php
require_once "../includes/db.php";
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM teachers WHERE id = ?");
$stmt->execute([$id]);

header("Location: manage_teachers.php");
exit;
?>
