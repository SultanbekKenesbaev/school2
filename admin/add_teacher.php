<?php
require_once "../includes/db.php";

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $subjects = $_POST['subjects'];
    $achievements = $_POST['achievements'];
    $about = $_POST['about'];

    $image = "";
    if (!empty($_FILES['image']['name'])) {
        $image = "uploads/" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "../public/" . $image);
    }

    $stmt = $pdo->prepare("INSERT INTO teachers (first_name, last_name, subjects, achievements, about, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$first_name, $last_name, $subjects, $achievements, $about, $image]);

    header("Location: manage_teachers.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .add-block {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .add-block div {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: left;
        }

        h2 {
            margin-bottom: 20px;
            color: #0070f3;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        textarea {
            resize: vertical;
            height: 80px;
        }

        button {
            background: rgb(16, 53, 187);
            background: linear-gradient(90deg,
                    rgba(0, 56, 255, 1) 0%,
                    rgb(0, 177, 212) 100%);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #218838;
        }

        a {
            display: block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
            text-align: center;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="add-block">
        <div>
            <h2>Добавить учителя</h2>
            <form method="post" enctype="multipart/form-data">
                Имя: <input type="text" name="first_name" required><br>
                Фамилия: <input type="text" name="last_name" required><br>
                Предметы: <input type="text" name="subjects" required><br>
                Достижения:<br> <textarea name="achievements" required></textarea><br>
                О себе:<br> <textarea name="about" required></textarea><br>
                Фото: <input type="file" name="image"><br>
                <button type="submit">Добавить</button>
            </form>
            <a href="manage_teachers.php">Назад</a>
        </div>
    </div>
</body>

</html>