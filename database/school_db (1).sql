-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Июн 18 2025 г., 11:29
-- Версия сервера: 5.7.24
-- Версия PHP: 8.3.1

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
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `achievements`
--

INSERT INTO `achievements` (`id`, `title`, `description`, `image`, `created_at`) VALUES
(1, 'ууууууууууууууууууууууу', 'уууууууууууууууууууууу', 'uploads/photo_2024-04-29_12-30-17.jpg', '2025-03-20 06:21:24');

-- --------------------------------------------------------

--
-- Структура таблицы `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$QO2zPZc6rX1eCRXzNcQUZOlg0EU8GhTVQqA8E5/QUtPwgRfEX/DJS');

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `image`, `created_at`) VALUES
(1, 'фффффффф', 'ввввввввввввввввввввввв', 'uploads/photo_2024-09-24_13-10-25.jpg', '2025-03-20 06:19:57');

-- --------------------------------------------------------

--
-- Структура таблицы `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `question_text` text NOT NULL,
  `option1` text NOT NULL,
  `option2` text,
  `option3` text,
  `option4` text,
  `correct_option` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `questions`
--

INSERT INTO `questions` (`id`, `subject_id`, `question_text`, `option1`, `option2`, `option3`, `option4`, `correct_option`) VALUES
(2, 1, 'вмоактмоук ', 'пмд35кьпмлукм', 'мдкьпмдукщмькщ', 'кбкщкз', 'кзщмлукщбк', 1),
(3, 1, 'керуруеи', 'уиекикеиек', 'уиекиекиек', 'иеиекиек', 'иеиекиек', 1),
(4, 1, 'иекиекиекиекиеки', 'екекикеикеие', 'екиекикк', 'киекиекиеки', 'еиекиеки', 1),
(5, 1, 'иеиекиекиекиекиеки', 'иекиекикеике', 'иекиекиеки', 'ииекиекике', 'еиекиекике', 1),
(6, 1, 'иекиекикеиеиекиекиекиек', 'иекиекиекиекиек', 'иекиекиеки', 'екиекиекиек', 'иекиекиек', 1),
(7, 1, 'иекиекиекиекиекиекие', 'иекиекиеки', 'екиекиеки', 'екиекиек', 'иекиекипа', 1),
(8, 1, 'иекикииненикни', 'кмкумкмук', 'мкумукмуку', 'мукмукмук', 'мукмукукмм', 1),
(9, 1, 'мукмукмумукмкмумук', 'мукмукмук', 'укмукм', 'мукмукмкукм', 'мукмукмукмук', 1),
(10, 1, 'мукмукмукмукмукмук', 'мукмукм', 'кмукмукм', 'кукмукмукм', 'кумукмукм', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `achievements` text,
  `about` text,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `students`
--

INSERT INTO `students` (`id`, `first_name`, `last_name`, `achievements`, `about`, `image`, `created_at`) VALUES
(1, 'dddddddd', 'dddddddd', 'dddddddddddddddddd', 'dddddddddddddddd', 'uploads/photo_2024-04-29_12-30-17.jpg', '2025-04-08 16:39:34');

-- --------------------------------------------------------

--
-- Структура таблицы `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `subjects`
--

INSERT INTO `subjects` (`id`, `name`) VALUES
(1, 'Математика'),
(2, 'Физика'),
(3, 'История'),
(4, 'Право'),
(5, 'Русский язык'),
(6, 'Биология'),
(7, 'Химия'),
(8, 'Английский язык');

-- --------------------------------------------------------

--
-- Структура таблицы `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `subjects` varchar(255) DEFAULT NULL,
  `achievements` text,
  `about` text,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `teachers`
--

INSERT INTO `teachers` (`id`, `first_name`, `last_name`, `subjects`, `achievements`, `about`, `image`, `created_at`) VALUES
(1, 'aaaaaaaaaaaaaaaaa', 'aaaaaaaaaaaaaaaaa', 'aaaaaa', 'aaaaaaaaaaa', 'aaaaaaaaaaaaaaaaaaaaaa', 'uploads/photo_2024-04-29_12-30-17.jpg', '2025-04-08 16:58:09');

-- --------------------------------------------------------

--
-- Структура таблицы `test_results`
--

CREATE TABLE `test_results` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `student_name` varchar(255) DEFAULT NULL,
  `student_lastname` varchar(255) DEFAULT NULL,
  `student_class` varchar(50) DEFAULT NULL,
  `total_questions` int(11) DEFAULT NULL,
  `correct_answers` int(11) DEFAULT NULL,
  `details` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `test_results`
--

INSERT INTO `test_results` (`id`, `subject_id`, `student_name`, `student_lastname`, `student_class`, `total_questions`, `correct_answers`, `details`, `created_at`) VALUES
(1, 1, NULL, NULL, NULL, 2, 1, '[{\"correct\": 1, \"question\": \"вмоактмоук \", \"your_answer\": 1}, {\"correct\": 1, \"question\": \"что такое что?\", \"your_answer\": 2}]', '2025-06-18 10:46:19'),
(2, 1, NULL, NULL, NULL, 10, 1, '[{\"correct\": 1, \"question\": \"вмоактмоук \", \"your_answer\": 1}, {\"correct\": 1, \"question\": \"мукмукмукмукмукмук\", \"your_answer\": 3}, {\"correct\": 1, \"question\": \"иекиекиекиекиеки\", \"your_answer\": 3}, {\"correct\": 1, \"question\": \"иекиекикеиеиекиекиекиек\", \"your_answer\": 3}, {\"correct\": 1, \"question\": \"мукмукмумукмкмумук\", \"your_answer\": 2}, {\"correct\": 1, \"question\": \"иекиекиекиекиекиекие\", \"your_answer\": 3}, {\"correct\": 1, \"question\": \"что такое что?\", \"your_answer\": 3}, {\"correct\": 1, \"question\": \"керуруеи\", \"your_answer\": 3}, {\"correct\": 1, \"question\": \"иеиекиекиекиекиеки\", \"your_answer\": 3}, {\"correct\": 1, \"question\": \"иекикииненикни\", \"your_answer\": 3}]', '2025-06-18 10:57:15');

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
-- Индексы таблицы `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`);

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
-- Индексы таблицы `test_results`
--
ALTER TABLE `test_results`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `achievements`
--
ALTER TABLE `achievements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `test_results`
--
ALTER TABLE `test_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
