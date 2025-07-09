<?php
require_once "../includes/db.php";
include("../includes/header-admin.php");
include("../includes/sidebar-admin.php");
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}
$stmt = $pdo->query("SELECT * FROM achievements ORDER BY created_at DESC");
$achievements = $stmt->fetchAll();
?>

<section class="dos-admin">
    <div class="admin-head">
        <h2>Управление достижениями</h2>
        <a href="add_achievement.php">&#127942; Добавить достижение</a>
    </div>
    <table class="table">
        <tr>
            <th class="head">Заголовок</th>
            <th class="head">Текст</th>
            <th class="head">Изображение</th>
            <th class="head">Дата</th>
            <th class="head">Действия</th>
        </tr>
        <?php foreach ($achievements as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['title']) ?></td>
                <td><?= nl2br(htmlspecialchars($item['description'])) ?></td>
                <td>
                    <?php if (!empty($item['image'])): ?>
                        <img src="../public/<?= htmlspecialchars($item['image']) ?>" width="100">
                    <?php endif; ?>
                </td>
                <td><?= date("d.m.Y H:i", strtotime($item['created_at'])) ?></td>
                <td class="redo">
                    <a class="add" href="edit_achievement.php?id=<?= $item['id'] ?>">Редактировать &#128221;</a> 
                    <a class="del" href="delete_achievement.php?id=<?= $item['id'] ?>" onclick="return confirm('Удалить достижение?');">Удалить &#10060;</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>