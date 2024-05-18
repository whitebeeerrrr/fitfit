<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Информация о тренере</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #feeebc;
            padding-top: 100px; /* Увеличил верхний отступ для учета фиксированного навигационного меню */
            padding-bottom: 50px;
        }
        .trainer-photo img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .trainer-info {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .trainer-info h2 {
            margin-top: 0;
            color: #333333;
        }
        .trainer-info p.specialization {
            font-weight: bold;
            font-size: 18px;
            color: #555555;
        }
        .trainer-info h3 {
            color: #333333;
        }
        .appointment-button {
            text-align: center;
            margin-top: 30px;
        }
        .custom-btn {
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 18px;
        }
        .navbar {
            background-color: rgba(0, 0, 0, 0.5) !important;
        }
        .navbar-brand {
            color: white !important; /* Белый цвет текста для названия */
        }
        .navbar-nav .nav-link {
            font-family: 'Roboto', sans-serif;
            color: white !important; /* Белый цвет текста */
        }
        .navbar-nav .nav-link:hover {
            color: #D8D9E9  !important; /* Цвет при наведении */
        }
        .footer {
            background-color: #333;
            color: white;
            padding: 20px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .footer p {
            margin-bottom: 0;
        }
    </style>
</head>
<body style="background-image: url('background.jpg'); background-size: cover;">

    <!-- Навигационное меню -->
    <nav class="navbar navbar-expand-md navbar-light fixed-top bg-transparent">
        <div class="container">
            <a class="navbar-brand" href="#">Фитнес клуб</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="index.html">Главная</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Расписание</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Групповые программы</a></li>
                    <li class="nav-item"><a class="nav-link" href="trainers.php">Тренеры</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Вакансии</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Атрибутика</a></li>
                    <li class="nav-item"><a class="nav-link" href="personal_cabinet.php">Личный кабинет</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="trainer-photo">
                    <?php
                    if(isset($_GET['trainer_id'])) {
                        $trainer_id = $_GET['trainer_id'];
                        $trainers = [
                            1 => 'да2.jpg',
                            2 => 'да4.jpg',
                            3 => 'да1.jpg'
                        ];
                        if(isset($trainers[$trainer_id])) {
                            $photo_src = $trainers[$trainer_id];
                            echo "<img src=\"$photo_src\" alt=\"Фото тренера\">";
                        } else {
                            echo "<p>Фото тренера не найдено</p>";
                        }
                    }
                    ?>
                </div>
                <div class="appointment-button">
                    <button type="button" class="btn btn-primary custom-btn" data-toggle="modal" data-target="#appointmentModal">
                        Записаться на тренировку
                    </button>
                </div>
            </div>
            <div class="col-md-8">
                <div class="trainer-info">
                    <?php
                   if(isset($_GET['trainer_id'])) {
                    $trainer_id = $_GET['trainer_id'];
                    $trainers = [
                        1 => [
                            'name' => 'Александр Авдюков ',
                            'specialization' => 'Бодибилдинг',
                            'about' => '<p>Бодибилдинг для меня – это не просто хобби, это моя страсть и образ жизни, которым я предан уже более двадцати лет. Моя дисциплина и упорство помогли мне стать профессиональным бодибилдером и достичь значительных результатов в этой области. В возрасте восемнадцати лет я уже имел звание мастера спорта и многочисленные победы на соревнованиях по бодибилдингу как в России, так и за её пределами.</p>
                
                <p>Моя цель – помочь тебе достичь желаемых результатов в бодибилдинге. Начиная с правильной разминки и заканчивая развитием всех необходимых физических качеств, я обеспечу эффективные и интересные тренировки. Мой опыт и знания в этой области гарантируют, что ты достигнешь успеха в своих усилиях. Доверься мне, и вместе мы добьемся высот в бодибилдинге, которые ты раньше считал недостижимыми.</p>',
                            'experience' => 'С 2022 года'
                        ],
                        2 => ['name' => 'Фомина Анастасия', 'specialization' => 'Йога','about'=>'<p>Моя жизнь йогой началась несколько лет назад, и с тех пор она стала неотъемлемой частью моего бытия. Йога для меня не просто упражнения на коврике, это философия, способ общения с собой и миром вокруг меня. Моя практика помогла мне обрести внутреннюю гармонию, улучшить физическое и психическое здоровье, а также найти своё место в этом мире.</p>

                       <p> Моя цель – поделиться своим опытом и знаниями с теми, кто хочет открыть для себя мир йоги. Я помогу тебе погрузиться в практику с осознанностью и глубоким пониманием, начиная с основных асан и заканчивая медитативными техниками. Мои уроки будут не только эффективными, но и вдохновляющими, помогая тебе раскрыть свой потенциал на коврике и за его пределами. Доверься мне, и вместе мы сможем достичь гармонии, которую ты давно искал, и открыть новые горизонты в своей йогической практике.</p>'],
                        3 => ['name' => 'Семенов Сергей', 'specialization' => '<p>Кроссфит стал для меня не просто видом спорта, а образом жизни, который изменяет меня каждый день. Моя любовь к этой дисциплине началась несколько лет назад, когда я понял, что обычные тренировки больше не приносят мне удовлетворения, и я ищу что-то новое, более вызывающее. Кроссфит предложил мне не только физическую нагрузку, но и интеллектуальный вызов, постоянно заставляющий меня расти и развиваться как спортсмена и личности.</p><p>Моя цель – помочь тебе открыть для себя удивительный мир кроссфита и достичь новых высот в своей физической форме и выносливости. Мои тренировки не только сфокусированы на развитии силы и выносливости, но и на улучшении техники выполнения упражнений, предотвращении травм и поддержании мотивации. Доверься мне, и вместе мы сможем преодолеть любые вызовы, которые кроссфит бросает нам на пути, и достичь новых вершин в своей спортивной карьере и личной жизни.</p>']
                    ];
                        if(isset($trainers[$trainer_id])) {
                            $trainer_info = $trainers[$trainer_id];
                            echo "<h2>{$trainer_info['name']}</h2>";
                            echo "<p class=\"specialization\">{$trainer_info['specialization']}</p>";
                            if(isset($trainer_info['about'])) {
                                echo "<h3>О себе</h3>";
                                echo "<p>{$trainer_info['about']}</p>";
                            }
                            if(isset($trainer_info['experience'])) {
                                echo "<h3>Опыт</h3>";
                                echo "<p>{$trainer_info['experience']}</p>";
                            }
                        } else {
                            echo "<p>Информация о тренере с идентификатором $trainer_id не найдена.</p>";
                        }
                    } else {
                        echo "<p>Информация о тренере не найдена.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно для записи на тренировку -->
    <div class="modal fade" id="appointmentModal" tabindex="-1" role="dialog" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appointmentModalLabel">Записаться на тренировку</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Добро пожаловать в наш фитнес-клуб! На данной странице вы можете записаться на тренировку. Просто выберите день в календаре, нажмите на ячейку и следуйте инструкциям. После записи выбранная ячейка изменит цвет, и вы сможете увидеть время своей записи.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary custom-btn" onclick="location.href='zapis.php'">Записаться</button>
            </div>
            <div class="modal-footer">
                <p>Отправляя данную форму, вы соглашаетесь с <a href="#">нашей политикой обработки персональных данных</a>.</p>
            </div>
        </div>
    </div>
</div>



    <!-- Футер -->
    <footer class="footer mt-auto py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Контактная информация</h5>
                    <p>Телефон: +7 (XXX) XXX-XX-XX</p>
                    <p>Email: info@example.com</p>
                </div>
                <div class="col-md-6">
                    <h5>Адрес</h5>
                    <p>Город, Улица, Дом</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
