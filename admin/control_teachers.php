<?php
require_once "../includes/db.php";
require_once "../includes/functions.php";
include("../includes/header-admin.php");
include("../includes/sidebar-admin.php");

checkAdmin();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {
    $username = sanitize($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $first_name = sanitize($_POST['first_name']);
    $last_name = sanitize($_POST['last_name']);
    $subjects = implode(',', $_POST['subjects'] ?? []);

    try {
        $stmt = $pdo->prepare("INSERT INTO teacher_user (username, password, first_name, last_name, subjects) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$username, $password, $first_name, $last_name, $subjects]);
        header("Location: control_teachers.php?success=Учитель успешно добавлен");
        exit();
    } catch (PDOException $e) {
        $error = "Ошибка: " . $e->getMessage();
    }
}

if (isset($_GET['delete_teacher_id'])) {
    $del_id = intval($_GET['delete_teacher_id']);
    $pdo->prepare("DELETE FROM teacher_user WHERE id = ?")->execute([$del_id]);
    header("Location: control_teachers.php?success=Учитель удалён");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['subject_name'])) {
    $subject_name = sanitize($_POST['subject_name']);
    $pdo->prepare("INSERT INTO control_subjects (name) VALUES (?)")->execute([$subject_name]);
    header("Location: control_teachers.php?success=Предмет добавлен");
    exit();
}

$teachers = $pdo->query("SELECT t.*, GROUP_CONCAT(s.name) AS subject_names 
    FROM teacher_user t 
    LEFT JOIN control_subjects s ON FIND_IN_SET(s.id, t.subjects)
    GROUP BY t.id")->fetchAll();
$subjects = $pdo->query("SELECT * FROM control_subjects")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Управление учителями и предметами</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
        .add-form {
            background: var(--bg);
            padding: 1.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
            max-width: 400px;
        }
        .form-input, .form-select {
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
        .teachers-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--bg);
            box-shadow: var(--shadow);
            border-radius: var(--radius);
            overflow: hidden;
        }
        .teachers-table th {
            background: var(--primary);
            color: var(--text-light);
            padding: 1rem;
            text-align: left;
            font-weight: 500;
        }
        .teachers-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border);
        }
        .action-link {
            color: var(--primary);
            text-decoration: none;
            margin: 0 0.5rem;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }
        .action-link.danger {
            color: var(--danger);
        }
        .success-message {
            color: var(--success);
            text-align: center;
            padding: 1rem;
        }
        .error-message {
            color: var(--danger);
            text-align: center;
            padding: 1rem;
        }
    </style>
</head>
<body>
    <div class="admin-content">
        <h1 class="page-title">Управление учителями и предметами</h1>
        <?php if (isset($_GET['success'])): ?>
            <div class="success-message"><?= htmlspecialchars($_GET['success']) ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <h2>Добавить учителя</h2>
        <form method="post" class="add-form">
            <input type="text" name="username" class="form-input" placeholder="Логин" required>
            <input type="password" name="password" class="form-input" placeholder="Пароль" required>
            <input type="text" name="first_name" class="form-input" placeholder="Имя" required>
            <input type="text" name="last_name" class="form-input" placeholder="Фамилия" required>
            <select name="subjects[]" class="form-select" multiple required>
                <?php foreach ($subjects as $subj): ?>
                    <option value="<?= $subj['id'] ?>"><?= htmlspecialchars($subj['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="submit-btn">Добавить учителя</button>
        </form>
        <h2>Добавить предмет</h2>
        <form method="post" class="add-form">
            <input type="text" name="subject_name" class="form-input" placeholder="Название предмета" required>
            <button type="submit" class="submit-btn">Добавить предмет</button>
        </form>
        <h2>Список учителей</h2>
        <table class="teachers-table">
            <thead>
                <tr>
                    <th>№</th>
                    <th>Имя</th>
                    <th>Предметы</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php $n = 1; ?>
                <?php foreach ($teachers as $teacher): ?>
                    <tr>
                        <td><?= $n++ ?></td>
                        <td><?= htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']) ?></td>
                        <td><?= htmlspecialchars($teacher['subject_names']) ?></td>
                        <td>
                            <a href="control_teachers.php?delete_teacher_id=<?= $teacher['id'] ?>" class="action-link danger"
                               onclick="return confirm('Вы точно хотите удалить учителя?');">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
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
</body>
</html>