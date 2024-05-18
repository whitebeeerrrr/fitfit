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

// SQL запрос для получения всех сообщений пользователей и администраторов
$sqlUserChat = "SELECT * FROM user_chat ORDER BY id ASC";
$sqlAdminChat = "SELECT * FROM admin_chat ORDER BY id ASC";

$resultUserChat = $conn->query($sqlUserChat);
$resultAdminChat = $conn->query($sqlAdminChat);

// Обработка отправленного сообщения администратором
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
    $message = $_POST['message'];

    // Экранирование специальных символов в строке для безопасности SQL-запроса
    $message = $conn->real_escape_string($message);

    // SQL запрос для вставки сообщения администратора в таблицу admin_chat
    $sql = "INSERT INTO admin_chat (message) VALUES ('$message')";

    if ($conn->query($sql) === TRUE) {
        // Сообщение успешно добавлено
    } else {
        // Если возникла ошибка при вставке сообщения
        echo "Ошибка: " . $sql . "<br>" . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Чат для администратора</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('222.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: black;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background-color: rgba(0, 0, 0, 0.5) !important;
            font-family: 'Butcherman', cursive;
        }

        .navbar-brand {
            color: white !important;
            display: inline;
        }

        .navbar-nav .nav-link {
            font-family: 'Butcherman', cursive;
            color: white !important;
        }

        .navbar-nav .nav-link:hover {
            color: #D8D9E9 !important;
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
            border: 1px solid #333;
            border-radius: 10px;
            padding: 10px;
            background-color: #F5F5F5;
            margin-top: 100px;
        }

        #message {
            width: 100%;
            resize: none;
            margin-bottom: 10px;
            background-color: ;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 5px;
        }

        #chat-container * {
            margin-top: 35px;
        }

        #chat-container h1 {
            margin-top: 0;
        }

        .message {
            margin-bottom: 10px;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .user-message {
            background-color: #dff0d8;
            text-align: left;
        }

        .admin-message {
            background-color: #d9edf7;
            text-align: right;
        }

        #message-form {
            margin-top: 20px;
        }

        #message-form label {
            font-weight: bold;
        }

        #message-form textarea {
            width: 100%;
            resize: none;
            margin-bottom: 10px;
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
                    <li class="nav-item"><a class="nav-link" href="admin_page.php">Вакансии</a></li>
                    <li class="nav-item"><a class="nav-link" href="admi_page.php">Форум</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin.php">Расписание</a></li>
                    <li class="nav-item">
                        <form method="post">
                            <button type="submit" class="btn btn-danger" name="logout">Выход</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

<footer class="footer">
    <div class="container">
        <span>© 2024 Ваш Фитнес Клуб</span>
    </div>
</footer>


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
        
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Функция для отправки сообщения администратором на сервер
        $("#message-form").submit(function(event) {
            event.preventDefault(); // Предотвращаем отправку формы по умолчанию

            var message = $("#message").val(); // Получаем текст сообщения администратора

            $.post("<?php echo $_SERVER['PHP_SELF']; ?>", { message: message }, function(data) {
                // Добавляем сообщение администратора к чату
                $("#chat-container").append('<div class="message admin-message">' + message + ' :Администратор</div>');
                $("#message").val(''); // Очищаем поле ввода
                // Прокручиваем контейнер чата вниз, чтобы видеть последние сообщения
                $("#chat-container").scrollTop($("#chat-container")[0].scrollHeight);
            });
        });
    });
</script>

</body>
</html>

<?php
$conn->close();
?>
