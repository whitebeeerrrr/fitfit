<?php
// Подключение к базе данных
$servername = "localhost";
$username = "root"; // Название пользователя базы данных
$password = ""; // Пароль для подключения к базе данных
$dbname = "FITDatabase";

// Создание подключения
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Обработка данных из формы и вставка их в базу данных
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверяем, было ли отправлено значение оценки
    if(isset($_POST['rating']) && !empty($_POST['rating'])){
        $rating = $_POST['rating'];

        // Вставляем данные в таблицу Ratings
        $stmt = $conn->prepare("INSERT INTO Ratings (user_id, rating) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $rating);

        // Для примера предположим, что у вас есть система аутентификации пользователей и вы знаете идентификатор пользователя
        $user_id = 1; // Пример идентификатора пользователя, это может быть получено из сеанса аутентификации или другим способом

        // Выполняем запрос
        if ($stmt->execute() === TRUE) {
            // Данные успешно добавлены
            echo "Оценка успешно отправлена.";
        } else {
            // Ошибка при выполнении запроса
            echo "Ошибка: " . $stmt->error;
        }

        $stmt->close(); // Закрываем запрос
    } else {
        // Если оценка не была отправлена
        echo "Пожалуйста, выберите оценку перед отправкой.";
    }
}

// Закрываем соединение
$conn->close();
?>
