<?php
require_once "includes/lang.php"; // Ensure translations are available
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= t('page_title_home') ?></title>
    <link rel="stylesheet" href="./public/css/styles.css">
    <link rel="stylesheet" href="./public/css/fotter.css">
    <link rel="stylesheet" href="./public/css/about.css">
    <link rel="stylesheet" href="./public/css/main.css">
    <link rel="stylesheet" href="./public/css/header.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .weather-block {
            color: white;
            font-size: clamp(13px, 1.5vw, 23px);
            font-weight: 900;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: end;
            margin-bottom: 50px;
        }

        .slider {
            width: 100%;
            overflow: hidden;
            position: relative;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .slides {
            display: flex;
            transition: transform 0.5s ease;
            height: 400px;
        }

        .slide {
            min-width: 100%;
            position: relative;
            overflow: hidden;
            border-radius: 50px 0 50px 0;
        }

        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .services-container {
            position: relative;
        }

        .serv {
            position: absolute;
            top: 20px;
            left: 20px;
            display: grid;
            gap: 20px;
        }

        .serv2 {
            position: absolute;
            top: 20px;
            right: 20px;
            display: grid;
            gap: 20px;
        }

        .service-card {
            background: white;
            border-radius: 10px;
            padding: 10px;
            width: 230px;
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }

        .service-card:hover {
            transform: translateY(-3px);
        }

        .service-card a {
            width: 100%;
            height: 100%;
            display: flex;
        }

        .service-card img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .services-container {
                padding: 10px;
                display: flex;
                gap: 10px;
                overflow-x: auto;
            }

            .serv, .serv2 {
                position: relative;
                top: 0;
                left: 0;
                right: 0;
                display: flex;
                flex-wrap: nowrap;
                gap: 10px;
                margin-bottom: 20px;
            }

            .service-card {
                flex: 0 0 auto;
                width: 100px;
                height: 100px;
            }
        }

        .loader {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: rgba(255, 255, 255, 0.2);
            z-index: 10;
        }

        .text-content > .text {
            margin: 30px 0 20px 0;
        }

        .text p {
            text-align: end;
        }

        .loader::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: #fff;
            animation: loader 3s linear infinite;
        }

        @keyframes loader {
            0% {
                transform: translateX(-100%);
            }
            100% {
                transform: translateX(0);
            }
        }

        .edo > span {
            color: #5C5C5C;
            font-size: 24px;
            font-weight: 900;
        }
    </style>
</head>

