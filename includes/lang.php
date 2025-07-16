<?php
session_start();

// Языки и иконки
$languages = [
    'ru'  => ['name' => 'RU',  'icon' => 'public/images/flags/ru.png'],
    'en'  => ['name' => 'EN',  'icon' => 'public/images/flags/us.png'],
    'kaa' => ['name' => 'QR',  'icon' => 'public/images/flags/qr.png'],
    'uz'  => ['name' => 'UZ',  'icon' => 'public/images/flags/uz.png']
];

$default_lang = 'ru';
$current_lang = $_SESSION['lang'] ?? $default_lang;

// Обработка смены языка
if (isset($_GET['lang']) && array_key_exists($_GET['lang'], $languages)) {
    $_SESSION['lang'] = $_GET['lang'];
    $current_lang = $_GET['lang'];
}

// Загрузка перевода
$translation_file = __DIR__ . "/lang/{$current_lang}.json";
$translations = file_exists($translation_file)
    ? json_decode(file_get_contents($translation_file), true)
    : [];

// Функция перевода с параметрами
if (!function_exists('t')) {
    function t($key, $params = []) {
        global $translations;

        $text = $translations[$key] ?? $key;

        if (!empty($params)) {
            foreach ($params as $param => $value) {
                $text = str_replace("{" . $param . "}", $value, $text);
            }
        }

        return $text;
    }
}
?>
