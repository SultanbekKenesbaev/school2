-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Хост: sql305.byetcluster.com
-- Время создания: Июл 08 2025 г., 04:14
-- Версия сервера: 11.4.7-MariaDB
-- Версия PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `if0_39335783_school_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `achievements`
--

CREATE TABLE `achievements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Дамп данных таблицы `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(2, 'admin.5.mektep', '$2y$10$Cq.SpaUzpCJ04vRfndzitOVG0Qz1SSKiYkrysNPD1fmlKEKVfbDGe');

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
  `id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `achievements` text DEFAULT NULL,
  `about` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
  `id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `subjects` varchar(255) DEFAULT NULL,
  `achievements` text DEFAULT NULL,
  `about` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Дамп данных таблицы `teachers`
--

INSERT INTO `teachers` (`id`, `first_name`, `last_name`, `subjects`, `achievements`, `about`, `image`, `created_at`) VALUES
(2, 'Зилиха Жанабаевна', 'Баймуратова ', 'Математика', 'Учитель года', 'Классный руководитель 10-А класса', 'uploads/Рисунок3.png', '2025-07-02 05:36:56'),
(3, 'Умитхан Назарбай кызы', 'Юсупова ', ' Биология', 'Лидер профессионального мастерства', 'Люблю учить детей', 'uploads/Рисунок4.png', '2025-07-02 05:37:51');

-- --------------------------------------------------------

--
-- Структура таблицы `tests`
--

CREATE TABLE `tests` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `test_name` varchar(255) NOT NULL,
  `pdf_file` varchar(255) NOT NULL,
  `correct_answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL
) ;

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
  `id` int(11) NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `test_id` int(11) DEFAULT NULL,
  `student_name` varchar(255) DEFAULT NULL,
  `student_lastname` varchar(255) DEFAULT NULL,
  `student_class` varchar(50) DEFAULT NULL,
  `teacher_name` varchar(255) DEFAULT NULL,
  `total_questions` int(11) DEFAULT NULL,
  `correct_answers` int(11) DEFAULT NULL,
  `answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ;

--
-- Дамп данных таблицы `test_results`
--

INSERT INTO `test_results` (`id`, `subject_id`, `test_id`, `student_name`, `student_lastname`, `student_class`, `teacher_name`, `total_questions`, `correct_answers`, `answers`, `created_at`) VALUES
(1, 1, 1, 'султан', 'кенесбаев', '11д', 'Муратбек', 30, 27, '{\"1\":\"1\",\"2\":\"1\",\"3\":\"1\",\"4\":\"2\",\"5\":\"1\",\"6\":\"1\",\"7\":\"1\",\"8\":\"1\",\"9\":\"1\",\"10\":\"2\",\"11\":\"1\",\"12\":\"3\",\"13\":\"1\",\"14\":\"1\",\"15\":\"1\",\"16\":\"1\",\"17\":\"1\",\"18\":\"1\",\"19\":\"1\",\"20\":\"1\",\"21\":\"1\",\"22\":\"1\",\"23\":\"1\",\"24\":\"1\",\"25\":\"1\",\"26\":\"1\",\"27\":\"1\",\"28\":\"1\",\"29\":\"1\",\"30\":\"1\"}', '2025-07-04 00:15:13'),
(2, 1, 1, 'султан', 'кенесбаев', '11д', 'Муратбек', 30, 29, '{\"1\":\"1\",\"2\":\"1\",\"3\":\"1\",\"4\":\"1\",\"5\":\"1\",\"6\":\"2\",\"7\":\"1\",\"8\":\"1\",\"9\":\"1\",\"10\":\"1\",\"11\":\"1\",\"12\":\"1\",\"13\":\"1\",\"14\":\"1\",\"15\":\"1\",\"16\":\"1\",\"17\":\"1\",\"18\":\"1\",\"19\":\"1\",\"20\":\"1\",\"21\":\"1\",\"22\":\"1\",\"23\":\"1\",\"24\":\"1\",\"25\":\"1\",\"26\":\"1\",\"27\":\"1\",\"28\":\"1\",\"29\":\"1\",\"30\":\"1\"}', '2025-07-04 00:57:39'),
(3, 1, 1, 'Ережепбаев', 'Жанполат', '11а', 'Альбина Муратбаева', 30, 3, '{\"1\":\"1\",\"2\":\"2\",\"3\":\"3\",\"4\":\"4\",\"5\":\"3\",\"6\":\"2\",\"7\":\"1\",\"8\":\"2\",\"9\":\"3\",\"10\":\"4\",\"11\":\"4\",\"12\":\"4\",\"13\":\"3\",\"14\":\"3\",\"15\":\"2\",\"16\":\"4\",\"17\":\"4\",\"18\":\"2\",\"19\":\"2\",\"20\":\"3\",\"21\":\"3\",\"22\":\"2\",\"23\":\"3\",\"24\":\"4\",\"25\":\"2\",\"26\":\"3\",\"27\":\"1\",\"28\":\"4\",\"29\":\"3\",\"30\":\"2\"}', '2025-07-04 07:05:05'),
(4, 1, 1, 'Султан', 'Кенесбаев', '11д', 'Мурат', 30, 28, '{\"1\":\"1\",\"2\":\"3\",\"3\":\"1\",\"4\":\"4\",\"5\":\"1\",\"6\":\"1\",\"7\":\"1\",\"8\":\"1\",\"9\":\"1\",\"10\":\"1\",\"11\":\"1\",\"12\":\"1\",\"13\":\"1\",\"14\":\"1\",\"15\":\"1\",\"16\":\"1\",\"17\":\"1\",\"18\":\"1\",\"19\":\"1\",\"20\":\"1\",\"21\":\"1\",\"22\":\"1\",\"23\":\"1\",\"24\":\"1\",\"25\":\"1\",\"26\":\"1\",\"27\":\"1\",\"28\":\"1\",\"29\":\"1\",\"30\":\"1\"}', '2025-07-04 12:24:15'),
(5, 1, 1, 'Ережепбаев', 'Жанполат', '10Б', 'Альбина Муратбаева', 30, 4, '{\"1\":\"2\",\"2\":\"1\",\"3\":\"2\",\"4\":\"3\",\"5\":\"4\",\"6\":\"3\",\"7\":\"2\",\"8\":\"1\",\"9\":\"1\",\"10\":\"2\",\"11\":\"3\",\"12\":\"2\",\"13\":\"3\",\"14\":\"2\",\"15\":\"2\",\"16\":\"3\",\"17\":\"3\",\"18\":\"3\",\"19\":\"3\",\"20\":\"3\",\"21\":\"2\",\"22\":\"1\",\"23\":\"3\",\"24\":\"3\",\"25\":\"4\",\"26\":\"4\",\"27\":\"4\",\"28\":\"3\",\"29\":\"3\",\"30\":\"4\"}', '2025-07-04 12:37:30'),
(6, 1, 1, 'султан', 'кенесбаев', '11д', 'Муратбек', 30, 29, '{\"1\":\"1\",\"2\":\"1\",\"3\":\"1\",\"4\":\"1\",\"5\":\"1\",\"6\":\"1\",\"7\":\"1\",\"8\":\"1\",\"9\":\"1\",\"10\":\"1\",\"11\":\"1\",\"12\":\"1\",\"13\":\"1\",\"14\":\"1\",\"15\":\"1\",\"16\":\"1\",\"17\":\"1\",\"18\":\"1\",\"19\":\"4\",\"20\":\"1\",\"21\":\"1\",\"22\":\"1\",\"23\":\"1\",\"24\":\"1\",\"25\":\"1\",\"26\":\"1\",\"27\":\"1\",\"28\":\"1\",\"29\":\"1\",\"30\":\"1\"}', '2025-07-05 01:08:26');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `achievements`
--
ALTER TABLE `achievements`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Индексы таблицы `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `achievements`
--
ALTER TABLE `achievements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `tests`
--
ALTER TABLE `tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `test_results`
--
ALTER TABLE `test_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
