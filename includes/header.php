<?php
require_once "includes/lang.php";
?>

<header class="pass">
    <div class="container">
        <div class="menu" id="nav-links">
            <div class="menu-items">
                <h1><?= t('school_name') ?></h1>
                <ul class="menu-list">
                    <li><a href="index.php"><?= t('menu_home') ?></a></li>
                    <li><a href="achievements.php"><?= t('menu_achievements') ?></a></li>
                    <li><a href="news.php"><?= t('menu_news') ?></a></li>
                    <li><a href="best_students.php"><?= t('menu_best_students') ?></a></li>
                    <li><a href="teachers.php"><?= t('menu_teachers') ?></a></li>
                    <li><a href="tests.php"><?= t('menu_tests') ?></a></li>
                    <li><a href="control_works.php"><?= t('menu_tests_2') ?></a></li>
                    <li><a href="about.php"><?= t('menu_about') ?></a></li>
                    <?php if (isset($_SESSION['admin'])): ?>
                        <li><a href="admin/dashboard.php"><?= t('menu_admin_panel') ?></a></li>
                        <li><a href="admin/logout.php"><?= t('menu_logout') ?></a></li>
                    <?php else: ?>
                    <?php endif; ?>
                    <li>
                        <div class="language-switcher">
                            <!-- Десктопный селект -->
                            <div class="lang-select">
                                <div class="selected-lang">
                                    <img src="<?= $languages[$current_lang]['icon'] ?>" alt="<?= $languages[$current_lang]['name'] ?>">
                                </div>
                                <div class="lang-options">
                                    <?php foreach ($languages as $code => $lang): ?>
                                        <a href="?lang=<?= $code ?>" class="lang-option <?= $current_lang == $code ? 'active' : '' ?>">
                                            <img src="<?= $lang['icon'] ?>" alt="<?= $lang['name'] ?>">
                                            <span><?= $lang['name'] ?></span>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Мобильная сетка языков -->
                            <div class="lang-options">
                                <?php foreach ($languages as $code => $lang): ?>
                                    <a href="?lang=<?= $code ?>" class="lang-option <?= $current_lang == $code ? 'active' : '' ?>">
                                        <img src="<?= $lang['icon'] ?>" alt="<?= $lang['name'] ?>">
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>


                    </li>
                </ul>

            </div>

            <button><a href="./admin/index.php"><?= t('login_button') ?></a></button>
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
</script>