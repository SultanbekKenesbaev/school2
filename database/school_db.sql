-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:8889
-- Время создания: Июл 13 2025 г., 14:23
-- Версия сервера: 8.0.40
-- Версия PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `school_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `achievements`
--

CREATE TABLE `achievements` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `achievements`
--

INSERT INTO `achievements` (`id`, `title`, `description`, `image`, `created_at`) VALUES
(2, 'Учебное достижение', 'В 2024 году ученица 8-А класса Алина Ахметова заняла 1-е место в районной олимпиаде по английскому языку, обойдя 23 участника из других школ.', 'uploads/6465c2b9b4aaf_1.jpg', '2025-07-02 12:49:31'),
(3, 'Спортивное достижение', 'Команда школы №5 стала победителем городского турнира по мини-футболу среди школьников, выиграв финальный матч со счётом 3:1.', 'uploads/720___iqPsbq2OF6mgX3E7uwl1emn5OBTXBUA.jpg', '2025-07-02 12:50:28'),
(4, 'IT-достижение', 'Команда школьного IT-кружка стала победителем районного конкурса проектов по программированию, представив мобильное приложение для учёта успеваемости.', 'uploads/6e84a8bd-ccf5-48d3-9b38-449af37e9ddc.jpeg', '2025-07-02 12:51:44');

-- --------------------------------------------------------

--
-- Структура таблицы `admins`
--

CREATE TABLE `admins` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(2, 'admin.5.mektep', '$2y$10$Cq.SpaUzpCJ04vRfndzitOVG0Qz1SSKiYkrysNPD1fmlKEKVfbDGe');

-- --------------------------------------------------------

--
-- Структура таблицы `classes`
--

CREATE TABLE `classes` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `classes`
--

INSERT INTO `classes` (`id`, `name`) VALUES
(1, '11д'),
(4, '10а');

-- --------------------------------------------------------

--
-- Структура таблицы `control_subjects`
--

CREATE TABLE `control_subjects` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `control_subjects`
--

INSERT INTO `control_subjects` (`id`, `name`) VALUES
(1, 'математика'),
(2, 'биялогия');

-- --------------------------------------------------------

--
-- Структура таблицы `control_tests`
--

CREATE TABLE `control_tests` (
  `id` int NOT NULL,
  `subject_id` int DEFAULT NULL,
  `class_id` int DEFAULT NULL,
  `test_name` varchar(255) NOT NULL,
  `pdf_file` varchar(255) NOT NULL,
  `correct_answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `teacher_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `control_tests`
--

INSERT INTO `control_tests` (`id`, `subject_id`, `class_id`, `test_name`, `pdf_file`, `correct_answers`, `start_date`, `end_date`, `teacher_id`) VALUES
(4, 1, 1, 'блок тест', '../public/uploads/tilmochv2.pdf', '{\"1\":\"1\",\"2\":\"1\",\"3\":\"1\",\"4\":\"1\",\"5\":\"1\",\"6\":\"1\",\"7\":\"1\",\"8\":\"1\",\"9\":\"1\",\"10\":\"1\",\"11\":\"1\",\"12\":\"1\",\"13\":\"1\",\"14\":\"1\",\"15\":\"1\"}', '2025-07-13 17:18:00', '2025-07-13 23:24:00', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `control_test_results`
--

CREATE TABLE `control_test_results` (
  `id` int NOT NULL,
  `subject_id` int DEFAULT NULL,
  `test_id` int DEFAULT NULL,
  `class_id` int DEFAULT NULL,
  `student_name` varchar(255) DEFAULT NULL,
  `student_lastname` varchar(255) DEFAULT NULL,
  `grade` int DEFAULT NULL,
  `correct_answers_count` int DEFAULT NULL,
  `answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `control_test_results`
--

