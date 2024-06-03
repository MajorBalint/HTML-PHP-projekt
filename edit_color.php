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

$conn = mysqli_connect("localhost", "root", "", "mydb") or die("Kapcsolati hiba" . mysqli_error($conn));
mysqli_select_db($conn, "mydb");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = mysqli_real_escape_string($conn, $_POST["id"]);
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $metallic = mysqli_real_escape_string($conn, $_POST["metallic"]);
    $price = mysqli_real_escape_string($conn, $_POST["price"]);

    $query = "UPDATE Color SET name='$name', metallic='$metallic', price='$price' WHERE idColor='$id'";
    if (mysqli_query($conn, $query)) {
        header("Location: admin.php");
        exit;
    } else {
        echo "Hiba történt a frissítés során: " . mysqli_error($conn);
    }
}

$id = mysqli_real_escape_string($conn, $_GET["id"]);
$query = "SELECT * FROM Color WHERE idColor='$id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $name = $row["name"];
    $metallic = $row["metallic"];
    $price = $row["price"];
} else {
    echo "Nem található szín az adatbázisban.";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Szín szerkesztése</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
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
            <li class="nav-item ">
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
    <h2>Szín szerkesztése</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div class="form-group">
            <label for="name" class="btn btn-light" style="background-color: #ffffff; margin: 0 auto;">Név:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>" required>
        </div>
        <div class="form-group">
            <label for="metallic" class="btn btn-light" style="background-color: #ffffff; margin: 0 auto;">Metálfény:</label>
            <select class="form-control" id="metallic" name="metallic" required>
                <option value="0" <?php if ($metallic == 0) echo 'selected'; ?>>Nem</option>
                <option value="1" <?php if ($metallic == 1) echo 'selected'; ?>>Igen</option>
            </select>
        </div>
        <div class="form-group">
            <label for="price" class="btn btn-light" style="background-color: #ffffff; margin: 0 auto;">Ár:</label>
            <input type="number" class="form-control" id="price" name="price" value="<?php echo $price; ?>" required>
        </div>
        <button onclick="window.location.href='admin.php'" class="btn btn-primary">Vissza</button>
        <button type="submit" class="btn btn-primary">Mentés</button>
    </form>
</div>
</body>
</html>
<?php
$conn->close();
?>