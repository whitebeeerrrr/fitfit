<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "FITDatabase";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Выход из сессии
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.html");
    exit;
}

// Обработка избранного и удаления
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['favorite'])) {
        $id = $_POST['favorite'];
        $sql = "UPDATE Applications SET favorite = 1 WHERE id = $id";
        if ($conn->query($sql) !== TRUE) {
            echo json_encode(array("success" => false, "message" => "Ошибка: " . $conn->error));
            exit;
        }
    } elseif (isset($_POST['removeFavorite'])) {
        $id = $_POST['removeFavorite'];
        $sql = "UPDATE Applications SET favorite = 0 WHERE id = $id";
        if ($conn->query($sql) !== TRUE) {
            echo json_encode(array("success" => false, "message" => "Ошибка: " . $conn->error));
            exit;
        }
    }
}

$sql = "SELECT id, vacancyTitle, fullName, email, phone, resume, applicationDate, favorite FROM Applications";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
             ::-webkit-scrollbar {
  width: 0; /* Для WebKit (Chrome, Safari, Edge) */
}
        body {
            background-image: url('да_out.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            color: black;
            font-family: Arial, sans-serif;
            overflow-x: hidden;
        }

        .card {
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 0.75rem;
        }

        .card-text {
            font-size: 1rem;
        }

        .btn {
            margin-top: 10px;
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

        .scrollable {
            max-height: 80vh;
            overflow-y: auto;
            margin-bottom: 50px;
        }

        .highlight {
            background-color: #c3e6cb !important;
        }

        .centered-cards {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }

        .form-card {
            max-width: 500px;
            margin: 0 auto;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .centered-cards {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    margin-top: 100px; /* Пример значения отступа */
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

    <div class="container mt-5">
        <div class="centered-cards">
            <div class="form-card">
                <h1 class="text-center">Заявки на вакансии</h1>
            </div>
        </div>

        <div class="scrollable">
            <div class="row">
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<div class='col-md-4'>";
                        echo "<div class='card'>";
                        echo "<div class='card-body'>";
                        echo "<h5 class='card-title'>" . $row["vacancyTitle"] . "</h5>";
                        echo "<p class='card-text'><strong>ID:</strong> " . $row["id"] . "</p>";
                        echo "<p class='card-text'><strong>ФИО:</strong> " . $row["fullName"] . "</p>";
                        echo "<p class='card-text'><strong>Email:</strong> " . $row["email"] . "</p>";
                        echo "<p class='card-text'><strong>Телефон:</strong> " . $row["phone"] . "</p>";
                        echo "<p class='card-text'><strong>Соц.сети:</strong> " . $row["resume"] . "</p>";
                        echo "<p class='card-text'><strong>Дата подачи:</strong> " . $row["applicationDate"] . "</p>";
                        echo "<form method='post'>";
                        if ($row["favorite"] == 1) {
                            echo "<button type='submit' class='btn btn-success favoriteBtn' name='removeFavorite' value='" . $row["id"] . "'>Убрать из избранного</button>";
                        } else {
                            echo "<button type='submit' class='btn btn-primary favoriteBtn' name='favorite' value='" . $row["id"] . "'>Избранное</button>";
                        }
                        echo "</form>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>Нет заявок на вакансии.</p>";
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS и jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <footer class="footer">
        <div class="container">
            <span>© 2024 Ваш Фитнес Клуб</span>
        </div>
    </footer>

    <script>
    // Добавляем обработчик событий для кнопок "Избранное" и "Убрать из избранного"
    document.querySelectorAll('.favoriteBtn').forEach(btn => {
        btn.addEventListener('click', function(event) {
            event.preventDefault();
            const id = this.value;
            const action = this.name;
            fetch('admin_page.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: action + '=' + id
            })
            .then(response => {
                if (response.ok) {
                    if (action === 'favorite') {
                        this.closest('.card').classList.add('highlight');
                        this.name = 'removeFavorite';
                        this.textContent = 'Убрать из избранного';
                    } else {
                        this.closest('.card').classList.remove('highlight');
                        this.name = 'favorite';
                        this.textContent = 'Избранное';
                    }
                } else {
                    console.error('Ошибка:', response.statusText);
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
            });
        });
    });
    </script>

</body>
</html>
