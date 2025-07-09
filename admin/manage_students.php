<?php
require_once "../includes/db.php";
include("../includes/header-admin.php");
include("../includes/sidebar-admin.php");
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM students ORDER BY created_at DESC");
$students = $stmt->fetchAll();
?>
<section class="dos-admin">
    <div class="admin-head">
        <h2>Управление студентами</h2>
        <a href="add_student.php">➕  Добавить студента</a>
    </div>
    <table class="table">
        <tr>
            <th>Имя</th>
            <th>Фамилия</th>
            <th>Достижения</th>
            <th>О студенте</th>
            <th>Фото</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($students as $student): ?>
            <tr>
                <td><?= htmlspecialchars($student['first_name']) ?></td>
                <td><?= htmlspecialchars($student['last_name']) ?></td>
                <td><?= htmlspecialchars($student['achievements']) ?></td>
                <td><?= htmlspecialchars($student['about']) ?></td>
                <td>
                    <?php if (!empty($item['image'])): ?>
                        <img src="../public/<?= htmlspecialchars($item['image']) ?>" width="100">
                    <?php endif; ?>
                </td>
                <td class="redo">
                    <a class="add" href="edit_student.php?id=<?= $student['id'] ?>">Редактировать &#128221;</a>
                    <a class="del" href="delete_student.php?id=<?= $student['id'] ?>" onclick="return confirm('Удалить?')">Удалить &#10060;</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>