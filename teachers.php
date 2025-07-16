<?php
require_once "includes/db.php";
require_once "includes/lang.php";

$stmt = $pdo->query("SELECT * FROM teachers ORDER BY created_at DESC");
$teachers = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= t('page_title_teachers') ?></title>
    <link rel="stylesheet" href="./public/css/styles.css">
    <link rel="stylesheet" href="./public/css/fotter.css">
    <link rel="stylesheet" href="./public/css/about.css">
    <link rel="stylesheet" href="./public/css/main.css">
    <link rel="stylesheet" href="./public/css/header.css">
    <link rel="stylesheet" href="./public/css/teachers.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php include("includes/header.php"); ?>
    <section class="dos-section">
        <div class="teachers">
            <h2 class="dos-title"><?= t('section_title_teachers') ?></h2>
            <div class="container">
                <div class="cards">
                    <?php foreach ($teachers as $teacher): ?>
                        <div class="teacher-card"
                            data-firstname="<?= htmlspecialchars($teacher['first_name']) ?>"
                            data-lastname="<?= htmlspecialchars($teacher['last_name']) ?>"
                            data-subjects="<?= htmlspecialchars($teacher['subjects']) ?>"
                            data-achievements="<?= htmlspecialchars($teacher['achievements']) ?>"
                            data-about="<?= htmlspecialchars($teacher['about']) ?>"
                            data-image="public/<?= htmlspecialchars($teacher['image']) ?>">

                            <?php if ($teacher['image']): ?>
                                <img src="public/<?= htmlspecialchars($teacher['image']) ?>" alt="<?= t('alt_teacher_photo') ?>" width="150">
                            <?php endif; ?>

                            <h3><?= htmlspecialchars($teacher['first_name']) ?> <?= htmlspecialchars($teacher['last_name']) ?></h3>
                            <p class="teacher-subjects" data-full="<?= htmlspecialchars($teacher['subjects']) ?>">
                                <strong><?= t('subjects_label') ?>:</strong> <?= htmlspecialchars($teacher['subjects']) ?>
                            </p>

                            <p class="teacher-achievements" data-full="<?= htmlspecialchars($teacher['achievements']) ?>">
                                <strong><?= t('achievements_label') ?>:</strong> <?= nl2br(htmlspecialchars($teacher['achievements'])) ?>
                            </p>

                            <p class="teacher-about" data-full="<?= htmlspecialchars($teacher['about']) ?>">
                                <?= nl2br(htmlspecialchars($teacher['about'])) ?>
                            </p>

                            <button class="more-btn"><?= t('button_details') ?></button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <div class="modal-overlay" id="modal">
        <div class="modal-content">
            <span class="close-btn"><?= t('modal_close') ?></span>
            <img id="modal-image" src="" alt="<?= t('alt_teacher_photo') ?>">
            <h3 id="modal-name"></h3>
            <p><strong><?= t('subjects_label') ?>:</strong> <span id="modal-subjects"></span></p>
            <p><strong><?= t('achievements_label') ?>:</strong></p>
            <p id="modal-achievements"></p>
            <p><strong><?= t('about_label') ?>:</strong></p>
            <p id="modal-about"></p>
        </div>
    </div>

    <?php include("includes/footer.php"); ?>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const MAX_LENGTH = 20;
            const translations = {
                subjects_label: "<?= t('subjects_label') ?>",
                achievements_label: "<?= t('achievements_label') ?>"
            };

            const truncateText = (text, maxLength) => {
                if (!text) return "";
                return text.length > maxLength ? text.slice(0, maxLength).trim() + '...' : text;
            };

            document.querySelectorAll('.teacher-subjects').forEach(el => {
                const full = el.dataset.full;
                const short = truncateText(full, MAX_LENGTH);
                el.innerHTML = `<strong>${translations.subjects_label}:</strong> ${short}`;
            });

            document.querySelectorAll('.teacher-achievements').forEach(el => {
                const full = el.dataset.full;
                const short = truncateText(full, MAX_LENGTH).replace(/\n/g, "<br>");
                el.innerHTML = `<strong>${translations.achievements_label}:</strong> ${short}`;
            });

            document.querySelectorAll('.teacher-about').forEach(el => {
                const full = el.dataset.full;
                const short = truncateText(full, MAX_LENGTH).replace(/\n/g, "<br>");
                el.innerHTML = short;
            });

            const modal = document.getElementById('modal');
            const closeBtn = document.querySelector('.close-btn');

            const modalImg = document.getElementById('modal-image');
            const modalName = document.getElementById('modal-name');
            const modalSubjects = document.getElementById('modal-subjects');
            const modalAchievements = document.getElementById('modal-achievements');
            const modalAbout = document.getElementById('modal-about');

            document.querySelectorAll('.more-btn').forEach(button => {
                button.addEventListener('click', (e) => {
                    const card = button.closest('.teacher-card');

                    modalImg.src = card.dataset.image;
                    modalName.textContent = `${card.dataset.firstname} ${card.dataset.lastname}`;
                    modalSubjects.textContent = card.dataset.subjects;
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