<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список тренеров</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
<style>
    /* CSS для изменения цвета выпадающего списка и применения шрифта */
    select#directionFilter, select#trainerFilter {
        background-color: #D8D9E9; /* Измените на желаемый цвет фона */
        color: black; /* Измените на желаемый цвет текста */
        width: 500px; /* Уменьшаем ширину списка */
        font-family: 'Butcherman', cursive; /* Применяем шрифт Butcherman */
    }
    label {
        color: #D8D9E9; /*  */
    }
    .carousel-item {
        width: calc(33.33% - 20px); /* Изменяем ширину, чтобы было по три в ряд */
        height: auto;
        border-radius: 20px;
        background-color: #95bcd6;
        overflow: hidden;
        margin-right: 10px;
        margin-bottom: 20px; /* Добавляем отступ снизу для разделения на ряды */
        display: inline-block;
        cursor: pointer;
        transition: 1000ms all;
        transform-origin: center left;
        position: relative;
    }
    .carousel-item__img {
        width: 100%;
        height: 500px; /* Фиксированная высота */
        object-fit: cover;
    }
    .carousel-item__details {
        background: linear-gradient(to top, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0) 100%);
        font-size: 10px;
        opacity: 0;
        transition: 450ms opacity;
        padding: 10px;
        position: absolute;
        bottom: 0; /* Располагаем надпись внизу */
        left: 0;
        right: 0;
    }
    .carousel-item:hover .carousel-item__details { /* Изменяем стили надписи при наведении на карточку */
        opacity: 1;
    }
    .carousel-item__details span {
        font-size: 0.9rem;
        color: #2ecc71;
    }
    .carousel-item__details .controls {
        padding-top: 180px;
    }
    .carousel-item__details .carousel-item__details--title,
    .carousel-item__details--subtitle {
        color: #fff;
        margin: 5px 0;
    }
    /* Стили для видеофона */
    #video-background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw; /* Set width to 100% of viewport width */
        height: 100vh; /* Set height to 100% of viewport height */
        object-fit: cover; /* Ensure the video covers the entire container */
        z-index: -1;
    }
    /* Dark semi-transparent overlay */
    #video-background::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: -1;
    }
    .navbar {
        background-color: rgba(0, 0, 0, 0.5) !important;
        font-family: 'Butcherman', cursive;
    }
    .navbar-brand {
        color: white !important; /* Белый цвет текста для названия */
    }
    .navbar-nav .nav-link {
        font-family: 'Butcherman', cursive;
        color: white !important; /* Белый цвет текста */
    }
    .navbar-nav .nav-link:hover {
        color:#D8D9E9  !important; /* #D8D9E9 ЦВЕТ*/
    }
    .container.text-center {
        position: relative; /* Добавляем относительное позиционирование для контейнера с карточками */
        z-index: 1; /* Чтобы карточки оказались поверх фона */
        margin-bottom: 57px; /* Добавляем отступ внизу */
    }
    .info-block {
        background-color: rgba(0, 0, 0, 0.7); /* Темный цвет фона с прозрачностью */
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 10px;
        color: white; /* Белый цвет текста */
        margin-top: 100px; /* Отступ сверху */
        font-family: 'Butcherman', cursive; /* Используем шрифт Butcherman */
    }
    .info-block h3 {
        color: #ffffff; /* Устанавливаем цвет заголовка */
    }
    .info-block p {
        color: #ffffff; /* Устанавливаем цвет текста */
    }
    ::-webkit-scrollbar {
        width: 0; /* Для WebKit (Chrome, Safari, Edge) */
    }
    /* Стили для изменения цвета выпадающего списка и применения шрифта */
    .butcherman-font {
        font-family: 'Butcherman', cursive;
    }
</style>

