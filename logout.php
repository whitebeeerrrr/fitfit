<?php
session_start();  // Запускаем сессию
if (isset($_POST['logout'])) {
    session_destroy();  // Уничтожаем сессию пользователя
    header('Location: index.html');  // Перенаправляем на главную страницу
    exit();
}
?>