<body class="img-main">
    <?php include("includes/header.php"); ?>
    <div class="services-container">
        <div class="serv">
            <div class="service-card">
                <a href="https://login.emaktab.uz/"><img src="./public/images/emaktab.png" alt="<?= t('alt_emaktab') ?>"></a>
            </div>
            <div class="service-card">
                <a href="https://kundalik.com/"><img src="./public/images/kundalik.svg" alt="<?= t('alt_kundalik') ?>"></a>
            </div>
            <div class="service-card">
                <a href="https://uzbmb.uz/"><img src="./public/images/dtm.jpg" alt="<?= t('alt_dtm') ?>"></a>
            </div>
        </div>
        <div class="serv2">
            <div class="service-card">
                <a href="https://my.gov.uz/uz"><img src="./public/images/My.gov.uz.svg" alt="<?= t('alt_my_gov') ?>"></a>
            </div>
            <div class="service-card">
                <a class="edo" href="https://edo.ijro.uz/"><img src="./public/images/ijro.png" alt="<?= t('alt_ijro') ?>"><span><?= t('edo_ijro') ?></span></a>
            </div>
            <div class="service-card">
                <a class="edo" href="https://erp.maktab.uz/"><img src="./public/images/erp.jpg" alt="<?= t('alt_erp') ?>"><span><?= t('erp_maktab') ?></span></a>
            </div>
        </div>
    </div>

    <section class="main-sec">
        <div class="container">
            <div class="main-block">
                <div class="text-content">
                    <div class="slider">
                        <div class="loader"></div>
                        <div class="slides">
                            <div class="slide">
                                <img src="./public/images/school/1.jpg" alt="<?= t('alt_slide_1') ?>">
                            </div>
                            <div class="slide">
                                <img src="./public/images/vaz1.jpg" alt="<?= t('alt_slide_2') ?>">
                            </div>
                            <div class="slide">
                                <img src="./public/images/vaz2.jpg" alt="<?= t('alt_slide_3') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="text">
                        <i><?= t('quote') ?></i>
                        <p><?= t('quote_author') ?></p>
                    </div>
                    <button class="main-button"><a href="./about.php"><?= t('button_read') ?></a></button>
                </div>

                <div>
                    <div class="weather-block" id="weatherBlock">
                        <?= t('weather_loading') ?>
                    </div>
                    <div class="img-content">
                        <div class="border-img2">
                            <img src="./public/images/school/1.jpg" alt="<?= t('alt_school_image') ?>">
                        </div>
                        <div class="small-circle" id="small1">
                            <div class="inf-content">
                                <img src="./public/images/Group 18.png" alt="<?= t('alt_shift_info') ?>">
                                <div class="content-text" id="shiftInfo"></div>
                            </div>
                        </div>
                        <div class="small-circle" id="small2">
                            <div class="inf-content">
                                <img src="./public/images/Group 101.png" alt="<?= t('alt_lesson_info') ?>">
                                <div class="content-text" id="lessonInfo"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Pass translations to JavaScript
        const translations = {
            first_shift: "<?= t('first_shift') ?>",
            second_shift: "<?= t('second_shift') ?>",
            classes_label: "<?= t('classes_label') ?>",
            lessons_not_started: "<?= t('lessons_not_started') ?>",
            lesson_in_progress: "<?= t('lesson_in_progress') ?>",
            break_time: "<?= t('break_time') ?>",
            lessons_ended: "<?= t('lessons_ended') ?>",
            weather_error: "<?= t('weather_error') ?>"
        };

        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.img-content');
            const small1 = document.getElementById('small1');
            const small2 = document.getElementById('small2');

            let angle = 0;
            const speed = 0.01;

            function getRadius() {
                return window.innerWidth < 768 ? 130 : 200;
            }

            function updateCirclePositions() {
                const radius = getRadius();
                const centerX = container.offsetWidth / 2;
                const centerY = container.offsetHeight / 2;

                const x1 = centerX + Math.cos(angle) * radius - 25;
                const y1 = centerY + Math.sin(angle) * radius - 25;
                small1.style.left = x1 + 'px';
                small1.style.top = y1 + 'px';

                const x2 = centerX + Math.cos(angle + Math.PI) * radius - 25;
                const y2 = centerY + Math.sin(angle + Math.PI) * radius - 25;
                small2.style.left = x2 + 'px';
                small2.style.top = y2 + 'px';
            }

            function animate() {
                angle += speed;
                updateCirclePositions();
                requestAnimationFrame(animate);
            }

            window.addEventListener('resize', function() {
                updateCirclePositions();
            });

            animate();
        });

        const firstShiftClasses = [1, 2, 3, 4, 9, 11];
        const secondShiftClasses = [5, 6, 7, 8, 10];

        const now = new Date();
        const totalMinutes = now.getHours() * 60 + now.getMinutes();
        const firstShiftStart = 8 * 60 + 30;
        const secondShiftStart = 13 * 60 + 30;

        const shiftInfoEl = document.getElementById("shiftInfo");

        if (totalMinutes >= firstShiftStart && totalMinutes < secondShiftStart) {
            shiftInfoEl.innerHTML = `${translations.first_shift} <br> ${translations.classes_label}: ${firstShiftClasses.join(", ")}`;
        } else if (totalMinutes >= secondShiftStart) {
            shiftInfoEl.innerHTML = `${translations.second_shift} <br> ${translations.classes_label}: ${secondShiftClasses.join(", ")}`;
        } else {
            shiftInfoEl.innerHTML = translations.lessons_not_started;
        }

        const lessonDuration = 45;
        const breakDuration = 5;
        const maxLessons = 6;

        function getCurrentLessonInfo() {
            const now = new Date();
            const hours = now.getHours().toString().padStart(2, "0");
            const minutes = now.getMinutes().toString().padStart(2, "0");
            const timeString = `${hours}:${minutes}`;

            const totalMinutes = now.getHours() * 60 + now.getMinutes();
            let shiftStart = 0;

            if (totalMinutes >= firstShiftStart && totalMinutes < secondShiftStart) {
                shiftStart = firstShiftStart;
            } else if (totalMinutes >= secondShiftStart) {
                shiftStart = secondShiftStart;
            } else {
                return `${timeString}<br>${translations.lessons_not_started}`;
            }

            const minutesSinceStart = totalMinutes - shiftStart;
            const lessonBlock = lessonDuration + breakDuration;
            const currentBlock = Math.floor(minutesSinceStart / lessonBlock);
            const timeInCurrentBlock = minutesSinceStart % lessonBlock;

            if (currentBlock >= maxLessons) {
                return `${timeString}<br>${translations.lessons_ended}`;
            }

            if (timeInCurrentBlock < lessonDuration) {
                return `${timeString}<br>${translations.lesson_in_progress.replace('{lesson_number}', currentBlock + 1)}`;
            } else {
                return `${timeString}<br>${translations.break_time}`;
            }
        }

        function updateLessonInfo() {
            document.getElementById("lessonInfo").innerHTML = getCurrentLessonInfo();
        }

        updateLessonInfo();
        setInterval(updateLessonInfo, 10000);

        const API_KEY = "639428ef3b4b3f8e2363e3ea1d6646dd";
        const CITY = "Kegeyli";
        const currentLang = "<?= isset($_SESSION['lang']) ? $_SESSION['lang'] : 'ru' ?>";

        async function fetchWeather() {
            try {
                const res = await fetch(`https://api.openweathermap.org/data/2.5/weather?q=${CITY},UZ&units=metric&lang=${currentLang}&appid=${API_KEY}`);
                const data = await res.json();

                const temp = Math.round(data.main.temp);
                const desc = data.weather[0].description;
                const icon = data.weather[0].icon;

                document.getElementById("weatherBlock").innerHTML = `
                    <img src="https://openweathermap.org/img/wn/${icon}@2x.png" alt="${translations.weather_icon_alt}" style="vertical-align:middle">
                    ${t('weather_city')}: ${temp}°C, ${desc}
                `;
            } catch (err) {
                document.getElementById("weatherBlock").innerText = translations.weather_error;
                console.error("Weather error:", err);
            }
        }

        fetchWeather();
        setInterval(fetchWeather, 30 * 60 * 1000);

        document.addEventListener('DOMContentLoaded', function() {
            const slider = document.querySelector('.slider');
            const slides = document.querySelector('.slides');
            const slideItems = document.querySelectorAll('.slide');
            let currentIndex = 0;
            const slideWidth = 100;
            const intervalTime = 5000;

            const firstSlideClone = slideItems[0].cloneNode(true);
            slides.appendChild(firstSlideClone);

            function nextSlide() {
                currentIndex++;
                slides.style.transform = `translateX(-${currentIndex * slideWidth}%)`;

                if (currentIndex >= slideItems.length) {
                    setTimeout(() => {
                        slides.style.transition = 'none';
                        currentIndex = 0;
                        slides.style.transform = `translateX(0)`;
                        setTimeout(() => {
                            slides.style.transition = 'transform 0.5s ease';
                        }, 10);
                    }, 500);
                }
            }

            let slideInterval = setInterval(nextSlide, intervalTime);

            slider.addEventListener('mouseenter', () => {
                clearInterval(slideInterval);
                document.querySelector('.loader').style.animationPlayState = 'paused';
            });

            slider.addEventListener('mouseleave', () => {
                slideInterval = setInterval(nextSlide, intervalTime);
                document.querySelector('.loader').style.animationPlayState = 'running';
            });

            nextSlide();
        });
    </script>
</body>
</html>