<?php
require_once "../includes/db.php";
require_once "../includes/functions.php";
include("../includes/sidebar-teacher.php");

checkTeacher();
// Удаление класса
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_class_id'])) {
    $delete_id = intval($_POST['delete_class_id']);

    try {
        // Проверка: нет ли зависимостей в других таблицах
        $check = $pdo->prepare("SELECT COUNT(*) FROM control_test_results WHERE class_id = ?");
        $check->execute([$delete_id]);
        if ($check->fetchColumn() > 0) {
            $error = "Нельзя удалить: класс используется в результатах тестов.";
        } else {
            $stmt = $pdo->prepare("DELETE FROM classes WHERE id = ?");
            $stmt->execute([$delete_id]);
            $success = "Класс успешно удалён.";
        }
    } catch (PDOException $e) {
        $error = "Ошибка удаления: " . $e->getMessage();
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['class_name'])) {
    $class_name = trim($_POST['class_name']);
    if (empty($class_name)) {
        $error = "Ошибка: Название класса не может быть пустым.";
    } else {
        // Проверка уникальности класса
        $stmt = $pdo->prepare("SELECT id FROM classes WHERE name = ?");
        $stmt->execute([$class_name]);
        if ($stmt->fetch()) {
            $error = "Ошибка: Класс '$class_name' уже существует.";
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO classes (name) VALUES (?)");
                $stmt->execute([$class_name]);
                $success = "Класс '$class_name' успешно добавлен.";
            } catch (PDOException $e) {
                $error = "Ошибка базы данных: " . $e->getMessage();
            }
        }
    }
}

$classes = $pdo->query("SELECT * FROM classes ORDER BY name")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Управление классами</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../public/css/sidebar.css">
    <style>
        :root {
            --primary: #3498db;
            --primary-light: #5dade2;
            --danger: #e74c3c;
            --success: #2ecc71;
            --text: #2c3e50;
            --text-light: #ecf0f1;
            --bg: #ffffff;
            --bg-secondary: #f5f7fa;
            --border: #dfe6e9;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --radius: 8px;
        }
        .teacher-content {
            padding: 2rem;
            background: var(--bg-secondary);
            min-height: calc(100vh - 60px);
            width: 100%;
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--primary-light);
        }
        .page-title {
            color: var(--primary);
            font-size: 1.8rem;
            margin: 0;
        }
        .add-form {
            background: var(--bg);
            padding: 1.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            max-width: 400px;
            margin-bottom: 2rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--text);
        }
        .form-input {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 1rem;
            transition: border 0.3s;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        .submit-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: var(--radius);
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
        }
        .submit-btn:hover {
            background: var(--primary-light);
        }
        .cancel-btn {
            background: var(--bg-secondary);
            color: var(--text);
            border: 1px solid var(--border);
            padding: 0.8rem 1.5rem;
            border-radius: var(--radius);
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s;
        }
        .cancel-btn:hover {
            background: #e0e0e0;
        }
        .classes-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--bg);
            box-shadow: var(--shadow);
            border-radius: var(--radius);
            overflow: hidden;
        }
        .classes-table th {
            background: var(--primary);
            color: var(--text-light);
            padding: 1rem;
            text-align: left;
            font-weight: 500;
        }
        .classes-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border);
        }
        .error-message, .success-message {
            text-align: center;
            padding: 1rem;
        }
        .error-message {
            color: var(--danger);
        }
        .success-message {
            color: var(--success);
        }
    </style>
</head>
<body>
    <div class="teacher-content">
        <div class="page-header">
            <h1 class="page-title">Управление классами</h1>
            <a href="control_tests_add.php" class="cancel-btn">Добавить тест</a>
        </div>
        <?php if (isset($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="success-message"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <form method="post" class="add-form">
            <div class="form-group">
                <label class="form-label">Название класса:</label>
                <input type="text" name="class_name" class="form-input" placeholder="Например, 5А" required>
            </div>
            <button type="submit" class="submit-btn">Добавить класс</button>
        </form>
        <h2>Список классов</h2>
<table class="classes-table">
    <thead>
        <tr>
            <th>№</th>
            <th>Название класса</th>
            <th>Действие</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($classes)): ?>
            <tr><td colspan="3" style="text-align: center;">Нет классов</td></tr>
        <?php else: ?>
            <?php $n = 1; ?>
            <?php foreach ($classes as $cls): ?>
                <tr>
                    <td><?= $n++ ?></td>
                    <td><?= htmlspecialchars($cls['name']) ?></td>
                    <td>
                        <form method="post" onsubmit="return confirm('Удалить этот класс?');" style="display:inline;">
                            <input type="hidden" name="delete_class_id" value="<?= $cls['id'] ?>">
                            <button type="submit" class="cancel-btn" style="color: red;">Удалить</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

    </div>
</body>
</html>