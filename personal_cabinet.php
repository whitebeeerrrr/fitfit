<?php
// Начало сеанса PHP
session_start();

// Параметры подключения к базе данных
$servername = "localhost"; // Имя сервера базы данных
$username = "root"; // Имя пользователя базы данных
$password = ""; // Пароль пользователя базы данных
$dbname = "FITDatabase"; // Имя базы данных

// Создание соединения
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}

$message = ""; // Инициализация сообщения о регистрации или входе

// Обработка регистрации или авторизации
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        $email = $_POST['registerEmail'];
        $password = $_POST['registerPassword'];

        // Защита от SQL-инъекций
        $email = mysqli_real_escape_string($conn, $email);
        $password = mysqli_real_escape_string($conn, $password);

        // Проверка наличия пользователя с таким же email
        $check_sql = "SELECT * FROM Users WHERE email='$email'";
        $check_result = $conn->query($check_sql);
        if ($check_result->num_rows > 0) {
            $message = "error";
        } else {
            // Хэширование пароля (реальная реализация должна быть более сложной)
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // SQL запрос на добавление пользователя в базу данных
            $insert_sql = "INSERT INTO Users (email, password) VALUES ('$email', '$hashed_password')";

            if ($conn->query($insert_sql) === TRUE) {
                $message = "success";
            } else {
                $message = "error";
            }
        }
    } elseif (isset($_POST['login'])) {
        $email = $_POST['loginEmail'];
        $password = $_POST['loginPassword'];

        // Проверка на пустые поля
        if (empty($email) || empty($password)) {
            $message = "empty";
        } else {
            // Защита от SQL-инъекций
            $email = mysqli_real_escape_string($conn, $email);

            // SQL запрос для получения пользователя с указанным email
            $sql = "SELECT * FROM Users WHERE email='$email'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Пользователь найден
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    // Пароль верный - пользователь аутентифицирован
                    $message = "success";
                    // Сохраняем его идентификатор в сессии
                    $_SESSION['user_id'] = $row['id'];

                    // Проверяем роль пользователя
                    $user_role = $row['role'];
                    if($user_role == 'admin') {
                        // Пользователь администратор, перенаправляем на страницу для админов
                        header("Location: admin_page.php");
                        exit;
                    } else {
                        // Пользователь не администратор, перенаправляем на страницу ls.html
                        header("Location: ls.html");
                        exit;
                    }
                } else {
                    $message = "error";
                }
            } else {
                $message = "error";
            }
        }
    }
}

// Проверка, авторизован ли пользователь
if(isset($_SESSION['user_id'])){
    // Получаем информацию о пользователе
    $user_id = $_SESSION['user_id'];
    $user_query = "SELECT * FROM Users WHERE id='$user_id'";
    $user_result = $conn->query($user_query);

    if ($user_result->num_rows > 0) {
        $user_row = $user_result->fetch_assoc();
        // Проверяем роль пользователя
        $user_role = $user_row['role'];
        if($user_role == 'admin') {
            // Пользователь администратор, перенаправляем на страницу для админов
            header("Location: admin_page.php");
            exit;
        } else {
            // Пользователь не администратор, перенаправляем на страницу ls.html
            header("Location: ls.html");
            exit;
        }
    }
}

// Закрытие соединения
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
     body {
    background-image: url('21.png'); /* Путь к вашей картинке */
    background-repeat: no-repeat; /* Не дублировать фоновую картинку */
    background-size: cover; /* Масштабировать фоновую картинку по размеру экрана */
    color: black; /* Черный текст по умолчанию */
}

