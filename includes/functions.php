<?php

// Очистка входных данных для предотвращения XSS
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Проверка, является ли пользователь администратором
function checkAdmin() {
    session_start();
    if (!isset($_SESSION['admin']) || $_SESSION['role'] != 'admin') {
        header("Location: index.php");
        exit();
    }
}

// Проверка, является ли пользователь учителем
function checkTeacher() {
    session_start();
    if (!isset($_SESSION['teacher']) || $_SESSION['role'] != 'teacher') {
        header("Location: ../admin/manage_classes.php");
        exit();
    }
}

?>