INSERT INTO `control_test_results` (`id`, `subject_id`, `test_id`, `class_id`, `student_name`, `student_lastname`, `grade`, `correct_answers_count`, `answers`, `created_at`) VALUES
(1, 1, NULL, 1, 'Sultanbek', 'Kenesbaev', 5, 15, '{\"1\":\"1\",\"2\":\"1\",\"3\":\"1\",\"4\":\"1\",\"5\":\"1\",\"6\":\"1\",\"7\":\"1\",\"8\":\"1\",\"9\":\"1\",\"10\":\"1\",\"11\":\"1\",\"12\":\"1\",\"13\":\"1\",\"14\":\"1\",\"15\":\"1\"}', '2025-07-13 12:42:52'),
(2, 1, NULL, 4, 'Sultanbek', 'Kenesbaev', 4, 12, '{\"1\":\"1\",\"2\":\"2\",\"3\":\"1\",\"4\":\"2\",\"5\":\"1\",\"6\":\"2\",\"7\":\"1\",\"8\":\"1\",\"9\":\"1\",\"10\":\"1\",\"11\":\"1\",\"12\":\"1\",\"13\":\"1\",\"14\":\"1\",\"15\":\"1\"}', '2025-07-13 16:43:29'),
(3, 1, NULL, 4, 'Sultanbek', 'Kenesbaev', 2, 0, '{\"1\":\"2\",\"2\":\"2\",\"3\":\"2\",\"4\":\"2\",\"5\":\"2\",\"6\":\"2\",\"7\":\"2\",\"8\":\"2\",\"9\":\"2\",\"10\":\"2\",\"11\":\"2\",\"12\":\"2\",\"13\":\"2\",\"14\":\"2\",\"15\":\"2\"}', '2025-07-13 16:48:02');

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE `news` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `image`, `created_at`) VALUES
(3, '«Вы — золотое поколение, строители Нового Узбекистана». Президент поздравил с Днём молодёжи', 'Президент Узбекистана поздравил молодых людей с Днём молодёжи и назвал их «золотым поколением», которое создаст «новую историю как строители Нового Узбекистана».', 'uploads/image_2025-07-02_17-24-09.png', '2025-07-02 12:24:39'),
(4, 'В Узбекистане ученики 7–9 классов начнут учиться в университетах', 'Ташкент, Узбекистан – АН Podrobno.uz. С 1 сентября в Узбекистане стартует новый эксперимент: школьники 7–9 классов будут проходить часть занятий прямо в вузах. Цель — помочь детям познакомиться с разными профессиями, выбрать будущую специальность и получить первые практические навыки. \r\n\r\n', 'uploads/image_2025-07-02_17-26-26.png', '2025-07-02 12:26:37'),
(5, 'Ученики школ начнут обучаться в университетах: новый эксперимент в Ташкенте', 'Vaib.uz (Узбекистан. 2 июля). С 1 сентября 2025 года в Узбекистане стартует новый экспериментальный проект по подготовке кадров по схеме «школа — техникум (лицей) — университет». Цель этой инициативы — создать непрерывную образовательную цепочку, которая поможет школьникам уже с раннего возраста определиться с будущей профессией и осознанно строить свой образовательный маршрут.\r\n\r\nПервыми участниками проекта станут учащиеся школ №250 и №273 Юнусабадского района Ташкента. Эти учебные заведения будут прикреплены к Ташкентскому архитектурно-строительному университету.', 'uploads/image_2025-07-02_17-27-48.png', '2025-07-02 12:28:02');

-- --------------------------------------------------------

--
-- Структура таблицы `students`
--

