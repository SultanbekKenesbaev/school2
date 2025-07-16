<?php

require_once "../includes/db.php";
require_once "../includes/functions.php";
include("../includes/header-admin.php");
include("../includes/sidebar-admin.php");




checkAdmin();

$subjects = $pdo->query("SELECT * FROM subjects")->fetchAll();
$subject_id = $_GET['subject_id'] ?? '';

$sql = "
    SELECT r.*, s.name AS subject_name 
    FROM test_results r
    JOIN subjects s ON r.subject_id = s.id
";

if ($subject_id) {
    $sql .= " WHERE r.subject_id = " . intval($subject_id);
}

$sql .= " ORDER BY r.correct_answers DESC";

$results = $pdo->query($sql)->fetchAll();
?>

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
    
    .admin-content {
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
    
    .score-high {
        color: var(--success);
    }
    
    .score-medium {
        color: var(--warning);
    }
    
    .score-low {
        color: var(--danger);
    }
    
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
    
    @media (max-width: 768px) {
        .filter-form {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .results-table {
            display: block;
            overflow-x: auto;
        }
    }
</style>

<div class="admin-content">
    <h1 class="page-title">Результаты тестов</h1>
    
    <form method="get" class="filter-form">
        <label class="filter-label">Предмет:</label>
        <select name="subject_id" class="filter-select" onchange="this.form.submit()">
            <option value=""> Все предметы </option>
            <?php foreach ($subjects as $subj): ?>
                <option value="<?= $subj['id'] ?>" 
                    <?= ($subject_id == $subj['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($subj['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
    
    <?php if (empty($results)): ?>
        <div class="no-results">
            <p>Нет результатов для отображения</p>
        </div>
    <?php else: ?>
        <table class="results-table">
            <thead>
                <tr>
                    <th>Дата</th>
                    <th>Предмет</th>
                    <th>Ученик</th>
                    <th>Класс</th>
                    <th>Результат</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $r): 
                    $score_percent = round(($r['correct_answers'] / $r['total_questions']) * 100);
                    $score_class = $score_percent >= 70 ? 'score-high' : 
                                 ($score_percent >= 40 ? 'score-medium' : 'score-low');
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
                        <td><?= htmlspecialchars($r['student_class']) ?></td>
                        <td class="score-cell <?= $score_class ?>">
                            <?= $r['correct_answers'] ?> из <?= $r['total_questions'] ?> 
                            (<?= $score_percent ?>%)
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

