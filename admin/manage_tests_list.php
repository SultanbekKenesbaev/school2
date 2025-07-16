<?php
require_once dirname(__DIR__) . "/includes/db.php";
require_once dirname(__DIR__) . "/includes/functions.php";
include(dirname(__DIR__) . "/includes/header-admin.php");
include(dirname(__DIR__) . "/includes/sidebar-admin.php");

checkAdmin();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Логирование для отладки
function log_error($message) {
    error_log("[manage_tests_list.php] " . $message . " at " . date('Y-m-d H:i:s') . "\n", 3, dirname(__DIR__) . "/error.log");
}

$subject_id = $_GET['subject_id'] ?? null;
if (!$subject_id || !is_numeric($subject_id)) {
    log_error("Ошибка: subject_id не передан или неверный. subject_id=$subject_id");
    die("Ошибка: Предмет не выбран.");
}

try {
    $stmt = $pdo->prepare("SELECT * FROM subjects WHERE id = ?");
    $stmt->execute([$subject_id]);
    $subject = $stmt->fetch();
    if (!$subject) {
        log_error("Ошибка: Предмет с id=$subject_id не найден.");
        die("Ошибка: Предмет не найден.");
    }

    $stmt = $pdo->prepare("SELECT id, test_name, pdf_file FROM tests WHERE subject_id = ? ORDER BY id");
    $stmt->execute([$subject_id]);
    $tests = $stmt->fetchAll();
} catch (PDOException $e) {
    log_error("Ошибка базы данных: " . $e->getMessage());
    die("Ошибка базы данных: " . $e->getMessage());
}

if (isset($_GET['delete_test_id'])) {
    $del_id = intval($_GET['delete_test_id']);
    try {
        $stmt = $pdo->prepare("SELECT pdf_file FROM tests WHERE id = ?");
        $stmt->execute([$del_id]);
        $test = $stmt->fetch();
        if ($test && $test['pdf_file'] && file_exists($test['pdf_file'])) {
            unlink($test['pdf_file']);
        }
        $pdo->prepare("DELETE FROM tests WHERE id = ?")->execute([$del_id]);
        header("Location: manage_tests_list.php?subject_id=" . $subject_id . "&success=Тест успешно удалён");
        exit();
    } catch (PDOException $e) {
        log_error("Ошибка при удалении теста: " . $e->getMessage());
        die("Ошибка при удалении теста: " . $e->getMessage());
    }
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
        overflow: auto;
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
    .tests-count {
        color: #7f8c8d;
        font-size: 1.2rem;
    }
    .add-test-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--success);
        color: white;
        padding: 0.8rem 1.2rem;
        border-radius: var(--radius);
        text-decoration: none;
        transition: background 0.3s;
    }
    .add-test-btn:hover {
        background: #27ae60;
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
        vertical-align: top;
    }
    .tests-table tr:last-child td {
        border-bottom: none;
    }
    .tests-table tr:hover {
        background: rgba(52, 152, 219, 0.05);
    }
    .test-name {
        font-weight: 500;
    }
    .actions-cell {
        white-space: nowrap;
    }
    .action-link {
        color: var(--primary);
        text-decoration: none;
        margin-right: 1rem;
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
    .no-tests {
        text-align: center;
        padding: 2rem;
        color: #7f8c8d;
    }
    .success-message {
        color: var(--success);
        text-align: center;
        padding: 1rem;
    }
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        .tests-table {
            display: block;
            overflow-x: auto;
        }
    }
</style>
<div class="admin-content">
    <div class="page-header">
        <div>
            <h1 class="page-title">Тесты по предмету: <?= htmlspecialchars($subject['name']) ?></h1>
            <div class="tests-count">Всего тестов: <?= count($tests) ?></div>
        </div>
        <a href="add_test.php?subject_id=<?= htmlspecialchars($subject_id) ?>" class="add-test-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
            </svg>
            Добавить тест
        </a>
    </div>
    <?php if (isset($_GET['success'])): ?>
        <div class="success-message"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>
    <?php if (empty($tests)): ?>
        <div class="no-tests">
            <p>Нет тестов для этого предмета</p>
            <p>Добавьте первый тест, нажав на кнопку выше</p>
        </div>
    <?php else: ?>
        <table class="tests-table">
            <thead>
                <tr>
                    <th style="width: 50px;">№</th>
                    <th>Название теста</th>
                    <th>PDF</th>
                    <th style="width: 150px;">Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tests as $index => $test): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td class="test-name"><?= htmlspecialchars($test['test_name']) ?></td>
                        <td><a href="<?= htmlspecialchars($test['pdf_file']) ?>" target="_blank">Просмотреть PDF</a></td>
                        <td class="actions-cell">
                            <a href="edit_test.php?id=<?= htmlspecialchars($test['id']) ?>&subject_id=<?= htmlspecialchars($subject_id) ?>" class="action-link">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 0 .11-.168l10-10z"/>
                                </svg>
                                Редактировать
                            </a>
                            <a href="manage_tests_list.php?subject_id=<?= htmlspecialchars($subject_id) ?>&delete_test_id=<?= htmlspecialchars($test['id']) ?>" 
                               class="action-link danger"
                               onclick="return confirm('Вы точно хотите удалить этот тест?');">
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
    <?php endif; ?>
</div>
