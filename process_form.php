<?php
if(isset($_POST['submit'])) {
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    echo "Данные получены:<br>";
    echo "ФИО: $fullName<br>";
    echo "Email: $email<br>";
    echo "Номер телефона: $phone<br>";
}
?>
