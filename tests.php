<?php
require_once "includes/db.php";

// Проверяем, существует ли lang.php
if (!file_exists("includes/lang.php")) {
    die("Ошибка: Файл includes/lang.php не найден.");
}
require_once "includes/lang.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Проверяем, загружены ли переводы
global $translations;
if (empty($translations)) {
    error_log("Ошибка: Переводы не загружены для языка {$_SESSION['lang']}. Проверьте includes/lang/{$_SESSION['lang']}.json");
}

try {
    $stmt = $pdo->query("SELECT * FROM subjects");
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Ошибка базы данных (subjects): " . $e->getMessage());
    die(t('error_query') . ": " . $e->getMessage());
}

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
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Ошибка базы данных (top_students): " . $e->getMessage());
    die(t('error_query') . ": " . $e->getMessage());
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
    <title><?= t('page_title_tests') ?></title>
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
    <?php
    if (!file_exists("includes/header.php")) {
        die("Ошибка: Файл includes/header.php не найден.");
    }
    include("includes/header.php");
    ?>

    <main class="container">
        <section class="subjects-section">
            <h1 class="section-title"><?= t('section_title_subjects') ?></h1>
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
                                <span class="tests-count"><?= t('tests_available') ?>: <?php
                                    try {
                                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tests WHERE subject_id = ?");
                                        $stmt->execute([$subj['id']]);
                                        echo $stmt->fetchColumn();
                                    } catch (PDOException $e) {
                                        error_log("Ошибка базы данных (tests count): " . $e->getMessage());
                                        echo "0";
                                    }
                                ?></span>
                                <span class="material-icons arrow-icon">arrow_forward</span>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="top-students-section">
            <h2 class="section-title"><?= t('section_title_top_students') ?></h2>
            <form method="get" class="filter-form">
                <label><?= t('filter_label_period') ?>:</label>
                <select name="period" class="filter-select" onchange="this.form.submit()">
                    <option value="all" <?= $period == 'all' ? 'selected' : '' ?>><?= t('filter_option_all') ?></option>
                    <option value="week" <?= $period == 'week' ? 'selected' : '' ?>><?= t('filter_option_week') ?></option>
                    <option value="month" <?= $period == 'month' ? 'selected' : '' ?>><?= t('filter_option_month') ?></option>
                    <option value="3months" <?= $period == '3months' ? 'selected' : '' ?>><?= t('filter_option_3months') ?></option>
                    <option value="6months" <?= $period == '6months' ? 'selected' : '' ?>><?= t('filter_option_6months') ?></option>
                    <option value="year" <?= $period == 'year' ? 'selected' : '' ?>><?= t('filter_option_year') ?></option>
                </select>
            </form>
            <div class="achievement-badge">
                <span class="material-icons">emoji_events</span>
                <span><?= t('best_results') ?></span>
            </div>
            <div class="table-container">
                <table class="top-students-table">
                    <thead>
                        <tr>
                            <th class="place-col"><?= t('table_header_place') ?></th>
                            <th class="student-col"><?= t('table_header_student') ?></th>
                            <th class="class-col"><?= t('table_header_class') ?></th>
                            <th class="teacher-col"><?= t('table_header_teacher') ?></th>
                            <th class="score-col"><?= t('table_header_score') ?></th>
                            <th class="badge-col"><?= t('table_header_badge') ?></th>
                            <th class="certificate-col"><?= t('table_header_certificate') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($top_students)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center;"><?= t('no_data') ?></td>
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
                                                <?= t('certificate') ?>
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

    <?php

    if (!file_exists("includes/footer.php")) {
        die("Ошибка: Файл includes/footer.php не найден.");
    }
    include("includes/footer.php");
    ?>
</body>
</html>