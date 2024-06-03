<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "mydb") or die("Kapcsolati hiba" . mysqli_error($conn));
mysqli_select_db($conn, "mydb");

$query = "SELECT DISTINCT brand FROM Cars";
$brandResult = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Autó szerkesztése</title>
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
            <li class="nav-item active">
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
        <h2>Konfigurátor</h2>
        <form method="POST" action="configurator.php">
            <div class="form-group">
                <label for="brand" class="btn btn-light" style="background-color: #ffffff; margin: 0 auto;">Márka:</label>
                <select class="form-control" id="brand" name="brand" required>
                    <option value="">Márka kiválasztása</option>
                    <?php
                    while ($row = mysqli_fetch_assoc($brandResult)) {
                        $brand = $row["brand"];
                        echo "<option value='$brand'>$brand</option>";
                    }
                    ?>
                </select>
            <br/>
            <button onclick="history.go(-1);" class="btn btn-primary" >Vissza</button>
            <button type="submit" class="btn btn-primary" name="tovabb">Tovább</button>
            <br/>
            <?php
                if(isset($_POST['tovabb']))
                    header("Location: configurator2.php?brand=".$_POST['brand']."");
            ?>
            <br/>
            <?php
            $result = mysqli_query($conn, "SELECT idCars, brand, model, Cars.price, Cars.model_year, type, size, name, metallic FROM Cars
                LEFT JOIN Engine ON Engine_idEngine = idEngine
                LEFT JOIN Color ON Color_idColor = idColor
                WHERE User_idUsers = {$_SESSION['idUsers']}");

            if (mysqli_num_rows($result) == 0): ?>
                <br/>
                <span class="btn btn-light" style="background-color: #ffffff; margin: 0 auto;">Önnek nincs egyetlen konfigurációja sem.</span>
            <?php else: ?>
            <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Márka</th>
                    <th>Modell</th>
                    <th>Ár</th>
                    <th>Évszám</th>
                    <th>Motor típusa</th>
                    <th>Motor mérete</th>
                    <th>Szín neve</th>
                    <th>Metál</th>
                    <th>Törlés</th>
                    
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['idCars']; ?></td>
                        <td><?php echo $row['brand']; ?></td>
                        <td><?php echo $row['model']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['model_year']; ?></td>
                        <td><?php echo $row['type']; ?></td>
                        <td><?php echo $row['size']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo ($row['metallic'] == 1) ? 'Igen' : 'Nem'; ?></td>
                        <?php echo "<td><a href='delete_car2.php?id=".$row['idCars']."'>Törlés</a></td>";?>
                    </tr>
                <?php endwhile; ?>
            </thead>
            </table>
        <?php endif; mysqli_close($conn);?>
        </form>
        <span class="btn btn-light" style=" background-color: #ffffff; margin: 0 auto;"> Az ID megadásával rendelheti meg autóját a kiválasztott kereskedésben. </span>
    </div>
</body>
</html>