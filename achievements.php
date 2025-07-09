<?php
require_once "includes/db.php";
require_once "includes/functions.php";

$stmt = $pdo->query("SELECT * FROM achievements ORDER BY created_at DESC");
$achievements = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Достижения</title>
    <link rel="stylesheet" href="./public/css/styles.css">
    <link rel="stylesheet" href="./public/css/fotter.css">
    <link rel="stylesheet" href="./public/css/about.css">
    <link rel="stylesheet" href="./public/css/main.css">
    <link rel="stylesheet" href="./public/css/header.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal.hidden {
            display: none;
        }

        .modal-content {
            background: var(--glass-base);
            backdrop-filter: blur(var(--glass-blur));
            -webkit-backdrop-filter: blur(var(--glass-blur));
            padding: 20px 25px;
            max-width: 600px;
            width: 90%;
            border-radius: 12px;
            position: relative;
            animation: fadeIn 0.3s ease;
            box-shadow: var(--glass-shadow);
            border: 1px solid var(--glass-border);
        }

        .modal-content img {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: var(--glass-shadow);
        }

        .modal-content h3 {
            margin: 0 0 10px;
            font-size: 22px;
            color: var(--text-dark);
        }

        .modal-content p {
            font-size: 16px;
            line-height: 1.5;
            color: var(--text-dark);
            white-space: pre-wrap;
        }

        .modal .close {
            position: absolute;
            top: 12px;
            right: 18px;
            font-size: 25px;
            font-weight: bold;
            color: var(--text-dark);
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .modal .close:hover {
            color: var(--glass-highlight);
        }

        @media (max-width: 768px) {
            .modal-content {
                width: 95%;
                padding: 15px 20px;
            }

            .modal-content h3 {
                font-size: 20px;
            }

            .modal-content p {
                font-size: 15px;
            }

            .modal-content img {
                max-height: 250px;
            }

            .modal .close {
                top: 10px;
                right: 15px;
                font-size: 22px;
            }
        }

        @media (max-width: 480px) {
            .modal-content {
                padding: 12px 15px;
                max-width: 100%;
            }

            .modal-content h3 {
                font-size: 18px;
            }

            .modal-content p {
                font-size: 14px;
            }

            .modal-content img {
                max-height: 200px;
            }

            .modal .close {
                font-size: 20px;
                top: -20px;
                right: 0;
            }
        }

        @keyframes fadeIn {
            from {
                transform: scale(0.95);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>

</head>

<body>
    <?php include("includes/header.php"); ?>
    <section class="dos-section">
        <div class="container">
            <h2 class="dos-title">Достижения</h2>
            <div class="box-dos">
                <?php foreach ($achievements as $item): ?>
                    <div class="dos-block">
                        <?php if (!empty($item['image'])): ?>
                            <img class="dos-img" src="public/<?= htmlspecialchars($item['image']) ?>" alt="Достижение" width="300">
                        <?php endif; ?>
                        <div class="text-box-item">
                            <h3><?= htmlspecialchars($item['title']) ?></h3>
                            <p><?= nl2br(htmlspecialchars($item['description'])) ?></p>
                        </div>
                        <button class="dos-btn">Подробнее</button>
                        <p><small>Дата публикации: <?= date("d.m.Y H:i", strtotime($item['created_at'])) ?></small></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div id="modal" class="modal hidden">
            <div class="modal-content">
                <span class="close">&times;</span>
                <img id="modal-image" src="" alt="Изображение достижения" style="width: 100%; max-width: 400px; margin-bottom: 15px;">
                <div class="text-box-item">
                    <h3 id="modal-title"></h3>
                    <p id="modal-description"></p>
                </div>
            </div>
        </div>


    </section>
    <?php include("includes/footer.php"); ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const maxTitleLength = 50;
            const maxDescLength = 120;

            const modal = document.getElementById("modal");
            const modalTitle = document.getElementById("modal-title");
            const modalDesc = document.getElementById("modal-description");
            const modalImage = document.getElementById("modal-image");
            const closeModal = document.querySelector(".modal .close");

            document.querySelectorAll(".dos-block").forEach(block => {
                const titleEl = block.querySelector(".text-box-item h3");
                const descEl = block.querySelector(".text-box-item p");
                const imgEl = block.querySelector(".dos-img");
                const button = block.querySelector(".dos-btn");

                const fullTitle = titleEl.textContent.trim();
                const fullDesc = descEl.innerHTML.trim();
                const imgSrc = imgEl ? imgEl.getAttribute("src") : "";

                if (fullTitle.length > maxTitleLength) {
                    titleEl.textContent = fullTitle.slice(0, maxTitleLength) + "...";
                }

                if (descEl.textContent.length > maxDescLength) {
                    descEl.textContent = fullDesc.slice(0, maxDescLength) + "...";
                }

                button.addEventListener("click", () => {
                    modalTitle.textContent = fullTitle;
                    modalDesc.innerHTML = fullDesc;
                    modalImage.setAttribute("src", imgSrc);
                    modal.classList.remove("hidden");
                });
            });

            closeModal.addEventListener("click", () => {
                modal.classList.add("hidden");
            });

            modal.addEventListener("click", (e) => {
                if (e.target === modal) {
                    modal.classList.add("hidden");
                }
            });
        });
    </script>


</body>

</html>