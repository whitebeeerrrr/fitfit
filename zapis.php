<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "FITDatabase";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $day = $_POST['day'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $time = $_POST['time'];
    $trainer = $_POST['trainer'];

    $day = $conn->real_escape_string($day);
    $name = $conn->real_escape_string($name);
    $phone = $conn->real_escape_string($phone);
    $email = $conn->real_escape_string($email);
    $time = $conn->real_escape_string($time);
    $trainer = $conn->real_escape_string($trainer);

    $check_sql = "SELECT * FROM appointments WHERE day = '$day' AND time = '$time' AND trainer = '$trainer'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows == 0) {
        $sql = "INSERT INTO appointments (day, name, phone, email, time, trainer) VALUES ('$day', '$name', '$phone', '$email', '$time', '$trainer')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>document.addEventListener('DOMContentLoaded', function() { showMessage('Вы успешно записаны!'); });</script>";
        } else {
            echo "<script>document.addEventListener('DOMContentLoaded', function() { showMessage('Ошибка: " . $sql . "<br>" . $conn->error . "'); });</script>";
        }
    } else {
        echo "<script>document.addEventListener('DOMContentLoaded', function() { showMessage('Ошибка: Запись на это время уже существует.'); });</script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'load') {
    $month = isset($_GET['month']) ? intval($_GET['month']) : date('m');
    $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

    $sql = "SELECT DATE_FORMAT(day, '%Y-%m-%d') as day, name, phone, email, time, trainer FROM appointments WHERE MONTH(day) = $month AND YEAR(day) = $year";
    $result = $conn->query($sql);
    $bookings = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
    }
    echo json_encode($bookings);
    exit;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Календарь занятий фитнес-клуба</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        ::-webkit-scrollbar {
            width: 0;
        }
        body {
            background-image: url('да_out.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            color: black;
            font-family: 'Roboto', sans-serif;
        }
        .navbar {
            background-color: rgba(0, 0, 0, 0.7) !important;
            font-family: 'Butcherman', cursive;
        }
        .navbar-brand {
            color: white !important;
        }
        .navbar-nav .nav-link {
            font-family: 'Butcherman', cursive;
            color: white !important;
        }
        .navbar-nav .nav-link:hover {
            color: #D8D9E9 !important;
        }
        .footer {
            width: 100%;
            background-color: rgba(52, 58, 64, 0.8);
            color: white;
            text-align: center;
            padding: 20px 0;
            position: fixed;
            bottom: 0;
        }
        main {
            padding: 80px 20px 20px;
        }
        .calendar-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .calendar-body {
            display: flex;
            flex-wrap: wrap;
        }
        .calendar-day {
            width: calc(100% / 7);
            padding: 15px;
            box-sizing: border-box;
            text-align: left;
            border: 1px solid #ddd;
            cursor: pointer;
            min-height: 150px;
            background-color: #f9f9f9;
            transition: background-color 0.3s ease;
            position: relative;
            color: #333;
        }
        .calendar-day.booked {
            background-color: #90EE90;
            color: black;
        }
        .calendar-day:hover {
            background-color: #e0e0e0;
        }
        .calendar-day span {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .booking-info-container {
            max-height: 120px;
            overflow-y: auto;
            padding-right: 30px;
        }
        .booking-info {
            background-color: inherit;
            border: none;
            color: inherit;
            padding: 5px;
            margin-top: 5px;
            border-radius: 4px;
        }
        .scroll-buttons {
            position: absolute;
            top: 5px;
            right: 5px;
            display: flex;
            flex-direction: column;
        }
        .scroll-button {
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            margin: 2px;
            font-size: 14px;
            border-radius: 5px;
        }
        .modal-header {
            background-color: #007bff;
            color: white;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .highlight {
            position: absolute;
            top: 5px;
            right: 5px;
            background: red;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
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
                    <li class="nav-item"><a class="nav-link" href="#">Личный кабинет</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <section id="calendar">
            <div class="calendar-container">
                <div class="calendar-header">
                    <button id="prevMonth" class="btn btn-primary"><i class="fas fa-chevron-left"></i></button>
                    <h3 id="currentMonth"></h3>
                    <button id="nextMonth" class="btn btn-primary"><i class="fas fa-chevron-right"></i></button>
                </div>
                <div class="calendar-body" id="calendar-body">
                    <!-- Дни будут добавлены с помощью JavaScript -->
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <span>© 2024 Ваш Фитнес Клуб</span>
        </div>
    </footer>

    <div class="modal fade" id="appointmentModal" tabindex="-1" role="dialog" aria-labelledby="appointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appointmentModalLabel">Запись на тренировку</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="modalDay"></p>
                    <form id="booking-form" method="post" action="zapis.php">
                        <div class="form-group">
                            <label for="name">Имя:</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Телефон:</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="time">Время:</label>
                            <input type="time" class="form-control" id="time" name="time" required>
                        </div>
                        <div class="form-group">
                            <label for="trainer">Тренер:</label>
                            <select class="form-control" id="trainer" name="trainer" required>
                                <option value="Александр Авдюков">Александр Авдюков</option>
                                <option value="Фомина Анастасия">Фомина Анастасия</option>
                                <option value="Семенов Сергей">Семенов Сергей</option>
                            </select>
                        </div>
                        <input type="hidden" id="day" name="day">
                        <button type="submit" class="btn btn-primary">Записаться</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <p>Отправляя данную форму, вы соглашаетесь с <a href="#">нашей политикой обработки персональных данных</a>.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel">Сообщение</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="messageContent">
                    <!-- Сообщение будет вставлено с помощью JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const calendarBody = document.getElementById('calendar-body');
            const currentMonthSpan = document.getElementById('currentMonth');
            let currentMonth = new Date().getMonth();
            let currentYear = new Date().getFullYear();

            function loadCalendar() {
                const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
                const firstDay = new Date(currentYear, currentMonth, 1).getDay();
                const monthNames = ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"];
                currentMonthSpan.textContent = `${monthNames[currentMonth]} ${currentYear}`;

                fetch(`zapis.php?action=load&month=${currentMonth + 1}&year=${currentYear}`)
                    .then(response => response.json())
                    .then(data => {
                        const bookingsMap = {};
                        data.forEach(booking => {
                            const day = new Date(booking.day).getDate();
                            if (!bookingsMap[day]) {
                                bookingsMap[day] = [];
                            }
                            bookingsMap[day].push(booking);
                        });

                        calendarBody.innerHTML = '';
                        for (let i = 0; i < firstDay; i++) {
                            const emptyCell = document.createElement('div');
                            emptyCell.className = 'calendar-day';
                            calendarBody.appendChild(emptyCell);
                        }

                        for (let day = 1; day <= daysInMonth; day++) {
                            const dayCard = document.createElement('div');
                            dayCard.className = 'calendar-day';
                            dayCard.setAttribute('data-day', day);
                            dayCard.innerHTML = `<span>${day}</span>`;

                            if (bookingsMap[day]) {
                                dayCard.classList.add("booked");
                                const bookingInfoContainer = document.createElement('div');
                                bookingInfoContainer.className = 'booking-info-container';
                                const scrollButtons = document.createElement('div');
                                scrollButtons.className = 'scroll-buttons';
                                let bookingIndex = 0;

                                function updateBookingInfo() {
                                    bookingInfoContainer.innerHTML = '';
                                    const booking = bookingsMap[day][bookingIndex];
                                    const bookingInfo = document.createElement('div');
                                    bookingInfo.className = 'booking-info';
                                    bookingInfo.innerHTML = `<p>Записан: ${booking.name}</p><p>Время: ${booking.time}</p><p>Тренер: ${booking.trainer}</p>`;
                                    bookingInfoContainer.appendChild(bookingInfo);
                                }

                                const prevButton = document.createElement('button');
                                prevButton.className = 'scroll-button';
                                prevButton.innerHTML = '&laquo;';
                                prevButton.addEventListener('click', (event) => {
                                    event.stopPropagation();
                                    if (bookingIndex > 0) {
                                        bookingIndex--;
                                        updateBookingInfo();
                                    }
                                });

                                const nextButton = document.createElement('button');
                                nextButton.className = 'scroll-button';
                                nextButton.innerHTML = '&raquo;';
                                nextButton.addEventListener('click', (event) => {
                                    event.stopPropagation();
                                    if (bookingIndex < bookingsMap[day].length - 1) {
                                        bookingIndex++;
                                        updateBookingInfo();
                                    }
                                });

                                scrollButtons.appendChild(prevButton);
                                scrollButtons.appendChild(nextButton);
                                dayCard.appendChild(bookingInfoContainer);
                                dayCard.appendChild(scrollButtons);
                                updateBookingInfo();
                            }

                            dayCard.addEventListener('click', function() {
                                document.querySelector('#modalDay').textContent = `Вы выбрали день: ${currentYear}-${currentMonth + 1}-${day}`;
                                document.getElementById('day').value = `${currentYear}-${currentMonth + 1}-${day}`;
                                $('#appointmentModal').modal('show');
                            });

                            calendarBody.appendChild(dayCard);
                        }
                    });
            }

            document.getElementById('prevMonth').addEventListener('click', function() {
                if (currentMonth === 0) {
                    currentMonth = 11;
                    currentYear--;
                } else {
                    currentMonth--;
                }
                loadCalendar();
            });

            document.getElementById('nextMonth').addEventListener('click', function() {
                if (currentMonth === 11) {
                    currentMonth = 0;
                    currentYear++;
                } else {
                    currentMonth++;
                }
                loadCalendar();
            });

            loadCalendar();

            function showMessage(message) {
                document.getElementById('messageContent').textContent = message;
                $('#messageModal').modal('show');
            }
        });
    </script>
</body>
</html>
