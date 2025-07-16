<?php
require_once "includes/lang.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= t('page_title_about') ?></title>
    <link rel="stylesheet" href="./public/css/styles.css">
    <link rel="stylesheet" href="./public/css/fotter.css">
    <link rel="stylesheet" href="./public/css/about.css">
    <link rel="stylesheet" href="./public/css/main.css">
    <link rel="stylesheet" href="./public/css/header.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
       .container1 {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.box {
  background: var(--glass-base);
  backdrop-filter: blur(var(--glass-blur));
  -webkit-backdrop-filter: blur(var(--glass-blur));
  padding: 15px;
  margin: 10px;
  border-radius: var(--border-radius);
  box-shadow: var(--glass-shadow);
  border: 1px solid var(--glass-border);
  text-align: center;
  width: 100%;
  max-width: 400px;
  height: 250px;
  transition: var(--transition);
}

.box:hover {
  box-shadow: 0 0 20px var(--glass-highlight);
}

.box p {
  text-align: center;
  font-size: 20px;
  margin-top: 15px;
  color: var(--text-dark);
}

.box img {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: contain;
  margin-bottom: 10px;
  border: 2px solid var(--glass-border);
  box-shadow: var(--glass-shadow);
}

.title {
  font-weight: bold;
  font-size: 25px;
  color: var(--text-dark);
}

.hierarchy {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  margin-bottom: 50px;
}

.title-struc {
  font-size: 32px;
  color: var(--text-dark);
  margin: 40px 0 30px 0;
}

@media (max-width: 768px) {
  .title {
    font-weight: bold;
    font-size: 18px;
    color: var(--text-dark);
  }

  .box p {
    text-align: center;
    font-size: 15px;
    margin-top: 10px;
    color: var(--text-dark);
  }
}

@media (min-width: 768px) {
  .hierarchy {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
  }
}
    </style>
</head>

<body class="about-body">
    <?php include("includes/header.php"); ?>
    <section class="about-section">
        <div class="container">
            <h1><?= t('section_title_about') ?></h1>
            <div class="content-about-block">
                <div class="img-about">
                    <div class="border-img">
                        <img src="./public/images/school/2.jpg" alt="<?= t('alt_school_image') ?>" class="about-img">
                    </div>
                </div>
                <div class="text-about">
                    <div class="about-item">
                        <h1><?= t('about_our_school') ?></h1>
                        <p><?= t('about_our_school_desc') ?></p>
                    </div>
                    <div class="about-item">
                        <h1><?= t('our_mission') ?></h1>
                        <p><?= t('our_mission_desc') ?></p>
                    </div>
                    <div class="about-item">
                        <h1><?= t('join_us') ?></h1>
                        <p><?= t('join_us_desc') ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <h2 class="title-struc"><?= t('leadership_and_staff') ?></h2>
            <div class="container1">
                <div class="box">
                    <img src="./public/images/director.jpg" alt="<?= t('alt_director') ?>">
                    <div class="title"><?= t('director') ?></div>
                    <p><?= t('director_name') ?></p>
                </div>
                <div class="hierarchy">
                    <div class="box">
                        <img src="./public/images/zamdirector.jpg" alt="<?= t('alt_deputy_director') ?>">
                        <div class="title"><?= t('deputy_director') ?></div>
                        <p><?= t('deputy_director_1_name') ?></p>
                    </div>
                    <div class="box">
                        <img src="./public/images/zamdirector2.jpg" alt="<?= t('alt_deputy_director') ?>">
                        <div class="title"><?= t('deputy_director') ?></div>
                        <p><?= t('deputy_director_2_name') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include("includes/footer.php"); ?>
</body>
</html>