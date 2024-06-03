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
$color = isset($_GET['color']) ? $_GET['color'] : "";

$colorQuery = "SELECT price FROM Color WHERE idColor='$color'";
$colorResult = mysqli_query($conn, $colorQuery);
$colorData = mysqli_fetch_assoc($colorResult);
$colorPrice = $colorData["price"];

$modelQuery = "SELECT price FROM Cars WHERE model='$model' AND User_idUsers IS NULL LIMIT 1";
$modelResult = mysqli_query($conn, $modelQuery);
$modelData = mysqli_fetch_assoc($modelResult);
$modelPrice = $modelData["price"];

$engineQuery = "SELECT price FROM Engine WHERE idEngine='$engine'";
$engineResult = mysqli_query($conn, $engineQuery);
$engineData = mysqli_fetch_assoc($engineResult);
$enginePrice = $engineData["price"];

$finalprice = $colorPrice + $modelPrice + $enginePrice;
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
    <h6 class="btn btn-light" style=" background-color: #ffffff; margin: 0 auto;">Márka:</h6>
    <h4 style="text-align: center;"><?php echo $selectedBrand; ?></h4>
    <h6 class="btn btn-light" style=" background-color: #ffffff; margin: 0 auto;">Modell:</h6>
    <h4 style="text-align: center;"><?php echo $model; ?></h4>
    <h6 class="btn btn-light" style=" background-color: #ffffff; margin: 0 auto;">Motor:</h6>
    <?php
    $engineQuery = "SELECT * FROM Engine WHERE idEngine='$engine'";
    $engineResult = mysqli_query($conn, $engineQuery);
    $engineData = mysqli_fetch_assoc($engineResult);
    $engineType = $engineData["type"];
    $engineSize = $engineData["size"];
    ?>
    <h4 style="text-align: center;"><?php echo $engineType . " " . $engineSize; ?></h4>
    <h6 class="btn btn-light" style=" background-color: #ffffff; margin: 0 auto;">Szín:</h6>
    <?php
    $colorQuery = "SELECT * FROM Color WHERE idColor='$color'";
    $colorResult = mysqli_query($conn, $colorQuery);
    $colorData = mysqli_fetch_assoc($colorResult);
    $colorName = $colorData["name"];
    $colorMetallic = $colorData["metallic"];
    $isMetallic = ($colorMetallic == 1) ? "Igen" : "Nem";
    ?>
    <h4 style="text-align: center;"><?php echo $colorName . " (Metál: " . $isMetallic . ")"; ?></h4>
    <form method="POST" action="configurator5.php?brand=<?php echo $selectedBrand ?>&model=<?php echo $model ?>&engine=<?php echo $engine ?>&color=<?php echo $color ?>">
        <div class="form-group">
            <h6 class="btn btn-light" style=" background-color: #ffffff; margin: 0 auto;">Extrák:</h6>
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Megnevezés</th>
                    <th>Ár</th>
                    <th>Tömeg</th>
                    <th>Leírás</th>
                    <th></th>
                </tr>
                
                <?php
                $extraQuery = "SELECT * FROM Extra";
                $extraResult = mysqli_query($conn, $extraQuery);
                $num = 0;
                while ($row = mysqli_fetch_assoc($extraResult)) {
                    $num++;

                    $extraID = $row["idExtra"];
                    $extraType = $row["type"];
                    $extraPrice = $row["price"];
                    $extraWeight = $row["weight"];
                    $extraDescription = $row["description"];
                
                    echo "<tr>";
                    echo "<td>$extraID</td>";
                    echo "<td>$extraType</td>";
                    echo "<td>$extraPrice</td>";
                    echo "<td>$extraWeight</td>";
                    echo "<td>$extraDescription</td>";
                    echo "<td><input type='checkbox' name='extras[]' value='$extraID'></td>"; // Checkbox hozzáadása
                    echo "</tr>";
                }                
                ?>
                </thead>
            </table>
            <br/>
            <button type="submit" class="btn btn-primary" name="mentes">Mentés</button>
        </div>
    </form>
    <?php

if (isset($_POST['mentes'])) {
    $yearQuery = "SELECT model_year FROM Cars WHERE model='$model' LIMIT 1";
    $yearResult = mysqli_query($conn, $yearQuery);
    $yearData = mysqli_fetch_assoc($yearResult);
    $year = $yearData["model_year"];
    $insertQuery = "INSERT INTO Cars (model, 
    price, model_year, brand, Engine_idEngine, Color_idColor, User_idUsers)
        VALUES ('$model', '$finalprice', '$year', '$selectedBrand', '$engine', '$color', {$_SESSION['idUsers']})";;
    mysqli_query($conn, $insertQuery);
    $carId = mysqli_insert_id($conn);
    
    if(isset($_POST['extras'])){
        $extras = $_POST['extras'];
        foreach($extras as $extraID){
            $insertExtraQuery = "INSERT INTO Cars_has_Extra (Cars_idCars, Extra_idExtra) VALUES ($carId, $extraID)";
            mysqli_query($conn, $insertExtraQuery);
        }
    }

    $updatetQuery = "UPDATE Cars SET price='$finalprice' WHERE idCars='$carId'";
    mysqli_query($conn, $updatetQuery);

    mysqli_close($conn);
    $redirect= "add_extra_price.php?id=$carId";
        echo "<script>window.location.href = '{$redirect}';</script>";
    exit();
}
?>
</div>
</body>
</html>