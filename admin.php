<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "FITDatabase";

// Создание подключения
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action == 'update') {
            // Обновление записи
            $id = $_POST['id'];
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $time = $_POST['time'];
            $trainer = $_POST['trainer'];
            
            $name = $conn->real_escape_string($name);
            $phone = $conn->real_escape_string($phone);
            $email = $conn->real_escape_string($email);
            $time = $conn->real_escape_string($time);
            $trainer = $conn->real_escape_string($trainer);

            $sql = "UPDATE appointments SET name='$name', phone='$phone', email='$email', time='$time', trainer='$trainer' WHERE id=$id";
            if ($conn->query($sql) === TRUE) {
                $response['status'] = 'success';
                $response['message'] = 'Запись успешно обновлена!';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Ошибка обновления записи: ' . $conn->error;
            }
        } elseif ($action == 'delete') {
            // Удаление записи
            $id = $_POST['id'];
            $sql = "DELETE FROM appointments WHERE id=$id";
            if ($conn->query($sql) === TRUE) {
                $response['status'] = 'success';
                $response['message'] = 'Запись успешно удалена!';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Ошибка удаления записи: ' . $conn->error;
            }
        }
    }
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'load') {
    $month = isset($_GET['month']) ? intval($_GET['month']) : date('m');
    $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

    $sql = "SELECT id, DATE_FORMAT(day, '%Y-%m-%d') as day, name, phone, email, time, trainer FROM appointments WHERE MONTH(day) = $month AND YEAR(day) = $year";
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
    <title>Админ панель - Календарь занятий</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-image: url('да_out.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            color: black;
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

        main {
            padding: 80px 20px 20px;
        }

        .calendar-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
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
        }

        .calendar-day-header {
            font-weight: bold;
        }

        .calendar-day.booked {
            background-color: #90EE90;
        }

        .calendar-day:hover {
            background-color: #f0f0f0;
        }

        .calendar-day span {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .booking-info {
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 5px;
            margin-top: 5px;
            border-radius: 4px;
        }

        .action-buttons {
            margin-top: 5px;
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
                    <button type="button" class="btn btn-danger" onclick="window.location.href='index.html';">Выход</button>

                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main>
        <section id="calendar">
            <h2 class="text-center">Календарь занятий</h2>
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

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Редактировать запись</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="edit-form" method="post">
                        <input type="hidden" id="edit-id" name="id">
                        <input type="hidden" name="action" value="update">
                        <div class="form-group">
                            <label for="edit-name">Имя:</label>
                            <input type="text" class="form-control" id="edit-name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-phone">Телефон:</label>
                            <input type="tel" class="form-control" id="edit-phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-email">Email:</label>
                            <input type="email" class="form-control" id="edit-email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-time">Время:</label>
                            <input type="time" class="form-control" id="edit-time" name="time" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-trainer">Тренер:</label>
                            <select class="form-control" id="edit-trainer" name="trainer" required>
                                <option value="Иван Иванов">Иван Иванов</option>
                                <option value="Мария Смирнова">Мария Смирнова</option>
                                <option value="Анна Петрова">Анна Петрова</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Удалить запись</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Вы уверены, что хотите удалить эту запись?</p>
                    <form id="delete-form" method="post">
                        <input type="hidden" id="delete-id" name="id">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="btn btn-danger">Удалить</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    </form>
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

                fetch(`admin.php?action=load&month=${currentMonth + 1}&year=${currentYear}`)
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
                                bookingsMap[day].forEach(booking => {
                                    const bookingInfo = document.createElement('div');
                                    bookingInfo.className = 'booking-info';
                                    bookingInfo.innerHTML = `
                                        <p>Записан: ${booking.name}</p>
                                        <p>Время: ${booking.time}</p>
                                        <p>Тренер: ${booking.trainer}</p>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm btn-warning edit-btn" data-id="${booking.id}" data-name="${booking.name}" data-phone="${booking.phone}" data-email="${booking.email}" data-time="${booking.time}" data-trainer="${booking.trainer}">Редактировать</button>
                                            <button class="btn btn-sm btn-danger delete-btn" data-id="${booking.id}">Удалить</button>
                                        </div>
                                    `;
                                    dayCard.appendChild(bookingInfo);
                                });
                            }

                            calendarBody.appendChild(dayCard);
                        }

                        document.querySelectorAll('.edit-btn').forEach(button => {
                            button.addEventListener('click', function() {
                                const id = this.getAttribute('data-id');
                                const name = this.getAttribute('data-name');
                                const phone = this.getAttribute('data-phone');
                                const email = this.getAttribute('data-email');
                                const time = this.getAttribute('data-time');
                                const trainer = this.getAttribute('data-trainer');
                                
                                document.getElementById('edit-id').value = id;
                                document.getElementById('edit-name').value = name;
                                document.getElementById('edit-phone').value = phone;
                                document.getElementById('edit-email').value = email;
                                document.getElementById('edit-time').value = time;
                                document.getElementById('edit-trainer').value = trainer;
                                
                                $('#editModal').modal('show');
                            });
                        });

                        document.querySelectorAll('.delete-btn').forEach(button => {
                            button.addEventListener('click', function() {
                                const id = this.getAttribute('data-id');
                                document.getElementById('delete-id').value = id;
                                $('#deleteModal').modal('show');
                            });
                        });
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

            document.getElementById('edit-form').addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(this);
                fetch('admin.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        $('#editModal').modal('hide');
                        loadCalendar();
                    } else {
                        alert(data.message);
                    }
                });
            });

            document.getElementById('delete-form').addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(this);
                fetch('admin.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        $('#deleteModal').modal('hide');
                        loadCalendar();
                    } else {
                        alert(data.message);
                    }
                });
            });

            loadCalendar();
        });
    </script>
</body>
</html>