</head>
<body>
    <!-- Видеофон -->
    <video autoplay muted loop id="video-background">
        <source src="IMG_5465.mp4" type="video/mp4">
    </video>

    <!-- Хедер -->
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
                    <li class="nav-item"><a class="nav-link" href="vakancii.html">Вакансии</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Атрибутика</a></li>
                    <li class="nav-item"><a class="nav-link" href="personal_cabinet.php">Личный кабинет</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Информационный блок -->
    <div class="container text-center mt-5">
        <div class="info-block">
            <h3>Зачем нужны тренера?</h3>
            <p>Тренеры помогают вам правильно выполнить упражнения, разработать персональную программу тренировок, следить за вашим прогрессом и мотивировать вас на достижение ваших целей.</p>
        </div>

        <!-- Объединенный контейнер для фильтров -->
        <div class="container-fluid">
            <div class="row">
                <!-- Выпадающий список для фильтрации по направлению -->
                <div class="col-md-6">
                    <div class="container text-center mt-3">
                    <label for="directionFilter" class="butcherman-font"><h1>Выберите направление:</h1></label>
                        <select id="directionFilter" class="form-control">
                            <option value="all">Все направления</option>
                            <option value="кроссфит">Кроссфит</option>
                            <option value="йога">Йога</option>
                            <option value="Паурлифтинг">Паурлифтинг</option>
                            <option value="Бодибилдинг">Бодибилдинг</option>
                            <!-- Добавьте другие опции направлений по желанию -->
                        </select>
                    </div>
                </div>

                <!-- Выпадающий список для фильтрации по имени тренера -->
                <div class="col-md-6">
                    <div class="container text-center mt-3">
                        <label for="trainerFilter" class="butcherman-font"><h1>Выберите тренера:</h1></label>
                        <select id="trainerFilter" class="form-control">
                            <option value="all">Все тренеры</option>
                            <option value="Авдюков Александр">Авдюков Александр</option>
                            <option value ="Фомина Анастасия">Фомина Анастасия </option>
                            <option value ="Семенов Сергей">Семенов Сергей </option>
                            <!-- Добавьте другие опции для фильтрации по имени тренера -->
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Карточки с тренерами -->
        <!-- Карточка с тренером -->
        <div class="carousel-item" data-direction="Бодибилдинг" data-trainer="Авдюков Александр">
            <a href="trainer_info.php?trainer_id=1"> <!-- Замените 1 на реальный идентификатор тренера -->
                <img class="carousel-item__img" src="да2.jpg" alt="Фото тренера">
                <div class="carousel-item__details">
                    <div class="controls">
                        <span class="fas fa-play-circle"></span>
                        <span class="fas fa-plus-circle"></span>
                    </div>
                    <h5 class="carousel-item__details--title">Авдюков Александр</h5>
                    <h6 class="carousel-item__details--subtitle">Специализация: Бодибилдинг</h6>
                </div>
            </a>
        </div>

        <div class="carousel-item" data-direction="йога" data-trainer="Фомина Анастасия">
            <a href="trainer_info.php?trainer_id=2"> <!-- Замените 1 на реальный идентификатор тренера -->
                <img class="carousel-item__img" src="да4.jpg" alt="Фото тренера">
                <div class="carousel-item__details">
                    <div class="controls">
                        <span class="fas fa-play-circle"></span>
                        <span class="fas fa-plus-circle"></span>
                    </div>
                    <h5 class="carousel-item__details--title">Фомина Анастасия</h5>
                    <h6 class="carousel-item__details--subtitle">Специализация: Йога</h6>
                </div>
            </a>
        </div>

        <div class="carousel-item" data-direction="кроссфит" data-trainer="Семенов Сергей">
            <a href="trainer_info.php?trainer_id=3"> <!-- Замените 1 на реальный идентификатор тренера -->
                <img class="carousel-item__img" src="да1.jpg" alt="Фото тренера">
                <div class="carousel-item__details">
                    <div class="controls">
                        <span class="fas fa-play-circle"></span>
                        <span class="fas fa-plus-circle"></span>
                    </div>
                    <h5 class="carousel-item__details--title">Семенов Сергей</h5>
                    <h6 class="carousel-item__details--subtitle">Специализация: Кроссфит</h6>
                </div>
            </a>
        </div>
        <div class="carousel-item" data-direction="йога" data-trainer="Фомина Анастасия">
            <a href="trainer_info.php?trainer_id=2"> <!-- Замените 1 на реальный идентификатор тренера -->
                <img class="carousel-item__img" src="2ФОТО.jpg" alt="Фото тренера">
                <div class="carousel-item__details">
                    <div class="controls">
                        <span class="fas fa-play-circle"></span>
                        <span class="fas fa-plus-circle"></span>
                    </div>
                    <h5 class="carousel-item__details--title">Фомина Анастасия</h5>
                    <h6 class="carousel-item__details--subtitle">Специализация: Йога</h6>
                </div>
            </a>
        </div>
      
    
    </div>
    <!-- Футер -->
<footer class="navbar navbar-expand-md navbar-light fixed-bottom bg-transparent">
    <div class="container">
        <span class="navbar-brand">Контактная информация:</span>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#footerNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="footerNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><span class="nav-link">Телефон: +7-XXX-XXX-XX-XX</span></li>
                <li class="nav-item"><span class="nav-link">Email: info@example.com</span></li>
                <li class="nav-item"><span class="nav-link">Адрес: г. Ваш город, ул. Ваша улица, д. Ваш дом</span></li>
            </ul>
        </div>
    </div>
</footer>


    <!-- Скрипты Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript для фильтрации -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const directionFilter = document.getElementById('directionFilter');
            const trainerFilter = document.getElementById('trainerFilter');
            const carouselItems = document.querySelectorAll('.carousel-item');

            directionFilter.addEventListener('change', filterItems);
            trainerFilter.addEventListener('change', filterItems);

            function filterItems() {
                const selectedDirection = directionFilter.value.toLowerCase(); // Приведение к нижнему регистру
                const selectedTrainer = trainerFilter.value.toLowerCase(); // Приведение к нижнему регистру

                carouselItems.forEach(function (item) {
                    const itemDirection = item.dataset.direction.toLowerCase(); // Приведение к нижнему регистру
                    const itemTrainer = item.dataset.trainer.toLowerCase(); // Приведение к нижнему регистру

                    if ((selectedDirection === 'all' || selectedDirection === itemDirection) &&
                        (selectedTrainer === 'all' || selectedTrainer === itemTrainer)) {
                        item.style.display = 'inline-block'; // Показываем карточку
                    } else {
                        item.style.display = 'none'; // Скрываем карточку
                    }
                });
            }
        });
    </script>
</body>
</html>