.registration-card {
    max-width: 400px;
    margin: 0 auto;
    margin-top: 100px;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    background-color: rgba(255, 255, 255, 0.7); /* Желтоватый фон с небольшой прозрачностью */
}


      .navbar {
        background-color: rgba(0, 0, 0, 0.5) !important; /* Цвет фона навигационного меню с полупрозрачностью */
        font-family: 'Butcherman', cursive; /* Применение шрифта Butcherman */
      }

      .navbar-brand {
        color: white !important; /* Белый цвет текста для названия */
        display: inline; /* Размещаем элемент в строку */
      }

      .navbar-nav .nav-link {
        font-family: 'Butcherman', cursive; /* Применение шрифта Butcherman */
        color: white !important; /* Белый цвет текста для элементов навигации */
      }

      .navbar-nav .nav-link:hover {
        color:#D8D9E9  !important; /* Изменение цвета текста при наведении */
      }

      .footer {
    position: fixed;
    bottom: 0;
    width: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Цвет фона с полупрозрачностью */
    color: white; /* Белый цвет текста */
    text-align: center;
    padding: 20px 0;
}


      .registration-card {
        max-width: 400px;
        margin: 0 auto;
        margin-top: 100px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 10px;
      }

      .border-success {
        border-color: #28a745 !important; /* Цвет рамки при успешной регистрации или входе */
      }

      .border-danger {
        border-color: #dc3545 !important; /* Цвет рамки при ошибке регистрации или входа */
      }

      .hide {
        display: none;
      }

    </style>
</head>
<body>
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
                    
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
            <div class="registration-card <?php echo $message === 'success' ? 'border-success' : ''; ?>">

                    <h2 class="text-center mb-4"><?php echo $message == 'success' ? 'Регистрация' : 'Авторизация'; ?></h2>
                    <?php if($message == 'error'): ?>
                        <div class="alert alert-danger" role="alert">
                            Ошибка: неверный email или пароль!
                        </div>
                    <?php elseif($message == 'empty'): ?>
                        <div class="alert alert-danger" role="alert">
                            Ошибка: введите логин и пароль!
                        </div>
                    <?php endif; ?>
                    <div id="forms">
                        <form method="post" <?php echo $message == 'success' ? 'class="hide"' : ''; ?>>
                            <div class="form-group">
                                <label for="loginEmail">Email адрес</label>
                                <input type="email" class="form-control" id="loginEmail" aria-describedby="emailHelp" placeholder="Введите email" name="loginEmail">
                            </div>
                            <div class="form-group">
                                <label for="loginPassword">Пароль</label>
                                <input type="password" class="form-control" id="loginPassword" placeholder="Пароль" name="loginPassword">
                            </div>
                            <button type="submit" class="btn btn-primary btn-block" name="login">Войти</button>
                        </form>
                        <form method="post" <?php echo $message == 'success' ? '' : 'class="hide"'; ?>>
                            <div class="form-group">
                                <label for="registerEmail">Email адрес</label>
                                <input type="email" class="form-control" id="registerEmail" aria-describedby="emailHelp" placeholder="Введите email" name="registerEmail">
                            </div>
                            <div class="form-group">
                                <label for="registerPassword">Пароль</label>
                                <input type="password" class="form-control" id="registerPassword" placeholder="Пароль" name="registerPassword">
                            </div>
                            <button type="submit" class="btn btn-primary btn-block" name="register">Зарегистрироваться</button>
                        </form>
                    </div>
                    <p class="text-center mt-3">
                        <a href="#" onclick="toggleForms()"><?php echo $message == 'success' ? 'Уже есть аккаунт? Войдите!' : 'Нет аккаунта? Зарегистрируйтесь!'; ?></a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <span>© 2024 Ваш Фитнес Клуб</span>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
      function toggleForms() {
        $('#forms form').toggleClass('hide');
        var text = $('#forms form:first').hasClass('hide') ? 'Регистрация' : 'Авторизация';
        $('h2').text(text);
        var linkText = $('#forms form:first').hasClass('hide') ? 'Нет аккаунта? Зарегистрируйтесь!' : 'Уже есть аккаунт? Войдите!';
        $('p a').text(linkText);
      }
    </script>
</body>
</html>
