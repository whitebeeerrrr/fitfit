<?php
session_start();

// Проверка аутентификации пользователя
if (!isset($_SESSION['user_id'])) {
    // Пользователь не аутентифицирован, перенаправляем его на страницу входа
    header("Location:ls.html ");
    exit;
}

// Пользователь успешно аутентифицирован, можно получать информацию о пользователе из сессии или базы данных

// Пример: получение имени пользователя из сессии
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Пользователь';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль пользователя</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <h1 class="text-center">Профиль пользователя</h1>
                        <p class="text-center">Добро пожаловать, <?php echo $user_name; ?>, на ваш профиль!</p>
                        <!-- Здесь можно выводить информацию о пользователе или другие элементы интерфейса -->
                        <div class="text-center">
                            <a href="logout.php" class="btn btn-danger">Выйти</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
