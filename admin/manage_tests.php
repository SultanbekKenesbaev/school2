<?php
require_once "../includes/db.php";
require_once "../includes/functions.php";
include("../includes/header-admin.php");
include("../includes/sidebar-admin.php");

checkAdmin();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['subject_name'])) {
    $subject_name = trim($_POST['subject_name']);
    if (!empty($subject_name)) {
        $stmt = $pdo->prepare("INSERT INTO subjects (name) VALUES (?)");
        $stmt->execute([$subject_name]);
        header("Location: manage_tests.php");
        exit();
    }
}

if (isset($_GET['delete_subject_id'])) {
    $del_id = intval($_GET['delete_subject_id']);
    $pdo->prepare("DELETE FROM tests WHERE subject_id = ?")->execute([$del_id]);
    $pdo->prepare("DELETE FROM subjects WHERE id = ?")->execute([$del_id]);
    header("Location: manage_tests.php");
    exit();
}

$subjects = $pdo->query("SELECT * FROM subjects")->fetchAll();
$counts = [];
foreach ($subjects as $subject) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tests WHERE subject_id = ?");
    $stmt->execute([$subject['id']]);
    $counts[$subject['id']] = $stmt->fetchColumn();
}
?>
<style>
    :root {
        --primary: #3498db;
        --primary-light: #5dade2;
        --danger: #e74c3c;
        --danger-light: #f5b7b1;
        --success: #2ecc71;
        --text: #2c3e50;
        --text-light: #ecf0f1;
        --bg: #ffffff;
        --bg-secondary: #f5f7fa;
        --border: #dfe6e9;
        --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        --radius: 8px;
    }
    .admin-content {
        padding: 2rem;
        background: var(--bg-secondary);
        min-height: calc(100vh - 60px);
        width: 100%;
    }
    .page-title {
        color: var(--primary);
        font-size: 1.8rem;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--primary-light);
    }
    .tests-table {
        width: 100%;
        border-collapse: collapse;
        background: var(--bg);
        box-shadow: var(--shadow);
        border-radius: var(--radius);
        overflow: hidden;
    }
    .tests-table th {
        background: var(--primary);
        color: var(--text-light);
        padding: 1rem;
        text-align: left;
        font-weight: 500;
    }
    .tests-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border);
    }
    .tests-table tr:last-child td {
        border-bottom: none;
    }
    .tests-table tr:hover {
        background: rgba(52, 152, 219, 0.05);
    }
    .action-link {
        color: var(--primary);
        text-decoration: none;
        margin: 0 0.5rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        transition: color 0.3s;
    }
    .action-link:hover {
        color: var(--primary-light);
        text-decoration: underline;
    }
    .action-link.danger {
        color: var(--danger);
    }
    .action-link.danger:hover {
        color: var(--danger-light);
    }
    .count-badge {
        display: inline-block;
        background: var(--primary);
        color: white;
        padding: 0.3rem 0.6rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
    }
    .add-subject-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--success);
        color: white;
        padding: 0.8rem 1.2rem;
        border-radius: var(--radius);
        text-decoration: none;
        margin-bottom: 1.5rem;
        transition: background 0.3s;
    }
    .add-subject-btn:hover {
        background: #27ae60;
    }
    .add-form {
        background: var(--bg);
        padding: 1.5rem;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        margin-bottom: 2rem;
        max-width: 400px;
    }
    .form-input {
        width: 100%;
        padding: 0.8rem;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        margin-bottom: 1rem;
    }
    .submit-btn {
        background: var(--primary);
        color: white;
        border: none;
        padding: 0.8rem 1.5rem;
        border-radius: var(--radius);
        cursor: pointer;
    }
    @media (max-width: 768px) {
        .tests-table {
            display: block;
            overflow-x: auto;
        }
        .action-link {
            display: block;
            margin: 0.5rem 0;
        }
    }
</style>
<div class="admin-content">
    <h1 class="page-title">Управление тестами</h1>
    <form method="post" class="add-form">
        <input type="text" name="subject_name" class="form-input" placeholder="Название нового предмета" required>
        <button type="submit" class="submit-btn">Добавить предмет</button>
    </form>
    <table class="tests-table">
        <thead>
            <tr>
                <th>№</th>
                <th>Предмет</th>
                <th>Тесты</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php $n = 1; ?>
            <?php foreach ($subjects as $subject): ?>
                <tr>
                    <td><?= $n++ ?></td>
                    <td><?= htmlspecialchars($subject['name']) ?></td>
                    <td><span class="count-badge"><?= $counts[$subject['id']] ?></span></td>
                    <td>
                        <a href="manage_tests_list.php?subject_id=<?= $subject['id'] ?>" class="action-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10z"/>
                            </svg>
                            Редактировать
                        </a>
                        <a href="manage_tests.php?delete_subject_id=<?= $subject['id'] ?>" class="action-link danger"
                           onclick="return confirm('Вы точно хотите удалить предмет: <?= htmlspecialchars(addslashes($subject['name'])) ?>?');">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>
                            Удалить
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
