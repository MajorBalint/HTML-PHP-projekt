<?php
session_start();

if (isset($_POST['theme'])) {
    $_SESSION['theme'] = $_POST['theme'];
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Autó Konfigurátor</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">

    <?php
        $currentTheme = isset($_SESSION['theme']) ? $_SESSION['theme'] : 'light';

        echo '<body class="' . $currentTheme . '">';
        echo '<style>.navbar { background-color: ' . ($currentTheme === 'dark' ? '#555555' : '#ff6f00') . '; color: ' . ($currentTheme === 'dark' ? '#ffffff' : '#000000') . '; }</style>';
    ?>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item active">
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
                <li class="nav-item ">
                    <a class="nav-link" href="registration.php">Regisztráció</a>
                </li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h1>Üdvözöljük az Autó Konfigurátorban!</h1>
                    <div class="login-options">
                        <a href="login.php" class="btn btn-primary">Bejelentkezés</a>
                        <a href="registration.php" class="btn btn-secondary">Regisztráció</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div class="color-switcher">
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <button type="submit" name="theme" value="light" class="btn btn-secondary" style="background-color: #ff6f00;">Narancssárga téma</button>
            <button type="submit" name="theme" value="dark" class="btn btn-secondary">Szürke téma</button>
        </form>
        <?php
            if(isset($_POST['theme'])) {
                $_SESSION['theme'] = $_POST['theme'];
            }
        ?>
    </div>
</body>
</html>
