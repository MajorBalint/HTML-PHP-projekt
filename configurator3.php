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

if (isset($_GET['brand'])) {
    $selectedBrand = $_GET['brand'];
}

$model = isset($_GET['model']) ? $_GET['model'] : "";
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
    <h6 class="btn btn-light" style="text-align: center; background-color: #ffffff; margin: 0 auto;">Márka:</h5>
    <h4 style="text-align: center;"><?php echo $selectedBrand; ?></h4>
    <h6 class="btn btn-light" style="text-align: center; background-color: #ffffff; margin: 0 auto;">Modell:</h6>
    <h4 style="text-align: center;"><?php echo $model; ?></h4>
    <form method="POST" action="configurator3.php?brand=<?php echo $selectedBrand ?>&model=<?php echo $model ?>">
        <div class="form-group">
            <label for="engine" class="btn btn-light" style="text-align: center; background-color: #ffffff; margin: 0 auto;">Motor:</label>
            <select class="form-control" id="engine" name="engine" required>
                <option value="">Motor kiválasztása</option>
                <?php
                $engineQuery = "SELECT * FROM Engine";
                $engineResult = mysqli_query($conn, $engineQuery);
                while ($row = mysqli_fetch_assoc($engineResult)) {
                    $engineID = $row["idEngine"];
                    $engineSize = $row["size"];
                    $engineType = $row["type"];
                    $enginePrice = $row["price"];
                    $engineC = $row["consumption"];
                    $engineHp = $row["horsepower"];
                    $engineRange = $row["driving_range"];
                    $enginePrice = $row["price"];
                    echo "<option value='$engineID'>$engineID &nbsp; $engineType &nbsp; $engineSize ccm &nbsp; $engineHp kW &nbsp; $engineC L &nbsp; $engineRange km &nbsp; $enginePrice Ft</option>";
                }
                ?>
            </select>
            <br/>
            <button onclick="window.location.href='configurator2.php?brand=<?php echo  $selectedBrand?>'" class="btn btn-primary">Vissza</button>
            <button type="submit" class="btn btn-primary" name="tovabb">Tovább</button>
        </div>
    </form>
    <?php
    if (isset($_POST['tovabb'])) {
        $id = $_POST['engine'];
        header("Location: configurator4.php?brand=$selectedBrand&model=$model&engine=$id");
        mysqli_close($conn);
        exit;
    }
    ?>
</div>
</body>
</html>