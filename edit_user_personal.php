<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Felhasználói adatok szerkesztése</title>
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
    <h2>Felhasználó adatainak szerkesztése</h2>
    <form method="POST" action="edit_user_personal.php?edit_user_id=<?php echo $_GET['edit_user_id']; ?>">
        <input type="hidden" name="idUsers" value="<?php echo $idUsers; ?>">
        <div class="form-group">
            <label for="user_name" class="btn btn-light" style="background-color: #ffffff; margin: 0 auto;">Felhasználó név:</label>
            <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Felhasználónév">
        </div>
        <div class="form-group">
            <label for="password" class="btn btn-light" style="background-color: #ffffff; margin: 0 auto;">Jelszó:</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Jelszó">
        </div>
        <div class="form-group">
            <label for="email" class="btn btn-light" style="background-color: #ffffff; margin: 0 auto;">Email-cím</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="Email-cím">
        </div>
        <button onclick="window.location.href='login.php'" class="btn btn-primary">Vissza</button>
        <button type="submit" class="btn btn-primary" id="mentes" name="mentes">Mentés</button>
    </form>
</div>
</body>
</html>
<?php
$conn = mysqli_connect("localhost", "root", "", "mydb") or die("Kapcsolati hiba" . mysqli_error($conn));
mysqli_select_db($conn, "mydb");
$idUsers = mysqli_real_escape_string($conn, $_GET["edit_user_id"]);
if(isset($_POST['mentes'])){
    $kesz=0;
    if (!empty($_POST['user_name'])) {
        $kesz++;
        $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
        $query = "UPDATE Users SET user_name='$user_name' WHERE idUsers='$idUsers'";
        mysqli_query($conn, $query);
    } 
    if (!empty($_POST['password'])) {
        $kesz++;
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $query = "UPDATE Users SET password='$password' WHERE idUsers='$idUsers'";
        mysqli_query($conn, $query);
    } 
    if (!empty($_POST['email'])) {
        $kesz++;
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $query = "UPDATE Users SET email='$email' WHERE idUsers='$idUsers'";
        mysqli_query($conn, $query);
    }
    if($kesz > 0)
        header("Location: login.php");
    else {
        echo '<br><a href="login.php">Vissza</a>';
    }

    $conn->close();
}
?>