<?php
ob_start(); // Включаем буферизацию вывода

require_once "../includes/db.php";
require_once "../includes/functions.php";
require_once "../includes/tcpdf/tcpdf.php";

session_start();
checkTeacher();

$teacher_id = $_SESSION['teacher'];

// Получаем имя и фамилию учителя
$stmt = $pdo->prepare("SELECT first_name, last_name, subjects FROM teacher_user WHERE id = ?");
$stmt->execute([$teacher_id]);
$teacher = $stmt->fetch();
$teacher_name = $teacher['first_name'] . ' ' . $teacher['last_name'];
$subject_ids = explode(',', $teacher['subjects']);

// Получаем фильтры (если есть)
$class_id = $_GET['class_id'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

// Формируем SQL-запрос с фильтрами
$sql = "
    SELECT r.*, s.name AS subject_name, c.name AS class_name
    FROM control_test_results r
    JOIN control_subjects s ON r.subject_id = s.id
    JOIN classes c ON r.class_id = c.id
    WHERE r.subject_id IN (" . implode(',', array_fill(0, count($subject_ids), '?')) . ")
";
$params = $subject_ids;

if ($class_id) {
    $sql .= " AND r.class_id = ?";
    $params[] = $class_id;
}

if ($start_date && $end_date) {
    $sql .= " AND r.created_at BETWEEN ? AND ?";
    $params[] = $start_date;
    $params[] = $end_date;
}

$sql .= " ORDER BY r.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll();

// Получаем название класса (если выбран)
$class_name = '';
if ($class_id) {
    $stmt = $pdo->prepare("SELECT name FROM classes WHERE id = ?");
    $stmt->execute([$class_id]);
    $class_name = $stmt->fetchColumn();
}

// Создаём PDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('School System');
$pdf->SetTitle('Контрольная аналитика');
$pdf->SetMargins(15, 15, 15);
$pdf->AddPage();
$pdf->SetFont('dejavusans', '', 12);

// Заголовок
$html = '<h2 style="text-align:center;">Результаты контрольных работ</h2>';
if ($class_name) {
    $html .= "<p><strong>Класс:</strong> " . htmlspecialchars($class_name) . "</p>";
}
if ($start_date && $end_date) {
    $html .= "<p><strong>Период:</strong> " . htmlspecialchars($start_date) . " — " . htmlspecialchars($end_date) . "</p>";
}

// Таблица
$html .= '
<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr style="background-color:#f2f2f2;">
            <th><strong>Дата</strong></th>
            <th><strong>Предмет</strong></th>
            <th><strong>Ученик</strong></th>
            <th><strong>Класс</strong></th>
            <th><strong>Оценка</strong></th>
        </tr>
    </thead>
    <tbody>
';

if (empty($results)) {
    $html .= '<tr><td colspan="5">Нет данных для отображения</td></tr>';
} else {
    foreach ($results as $r) {
        $date = date("d.m.Y H:i", strtotime($r['created_at']));
        $subject = htmlspecialchars($r['subject_name']);
        $student = htmlspecialchars($r['student_name'] . ' ' . $r['student_lastname']);
        $class = htmlspecialchars($r['class_name']);
        $grade = htmlspecialchars($r['grade']);

        $html .= "<tr>
            <td>$date</td>
            <td>$subject</td>
            <td>$student</td>
            <td>$class</td>
            <td>$grade</td>
        </tr>";
    }
}

$html .= '</tbody></table>';

// Подпись
$html .= "<br><br><p><strong>Учитель:</strong> " . htmlspecialchars($teacher_name) . "</p>";
$html .= "<p><strong>Подпись: _____________________</strong></p>";

// Вывод PDF
$pdf->writeHTML($html, true, false, true, false, '');

ob_end_clean(); // Удаляем всё, что было выведено до TCPDF
$pdf->Output('control_analytics.pdf', 'I');
exit;
