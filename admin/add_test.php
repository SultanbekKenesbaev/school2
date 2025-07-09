<?php
require_once "../includes/db.php";
require_once "../includes/functions.php";
include("../includes/header-admin.php");
include("../includes/sidebar-admin.php");

session_start();
checkAdmin();

$subject_id = $_GET['subject_id'];
$stmt = $pdo->prepare("SELECT name FROM subjects WHERE id = ?");
$stmt->execute([$subject_id]);
$subject = $stmt->fetch();

// Функция для транслитерации имени файла
function transliterate($string) {
    $translit = [
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z',
        'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
        'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
        'ы' => 'y', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', ' ' => '_', 'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G',
        'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I', 'Й' => 'Y', 'К' => 'K', 'Л' => 'L',
        'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F',
        'Х' => 'H', 'Ц' => 'Ts', 'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch', 'Ы' => 'Y', 'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya'
    ];
    $string = strtr($string, $translit);
    return preg_replace('/[^A-Za-z0-9_\.]/', '', $string);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $test_name = $_POST['test_name'] ?? '';
    $correct_answers = $_POST['correct_answers'] ?? [];
    if (empty($test_name) || count($correct_answers) != 30 || !isset($_FILES['pdf_file']) || $_FILES['pdf_file']['error'] != 0) {
        die("Ошибка: все поля должны быть заполнены, PDF загружен и выбрано 30 правильных ответов.");
    }
    $upload_dir = "../public/uploads/";
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    $original_filename = $_FILES['pdf_file']['name'];
    $extension = pathinfo($original_filename, PATHINFO_EXTENSION);
    $sanitized_filename = transliterate(pathinfo($original_filename, PATHINFO_FILENAME)) . '.' . $extension;
    $upload_file = $upload_dir . $sanitized_filename;
    if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $upload_file)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO tests (subject_id, test_name, pdf_file, correct_answers) VALUES (?, ?, ?, ?)");
            $stmt->execute([$subject_id, $test_name, $upload_file, json_encode($correct_answers)]);
            header("Location: manage_tests_list.php?subject_id=$subject_id");
            exit();
        } catch (PDOException $e) {
            die("Ошибка базы данных: " . $e->getMessage());
        }
    } else {
        die("Ошибка загрузки PDF-файла.");
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
    .add-form {
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
        text-decoration: none;
        transition: background 0.3s;
    }
    .cancel-btn:hover {
        background: #e0e0e0;
    }
    @media (max-width: 768px) {
        .add-form {
            padding: 1.5rem;
        }
        .form-actions {
            flex-direction: column;
        }
    }
</style>
<div class="admin-content">
    <a href="manage_tests_list.php?subject_id=<?= $subject_id ?>" class="back-link">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
        </svg>
        Назад к списку тестов
    </a>
    <div class="page-header">
        <h1 class="page-title">Добавить тест к предмету: <?= htmlspecialchars($subject['name']) ?></h1>
    </div>
    <form method="post" class="add-form" enctype="multipart/form-data">
        <div class="form-group">
            <label class="form-label">Название теста:</label>
            <input type="text" name="test_name" class="form-input" required>
        </div>
        <div class="form-group">
            <label class="form-label">PDF-файл:</label>
            <input type="file" name="pdf_file" class="form-input" accept="application/pdf" required>
        </div>
        <div class="form-group">
            <label class="form-label">Правильные ответы:</label>
            <?php for ($i = 1; $i <= 30; $i++): ?>
                <div class="question-block">
                    <p>Вопрос <?= $i ?>:</p>
                    <div class="radio-group">
                        <?php for ($j = 1; $j <= 4; $j++): ?>
                            <label class="radio-label">
                                <input type="radio" name="correct_answers[<?= $i ?>]" value="<?= $j ?>" class="radio-input" required>
                                Вариант <?= $j ?>
                            </label>
                        <?php endfor; ?>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
        <div class="form-actions">
            <button type="submit" class="submit-btn">Добавить тест</button>
            <a href="manage_tests_list.php?subject_id=<?= $subject_id ?>" class="cancel-btn">Отмена</a>
        </div>
    </form>
</div>
<?php include("../includes/footer-admin.php"); ?>