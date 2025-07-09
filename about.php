<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>О школе</title>
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
            <h1>О Нас</h1>
            <div class="content-about-block">
                <div class="img-about">
                    <div class="border-img">
                        <img src="./public/images/school/2.jpg" alt="" class="about-img">
                    </div>
                </div>
                <div class="text-about">
                    <div class="about-item">
                        <h1>О нашей школе</h1>
                        <p>Наша школа — это образовательное учреждение с богатой историей и традициями, расположенное в сердце Нукусского района. С момента своего основания в [год основания] году, мы стремимся создавать условия для всестороннего развития каждого ученика.</p>
                    </div>
                    <div class="about-item">
                        <h1>Наша миссия</h1>
                        <p>Мы верим, что образование — это ключ к успешному будущему. Наша миссия заключается в предоставлении качественного образования, воспитании гражданственности, уважения к культуре и традициям, а также формировании навыков, необходимых для жизни в современном мире.</p>
                    </div>
                    <div class="about-item">
                        <h1>Присоединяйтесь к нам</h1>
                        <p>Мы приглашаем вас стать частью нашей дружной школьной семьи. Посещая нашу школу, вы получаете не только знания, но и возможность развиваться, расти и достигать новых высот.​</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <h2 class="title-struc">Руководство и педагогический состав</h2>
            <div class="container1">
                <div class="box">
                    <img src="./public/images/director.jpg" alt="Директор">
                    <div class="title">Директор</div>
                    <p>Каллибекова Гаухаргул Сатбаевна</p>
                </div>
                <div class="hierarchy">
                    <div class="box">
                        <img src="./public/images/zamdirector.jpg" alt="Заместитель">
                        <div class="title">Заместитель директора</div>
                        <p>Абдисаттаров Ерназар Умирбаевич</p>
                    </div>
                    <div class="box">
                        <img src="./public/images/zamdirector2.jpg" alt="Заместитель">
                        <div class="title">Заместитель директора</div>
                        <p>Жумамуратова Зухра  Жумабековна</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include("includes/footer.php"); ?>
</body>

</html>