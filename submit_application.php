<?php
// Подключение к базе данных
$servername = "localhost"; // Имя сервера
$username = "root"; // Имя пользователя
$password = ""; // Пароль
$dbname = "FITDatabase"; // Имя базы данных

// Создание подключения
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Переменная для хранения уведомления
$notification = '';

// Обработка данных из формы и вставка их в базу данных
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vacancyTitle = $_POST['vacancyTitle'];
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $resume = $_POST['resume'];
    $specialization = $_POST['specialization']; // Получаем выбранную специализацию из формы
    
    // Текущая дата
    $hireDate = date("Y-m-d");

    // Подготовленный запрос для вставки данных в таблицу Applications
    $stmt = $conn->prepare("INSERT INTO Applications (vacancyTitle, fullName, email, phone, resume) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $vacancyTitle, $fullName, $email, $phone, $resume);

    // Выполнение подготовленного запроса
    if ($stmt->execute() === TRUE) {
        $notification = '<div class="alert alert-success" role="alert">"Ваша заявка успешно отправлена. Наша команда свяжется с вами в ближайшее время."</div>';
        
        // Получение ID последней вставленной записи
        $applicationId = $stmt->insert_id;

        // Проверка типа вакансии и вставка данных в соответствующую таблицу
        switch ($vacancyTitle) {
            case "fitness_trainer":
                $tables = ["Trainers"]; // Массив таблиц, в которые нужно вставить данные
                break;
            case "aqua_aerobics_instructor":
                $tables = ["AquaAerobicsInstructors"];
                break;
            case "personal_trainer":
                $tables = ["PersonalTrainers"];
                break;
            case "massage_therapist":
                $tables = ["MassageTherapists"];
                break;
            default:
                $tables = [];
                break;
        }

        // Вставка данных в каждую таблицу
        foreach ($tables as $table) {
            $stmt2 = $conn->prepare("INSERT INTO $table (id, fullName, email, phone, specialization, hireDate) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt2->bind_param("isssss", $applicationId, $fullName, $email, $phone, $specialization, $hireDate);
            
            // Выполнение подготовленного запроса
            if ($stmt2->execute() !== TRUE) {
                $notification .= '<div class="alert alert-danger" role="alert">Ошибка при добавлении данных в таблицу ' . $table . ': ' . $stmt2->error . '</div>';
            }

            $stmt2->close(); // Закрытие подготовленного запроса
        }

        // Обработка загруженного файла
        if(isset($_FILES['resumeFile']) && $_FILES['resumeFile']['error'] === UPLOAD_ERR_OK) {
            $tempFile = $_FILES['resumeFile']['tmp_name'];
            $fileName = $_FILES['resumeFile']['name'];
            $targetFile = 'uploads/' . $fileName;

            // Перемещаем загруженный файл в целевую директорию
            if(move_uploaded_file($tempFile, $targetFile)) {
                // Сохраняем информацию о файле в базе данных
                $stmt3 = $conn->prepare("INSERT INTO uploaded_files (application_id, file_name, file_path) VALUES (?, ?, ?)");
                $stmt3->bind_param("iss", $applicationId, $fileName, $targetFile);
                $stmt3->execute();
                $stmt3->close();
                
                $notification .= '<div class="alert alert-success" role="alert">Файл успешно загружен и сохранен в базе данных.</div>';
            } else {
                $notification .= '<div class="alert alert-danger" role="alert">Ошибка при загрузке файла.</div>';
            }
        }
    } else {
        $notification = '<div class="alert alert-danger" role="alert">Ошибка при отправке заявки: ' . $stmt->error . '</div>';
    }

    $stmt->close(); // Закрытие подготовленного запроса
}

// Закрытие соединения
$conn->close();
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Уведомление</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
      body {
    background-image: url("жэ.jpg");
    background-size: cover; /* Растягивает изображение на весь экран */
}
.card {
    background-color: rgba(255, 255, 255, 0.91); /* Прозрачный белый фон */
}

    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
               <h1> Уведомление</h1>
            </div>
            <div class="card-body">
                <!-- Вывод уведомления -->
                <?php echo $notification; ?>
                <!-- Кнопка "Вернуться назад" -->
                <button class="btn btn-primary" onclick="history.go(-1);">Вернуться назад</button>
            </div>
        </div>
        <!-- Карточка для оценки работы -->
        <div class="card mt-3">
            <div class="card-body">
                <p class="card-text">Оцените нашу работу:</p>
                <div class="btn-group" role="group" aria-label="Basic example" id="ratingButtons">
                    <?php
                        // Вывод кнопок с шкалой от 1 до 10
                        for ($i = 1; $i <= 10; $i++) {
                            echo '<button type="button" class="btn btn-secondary" onclick="submitRating(' . $i . ')">' . $i . '</button>';
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS и jQuery (необходимы для работы некоторых компонентов Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Функция для отправки оценки на сервер
        function submitRating(rating) {
            // Создаем объект XMLHttpRequest
            var xhr = new XMLHttpRequest();

            // Устанавливаем метод и адрес URL для отправки данных
            xhr.open("POST", "submit_rating.php", true);

            // Устанавливаем заголовок Content-Type
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // Формируем данные для отправки
            var params = "rating=" + rating;

            // Отправляем запрос
            xhr.send(params);

            // Обработчик события изменения состояния запроса
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    // Если запрос успешно выполнен, изменяем стиль кнопки на зеленый
                    if (xhr.status === 200) {
                        document.getElementById("ratingButtons").innerHTML = '<button type="button" class="btn btn-success">Оценка отправлена</button>';
                    }
                }
            };
        }
    </script>
</body>
</html>
