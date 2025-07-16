<?php
require_once "../includes/db.php";
require_once "../includes/functions.php";
include("../includes/sidebar-teacher.php");

checkTeacher();

$teacher_id = $_SESSION['teacher'];
$test_id = $_GET['id'] ?? null;

if (!$test_id) {
    die("ID теста не указан.");
}

$stmt = $pdo->prepare("SELECT * FROM control_tests WHERE id = ?");
$stmt->execute([$test_id]);
$test = $stmt->fetch();

if (!$test) {
    die("Тест не найден.");
}

$stmt = $pdo->prepare("SELECT subjects FROM teacher_user WHERE id = ?");
$stmt->execute([$teacher_id]);
$teacher = $stmt->fetch();
$subject_ids = explode(',', $teacher['subjects']);

$subjects = $pdo->prepare("SELECT * FROM control_subjects WHERE id IN (" . implode(',', array_fill(0, count($subject_ids), '?')) . ")");
$subjects->execute($subject_ids);
$subjects = $subjects->fetchAll();

$classes = $pdo->query("SELECT * FROM classes ORDER BY name")->fetchAll();

function transliterate($string) {
    $translit = [
        'а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'yo','ж'=>'zh','з'=>'z','и'=>'i','й'=>'y','к'=>'k',
        'л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'ts','ч'=>'ch','ш'=>'sh','щ'=>'sch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya',
        'А'=>'A','Б'=>'B','В'=>'V','Г'=>'G','Д'=>'D','Е'=>'E','Ё'=>'Yo','Ж'=>'Zh','З'=>'Z','И'=>'I','Й'=>'Y','К'=>'K',
        'Л'=>'L','М'=>'M','Н'=>'N','О'=>'O','П'=>'P','Р'=>'R','С'=>'S','Т'=>'T','У'=>'U','Ф'=>'F','Х'=>'H','Ц'=>'Ts','Ч'=>'Ch','Ш'=>'Sh','Щ'=>'Sch','Ы'=>'Y','Э'=>'E','Ю'=>'Yu','Я'=>'Ya',' '=>'_'
    ];
    return preg_replace('/[^A-Za-z0-9_\.]/', '', strtr($string, $translit));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject_id = $_POST['subject_id'];
    $class_id = $_POST['class_id'];
    $test_name = $_POST['test_name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $correct_answers = $_POST['correct_answers'] ?? [];

    if (count($correct_answers) != 15) {
        $error = "Нужно выбрать 15 правильных ответов.";
    } else {
        $pdf_file_path = $test['pdf_file'];
        if (!empty($_FILES['pdf_file']['name']) && $_FILES['pdf_file']['error'] === 0) {
            $upload_dir = "../public/uploads/";
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);
            $original = $_FILES['pdf_file']['name'];
            $ext = pathinfo($original, PATHINFO_EXTENSION);
            $safe_name = transliterate(pathinfo($original, PATHINFO_FILENAME)) . "." . $ext;
            $new_path = $upload_dir . $safe_name;
            if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $new_path)) {
                $pdf_file_path = $new_path;
            }
        }

        $stmt = $pdo->prepare("UPDATE control_tests SET subject_id = ?, class_id = ?, test_name = ?, pdf_file = ?, correct_answers = ?, start_date = ?, end_date = ? WHERE id = ?");
        $stmt->execute([
            $subject_id,
            $class_id,
            $test_name,
            $pdf_file_path,
            json_encode($correct_answers),
            $start_date,
            $end_date,
            $test_id
        ]);

        header("Location: control_tests_add.php?success=Тест обновлен");
        exit();
    }
}

$correct = json_decode($test['correct_answers'], true);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Редактировать тест</title>
    <link rel="stylesheet" href="../public/css/sidebar.css">
</head>
<body>
    <h1>Редактировать тест: <?= htmlspecialchars($test['test_name']) ?></h1>
    <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>
    <form method="post" enctype="multipart/form-data">
        <label>Предмет:</label>
        <select name="subject_id">
            <?php foreach ($subjects as $subj): ?>
                <option value="<?= $subj['id'] ?>" <?= $subj['id'] == $test['subject_id'] ? 'selected' : '' ?>><?= htmlspecialchars($subj['name']) ?></option>
            <?php endforeach; ?>
        </select><br>

        <label>Класс:</label>
        <select name="class_id">
            <?php foreach ($classes as $cls): ?>
                <option value="<?= $cls['id'] ?>" <?= $cls['id'] == $test['class_id'] ? 'selected' : '' ?>><?= htmlspecialchars($cls['name']) ?></option>
            <?php endforeach; ?>
        </select><br>

        <label>Название теста:</label>
        <input type="text" name="test_name" value="<?= htmlspecialchars($test['test_name']) ?>"><br>

        <label>PDF-файл (оставьте пустым, если не менять):</label>
        <input type="file" name="pdf_file" accept="application/pdf"><br>

        <label>Дата начала:</label>
        <input type="datetime-local" name="start_date" value="<?= date('Y-m-d\TH:i', strtotime($test['start_date'])) ?>"><br>

        <label>Дата окончания:</label>
        <input type="datetime-local" name="end_date" value="<?= date('Y-m-d\TH:i', strtotime($test['end_date'])) ?>"><br>

        <label>Правильные ответы:</label>
        <?php for ($i = 1; $i <= 15; $i++): ?>
            <p>Вопрос <?= $i ?>:</p>
            <?php for ($j = 1; $j <= 4; $j++): ?>
                <label>
                    <input type="radio" name="correct_answers[<?= $i ?>]" value="<?= $j ?>" <?= ($correct[$i] ?? '') == $j ? 'checked' : '' ?>> Вариант <?= $j ?>
                </label>
            <?php endfor; ?><br>
        <?php endfor; ?>

        <button type="submit">Сохранить изменения</button>
    </form>
    <br>
    <a href="control_tests_add.php">Назад</a>
</body>
</html>
