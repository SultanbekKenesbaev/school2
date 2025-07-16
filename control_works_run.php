<?php
session_start();
require_once "includes/db.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function log_error($message) {
    error_log("[control_works_run.php] " . $message . " at " . date('Y-m-d H:i:s') . "\n", 3, "error.log");
}

$subject_id = $_POST['subject_id'] ?? $_GET['subject_id'] ?? null;
$class_id = $_POST['class_id'] ?? null;
$test_id = $_GET['test_id'] ?? null;

$base_url = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['student_name'])) {
    if (empty($_POST['student_name']) || empty($_POST['student_lastname']) || empty($subject_id) || empty($class_id)) {
        log_error("Ошибка: все поля формы должны быть заполнены.");
        die("Ошибка: все поля формы должны быть заполнены.");
    }
    $_SESSION['control_test'] = [
        'subject_id' => $subject_id,
        'class_id' => $class_id,
        'student_name' => trim($_POST['student_name']),
        'student_lastname' => trim($_POST['student_lastname']),
        'answers' => []
    ];
    try {
        $stmt = $pdo->prepare("SELECT * FROM control_tests WHERE subject_id = ? AND class_id = ? AND end_date >= NOW() ORDER BY RAND() LIMIT 1");
        $stmt->execute([$subject_id, $class_id]);
        $test = $stmt->fetch();
        if (!$test) {
            log_error("Ошибка: тесты для subject_id=$subject_id и class_id=$class_id не найдены.");
            die("Ошибка: тесты для этого предмета и класса не найдены.");
        }
        $pdf_path = str_replace('../public/', $base_url . '/public/', $test['pdf_file']);
        $server_path = '.' . str_replace($base_url, '', $pdf_path);
        $file_url = 'http://' . $_SERVER['HTTP_HOST'] . $pdf_path;
        $headers = @get_headers($file_url);
        $file_accessible = $headers && strpos($headers[0], '200') !== false;
        $http_status = $headers ? $headers[0] : 'Не удалось получить статус';
        if (!file_exists($server_path)) {
            log_error("PDF-файл не найден по пути: $server_path");
            $_SESSION['control_test']['pdf_file'] = $pdf_path;
            $_SESSION['control_test']['pdf_error'] = "PDF-файл не найден на сервере: $server_path.";
            $_SESSION['control_test']['http_status'] = $http_status;
        } elseif (!$file_accessible) {
            log_error("PDF-файл недоступен по URL: $file_url (HTTP статус: $http_status)");
            $_SESSION['control_test']['pdf_file'] = $pdf_path;
            $_SESSION['control_test']['pdf_error'] = "PDF-файл недоступен по URL: $file_url.";
            $_SESSION['control_test']['http_status'] = $http_status;
        } else {
            $_SESSION['control_test']['pdf_file'] = $pdf_path;
            $_SESSION['control_test']['pdf_error'] = null;
            $_SESSION['control_test']['http_status'] = $http_status;
        }
        $_SESSION['control_test']['test_id'] = $test['id'];
        $_SESSION['control_test']['correct_answers'] = json_decode($test['correct_answers'], true);
        header("Location: control_works_run.php?subject_id=$subject_id&test_id={$test['id']}");
        exit();
    } catch (PDOException $e) {
        log_error("Ошибка базы данных: " . $e->getMessage());
        die("Ошибка базы данных: " . $e->getMessage());
    }
}

