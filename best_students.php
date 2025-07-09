<?php
require_once "includes/db.php";


$stmt = $pdo->query("SELECT * FROM students ORDER BY created_at DESC");
$students = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./public/css/styles.css">
    <link rel="stylesheet" href="./public/css/fotter.css">
    <link rel="stylesheet" href="./public/css/about.css">
    <link rel="stylesheet" href="./public/css/main.css">
    <link rel="stylesheet" href="./public/css/header.css">
    <link rel="stylesheet" href="./public/css/teachers.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php include("includes/header.php"); ?>
    <section class="dos-section">
        <div class="teacherst">
            <h2 class="dos-title">Лучшие ученики</h2>
            <div class="container">
                <div class="cards">
                    <?php foreach ($students as $student): ?>
                        <div class="teacher-card"
                            data-firstname="<?= htmlspecialchars($student['first_name']) ?>"
                            data-lastname="<?= htmlspecialchars($student['last_name']) ?>"
                            data-achievements="<?= htmlspecialchars($student['achievements']) ?>"
                            data-about="<?= htmlspecialchars($student['about']) ?>"
                            data-image="public/<?= htmlspecialchars($student['image']) ?>">

                            <?php if ($student['image']): ?>
                                <img src="public/<?= htmlspecialchars($student['image']) ?>" alt="Фото студента" width="150">
                            <?php endif; ?>

                            <h3><?= htmlspecialchars($student['first_name']) ?> <?= htmlspecialchars($student['last_name']) ?></h3>
                            <p class="student-achievements" data-full="<?= htmlspecialchars($student['achievements']) ?>">
                                <strong>Достижения:</strong> <?= nl2br(htmlspecialchars($student['achievements'])) ?>
                            </p>
                            <p class="student-about" data-full="<?= htmlspecialchars($student['about']) ?>">
                                <?= nl2br(htmlspecialchars($student['about'])) ?>
                            </p>

                            <button class="more-btn">Подробнее</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <div class="modal-overlay" id="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <img id="modal-image" src="" alt="">
            <h3 id="modal-name"></h3>
            <p><strong>Достижения:</strong></p>
            <p id="modal-achievements"></p>
            <p><strong>О себе:</strong></p>
            <p id="modal-about"></p>
        </div>
    </div>

    <?php include("includes/footer.php"); ?>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const MAX_LENGTH = 20;

            function truncateText(text, maxLength) {
                if (!text) return "";
                if (text.length > maxLength) {
                    return text.slice(0, maxLength).trim() + '...';
                }
                return text;
            }

            document.querySelectorAll('.student-achievements').forEach(el => {
                const full = el.dataset.full || '';
                const truncated = truncateText(full, MAX_LENGTH);
                el.innerHTML = "<strong>Достижения:</strong> " + truncated;
            });

            document.querySelectorAll('.student-about').forEach(el => {
                const full = el.dataset.full || '';
                const truncated = truncateText(full, MAX_LENGTH);
                el.textContent = truncated;
            });
        });

        document.addEventListener("DOMContentLoaded", () => {
            const modal = document.getElementById('modal');
            const closeBtn = document.querySelector('.close-btn');

            const modalImg = document.getElementById('modal-image');
            const modalName = document.getElementById('modal-name');
            const modalAchievements = document.getElementById('modal-achievements');
            const modalAbout = document.getElementById('modal-about');

            const buttons = document.querySelectorAll('.more-btn');

            buttons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.stopPropagation(); 
                    const card = button.closest('.teacher-card');

                    modalImg.src = card.dataset.image;
                    modalName.textContent = `${card.dataset.firstname} ${card.dataset.lastname}`;
                    modalAchievements.innerHTML = card.dataset.achievements.replace(/\n/g, '<br>');
                    modalAbout.innerHTML = card.dataset.about.replace(/\n/g, '<br>');

                    modal.style.display = "flex";
                    document.body.style.overflow = 'hidden';
                });
            });

            closeBtn.addEventListener('click', () => {
                modal.style.display = "none";
                document.body.style.overflow = 'auto';
            });

            window.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.style.display = "none";
                    document.body.style.overflow = 'auto';
                }
            });
        });
    </script>
</body>

</html>