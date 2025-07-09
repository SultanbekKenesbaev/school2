<?php
require_once "../includes/db.php";
include("../includes/header-admin.php");
include("../includes/sidebar-admin.php");

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM teachers ORDER BY created_at DESC");
$teachers = $stmt->fetchAll();
?>

<section class="dos-admin">
    <div class="admin-head">
        <h2>Управление учителями</h2>
        <a href="add_teacher.php">➕ Добавить учителя</a>

    </div>
    <table class="table">
        <tr>
            <th>Имя</th>
            <th>Фамилия</th>
            <th>Предметы</th>
            <th>Достижения</th>
            <th>О себе</th>
            <th>Фото</th>
            <th>Действия</th>

        </tr>
        <?php foreach ($teachers as $teacher): ?>
            <tr>
                <td><?= htmlspecialchars($teacher['first_name']) ?></td>
                <td><?= htmlspecialchars($teacher['last_name']) ?></td>
                <td><?= htmlspecialchars($teacher['subjects']) ?></td>
                <td><?= htmlspecialchars($teacher['achievements']) ?></td>
                <td><?= htmlspecialchars($teacher['about']) ?></td>
                <td>
                    <?php if (!empty($item['image'])): ?>
                        <img src="../public/<?= htmlspecialchars($item['image']) ?>" width="100">
                    <?php endif; ?>
                </td>
                <td class="redo">
                    <a class="add" href="edit_teacher.php?id=<?= $teacher['id'] ?>">Редактировать &#128221;</a>
                    <a class="del" href="delete_teacher.php?id=<?= $teacher['id'] ?>" onclick="return confirm('Удалить учителя?')">Удалить &#10060;</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

</section>