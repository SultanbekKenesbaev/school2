<?php
require_once "../includes/db.php";
require_once "../includes/functions.php";
include("../includes/header-admin.php");
include("../includes/sidebar-admin.php");

checkAdmin(); // Проверка на администратора

// Получаем фильтры
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';
$subject_id = $_GET['subject_id'] ?? '';
$class_id = $_GET['class_id'] ?? '';

// Получаем список предметов и классов для фильтрации
$subjects = $pdo->query("SELECT id, name FROM control_subjects ORDER BY name")->fetchAll();
$classes = $pdo->query("SELECT id, name FROM classes ORDER BY name")->fetchAll();

// Получаем лучших учителей по активности и оценкам
$sql = "
    SELECT 
        t.id AS teacher_id,
        CONCAT(t.last_name, ' ', t.first_name) AS teacher_name,
        COUNT(DISTINCT ct.id) AS total_tests,
        COUNT(r.id) AS total_results,
        AVG(r.grade) AS avg_grade,
        COUNT(DISTINCT r.student_name) AS unique_students
    FROM teacher_user t
    LEFT JOIN control_tests ct ON ct.teacher_id = t.id
    LEFT JOIN control_test_results r ON r.test_id = ct.id
    WHERE 1 = 1
";

$params = [];

if (!empty($start_date)) {
    $sql .= " AND r.created_at >= ?";
    $params[] = $start_date . " 00:00:00";
}
if (!empty($end_date)) {
    $sql .= " AND r.created_at <= ?";
    $params[] = $end_date . " 23:59:59";
}
if (!empty($subject_id)) {
    $sql .= " AND r.subject_id = ?";
    $params[] = $subject_id;
}
if (!empty($class_id)) {
    $sql .= " AND r.class_id = ?";
    $params[] = $class_id;
}

$sql .= " GROUP BY t.id";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$teachers = $stmt->fetchAll();

// Обработка итогового рейтинга
foreach ($teachers as &$teacher) {
    $avg_grade = $teacher['avg_grade'] ?? 0;
    $activity_ratio = $teacher['total_results'] > 0 && $teacher['unique_students'] > 0 
        ? min($teacher['total_results'] / $teacher['unique_students'], 1) 
        : 0;

    $teacher['final_score'] = round(($avg_grade / 5) * 4 + $activity_ratio * 6, 2); // итог по шкале 0–10
}
unset($teacher);

// Сортировка по рейтингу
usort($teachers, function ($a, $b) {
    return $b['final_score'] <=> $a['final_score'];
});
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Лучшие учителя - Аналитика</title>
    <style>
        body { font-family: Arial; padding: 2rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 2rem; }
        th, td { border: 1px solid #ccc; padding: 0.75rem; text-align: center; }
        th { background: #3498db; color: white; }
        h2 { color: #2c3e50; }
        form { margin-bottom: 2rem; }
        select, input[type="date"] { padding: 0.4rem 0.6rem; margin-right: 1rem; }
        button { padding: 0.4rem 1rem; }
    </style>
</head>
<body>
<h2>🏆 Рейтинг учителей</h2>
<form method="get">
    <label>С: <input type="date" name="start_date" value="<?= htmlspecialchars($start_date) ?>"></label>
    <label>По: <input type="date" name="end_date" value="<?= htmlspecialchars($end_date) ?>"></label>

    <label>Предмет:
        <select name="subject_id">
            <option value="">Все</option>
            <?php foreach ($subjects as $s): ?>
                <option value="<?= $s['id'] ?>" <?= $subject_id == $s['id'] ? 'selected' : '' ?>><?= htmlspecialchars($s['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>Класс:
        <select name="class_id">
            <option value="">Все</option>
            <?php foreach ($classes as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $class_id == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </label>

    <button type="submit">Фильтровать</button>
</form>

<table>
    <thead>
        <tr>
            <th>№</th>
            <th>Учитель</th>
            <th>Тестов</th>
            <th>Прохождений</th>
            <th>Ср. оценка</th>
            <th>Активность</th>
            <th>Итог (0–10)</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($teachers)): ?>
            <tr><td colspan="7">Нет данных</td></tr>
        <?php else: ?>
            <?php foreach ($teachers as $i => $t): ?>
    <tr>
        <td><?= $i + 1 ?></td>
        <td><?= htmlspecialchars($t['teacher_name']) ?></td>
        <td><?= $t['total_tests'] ?></td>
        <td><?= $t['total_results'] ?></td>
        <td>
            <?= is_null($t['avg_grade']) ? '—' : round($t['avg_grade'], 2) ?>
        </td>
        <td>
            <?= $t['unique_students'] > 0 
                ? round(min($t['total_results'] / $t['unique_students'], 1), 2)
                : '—' ?>
        </td>
        <td><strong><?= $t['final_score'] ?? '0.00' ?></strong></td>
    </tr>
<?php endforeach; ?>

        <?php endif; ?>
    </tbody>
</table>
</body>
</html>
