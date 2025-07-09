<?php
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function checkAdmin() {
    session_start();
    if (!isset($_SESSION['admin'])) {
        header("Location: index.php");
        exit();
    }
}
