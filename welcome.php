<?php
session_start(); // Начало сессии

// Подключение к базе данных
$servername = "localhost";
$username = "root";
$password = "";
$database = "FITDatabase";

// Создание подключения
$conn = new mysqli($servername, $username, $password, $database);

// Проверка подключения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Обработка отправленного сообщения пользователя
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
    $message = $_POST['message'];

    // Экранирование специальных символов в строке для безопасности SQL-запроса
    $message = $conn->real_escape_string($message);

    // SQL запрос для вставки сообщения пользователя в таблицу user_chat
    $sql = "INSERT INTO user_chat (message) VALUES ('$message')";

    if ($conn->query($sql) === TRUE) {
        // Сообщение пользователя успешно добавлено
    } else {
        // Если возникла ошибка при вставке сообщения пользователя
        echo "Ошибка: " . $sql . "<br>" . $conn->error;
    }
}

// SQL запрос для получения всех сообщений пользователей и администраторов
$sqlUserChat = "SELECT * FROM user_chat ORDER BY id ASC";
$sqlAdminChat = "SELECT * FROM admin_chat ORDER BY id ASC";

$resultUserChat = $conn->query($sqlUserChat);
$resultAdminChat = $conn->query($sqlAdminChat);
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
            background-image: url('222.png');
            background-size: cover; /* Масштабировать изображение, чтобы оно занимало весь экран */
            background-position: center; /* Расположить изображение по центру */
            background-repeat: no-repeat; /* Не повторять изображение */
            color: black;
            font-family: Arial, sans-serif;
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
            color: #D8D9E9 !important; /* Изменение цвета текста при наведении */
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 20px 0;
        }

        #chat-container {
            max-height: calc(100vh - 250px);
            overflow-y: auto;
            border: 1px solid #333; /* Более темный цвет для границы */
            border-radius: 10px; /* Округление углов контейнера чата */
            padding: 10px;
            background-color: #F5F5F5;
            margin-top: 100px;
        }

        .message {
            margin-bottom: 10px;
            padding: 5px 10px; /* Добавляем отступы вокруг сообщений */
            border-radius: 5px; /* Закругляем углы сообщений */
        }

        .user-message {
            background-color: #dff0d8; /* Зеленый цвет фона для сообщений пользователя */
            text-align: left;
        }

        .admin-message {
            background-color: #d9edf7; /* Голубой цвет фона для сообщений администратора */
            text-align: right;
        }

        /* Оформление формы отправки сообщения */
        #message-form {
            margin-top: 20px;
        }

        #message-form label {
            font-weight: bold; /* Жирный шрифт для надписи над текстовым полем */
        }

        #message-form textarea {
            width: 100%;
            resize: none; /* Запрещаем изменение размера текстового поля пользователем */
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <!-- Навигационная панель -->
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
                    <li class="nav-item"><a class="nav-link" href="#">Личный кабинет</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Основной контент страницы -->
    <div class="container mt-5">
        <div id="chat-container">
            <h1 class="text-center mb-4">Чат с поддержкой</h1>

            <?php
            // Отображение всех сообщений пользователей
            if ($resultUserChat->num_rows > 0) {
                while($row = $resultUserChat->fetch_assoc()) {
                    echo '<div class="message user-message">Пользователь: ' . $row['message'] . '</div>';
                }
            }

            // Отображение всех сообщений администраторов
            if ($resultAdminChat->num_rows > 0) {
                while($row = $resultAdminChat->fetch_assoc()) {
                    echo '<div class="message admin-message">' . $row['message'] . ' :Администратор</div>';
                }
            }
            ?>
        </div>

        <!-- Форма отправки сообщения -->
        <form id="message-form" method="post">
            <label for="message">Введите ваше сообщение:</label><br>
            <textarea id="message" name="message" rows="4" required></textarea><br>
            <button type="submit" class="btn btn-primary">Отправить</button>
            <a href="ls.html" class="btn btn-primary">Вернуться на главную страницу</a>

        </form>
    </div>
    

    <!-- Футер -->
    <footer class="footer">
        <div class="container">
            <span>&copy; 2024 Ваш Фитнес Клуб</span>
        </div>
    </footer>

    <!-- Подключение скриптов -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Обработчик формы для отправки сообщения
            $("#message-form").submit(function(event) {
                // Ничего не делаем, если поле ввода пустое
                if ($("#message").val().trim() === '') {
                    event.preventDefault();
                    return false;
                }
                return true;
            });
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
