<?php
session_start(); // Начинаем сессию для хранения информации об авторизации

// Проверяем, были ли отправлены данные для входа
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    
    // Получаем введенные пользователем данные
    $loginUsername = $_POST['loginUsername'];
    $loginPassword = $_POST['loginPassword'];
    
    // Защита от SQL инъекций
    $loginUsername = $conn->real_escape_string($loginUsername);
    $loginPassword = $conn->real_escape_string($loginPassword);
    
    // SQL запрос для проверки наличия пользователя в таблице "UsersDetails"
    $sql = "SELECT * FROM UsersDetails WHERE username='$loginUsername'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($loginPassword, $row['password'])) {
            // Успешная авторизация, устанавливаем сессию для пользователя
            $_SESSION['username'] = $loginUsername;
            $_SESSION['userType'] = 'user'; // Устанавливаем тип пользователя как "user"
            header("Location: welcome.php"); // Перенаправляем пользователя на страницу welcome.php
            exit();
        } else {
            echo "Неверные учетные данные";
        }
    } else {
        // Пользователь не найден в таблице "UsersDetails", проверяем в таблице "admins"
        $sql = "SELECT * FROM admins WHERE username='$loginUsername'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($loginPassword, $row['password'])) {
                // Успешная авторизация, устанавливаем сессию для администратора
                $_SESSION['username'] = $loginUsername;
                $_SESSION['userType'] = 'admin'; // Устанавливаем тип пользователя как "admin"
                header("Location: admin_page.php"); // Перенаправляем администратора на страницу admin_page.php
                exit();
            } else {
                echo "Неверные учетные данные";
            }
        } else {
            echo "Пользователь не найден";
        }
    }
    
    // Закрытие подключения
    $conn->close();
}
?>
