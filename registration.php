<?php
session_start();

if (isset($_SESSION['theme'])) {
    $theme = $_SESSION['theme'];
} else {
    $theme = 'light';
}

if (isset($_SESSION['background'])) {
    $background = $_SESSION['background'];
} else {
    $background = 'light';
}

$conn = mysqli_connect("localhost", "root", "", "mydb")
or die ("Kapcsolati hiba" . mysqli_error());
mysqli_select_db($conn,"mydb");

if ($conn->connect_error) {
    die("A csatlakozás nem sikerült: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO Users (user_name, password, email, status) VALUES ('$username', '$password', '$email', '1')";   
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Regisztráció</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">

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
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Bejelentkezés</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="registration.php">Regisztráció</a>
                </li>
            </ul>
        </nav>
    </header>
    <?php
    if(isset($sql)){
        if ($conn->query($sql) === TRUE) {
            echo "<h1>Sikeres regisztráció!</h1>";
        } 
        else {
            echo "<h1>Hiba a regisztráció során: " . $conn->error."</h1>";
        }
        $conn->close();
    }
    ?>
    <main>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h1>Regisztráció</h1>
                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div class="form-group">
                            <br/>
                            <input type="text" class="form-control" id="username" name="username" placeholder= "Felhasználónév" required>
                        </div>
                        <div class="form-group">
                            <br/>
                            <input type="password" class="form-control" id="password" name="password" placeholder= "Jelszó" required>
                        </div>
                        <div class="form-group">
                            <br/>
                            <input type="email" class="form-control" id="email" name="email" placeholder= "Email-cím" required>
                        </div>
                        <button onclick="window.location.href='index.php'" class="btn btn-primary">Vissza</button>
                        <button type="submit" name="submit" class="btn btn-primary">Regisztráció</button>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </main>
</body>
</html>
