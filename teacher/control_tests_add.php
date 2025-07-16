<?php
require_once "../includes/db.php";
require_once "../includes/functions.php";
include("../includes/sidebar-teacher.php");

checkTeacher();

$teacher_id = $_SESSION['teacher'];
$stmt = $pdo->prepare("SELECT subjects FROM teacher_user WHERE id = ?");
$stmt->execute([$teacher_id]);
$teacher = $stmt->fetch();
$subject_ids = explode(',', $teacher['subjects']);

$subjects = $pdo->prepare("SELECT * FROM control_subjects WHERE id IN (" . implode(',', array_fill(0, count($subject_ids), '?')) . ")");
$subjects->execute($subject_ids);
$subjects = $subjects->fetchAll();

$classes = $pdo->query("SELECT * FROM classes ORDER BY name")->fetchAll();

function transliterate($string)
{
    $translit = [
        'а' => 'a',
        'б' => 'b',
        'в' => 'v',
        'г' => 'g',
        'д' => 'd',
        'е' => 'e',
        'ё' => 'yo',
        'ж' => 'zh',
        'з' => 'z',
        'и' => 'i',
        'й' => 'y',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'u',
        'ф' => 'f',
        'х' => 'h',
        'ц' => 'ts',
        'ч' => 'ch',
        'ш' => 'sh',
        'щ' => 'sch',
        'ы' => 'y',
        'э' => 'e',
        'ю' => 'yu',
        'я' => 'ya',
        ' ' => '_',
        'А' => 'A',
        'Б' => 'B',
        'В' => 'V',
        'Г' => 'G',
        'Д' => 'D',
        'Е' => 'E',
        'Ё' => 'Yo',
        'Ж' => 'Zh',
        'З' => 'Z',
        'И' => 'I',
        'Й' => 'Y',
        'К' => 'K',
        'Л' => 'L',
        'М' => 'M',
        'Н' => 'N',
        'О' => 'O',
        'П' => 'P',
        'Р' => 'R',
        'С' => 'S',
        'Т' => 'T',
        'У' => 'U',
        'Ф' => 'F',
        'Х' => 'H',
        'Ц' => 'Ts',
        'Ч' => 'Ch',
        'Ш' => 'Sh',
        'Щ' => 'Sch',
        'Ы' => 'Y',
        'Э' => 'E',
        'Ю' => 'Yu',
        'Я' => 'Ya'
    ];
    $string = strtr($string, $translit);
    return preg_replace('/[^A-Za-z0-9_\.]/', '', $string);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject_id = $_POST['subject_id'] ?? '';
    $class_id = $_POST['class_id'] ?? '';
    $test_name = $_POST['test_name'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $correct_answers = $_POST['correct_answers'] ?? [];

    if (empty($test_name) || empty($subject_id) || empty($class_id) || empty($start_date) || empty($end_date) || count($correct_answers) != 15 || !isset($_FILES['pdf_file']) || $_FILES['pdf_file']['error'] != 0) {
        $error = "Ошибка: все поля должны быть заполнены, PDF загружен и выбрано 15 правильных ответов.";
    } else {
        $upload_dir = "../public/uploads/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $original_filename = $_FILES['pdf_file']['name'];
        $extension = pathinfo($original_filename, PATHINFO_EXTENSION);
        $sanitized_filename = transliterate(pathinfo($original_filename, PATHINFO_FILENAME)) . '.' . $extension;
        $upload_file = $upload_dir . $sanitized_filename;
        if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $upload_file)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO control_tests (subject_id, class_id, test_name, pdf_file, correct_answers, start_date, end_date, teacher_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$subject_id, $class_id, $test_name, $upload_file, json_encode($correct_answers), $start_date, $end_date, $teacher_id]);
                header("Location: control_tests_add.php?success=Тест успешно добавлен");
                exit();
            } catch (PDOException $e) {
                $error = "Ошибка базы данных: " . $e->getMessage();
            }
        } else {
            $error = "Ошибка загрузки PDF-файла.";
        }
    }
}
// Удаление теста по ID
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $pdo->prepare("DELETE FROM control_tests WHERE id = ?")->execute([$delete_id]);
    header("Location: control_tests_add.php?success=Тест удалён");
    exit();
}

