<div class="sidebar">
    <h2>Админ-панель</h2>
    <ul class="side-item">

        <li><a href="dashboard.php">Главная</a></li>
        <li>
            <a href="manage_news.php">Управление новостями</a>
        </li>
        <li>
            <a href="manage_achievements.php">Управление достижениями</a>
        </li>
        <li>
            <a href="manage_students.php">Управление лучшие студенты</a>
        </li>
        <li>
            <a href="manage_teachers.php">Управление учителя</a>
        </li>
        <li>
            <a href="manage_tests.php">Управление Тесты</a>
        </li>
        <li>
            <a href="view_results.php">Результат Тесты</a>
        </li>
        <li>
            <a href="control_teachers.php">Управление учителя 2</a>
        </li>
        <li>
            <a href="control_analytics.php">Результат контрольного работы </a>
        </li>
        <li>
            <a href="control_analytics_teachers.php">Аналитика учителя   </a>
        </li>
        <?php if (isset($_SESSION['admin'])): ?>
            <li><a href="../admin/logout.php">Выход</a></li>
        <?php endif; ?>
    </ul>
</div>

</body>