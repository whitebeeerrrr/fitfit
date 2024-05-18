<?php
// Параметры подключения к базе данных
$servername = "localhost"; // Имя сервера базы данных (обычно localhost)
$username = "root"; // Имя пользователя базы данных
$password = ""; // Пароль пользователя базы данных
$database = "FITDatabase"; // Имя базы данных

// Создание подключения
$conn = new mysqli($servername, $username, $password, $database);

// Проверка подключения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Пример запроса для вставки данных в таблицу пользователей
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    if (isset($_POST['regUsername']) && isset($_POST['regEmail']) && isset($_POST['regPassword']) && isset($_POST['userType'])) {
        $regUsername = $_POST['regUsername'];
        $regEmail = $_POST['regEmail'];
        $regPassword = $_POST['regPassword'];
        $userType = $_POST['userType']; // Получаем тип пользователя
        
        // Защита от SQL инъекций
        $regUsername = $conn->real_escape_string($regUsername);
        $regEmail = $conn->real_escape_string($regEmail);
        $regPassword = $conn->real_escape_string($regPassword);
        
        // Хеширование пароля
        $hashed_password = password_hash($regPassword, PASSWORD_DEFAULT);
        
        // Определяем, в какую таблицу добавлять данные
        $table = ($userType == 'admin') ? 'admins' : 'UsersDetails';
        
        // SQL запрос для вставки данных в соответствующую таблицу
        $sql = "INSERT INTO $table (username, email, password) VALUES ('$regUsername', '$regEmail', '$hashed_password')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Новая запись успешно добавлена";
        } else {
            echo "Ошибка: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Закрытие подключения
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Регистрация и Авторизация</title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card-header .nav-link {
      color: #000 !important; /* Черный цвет текста */
    }
    body {
            background-image: url('да_out.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            color: black;
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
    background-color: #343a40;
    color: white;
    text-align: center;
    padding: 20px 0;
}
.card {
  max-width: 400px;
  margin: 0 auto;
  margin-top: 50px;
  border-radius: 15px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.card-header {
  background-color: #007bff;
  color: white;
  border-top-left-radius: 15px;
  border-top-right-radius: 15px;
}

.card-body {
  padding: 20px;
}

.form-group {
  margin-bottom: 20px;
}

.form-control {
  border-radius: 5px;
}

.btn-primary {
  background-color: #007bff;
  border-color: #007bff;
}

.btn-primary:hover {
  background-color: #0056b3;
  border-color: #0056b3;
}

.nav-link {
  color: white !important;
}

.tab-content {
  background-color: #f8f9fa;
  padding: 20px;
  border-bottom-left-radius: 15px;
  border-bottom-right-radius: 15px;
}


  </style>
</head>
<body>
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
                    <li class="nav-item"><a class="nav-link" href="#">Личный кабинет</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Футер -->
    <footer class="footer">
        <div class="container">
            <span>© 2024 Ваш Фитнес Клуб</span>
        </div>
    </footer>
  
  <div class="container mt-5">
    <div class="card mx-auto" style="max-width: 400px;">
      <div class="card-header bg-primary text-white">
        <ul class="nav nav-tabs card-header-tabs">
          <li class="nav-item">
            <a class="nav-link active" id="register-tab" data-toggle="tab" href="#register" role="tab">Регистрация</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="login-tab" data-toggle="tab" href="#login" role="tab">Вход</a>
          </li>
        </ul>
      </div>
      <div class="card-body">
        <div class="tab-content">
          <div class="tab-pane fade show active" id="register" role="tabpanel">
            <form method="post">
              <div class="form-group">
                <label for="regUsername">Имя пользователя:</label>
                <input type="text" class="form-control" id="regUsername" name="regUsername" required>
              </div>
              <div class="form-group">
                <label for="regEmail">Email:</label>
                <input type="email" class="form-control" id="regEmail" name="regEmail" required>
              </div>
              <div class="form-group">
                <label for="regPassword">Пароль:</label>
                <input type="password" class="form-control" id="regPassword" name="regPassword" required>
              </div>
              <div class="form-group">
                <label for="userType">Тип пользователя:</label>
                <select class="form-control" id="userType" name="userType">
                  <option value="user">Пользователь</option>
                  <option value="admin">Администратор</option>
                </select>
              </div>
              <button type="submit" class="btn btn-primary btn-block" name="register">Зарегистрироваться</button>
            </form>
          </div>
          <div class="tab-pane fade" id="login" role="tabpanel">
            <!-- HTML-форма для входа -->
            <form method="post" action="login.php"> <!-- Обработчик входа -->
              <div class="form-group">
                <label for="loginUsername">Имя пользователя:</label>
                <input type="text" class="form-control" id="loginUsername" name="loginUsername" required>
              </div>
              <div class="form-group">
                <label for="loginPassword">Пароль:</label>
                <input type="password" class="form-control" id="loginPassword" name="loginPassword" required>
              </div>
              <button type="submit" class="btn btn-primary btn-block">Войти</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
