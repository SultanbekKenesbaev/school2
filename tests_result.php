<?php
session_start();
require_once "includes/db.php";

if (!isset($_SESSION['test'])) {
    die("Ошибка: сессия сброшена или тест не был завершён корректно.");
}

$test = $_SESSION['test'];
$answers = $test['answers'];
$correct_answers = $test['correct_answers'];
$correct = 0;
$details = [];

foreach ($answers as $question => $answer) {
    $is_correct = ($answer == $correct_answers[$question]);
    if ($is_correct) $correct++;
    $details[] = [
        'question' => $question,
        'correct' => $correct_answers[$question],
        'your_answer' => $answer,
        'is_correct' => $is_correct
    ];
}

$score_percentage = round(($correct / 30) * 100);

try {
    $stmt = $pdo->prepare("INSERT INTO test_results
        (subject_id, test_id, student_name, student_lastname, student_class, teacher_name, total_questions, correct_answers, answers)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $test['subject_id'],
        $test['test_id'],
        $test['student_name'],
        $test['student_lastname'],
        $test['student_class'],
        $test['teacher_name'],
        30,
        $correct,
        json_encode($answers, JSON_UNESCAPED_UNICODE)
    ]);
} catch (PDOException $e) {
    die("Ошибка при сохранении в базу данных: " . $e->getMessage());
}

unset($_SESSION['test']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Результат</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        :root {
            --primary: #3498db;
            --primary-light: #5dade2;
            --success: #2ecc71;
            --danger: #e74c3c;
            --warning: #f39c12;
            --text-dark: #1c1c1c;
            --text-light: #ecf0f1;
            --glass-base: rgba(255, 255, 255, 0.15);
            --glass-blur: 10px;
            --glass-border: rgba(255, 255, 255, 0.3);
            --glass-shadow: 0 8px 32px rgba(31, 38, 135, 0.25);
            --radius: 8px;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #eaf0f5;
            color: var(--text-dark);
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .result-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: var(--glass-base);
            backdrop-filter: blur(var(--glass-blur));
            border-radius: var(--radius);
            box-shadow: var(--glass-shadow);
            border: 1px solid var(--glass-border);
        }
        .result-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .result-title {
            color: var(--primary);
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .student-info {
            color: #7f8c8d;
            margin-bottom: 1.5rem;
        }
        .score-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        .score-circle {
            position: relative;
            width: 150px;
            height: 150px;
        }
        .score-svg {
            transform: rotate(-90deg);
            width: 100%;
            height: 100%;
        }
        .score-circle-bg {
            fill: none;
            stroke: rgba(255, 255, 255, 0.15);
            stroke-width: 8;
        }
        .score-circle-progress {
            fill: none;
            stroke: var(--primary);
            stroke-width: 8;
            stroke-dasharray: 440;
            stroke-dashoffset: 440;
            transition: stroke-dashoffset 1s ease-out;
        }
        .score-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary);
        }
        .score-details {
            text-align: center;
        }
        .correct-answers {
            color: var(--success);
            font-size: 1.5rem;
            font-weight: bold;
        }
        .total-questions {
            font-size: 1.2rem;
        }
        .mistakes-section {
            margin-top: 2rem;
        }
        .section-title {
            color: var(--primary);
            font-size: 1.5rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-light);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .mistakes-list {
            list-style: none;
            padding: 0;
        }
        .mistake-item {
            background: var(--glass-base);
            backdrop-filter: blur(var(--glass-blur));
            border-radius: var(--radius);
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--danger);
            box-shadow: var(--glass-shadow);
            border: 1px solid var(--glass-border);
        }
        .question-text {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .answers-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 1rem;
        }
        .answer-box {
            padding: 0.8rem;
            border-radius: var(--radius);
            background: var(--glass-base);
            backdrop-filter: blur(var(--glass-blur));
            border: 1px solid var(--glass-border);
            box-shadow: var(--glass-shadow);
        }
        .correct-answer {
            background-color: rgba(46, 204, 113, 0.15);
            border-left: 4px solid var(--success);
        }
        .your-answer {
            background-color: rgba(231, 76, 60, 0.15);
            border-left: 4px solid var(--danger);
        }
        .answer-label {
            font-weight: 500;
            margin-bottom: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        .home-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--primary);
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: var(--radius);
            text-decoration: none;
            font-weight: 500;
            margin-top: 2rem;
            transition: background 0.3s;
        }
        .home-button:hover {
            background: var(--primary-light);
        }
        @media (max-width: 768px) {
            .result-container {
                margin: 1rem;
                padding: 1.5rem;
            }
            .score-container {
                flex-direction: column;
            }
            .answers-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="result-container">
        <div class="result-header">
            <h1 class="result-title">Результаты теста</h1>
            <div class="student-info">
                <?= htmlspecialchars($test['student_name']) ?> <?= htmlspecialchars($test['student_lastname']) ?>,
                класс <?= htmlspecialchars($test['student_class']) ?>,
                учитель <?= htmlspecialchars($test['teacher_name']) ?>
            </div>
        </div>
        <div class="score-container">
            <div class="score-circle">
                <svg class="score-svg" viewBox="0 0 160 160">
                    <circle class="score-circle-bg" cx="80" cy="80" r="70"></circle>
                    <circle class="score-circle-progress" cx="80" cy="80" r="70"
                        style="stroke-dashoffset: <?= 440 - (440 * $score_percentage / 100) ?>"></circle>
                </svg>
                <div class="score-text"><?= $score_percentage ?>%</div>
            </div>
            <div class="score-details">
                <div class="correct-answers"><?= $correct ?> правильных</div>
                <div class="total_questions">из 30 вопросов</div>
            </div>
        </div>
        <div class="mistakes-section">
            <h2 class="section-title">
                <span class="material-icons">error_outline</span>
                Ошибки: <?= 30 - $correct ?>
            </h2>
            <ul class="mistakes-list">
                <?php foreach ($details as $d): ?>
                    <?php if (!$d['is_correct']): ?>
                        <li class="mistake-item">
                            <div class="question-text">Вопрос <?= $d['question'] ?></div>
                            <div class="answers-container">
                                <div class="answer-box correct-answer">
                                    <div class="answer-label">
                                        <span class="material-icons" style="color: var(--success);">check_circle</span>
                                        Правильный ответ
                                    </div>
                                    Вариант <?= $d['correct'] ?>
                                </div>
                                <div class="answer-box your-answer">
                                    <div class="answer-label">
                                        <span class="material-icons" style="color: var(--danger);">cancel</span>
                                        Ваш ответ
                                    </div>
                                    Вариант <?= $d['your_answer'] ?>
                                </div>
                            </div>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
            <a href="tests.php" class="home-button">
                <span class="material-icons">home</span>
                На главную
            </a>
        </div>
    </div>
</body>
</html>