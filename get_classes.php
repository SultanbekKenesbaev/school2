<?php
require_once "includes/db.php";

$subject_id = $_GET['subject_id'] ?? null;
if (!$subject_id) {
    echo json_encode([]);
    exit();
}

try {
    $stmt = $pdo->prepare("
        SELECT DISTINCT c.id, c.name
        FROM classes c
        JOIN control_tests ct ON c.id = ct.class_id
        WHERE ct.subject_id = ? AND ct.end_date >= NOW()
    ");
    $stmt->execute([$subject_id]);
    $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($classes);
} catch (PDOException $e) {
    error_log("Ошибка базы данных (get_classes): " . $e->getMessage());
    echo json_encode([]);
}
?>