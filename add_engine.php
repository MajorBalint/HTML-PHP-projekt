<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['status'] != 2) {
    echo "Hiba, Önnek nincs jogosultsága megtekinteni az oldalt!";
    echo '<br><a href="admin.php">Vissza</a>';
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $size = $_POST['size'];
    $consumption = $_POST['consumption'];
    $horsepower = $_POST['horsepower'];
    $range = $_POST['range'];
    $price = $_POST['price'];

    $conn = mysqli_connect("localhost", "root", "", "mydb") or die("Kapcsolati hiba" . mysqli_error($conn));
    mysqli_select_db($conn, "mydb");

    if (!$conn) {
        echo '<br><a href="admin.php">Vissza</a>';
        die("Kapcsolódási hiba: " . mysqli_connect_error());
    }

    $type = mysqli_real_escape_string($conn, $type);
    $size = mysqli_real_escape_string($conn, $size);
    $consumption = mysqli_real_escape_string($conn, $consumption);
    $horsepower = mysqli_real_escape_string($conn, $horsepower);
    $range = mysqli_real_escape_string($conn, $range);
    $price = mysqli_real_escape_string($conn, $price);

    $sql = "INSERT INTO Engine (`type`, `size`, `consumption`, `horsepower`, `driving_range`, `price`) VALUES ('$type', '$size', '$consumption', '$horsepower', '$range', '$price')";

    if (mysqli_query($conn, $sql)) {
        echo "A motor sikeresen hozzáadva.";
        mysqli_close($conn);
        header("Location: admin.php");
        exit;
    } else {
        echo "Hiba történt a motor hozzáadásakor: " . mysqli_error($conn);
        echo '<br><a href="admin.php">Vissza</a>';
    }

    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Motor hozzáadása</title>
    <meta charset="UTF-8">
    <title>Autók, extrák, színek, motorok módosítása</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">

    <?php
    if (isset($_SESSION['theme'])) {
        $theme = $_SESSION['theme'];
    } else {
        $theme = 'light';
    }
    ?>
    <style>
        <?php if ($theme === 'light'): ?>
        body {
            background: url("https://i.pinimg.com/originals/ee/27/d0/ee27d0f1c93e3295662df76ce9192021.jpg") no-repeat center center fixed;
            background-size: cover;
        }

        .navbar {
            background-color: #ff6f00;
            color: #000000;
        }
        <?php elseif ($theme === 'dark'): ?>
        body {
            background: url("https://image.cnbcfm.com/api/v1/image/106429544-1583520556948gemera_exterior_5_high.jpg?v=1583520680") no-repeat center center fixed;
            background-size: cover;
        }

        .navbar {
            background-color: #555555;
            color: #ffffff;
        }
        <?php endif; ?>

        .jumbotron {
            background-image: url(<?php echo $background; ?>);
            background-size: cover;
            background-position: center;
            height: 500px;
        }
    </style>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item ">
                <a class="nav-link" href="index.php">Főoldal</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="configurator.php">Konfigurátor</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="user_role.php">Felhasználói jogkör beállítása</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin.php">Autók, extrák, színek, motorok módosítása</a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="login.php">Bejelentkezés</a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="registration.php">Regisztráció</a>
            </li>
        </ul>
    </nav>
</header>

<div class="container">
    <h1>Motor hozzáadása</h1>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <input type="text" class="form-control" id="type" name="type" placeholder="Típus" required>
        </div>
        <div class="form-group">
            <input type="number" class="form-control" id="size" name="size" placeholder="Méret">
        </div>
        <div class="form-group">
            <input type="number" class="form-control" id="consumption" name="consumption" placeholder="Fogyasztás">
        </div>
        <div class="form-group">
            <input type="number" class="form-control" id="horsepower" name="horsepower" placeholder="Teljesítmény" required>
        </div>
        <div class="form-group">
            <input type="number" class="form-control" id="range" name="range" placeholder="Hatótáv">
        </div>
        <div class="form-group">
            <input type="number" class="form-control" id="price" name="price" placeholder="Ár">
        </div>
        <button onclick="window.location.href='admin.php'" class="btn btn-primary">Vissza</button>
        <button type="submit" class="btn btn-primary">Hozzáadás</button>
    </form>
</div>
</body>
</html>