// Получение всех тестов текущего учителя
$tests = $pdo->prepare("
    SELECT t.*, s.name AS subject_name, c.name AS class_name 
    FROM control_tests t
    JOIN control_subjects s ON t.subject_id = s.id
    JOIN classes c ON t.class_id = c.id
    WHERE t.subject_id IN (" . implode(',', array_fill(0, count($subject_ids), '?')) . ")
    ORDER BY t.start_date DESC
");
$tests->execute($subject_ids);
$tests = $tests->fetchAll();


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Добавить контрольную работу</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../public/css/sidebar.css">
    <style>
        :root {
            --primary: #3498db;
            --primary-light: #5dade2;
            --danger: #e74c3c;
            --success: #2ecc71;
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

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--primary-light);
        }

        .page-title {
            color: var(--primary);
            font-size: 1.8rem;
            margin: 0;
        }

        .add-form {
            background: var(--bg);
            padding: 2rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            max-width: 800px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--text);
        }

        .form-input,
        .form-select {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 1rem;
            transition: border 0.3s;
        }

        .form-input:focus,
        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }

        .question-block {
            margin-bottom: 1rem;
        }

        .radio-group {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .radio-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .radio-input {
            accent-color: var(--primary);
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .submit-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: var(--radius);
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
        }

        .submit-btn:hover {
            background: var(--primary-light);
        }

        .cancel-btn {
            background: var(--bg-secondary);
            color: var(--text);
            border: 1px solid var(--border);
            padding: 0.8rem 1.5rem;
            border-radius: var(--radius);
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s;
        }

        .cancel-btn:hover {
            background: #e0e0e0;
        }

        .manage-classes-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.8rem 1rem;
            border-radius: var(--radius);
            font-size: 1rem;
            text-decoration: none;
            transition: background 0.3s;
        }

        .manage-classes-btn:hover {
            background: var(--primary-light);
        }

        .error-message,
        .success-message {
            text-align: center;
            padding: 1rem;
        }

        .error-message {
            color: var(--danger);
        }

        .success-message {
            color: var(--success);
        }
        
        .teacher-content{
            height: 100vh;
            overflow: auto;
        }
       .cont{
        display: flex;
       }
       .page-title2{
        padding: 0px 10px 40px 10px;
        color: var(--primary);
        font-size: 1.8rem;
       }
       .tns{
        display: grid;
        gap: 10px;
       }
    </style>
</head>

<body>
   <div class="cont"> 
   <div class="teacher-content">
        <div class="page-header">
            <h1 class="page-title">Добавить контрольную работу</h1>
            
        </div>
        <?php if (isset($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['success'])): ?>
            <div class="success-message"><?= htmlspecialchars($_GET['success']) ?></div>
        <?php endif; ?>
        <form method="post" class="add-form" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">Предмет:</label>
                <select name="subject_id" class="form-select" required>
                    <?php foreach ($subjects as $subj): ?>
                        <option value="<?= $subj['id'] ?>"><?= htmlspecialchars($subj['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Класс:</label>
                <select name="class_id" class="form-select" required>
                    <option value="">Выберите класс</option>
                    <?php foreach ($classes as $cls): ?>
                        <option value="<?= $cls['id'] ?>"><?= htmlspecialchars($cls['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Название теста:</label>
                <input type="text" name="test_name" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Дата начала:</label>
                <input type="datetime-local" name="start_date" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Дата окончания:</label>
                <input type="datetime-local" name="end_date" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">PDF-файл:</label>
                <input type="file" name="pdf_file" class="form-input" accept="application/pdf" required>
            </div>
            <div class="form-group">
                <label class="form-label">Правильные ответы:</label>
                <?php for ($i = 1; $i <= 15; $i++): ?>
                    <div class="question-block">
                        <p>Вопрос <?= $i ?>:</p>
                        <div class="radio-group">
                            <?php for ($j = 1; $j <= 4; $j++): ?>
                                <label class="radio-label">
                                    <input type="radio" name="correct_answers[<?= $i ?>]" value="<?= $j ?>" class="radio-input" required>
                                    Вариант <?= $j ?>
                                </label>
                            <?php endfor; ?>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
            <div class="form-actions">
                <button type="submit" class="submit-btn">Добавить тест</button>
                <a href="control_analytics.php" class="cancel-btn">Отмена</a>
            </div>
        </form>
    </div>
    <div style="margin-top: 3rem;">
        <h1 class="page-title2">Список добавленных тестов</h1>
        <table style="width:100%; border-collapse: collapse; background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden;">
            <thead>
                <tr style="background: var(--primary); color: white;">
                    <th style="padding: 1rem;">Название</th>
                    <th style="padding: 1rem;">Предмет</th>
                    <th style="padding: 1rem;">Класс</th>
                    <th style="padding: 1rem;">Начало</th>
                    <th style="padding: 1rem;">Окончание</th>
                    <th style="padding: 1rem;">Файл</th>
                    <th style="padding: 1rem;">Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tests)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 1rem;">Нет тестов</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($tests as $t): ?>
                        <tr>
                            <td style="padding: 1rem;"><?= htmlspecialchars($t['test_name']) ?></td>
                            <td style="padding: 1rem;"><?= htmlspecialchars($t['subject_name']) ?></td>
                            <td style="padding: 1rem;"><?= htmlspecialchars($t['class_name']) ?></td>
                            <td style="padding: 1rem;"><?= date("d.m.Y H:i", strtotime($t['start_date'])) ?></td>
                            <td style="padding: 1rem;"><?= date("d.m.Y H:i", strtotime($t['end_date'])) ?></td>
                            <td style="padding: 1rem;">
                                <a href="<?= htmlspecialchars(str_replace('../', '../', $t['pdf_file'])) ?>" target="_blank">📄 PDF</a>
                            </td>
                            <td style="padding: 1rem;">
                                <a href="control_tests_edit.php?id=<?= $t['id'] ?>" style="color: #3498db;">✏️</a>
                                <a href="control_tests_add.php?delete=<?= $t['id'] ?>" onclick="return confirm('Удалить этот тест?')" style="color: #e74c3c; margin-left: 10px;">🗑️</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
   </div>
</body>

</html>