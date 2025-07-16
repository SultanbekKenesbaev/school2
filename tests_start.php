<?php
require_once "includes/db.php";
require_once "includes/lang.php";

$subject_id = $_GET['subject_id'] ?? 1;
$stmt = $pdo->prepare("SELECT * FROM subjects WHERE id = ?");
$stmt->execute([$subject_id]);
$subject = $stmt->fetch();

// Map database subject names to translation keys
$subject_translations = [
    'Математика' => 'subject_math',
    'Физика' => 'subject_physics',
    'Химия' => 'subject_chemistry',
    'Биология' => 'subject_biology',
    'История' => 'subject_history',
    'Литература' => 'subject_literature',
    'Русский язык' => 'subject_russian',
    'Английский язык' => 'subject_english',
    'География' => 'subject_geography',
    'Информатика' => 'subject_informatics'
];

// Get the translation key for the subject name
$subject_key = $subject_translations[$subject['name']] ?? 'subject_default';
$subject_name = t($subject_key);

// Map translated subject names to icons
$subject_icons = [
    t('subject_math') => 'functions',
    t('subject_physics') => 'bolt',
    t('subject_chemistry') => 'science',
    t('subject_biology') => 'eco',
    t('subject_history') => 'history_edu',
    t('subject_literature') => 'menu_book',
    t('subject_russian') => 'translate',
    t('subject_english') => 'language',
    t('subject_geography') => 'public',
    t('subject_informatics') => 'computer'
];
$icon = $subject_icons[$subject_name] ?? 'school';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= t('page_title_test_start') ?> - <?= htmlspecialchars($subject_name) ?></title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        :root {
            --primary: #3498db;
            --primary-dark: #2980b9;
            --accent: #2ecc71;
            --text: #2c3e50;
            --text-light: #ecf0f1;
            --glass-bg: rgba(255, 255, 255, 0.15);
            --glass-blur: 10px;
            --glass-border: rgba(255, 255, 255, 0.25);
            --glass-shadow: 0 8px 32px rgba(31, 38, 135, 0.25);
            --radius: 8px;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #eaf0f5;
            color: var(--text);
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .test-start-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: var(--glass-bg);
            backdrop-filter: blur(var(--glass-blur));
            -webkit-backdrop-filter: blur(var(--glass-blur));
            border-radius: var(--radius);
            box-shadow: var(--glass-shadow);
            border: 1px solid var(--glass-border);
        }
        .subject-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--primary);
        }
        .subject-icon {
            background: var(--primary);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
        }
        .subject-title {
            font-size: 1.8rem;
            color: var(--text);
            margin: 0;
        }
        .test-form {
            display: grid;
            gap: 1.5rem;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .form-label {
            font-weight: 500;
            color: var(--text);
        }
        .form-input {
            padding: 0.8rem 1rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: var(--radius);
            font-size: 1rem;
            transition: border 0.3s ease, background 0.3s ease;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(var(--glass-blur));
            color: var(--text);
        }
        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        .start-button {
            background: var(--primary);
            color: white;
            border: none;
            padding: 1rem 1.5rem;
            font-size: 1rem;
            font-weight: 500;
            border-radius: var(--radius);
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        .start-button:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        .test-info {
            margin-top: 2rem;
            padding: 1rem;
            background: var(--glass-bg);
            backdrop-filter: blur(var(--glass-blur));
            border-radius: var(--radius);
            border-left: 4px solid var(--accent);
            box-shadow: var(--glass-shadow);
            border: 1px solid var(--glass-border);
        }
        .info-title {
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        @media (max-width: 768px) {
            .test-start-container {
                margin: 1rem;
                padding: 1.5rem;
            }
            .subject-title {
                font-size: 1.5rem;
            }
            .subject-icon {
                background: var(--primary);
                width: 50px;
                height: 50px;
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="test-start-container">
        <div class="subject-header">
            <div class="subject-icon">
                <span class="material-icons"><?= $icon ?></span>
            </div>
            <h1 class="subject-title"><?= t('test_subject') ?>: <?= htmlspecialchars($subject_name) ?></h1>
        </div>
        <form method="post" action="tests_run.php?subject_id=<?= $subject_id ?>" class="test-form">
            <div class="form-group">
                <label for="student_name" class="form-label"><?= t('label_student_name') ?>:</label>
                <input type="text" id="student_name" name="student_name" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="student_lastname" class="form-label"><?= t('label_student_lastname') ?>:</label>
                <input type="text" id="student_lastname" name="student_lastname" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="student_class" class="form-label"><?= t('label_student_class') ?>:</label>
                <input type="text" id="student_class" name="student_class" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="teacher_name" class="form-label"><?= t('label_teacher_name') ?>:</label>
                <input type="text" id="teacher_name" name="teacher_name" class="form-input" required>
            </div>
            <button type="submit" class="start-button">
                <span class="material-icons">play_arrow</span>
                <?= t('button_start_test') ?>
            </button>
        </form>
    </div>
</body>
</html>