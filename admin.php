<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['status'] != 2) {
    echo "Hiba, Önnek nincs jogosultsága megtekinteni az oldalt!";
    echo '<br><a href="index.php">Vissza</a>';
    exit;
}

if (isset($_SESSION['theme'])) {
    $theme = $_SESSION['theme'];
} else {
    $theme = 'light';
}

$conn = mysqli_connect("localhost", "root", "", "mydb") or die("Kapcsolati hiba" . mysqli_error($conn));
mysqli_select_db($conn, "mydb");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Autók, extrák, színek, motorok módosítása</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">

    <?php
    $conn = mysqli_connect("localhost", "root", "", "mydb") or die("Kapcsolati hiba" . mysqli_error($conn));
    mysqli_select_db($conn, "mydb");

    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = $_POST['password'];

        $query = "SELECT idUsers, user_name, password FROM Users WHERE user_name='$username'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

            if (password_verify($password, $row['password'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['idUsers'] = $row['idUsers'];
                $_SESSION['status'] = $row['status'];

                header("Location: index.php");
                exit;
            } else {
                $error_message = "Hibás jelszó!";
            }
        } else {
            $error_message = "Hibás felhasználónév!";
        }
    }

    if (isset($_POST['logout'])) {
        session_unset();
        session_destroy();
    }

    if (isset($_SESSION['theme'])) {
        $theme = $_SESSION['theme'];
    } else {
        $theme = 'light';
    }
    ?>
    <style>
        <?php if ($theme === 'light'): ?>
        table {
        width: 100%;
        border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        tr:first-child {
            background-color: #ff6f00;;
            color: #ffffff;
        }

        tr:not(:first-child) {
            background-color: #ffffff;
        }
        body {
            background: url("https://i.pinimg.com/originals/ee/27/d0/ee27d0f1c93e3295662df76ce9192021.jpg") no-repeat center center fixed;
            background-size: cover;
        }

        .navbar {
            background-color: #ff6f00;
            color: #000000;
        }
        <?php elseif ($theme === 'dark'): ?>
        table {
        width: 100%;
        border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        tr:first-child {
            background-color: #333333;
            color: #ffffff;
        }

        tr:not(:first-child) {
            background-color: #ffffff;
        }
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
            <li class="nav-item active">
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
<form method="GET" action="" style="text-align: right;">
    <input type="text" name="search" placeholder="Keresés...">
    <input type="submit" value="Keresés">
</form>

<?php
$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT * FROM Cars WHERE (brand LIKE '%$search%' OR model LIKE '%$search%') AND User_idUsers IS NULL ORDER BY brand ASC";
$result = mysqli_query($conn, $sql);

echo "<h2>Autók</h2>";

if (mysqli_num_rows($result) > 0) {
    echo "<table>";
    echo "<tr><th>Márka&nbsp;&nbsp;&nbsp;</th><th>Modell&nbsp;&nbsp;&nbsp;</th><th>Gyártási év&nbsp;&nbsp;&nbsp;</th><th>Fogysztói ár&nbsp;&nbsp;&nbsp;</th><th>Műveletek</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>".$row['brand']."</td>&nbsp;&nbsp;&nbsp;";
        echo "<td>".$row['model']."</td>&nbsp;&nbsp;&nbsp;";
        echo "<td>".$row['model_year']."</td>&nbsp;&nbsp;&nbsp;";
        echo "<td>".$row['price']."</td>&nbsp;&nbsp;&nbsp;";
        echo "<td><a href='edit_car.php?id=".$row['idCars']."'>Szerkesztés</a> | <a href='delete_car.php?id=".$row['idCars']."'>Törlés</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Nincs autó a listában.";
}
echo "<a href='add_car.php'>Új autó felvétele</a>";
echo "<br>"; echo "<br>";

$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT * FROM Extra WHERE type LIKE '%$search%' OR description LIKE '%$search%'";
$result = mysqli_query($conn, $sql);

echo "<h2>Extrák</h2>";

if (mysqli_num_rows($result) > 0) {
    echo "<table>";
    echo "<tr><th>Típus&nbsp;&nbsp;&nbsp;</th><th>Ár&nbsp;&nbsp;&nbsp;</th><th>Tömeg&nbsp;&nbsp;&nbsp;</th><th>Leírás&nbsp;&nbsp;&nbsp;</th><th>Műveletek</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>".$row['type']."</td>&nbsp;&nbsp;&nbsp;";
        echo "<td>".$row['price']."</td>&nbsp;&nbsp;&nbsp;";
        echo "<td>".$row['weight']."</td>&nbsp;&nbsp;&nbsp;";
        echo "<td>".$row['description']."</td>&nbsp;&nbsp;&nbsp;";
        echo "<td><a href='edit_extra.php?id=".$row['idExtra']."'>Szerkesztés</a> | <a href='delete_extra.php?id=".$row['idExtra']."'>Törlés</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Nincs extra a listában.";
}
echo "<a href='add_extra.php'>Új extra felvétele</a>";
echo "<br>"; echo "<br>";

$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT * FROM Color WHERE name LIKE '%$search%'";
$result = mysqli_query($conn, $sql);

echo "<h2>Színek</h2>";

if (mysqli_num_rows($result) > 0) {
    echo "<table>";
    echo "<tr><th>Szín&nbsp;&nbsp;&nbsp;</th><th>Metál&nbsp;&nbsp;&nbsp;</th><th>Ár&nbsp;&nbsp;&nbsp;</th><th>Műveletek</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>".$row['name']."</td>&nbsp;&nbsp;&nbsp;";
        echo "<td>".($row['metallic'] == 1 ? "Metál" : "Nem metál")."</td>&nbsp;&nbsp;&nbsp;";
        echo "<td>".$row['price']."</td>&nbsp;&nbsp;&nbsp;";
        echo "<td><a href='edit_color.php?id=".$row['idColor']."'>Szerkesztés</a> | <a href='delete_color.php?id=".$row['idColor']."'>Törlés</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Nincs szín a listában.";
}
echo "<a href='add_color.php'>Új szín felvétele</a>";
echo "<br>"; echo "<br>";

$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT * FROM Engine WHERE type LIKE '%$search%'";
$result = mysqli_query($conn, $sql);

echo "<h2>Motorok</h2>";

if (mysqli_num_rows($result) > 0) {
    echo "<table>";
    echo "<tr><th>Típus&nbsp;&nbsp;&nbsp;</th><th>Lökettérfogat&nbsp;&nbsp;&nbsp;</th><th>Fogyasztás&nbsp;&nbsp;&nbsp;</th><th>Teljesítmény&nbsp;&nbsp;&nbsp;</th><th>Hatótávolság&nbsp;&nbsp;&nbsp;</th><th>Ár</th><th>Műveletek</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>".$row['type']."</td>&nbsp;&nbsp;&nbsp;";
        echo "<td>".$row['size']."</td>&nbsp;";
        echo "<td>".$row['consumption']."</td>&nbsp;&nbsp;&nbsp;";
        echo "<td>".$row['horsepower']."</td>&nbsp;&nbsp;&nbsp;";
        echo "<td>".$row['driving_range']."</td>&nbsp;&nbsp;&nbsp;";
        echo "<td>".$row['price']."</td>&nbsp;&nbsp;&nbsp;";
        echo "<td><a href='edit_engine.php?id=".$row['idEngine']."'>Szerkesztés</a> | <a href='delete_engine.php?id=".$row['idEngine']."'>Törlés</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Nincs motor a listában.";
}
echo "<a href='add_engine.php'>Új motor felvétele</a>";
echo "<br>"; echo "<br>";
?>
<button onclick="window.location.href='index.php'" class="btn btn-primary">Vissza</button>
</body>
</html>
<?php
$conn->close();
?>