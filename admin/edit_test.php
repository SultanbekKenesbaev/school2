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
    error_log("[edit_test.php] " . $message . " at " . date('Y-m-d H:i:s') . "\n", 3, dirname(__DIR__) . "/error.log");
}

$id = $_GET['id'] ?? null;
$subject_id = $_GET['subject_id'] ?? null;
if (!$id || !is_numeric($id) || !$subject_id || !is_numeric($subject_id)) {
    log_error("Ошибка: id или subject_id не переданы или неверные. id=$id, subject_id=$subject_id");
    die("Ошибка: Тест или предмет не выбраны. Пожалуйста, выберите тест для редактирования.");
}

try {
    $stmt = $pdo->prepare("SELECT * FROM tests WHERE id = ? AND subject_id = ?");
    $stmt->execute([$id, $subject_id]);
    $test = $stmt->fetch();
    if (!$test) {
        log_error("Ошибка: Тест с id=$id и subject_id=$subject_id не найден.");
        die("Ошибка: Тест не найден.");
    }
    $correct_answers = json_decode($test['correct_answers'], true) ?: [];
} catch (PDOException $e) {
    log_error("Ошибка базы данных: " . $e->getMessage());
    die("Ошибка базы данных: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $test_name = trim($_POST['test_name'] ?? '');
    $correct_answers = $_POST['correct_answers'] ?? [];
    $pdf_file = $test['pdf_file'];

    // Валидация
    if (empty($test_name)) {
        $error = "Название теста обязательно.";
    } elseif (count(array_filter($correct_answers, fn($answer) => in_array($answer, ['1', '2', '3', '4']))) != 30) {
        $error = "Необходимо указать ровно 30 правильных ответов (1, 2, 3 или 4).";
    } else {
        // Обработка загрузки PDF
        if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == 0) {
            $upload_dir = dirname(__DIR__) . "/public/uploads/";
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $pdf_name = time() . '_' . basename($_FILES['pdf_file']['name']);
            $upload_file = $upload_dir . $pdf_name;
            if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $upload_file)) {
                if ($pdf_file && file_exists($pdf_file)) {
                    unlink($pdf_file);
                }
                $pdf_file = "../public/uploads/" . $pdf_name;
            } else {
                $error = "Ошибка при загрузке PDF-файла.";
            }
        }

        // Обновление теста
        try {
            $stmt = $pdo->prepare("UPDATE tests SET test_name = ?, pdf_file = ?, correct_answers = ? WHERE id = ? AND subject_id = ?");
            $stmt->execute([$test_name, $pdf_file, json_encode($correct_answers), $id, $subject_id]);
            header("Location: manage_tests_list.php?subject_id=$subject_id&success=Тест успешно обновлён");
            exit();
        } catch (PDOException $e) {
            log_error("Ошибка при обновлении теста: " . $e->getMessage());
            $error = "Ошибка при обновлении теста: " . $e->getMessage();
        }
    }
}
?>
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
    .admin-content {
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
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--primary);
        text-decoration: none;
        margin-bottom: 1rem;
    }
    .back-link:hover {
        text-decoration: underline;
    }
    .edit-form {
        background: var(--bg);
        padding: 2rem;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        max-width: 800px;
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
    .question-block {
        margin-bottom: 1rem;
        padding: 1rem;
        background: var(--bg-secondary);
        border-radius: var(--radius);
    }
    .radio-group {
        display: flex;
        gap: 1rem;
        margin-top: 0.5rem;
    }
    .radio-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }
    .radio-input {
        accent-color: var(--primary);
    }
    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
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
        transition: background 0.3s;
        text-decoration: none;
    }
    .cancel-btn:hover {
        background: var(--border);
    }
    .error-message {
        color: var(--danger);
        text-align: center;
        padding: 1rem;
    }
</style>
<div class="admin-content">
    <div class="page-header">
        <h1 class="page-title">Редактировать тест: <?= htmlspecialchars($test['test_name']) ?></h1>
        <a href="manage_tests_list.php?subject_id=<?= htmlspecialchars($subject_id) ?>" class="back-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
            </svg>
            Назад к списку тестов
        </a>
    </div>
    <?php if (isset($error)): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data" class="edit-form">
        <div class="form-group">
            <label for="test_name" class="form-label">Название теста</label>
            <input type="text" id="test_name" name="test_name" class="form-input" value="<?= htmlspecialchars($test['test_name']) ?>" required>
        </div>
        <div class="form-group">
            <label for="pdf_file" class="form-label">PDF-файл (оставьте пустым, чтобы не изменять)</label>
            <input type="file" id="pdf_file" name="pdf_file" class="form-input" accept="application/pdf">
        </div>
        <div class="form-group">
            <label class="form-label">Правильные ответы (1–4 для каждого из 30 вопросов)</label>
            <?php for ($i = 1; $i <= 30; $i++): ?>
                <div class="question-block">
                    <label class="form-label">Вопрос <?= $i ?>:</label>
                    <div class="radio-group">
                        <?php for ($j = 1; $j <= 4; $j++): ?>
                            <label class="radio-label">
                                <input type="radio" name="correct_answers[<?= $i ?>]" value="<?= $j ?>" class="radio-input" 
                                    <?= isset($correct_answers[$i]) && $correct_answers[$i] == $j ? 'checked' : '' ?> required>
                                <?= $j ?>
                            </label>
                        <?php endfor; ?>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
        <div class="form-actions">
            <button type="submit" class="submit-btn">Сохранить изменения</button>
            <a href="manage_tests_list.php?subject_id=<?= htmlspecialchars($subject_id) ?>" class="cancel-btn">Отмена</a>
        </div>
    </form>
</div>
