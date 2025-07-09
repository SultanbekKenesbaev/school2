<?php
require_once "includes/db.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$stmt = $pdo->query("SELECT * FROM subjects");
$subjects = $stmt->fetchAll();

$period = $_GET['period'] ?? 'all';
$period_filters = [
    'week' => 'WHERE created_at >= NOW() - INTERVAL 1 WEEK',
    'month' => 'WHERE created_at >= NOW() - INTERVAL 1 MONTH',
    '3months' => 'WHERE created_at >= NOW() - INTERVAL 3 MONTH',
    '6months' => 'WHERE created_at >= NOW() - INTERVAL 6 MONTH',
    'year' => 'WHERE created_at >= NOW() - INTERVAL 1 YEAR'
];
$where_clause = $period_filters[$period] ?? '';

try {
    $top_students = $pdo->query("
        SELECT student_name, student_lastname, student_class, teacher_name, SUM(correct_answers) as total_score
        FROM test_results
        $where_clause
        GROUP BY student_name, student_lastname, student_class, teacher_name
        ORDER BY total_score DESC
        LIMIT 10
    ")->fetchAll();
} catch (PDOException $e) {
    die("Ошибка запроса: " . $e->getMessage());
}

$subject_icons = [
    'Математика' => 'π',
    'Физика' => '⚡',
    'Химия' => '🧪',
    'Биология' => '🌱',
    'История' => '🏛',
    'Литература' => '📚',
    'Русский язык' => '✍',
    'Английский язык' => 'translate',
    'География' => '🌎',
    'Информатика' => '💻'
];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Тесты</title>
    <link rel="stylesheet" href="./public/css/header.css">
    <link rel="stylesheet" href="./public/css/test.css">
    <link rel="stylesheet" href="./public/css/fotter.css">
    <link rel="stylesheet" href="./public/css/styles.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .certificate-cell > a {
            background-color: green;
            padding: 7px 12px;
            border-radius: 8px;
            color: white;
            display: flex;
            align-items: center;
        }
        .filter-form {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .filter-select {
            padding: 0.5rem;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <?php include("includes/header.php"); ?>

    <main class="container" id="translatable">
        <section class="subjects-section">
            <h1 class="section-title">Выберите предмет</h1>
            <div class="subjects-grid">
                <?php foreach ($subjects as $subj):
                    $icon = $subject_icons[$subj['name']] ?? 'school';
                ?>
                    <div class="subject-card">
                        <a href="tests_start.php?subject_id=<?= $subj['id'] ?>" class="subject-link">
                            <div class="subject-icon">
                                <span class="material-icons"><?= $icon ?></span>
                            </div>
                            <h3 class="subject-name"><?= htmlspecialchars($subj['name']) ?></h3>
                            <div class="subject-meta">
                                <span class="tests-count">Доступно тестов: <?php
                                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tests WHERE subject_id = ?");
                                    $stmt->execute([$subj['id']]);
                                    echo $stmt->fetchColumn();
                                ?></span>
                                <span class="material-icons arrow-icon">arrow_forward</span>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="top-students-section">
            <h2 class="section-title">ТОП 10 лучших учеников</h2>
            <form method="get" class="filter-form">
                <label>Период:</label>
                <select name="period" class="filter-select" onchange="this.form.submit()">
                    <option value="all" <?= $period == 'all' ? 'selected' : '' ?>>Все время</option>
                    <option value="week" <?= $period == 'week' ? 'selected' : '' ?>>Неделя</option>
                    <option value="month" <?= $period == 'month' ? 'selected' : '' ?>>Месяц</option>
                    <option value="3months" <?= $period == '3months' ? 'selected' : '' ?>>3 месяца</option>
                    <option value="6months" <?= $period == '6months' ? 'selected' : '' ?>>6 месяцев</option>
                    <option value="year" <?= $period == 'year' ? 'selected' : '' ?>>Год</option>
                </select>
            </form>
            <div class="achievement-badge">
                <span class="material-icons">emoji_events</span>
                <span>Лучшие результаты</span>
            </div>
            <div class="table-container">
                <table class="top-students-table">
                    <thead>
                        <tr>
                            <th class="place-col">Место</th>
                            <th class="student-col">Ученик</th>
                            <th class="class-col">Класс</th>
                            <th class="teacher-col">Учитель</th>
                            <th class="score-col">Суммарный балл</th>
                            <th class="badge-col">Награда</th>
                            <th class="certificate-col">Сертификат</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($top_students)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center;">Нет данных для отображения</td>
                            </tr>
                        <?php else: ?>
                            <?php $i = 1; ?>
                            <?php foreach ($top_students as $student): ?>
                                <tr class="<?= $i <= 3 ? 'top-three' : '' ?>">
                                    <td class="place-cell">
                                        <?php if ($i <= 3): ?>
                                            <span class="medal-icon material-icons">
                                                <?= $i == 1 ? 'military_tech' : ($i == 2 ? 'workspace_premium' : 'emoji_events') ?>
                                            </span>
                                        <?php endif; ?>
                                        <?= $i ?>
                                    </td>
                                    <td class="student-cell">
                                        <span class="student-avatar material-icons">person</span>
                                        <?= htmlspecialchars($student['student_name'] . ' ' . $student['student_lastname']) ?>
                                    </td>
                                    <td class="class-cell"><?= htmlspecialchars($student['student_class']) ?></td>
                                    <td class="teacher-cell"><?= htmlspecialchars($student['teacher_name']) ?></td>
                                    <td class="score-cell">
                                        <div class="score-progress">
                                            <div class="progress-bar" style="width: <?= min(100, $student['total_score']) ?>%"></div>
                                            <span class="score-value"><?= $student['total_score'] ?></span>
                                        </div>
                                    </td>
                                    <td class="badge-cell">
                                        <span class="material-icons"><?= $i <= 4 ? 'verified' : 'star' ?></span>
                                    </td>
                                    <td class="certificate-cell">
                                        <?php if ($i <= 3): ?>
                                            <a href="generate_certificate.php?rank=<?= $i ?>&name=<?= urlencode($student['student_name']) ?>&lastname=<?= urlencode($student['student_lastname']) ?>&class=<?= urlencode($student['student_class']) ?>" class="certificate-link">
                                                <span class="material-icons">file_download</span>
                                                Сертификат
                                            </a>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <?php include("includes/footer.php"); ?>

<script>
(async function () {
    const selectedLang = localStorage.getItem("selectedLang") || "rus_Cyrl";
    if (selectedLang === "rus_Cyrl") return;

    const elements = document.querySelectorAll(".lang");

    for (const el of elements) {
        const original = el.dataset.text || el.innerText.trim();
        if (original.length < 1) continue;

        try {
            const res = await fetch("/tilmoch-proxy.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    text: original,
                    source_lang: "rus_Cyrl",
                    target_lang: selectedLang
                })
            });

            const data = await res.json();
            if (data.translated_text) {
                el.innerText = data.translated_text;
            }
        } catch (err) {
            console.error("Ошибка перевода:", err);
        }
    }
})();
</script>




</body>
</html>
