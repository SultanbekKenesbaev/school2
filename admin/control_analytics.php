<?php
require_once "../includes/db.php";
require_once "../includes/functions.php";
include("../includes/header-admin.php");


checkAdmin();

$class_id = $_GET['class_id'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

$classes = $pdo->query("SELECT * FROM classes ORDER BY name")->fetchAll();

$sql = "
    SELECT r.*, s.name AS subject_name, c.name AS class_name
    FROM control_test_results r
    JOIN control_subjects s ON r.subject_id = s.id
    JOIN classes c ON r.class_id = c.id
    WHERE 1
";
$params = [];

if (!empty($class_id)) {
    $sql .= " AND r.class_id = ?";
    $params[] = $class_id;
}

if (!empty($start_date)) {
    $sql .= " AND r.created_at >= ?";
    $params[] = $start_date . " 00:00:00";
}

if (!empty($end_date)) {
    $sql .= " AND r.created_at <= ?";
    $params[] = $end_date . " 23:59:59";
}

$sql .= " ORDER BY r.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll();

$analytics_sql = "
    SELECT c.name AS class_name, AVG(r.grade) AS avg_grade
    FROM control_test_results r
    JOIN classes c ON r.class_id = c.id
    WHERE 1
";
$analytics_params = [];

if (!empty($start_date)) {
    $analytics_sql .= " AND r.created_at >= ?";
    $analytics_params[] = $start_date . " 00:00:00";
}

if (!empty($end_date)) {
    $analytics_sql .= " AND r.created_at <= ?";
    $analytics_params[] = $end_date . " 23:59:59";
}

$analytics_sql .= " GROUP BY r.class_id";
$analytics_stmt = $pdo->prepare($analytics_sql);
$analytics_stmt->execute($analytics_params);
$analytics = $analytics_stmt->fetchAll();

include("../includes/sidebar-admin.php");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Аналитика контрольных работ</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        :root {
            --primary: #3498db;
            --primary-light: #5dade2;
            --danger: #e74c3c;
            --success: #2ecc71;
            --warning: #f39c12;
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
        .page-title {
            color: var(--primary);
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-light);
        }
        .filter-form {
            background: var(--bg);
            padding: 1.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .filter-label {
            font-weight: 500;
            color: var(--text);
        }
        .filter-select {
            padding: 0.5rem 1rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 1rem;
            min-width: 250px;
            transition: border 0.3s;
        }
        .filter-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        .results-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--bg);
            box-shadow: var(--shadow);
            border-radius: var(--radius);
            overflow: hidden;
        }
        .results-table th {
            background: var(--primary);
            color: var(--text-light);
            padding: 1rem;
            text-align: left;
            font-weight: 500;
        }
        .results-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border);
        }
        .results-table tr:last-child td {
            border-bottom: none;
        }
        .results-table tr:hover {
            background: rgba(52, 152, 219, 0.05);
        }
        .score-cell {
            font-weight: bold;
        }
        .score-high { color: var(--success); }
        .score-medium { color: var(--warning); }
        .score-low { color: var(--danger); }
        .student-name {
            font-weight: 500;
        }
        .subject-name {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .subject-icon {
            color: var(--primary);
        }
        .no-results {
            text-align: center;
            padding: 2rem;
            color: #7f8c8d;
        }
        .analytics-section {
            margin-top: 2rem;
        }
        .analytics-table {
            width: 100%;
            border-collapse: collapse;
            background: var(--bg);
            box-shadow: var(--shadow);
            border-radius: var(--radius);
            margin-bottom: 2rem;
        }
        .analytics-table th {
            background: var(--primary);
            color: var(--text-light);
            padding: 1rem;
            text-align: left;
            font-weight: 500;
        }
        .analytics-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border);
        }
    </style>
</head>
<body>
    <div class="teacher-content">
        <div class="page-header">
            <h1 class="page-title">Аналитика контрольных работ</h1>
        </div>
        <div class="analytics-section">
            <h2 class="page-title">Средние оценки по классам</h2>
            <table class="analytics-table">
                <thead>
                    <tr>
                        <th>Класс</th>
                        <th>Средняя оценка</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($analytics as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['class_name']) ?></td>
                            <td><?= round($row['avg_grade'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <form method="get" action="control_analytics_pdf.php" style="margin-bottom: 2rem;">
    <?php if ($class_id): ?>
        <input type="hidden" name="class_id" value="<?= htmlspecialchars($class_id) ?>">
    <?php endif; ?>
</form>

        <form method="get" class="filter-form">
    <label class="filter-label">Класс:</label>
    <select name="class_id" class="filter-select">
        <option value="">Все классы</option>
        <?php foreach ($classes as $cls): ?>
            <option value="<?= $cls['id'] ?>" <?= ($class_id == $cls['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cls['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label class="filter-label">С даты:</label>
    <input type="date" name="start_date" class="filter-select" value="<?= htmlspecialchars($_GET['start_date'] ?? '') ?>">

    <label class="filter-label">По дату:</label>
    <input type="date" name="end_date" class="filter-select" value="<?= htmlspecialchars($_GET['end_date'] ?? '') ?>">

    <button type="submit" class="submit-btn" style="margin-left: 1rem;">Фильтровать</button>
</form>

        <table class="results-table">
            <thead>
                <tr>
                    <th>Дата</th>
                    <th>Предмет</th>
                    <th>Ученик</th>
                    <th>Класс</th>
                    <th>Оценка</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($results)): ?>
                    <tr><td colspan="5" class="no-results">Нет результатов для отображения</td></tr>
                <?php else: ?>
                    <?php foreach ($results as $r): 
                        $score_class = $r['grade'] >= 4 ? 'score-high' : ($r['grade'] == 3 ? 'score-medium' : 'score-low');
                    ?>
                        <tr>
                            <td><?= date("d.m.Y H:i", strtotime($r['created_at'])) ?></td>
                            <td>
                                <span class="subject-name">
                                    <span class="material-icons subject-icon">school</span>
                                    <?= htmlspecialchars($r['subject_name']) ?>
                                </span>
                            </td>
                            <td class="student-name">
                                <?= htmlspecialchars($r['student_name']) ?> <?= htmlspecialchars($r['student_lastname']) ?>
                            </td>
                            <td><?= htmlspecialchars($r['class_name']) ?></td>
                            <td class="score-cell <?= $score_class ?>">
                                <?= $r['grade'] ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>