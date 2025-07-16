<?php
require_once "includes/db.php";
require_once "includes/lang.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $stmt = $pdo->query("SELECT * FROM control_subjects");
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Ошибка базы данных (control_subjects): " . $e->getMessage());
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
    SELECT student_name, student_lastname, class_id, MAX(grade) AS grade, c.name AS class_name
    FROM control_test_results r
    JOIN classes c ON r.class_id = c.id
    $where_clause
    GROUP BY student_name, student_lastname, class_id, c.name
    ORDER BY MAX(grade) DESC
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Ошибка базы данных (top_students_control): " . $e->getMessage());
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
    <title><?= t('page_title_control_works') ?></title>
    <link rel="stylesheet" href="./public/css/header.css">
    <link rel="stylesheet" href="./public/css/test.css">
    <link rel="stylesheet" href="./public/css/fotter.css">
    <link rel="stylesheet" href="./public/css/styles.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .certificate-cell > a { background-color: green; padding: 7px 12px; border-radius: 8px; color: white; display: flex; align-items: center; }
        .filter-form { display: flex; gap: 1rem; margin-bottom: 1rem; }
        .filter-select { padding: 0.5rem; border-radius: 4px; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; }
        .modal-content { background: white; margin: 10% auto; padding: 20px; border-radius: 8px; max-width: 500px; width: 90%; }
        .modal-buttons { display: flex; flex-wrap: wrap; gap: 10px; }
        .modal-button { padding: 10px 20px; background: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .modal-button:hover { background: #2980b9; }
        .close { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
        .close:hover { color: #000; }
    </style>
</head>
<body>
    <?php include("includes/header.php"); ?>
    <main class="container">
        <section class="subjects-section">
            <h1 class="section-title"><?= t('section_title_control_works') ?></h1>
            <div class="subjects-grid">
                <?php foreach ($subjects as $subj):
                    $icon = $subject_icons[$subj['name']] ?? 'school';
                ?>
                    <div class="subject-card">
                        <a href="#" class="subject-link" data-subject-id="<?= $subj['id'] ?>">
                            <div class="subject-icon">
                                <span class="material-icons"><?= $icon ?></span>
                            </div>
                            <h3 class="subject-name"><?= htmlspecialchars($subj['name']) ?></h3>
                            <div class="subject-meta">
                                <span class="tests-count"><?= t('tests_available') ?>: <?php
                                    try {
                                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM control_tests WHERE subject_id = ? AND end_date >= NOW()");
                                        $stmt->execute([$subj['id']]);
                                        echo $stmt->fetchColumn();
                                    } catch (PDOException $e) {
                                        error_log("Ошибка базы данных (control_tests count): " . $e->getMessage());
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
                            <th class="score-col"><?= t('table_header_grade') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($top_students)): ?>
                            <tr><td colspan="4" style="text-align: center;"><?= t('no_data') ?></td></tr>
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
                                    <td class="class-cell"><?= htmlspecialchars($student['class_name']) ?></td>
                                    <td class="score-cell"><?= $student['grade'] ?></td>
                                </tr>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    <div id="classModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Выберите класс</h2>
            <div class="modal-buttons" id="classButtons"></div>
        </div>
    </div>
    <div id="nameModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Введите данные</h2>
            <form method="post" id="nameForm" action="control_works_run.php">
                <div class="form-group">
                    <label for="student_name" class="form-label">Имя:</label>
                    <input type="text" id="student_name" name="student_name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="student_lastname" class="form-label">Фамилия:</label>
                    <input type="text" id="student_lastname" name="student_lastname" class="form-input" required>
                    <input type="hidden" id="subject_id" name="subject_id">
                    <input type="hidden" id="class_id" name="class_id">
                </div>
                <button type="submit" class="modal-button">Начать тест</button>
            </form>
        </div>
    </div>
    <?php include("includes/footer.php"); ?>
    <script>
        const classModal = document.getElementById('classModal');
        const nameModal = document.getElementById('nameModal');
        const closeButtons = document.getElementsByClassName('close');
        const subjectLinks = document.getElementsByClassName('subject-link');
        const classButtonsContainer = document.getElementById('classButtons');
        const nameForm = document.getElementById('nameForm');

        for (let link of subjectLinks) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const subjectId = this.getAttribute('data-subject-id');
                document.getElementById('subject_id').value = subjectId;
                fetchClasses(subjectId);
                classModal.style.display = 'block';
            });
        }

        for (let close of closeButtons) {
            close.addEventListener('click', function() {
                classModal.style.display = 'none';
                nameModal.style.display = 'none';
            });
        }

        function fetchClasses(subjectId) {
            fetch(`get_classes.php?subject_id=${subjectId}`)
                .then(response => response.json())
                .then(data => {
                    classButtonsContainer.innerHTML = '';
                    data.forEach(cls => {
                        const button = document.createElement('button');
                        button.className = 'modal-button';
                        button.textContent = cls.name;
                        button.onclick = () => {
                            document.getElementById('class_id').value = cls.id;
                            classModal.style.display = 'none';
                            nameModal.style.display = 'block';
                        };
                        classButtonsContainer.appendChild(button);
                    });
                });
        }

        window.onclick = function(event) {
            if (event.target == classModal) classModal.style.display = 'none';
            if (event.target == nameModal) nameModal.style.display = 'none';
        };
    </script>
</body>
</html>