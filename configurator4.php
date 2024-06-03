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
$engine = isset($_GET['engine']) ? $_GET['engine'] : "";
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
    <h6 class="btn btn-light" style="text-align: center; background-color: #ffffff; margin: 0 auto;">Márka:</h6>
    <h4 style="text-align: center;"><?php echo $selectedBrand; ?></h4>
    <h6 class="btn btn-light" style="text-align: center; background-color: #ffffff; margin: 0 auto;">Modell:</h6>
    <h4 style="text-align: center;"><?php echo $model; ?></h4>
    <h6 class="btn btn-light" style="text-align: center; background-color: #ffffff; margin: 0 auto;">Motor:</h6>
    <?php
    $engineQuery = "SELECT * FROM Engine WHERE idEngine='$engine'";
    $engineResult = mysqli_query($conn, $engineQuery);
    $engineData = mysqli_fetch_assoc($engineResult);
    $engineType = $engineData["type"];
    $engineSize = $engineData["size"];
    ?>
    <h4 style="text-align: center;"><?php echo $engineType . " " . $engineSize; ?></h4>
    <form method="POST" action="configurator4.php?brand=<?php echo $selectedBrand ?>&model=<?php echo $model ?>&engine=<?php echo $engine ?>">
        <div class="form-group">
            <label for="color" class="btn btn-light" style=" background-color: #ffffff; margin: 0 auto;">Szín:</label>
            <select class="form-control" id="color" name="color" required>
                <option value="">Szín kiválasztása</option>
                <?php
                $colorQuery = "SELECT * FROM Color";
                $colorResult = mysqli_query($conn, $colorQuery);
                while ($row = mysqli_fetch_assoc($colorResult)) {
                    $colorID = $row["idColor"];
                    $colorName = $row["name"];
                    $colorMetallic = $row["metallic"];
                    $colorPrice = $row["price"];
                    $isMetallic = ($colorMetallic == 1) ? "Igen" : "Nem";
                    echo "<option value='$colorID'>$colorID &nbsp; $colorName &nbsp; Metál: $isMetallic &nbsp; Ár: $colorPrice Ft</option>";
                }
                ?>
            </select>
            <br/>
            <button onclick="window.location.href='configurator3.php?brand=<?php echo  $selectedBrand?>&model=<?php echo  $model?>'" class="btn btn-primary">Vissza</button>
            <button type="submit" class="btn btn-primary" name="tovabb">Tovább</button>
        </div>
    </form>
    <?php
    if (isset($_POST['tovabb'])) {
        $colorID = $_POST['color'];
        header("Location: configurator5.php?brand=$selectedBrand&model=$model&engine=$engine&color=$colorID");
        mysqli_close($conn);
        exit;
    }
    ?>
</div>
</body>
</html>