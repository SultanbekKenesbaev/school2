<?php session_start(); ?>
<header class="pass">
    <div class="container">
        <div class="menu" id="nav-links">
            <div class="menu-items">
                <h1 class="lang" data-text="Школа №5">Школа №5</h1>
                <ul class="menu-list">
                    <li><a class="lang" data-text="Главная" href="index.php">Главная</a></li>
                    <li><a class="lang" data-text="Достижения" href="achievements.php">Достижения</a></li>
                    <li><a class="lang" data-text="Новости" href="news.php">Новости</a></li>
                    <li><a class="lang" data-text="Лучшие ученики" href="best_students.php">Лучшие ученики</a></li>
                    <li><a class="lang" data-text="Учителя" href="teachers.php">Учителя</a></li>
                    <li><a class="lang" data-text="Тесты" href="tests.php">Тесты</a></li>
                    <li><a class="lang" data-text="О школе" href="about.php">О школе</a></li>
                    <?php if (isset($_SESSION['admin'])): ?>
                        <li><a href="admin/dashboard.php">Админ-панель</a></li>
                        <li><a href="admin/logout.php">Выход</a></li>
                    <?php endif; ?>
                </ul>
                <div style="margin-left: auto; padding-left: 20px;">
                    <label for="languageSelect">🌐 Язык:</label>
                    <select id="languageSelect">
                        <option value="rus_Cyrl">🇷 Русский</option>
                        <option value="uzn_Cyrl">🇺 Узбекский</option>
                        <option value="kaa_Cyrl">🇰 Каракалпакский</option>
                    </select>
                </div>
            </div>
            <button><a href="./admin/index.php">Войти</a></button>
        </div>
        <div class="menu-toggle primary" onclick="toggleMenu()">
            <div class="line1"></div>
            <div class="line2"></div>
            <div class="line3"></div>
        </div>
    </div>
</header>

<script>
    function toggleMenu() {
        document.getElementById("nav-links").classList.toggle("nav-active");
        document.querySelector(".menu-toggle").classList.toggle("toggle");
        document.querySelector(".pass").classList.toggle("active");
    }

    const savedLang = localStorage.getItem("selectedLang") || "rus_Cyrl";
    document.getElementById("languageSelect").value = savedLang;

    document.getElementById("languageSelect").addEventListener("change", function () {
        localStorage.setItem("selectedLang", this.value);
        location.reload();
    });
</script>
