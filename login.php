<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bejelentkezés</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    
    <?php
    session_start();

    $conn = mysqli_connect("localhost", "root", "", "mydb") or die("Kapcsolati hiba" . mysqli_error($conn));
    mysqli_select_db($conn, "mydb");

    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = $_POST['password'];

        $query = "SELECT idUsers, user_name, password, status FROM Users WHERE user_name='$username'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['status'] = $row['status'];
        
            if (password_verify($password, $row['password'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['idUsers'] = $row['idUsers'];

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

    if (isset($_SESSION['background'])) {
        $background = $_SESSION['background'];
    } else {
        $background = 'light';
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
            <li class="nav-item active">
                <a class="nav-link" href="login.php">Bejelentkezés</a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="registration.php">Regisztráció</a>
            </li>
        </ul>
    </nav>
</header>
<div class="container">
    <h2>Bejelentkezés</h2>
    <?php if(isset($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="form-group">
            <br/>
            <input type="text" class="form-control" id="username" name="username" placeholder="Felhasználónév" required>
        </div>
        <div class="form-group">
            <br/>
            <input type="password" class="form-control" id="password" name="password" placeholder="Jelszó" required>
        </div>
        <button onclick="window.location.href='index.php'" class="btn btn-primary">Vissza</button>
        <button type="submit" class="btn btn-primary">Bejelentkezés</button>
        <?php if(isset($_SESSION['idUsers'])): ?>
            <button onclick="window.location.href='edit_user_personal.php?edit_user_id=<?php echo $_SESSION['idUsers'] ?>'" class="btn btn-primary">Módosítás</button>
        <?php endif; ?>
    </form>
</div>
<?php if(isset($_SESSION['loggedin'])): ?>
    <?php echo '<style class="btn btn-light" style="background-color: #ffffff; margin: 0 auto; font-size: 25px">Ön már be van jelentkezve!</style>';?>
    <form method="POST" action="">
        <button type="submit" name="logout" class="btn btn-danger mt-3">Kijelentkezés</button>
    </form>
<?php endif; ?>
</body>
</html>