if (!$test_id || !isset($_SESSION['control_test'])) {
    log_error("Ошибка: тест не выбран или сессия не инициализирована. subject_id=$subject_id, test_id=$test_id");
    die("Ошибка: тест не выбран или сессия не инициализирована.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['answers'])) {
    $_SESSION['control_test']['answers'] = $_POST['answers'];
    header("Location: control_works_result.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Контрольная работа</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <style>
        :root {
            --primary: #3498db;
            --primary-dark: #2980b9;
            --accent: #2ecc71;
            --warning: #e74c3c;
            --text: #ecf0f1;
            --bg-glass: rgba(255, 255, 255, 0.1);
            --shadow-glass: 0 8px 32px rgba(31, 38, 135, 0.25);
            --border-glass: rgba(255, 255, 255, 0.25);
            --radius: 8px;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: var(--text);
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .test-container {
            display: flex;
            max-width: 1200px;
            margin: 2rem auto;
            gap: 2rem;
        }
        .pdf-container {
            flex: 1;
            background: var(--bg-glass);
            backdrop-filter: blur(12px);
            border-radius: var(--radius);
            box-shadow: var(--shadow-glass);
            border: 1px solid var(--border-glass);
            padding: 1rem;
            overflow-y: auto;
            max-height: 80vh;
        }
        .pdf-viewer {
            width: 100%;
            height: 100%;
            border: none;
        }
        .answers-container {
            flex: 1;
            background: var(--bg-glass);
            backdrop-filter: blur(12px);
            border-radius: var(--radius);
            box-shadow: var(--shadow-glass);
            border: 1px solid var(--border-glass);
            padding: 2rem;
            overflow-y: auto;
            max-height: 80vh;
        }
        .question-block {
            margin-bottom: 1rem;
        }
        .options-container {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .question-inline {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }
        .option-label {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.3rem 0.6rem;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 5px;
            cursor: pointer;
        }
        .option-radio {
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid var(--primary);
            border-radius: 50%;
            cursor: pointer;
            position: relative;
        }
        .option-radio:checked {
            background-color: var(--primary);
        }
        .option-radio:checked::after {
            content: '';
            position: absolute;
            width: 10px;
            height: 10px;
            background: white;
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .finish-button {
            background: var(--primary);
            color: white;
            border: none;
            padding: 1rem 2rem;
            font-size: 1rem;
            font-weight: 500;
            border-radius: var(--radius);
            cursor: pointer;
            transition: background 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 2rem auto 0;
        }
        .finish-button:hover {
            background: var(--primary-dark);
        }
        .error-message {
            color: var(--warning);
            text-align: center;
            padding: 1rem;
        }
    </style>
</head>
<body>
    <div class="test-container">
        <div class="pdf-container">
            <?php if (!isset($_SESSION['control_test']['pdf_error'])): ?>
                <iframe class="pdf-viewer" src="<?= htmlspecialchars($_SESSION['control_test']['pdf_file']) ?>"></iframe>
            <?php else: ?>
                <div class="error-message">PDF недоступен. Пожалуйста, обратитесь к учителю или администратору.</div>
            <?php endif; ?>
        </div>
        <div class="answers-container">
            <div id="timer" style="font-size: 1.2rem; font-weight: bold; text-align: center; margin-bottom: 1rem;">
                Осталось времени: <span id="time-remaining">30:00</span>
            </div>
            <form method="post" id="test-form">
                <?php for ($i = 1; $i <= 15; $i++): ?>
                    <div class="question-block">
                        <div class="question-inline">
                            <span class="question-number"><?= $i ?>:</span>
                            <?php for ($j = 1; $j <= 4; $j++): ?>
                                <label class="option-label no-text">
                                    <input type="radio" name="answers[<?= $i ?>]" value="<?= $j ?>" class="option-radio" required>
                                </label>
                            <?php endfor; ?>
                        </div>
                    </div>
                <?php endfor; ?>
                <button type="submit" class="finish-button">
                    <span class="material-icons">done</span>
                    Завершить тест
                </button>
            </form>
        </div>
    </div>
    <script>
        const duration = 30 * 60; // 30 минут в секундах
        let remaining = duration;
        const display = document.getElementById('time-remaining');
        const form = document.getElementById('test-form');

        function updateTimer() {
            const minutes = Math.floor(remaining / 60);
            const seconds = remaining % 60;
            display.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            if (remaining <= 0) {
                clearInterval(timerInterval);
                alert("Время вышло! Тест будет отправлен автоматически.");
                form.submit();
            } else {
                remaining--;
            }
        }

        const timerInterval = setInterval(updateTimer, 1000);
        updateTimer();
    </script>
</body>
</html>