CREATE TABLE `students` (
  `id` int NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `achievements` text,
  `about` text,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `students`
--

INSERT INTO `students` (`id`, `first_name`, `last_name`, `achievements`, `about`, `image`, `created_at`) VALUES
(4, 'Жуманазар  Жумагалий улы', 'Бахтыбаев', 'Самый лучший ученик', '8-А класс ученика', 'uploads/Рисунок2.png', '2025-07-02 05:34:56'),
(5, 'Айгерим ', 'Абдрасулова', 'Заняла 1-е место на районной олимпиаде по английскому языку среди учащихся 8-х классов.', 'Отличница, активно участвует в школьных мероприятиях, любит изучать иностранные языки и мечтает стать переводчиком.', 'uploads/Рисунок1.png', '2025-07-02 06:55:01');

-- --------------------------------------------------------

--
-- Структура таблицы `subjects`
--

CREATE TABLE `subjects` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `subjects`
--

INSERT INTO `subjects` (`id`, `name`) VALUES
(1, 'математика'),
(2, 'химия');

-- --------------------------------------------------------

--
-- Структура таблицы `teachers`
--

CREATE TABLE `teachers` (
  `id` int NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `subjects` varchar(255) DEFAULT NULL,
  `achievements` text,
  `about` text,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `teachers`
--

INSERT INTO `teachers` (`id`, `first_name`, `last_name`, `subjects`, `achievements`, `about`, `image`, `created_at`) VALUES
(2, 'Зилиха Жанабаевна', 'Баймуратова ', 'Математика', 'Учитель года', 'Классный руководитель 10-А класса', 'uploads/Рисунок3.png', '2025-07-02 05:36:56'),
(3, 'Умитхан Назарбай кызы', 'Юсупова ', ' Биология', 'Лидер профессионального мастерства', 'Люблю учить детей', 'uploads/Рисунок4.png', '2025-07-02 05:37:51');

-- --------------------------------------------------------

--
-- Структура таблицы `teacher_user`
--

CREATE TABLE `teacher_user` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `subjects` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `teacher_user`
--

INSERT INTO `teacher_user` (`id`, `username`, `password`, `first_name`, `last_name`, `subjects`) VALUES
(1, 'sultanbek', '$2y$10$0i6Hoq4r1eMZPlfRQbdm3ut5GtGswKUMoRrR1JvptH7.ObxFTiu0C', 'Sultanbek', 'Kenesbaev', '1');

-- --------------------------------------------------------

--
-- Структура таблицы `tests`
--

CREATE TABLE `tests` (
  `id` int NOT NULL,
  `subject_id` int DEFAULT NULL,
  `test_name` varchar(255) NOT NULL,
  `pdf_file` varchar(255) NOT NULL,
  `correct_answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `tests`
--

INSERT INTO `tests` (`id`, `subject_id`, `test_name`, `pdf_file`, `correct_answers`) VALUES
(1, 1, 'математика блок тест 2025', '../public/uploads/8001705_matem_profil.pdf', '{\"1\":\"1\",\"2\":\"1\",\"3\":\"1\",\"4\":\"1\",\"5\":\"1\",\"6\":\"1\",\"7\":\"1\",\"8\":\"1\",\"9\":\"1\",\"10\":\"1\",\"11\":\"1\",\"12\":\"1\",\"13\":\"1\",\"14\":\"1\",\"15\":\"1\",\"16\":\"1\",\"17\":\"1\",\"18\":\"1\",\"19\":\"1\",\"20\":\"1\",\"21\":\"1\",\"22\":\"1\",\"23\":\"1\",\"24\":\"1\",\"25\":\"1\",\"26\":\"1\",\"27\":\"1\",\"28\":\"1\",\"29\":\"1\",\"30\":\"1\"}');

-- --------------------------------------------------------

--
-- Структура таблицы `test_results`
--

CREATE TABLE `test_results` (
  `id` int NOT NULL,
  `subject_id` int DEFAULT NULL,
  `test_id` int DEFAULT NULL,
  `student_name` varchar(255) DEFAULT NULL,
  `student_lastname` varchar(255) DEFAULT NULL,
  `student_class` varchar(50) DEFAULT NULL,
  `teacher_name` varchar(255) DEFAULT NULL,
  `total_questions` int DEFAULT NULL,
  `correct_answers` int DEFAULT NULL,
  `answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `control_subjects`
--
ALTER TABLE `control_subjects`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `control_tests`
--
ALTER TABLE `control_tests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Индексы таблицы `control_test_results`
--
ALTER TABLE `control_test_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `test_id` (`test_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Индексы таблицы `teacher_user`
--
ALTER TABLE `teacher_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `control_subjects`
--
ALTER TABLE `control_subjects`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `control_tests`
--
ALTER TABLE `control_tests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `control_test_results`
--
ALTER TABLE `control_test_results`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `teacher_user`
--
ALTER TABLE `teacher_user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `control_tests`
--
ALTER TABLE `control_tests`
  ADD CONSTRAINT `control_tests_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `control_subjects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `control_tests_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `control_test_results`
--
ALTER TABLE `control_test_results`
  ADD CONSTRAINT `control_test_results_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `control_subjects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `control_test_results_ibfk_2` FOREIGN KEY (`test_id`) REFERENCES `control_tests` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `control_test_results_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
