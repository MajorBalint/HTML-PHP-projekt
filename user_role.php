<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['idUsers'] > 1) {
    echo "Hiba, Önnek nincs jogosultsága megtekinteni az oldalt!";
    echo '<br><a href="index.php">Vissza</a>';
    exit;
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

$conn = mysqli_connect("localhost", "root", "", "mydb") or die("Kapcsolati hiba" . mysqli_error($conn));
mysqli_select_db($conn, "mydb");

if (isset($_POST['update_role'])) {
    $userId = $_POST['user_id'];
    $newRole = $_POST['new_role'][$userId];

    $stmt = $conn->prepare("UPDATE Users SET status = ? WHERE idUsers = ?");
    $stmt->bind_param("ii", $newRole, $userId);
    if ($stmt->execute()) {
        $updateMessage = "Felhasználói jogkör sikeresen frissítve.";
    } else {
        $updateMessage = "Hiba a felhasználói jogkör frissítésekor: " . $conn->error;
    }
    $stmt->close();
}

$search = isset($_GET['search']) ? $_GET['search'] : '';

$stmt = $conn->prepare("SELECT * FROM Users WHERE user_name LIKE ?");
$searchPattern = "%$search%";
$stmt->bind_param("s", $searchPattern);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Felhasználói jogkör beállítás</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
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
    <nav class="navbar navbar-expand-lg navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item ">
                <a class="nav-link" href="index.php">Főoldal</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="configurator.php">Konfigurátor</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="user_role.php">Felhasználói jogkör beállítása</a>
            </li>
            <li class="nav-item">
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
    <br/>
    <h1>Felhasználói jogkör beállítás</h1>
    <h5> A módosításhoz kérem keressen rá a felhasználó nevére! </h5>
    <?php if (isset($updateMessage)): ?>
        <h4><?php echo $updateMessage; ?></h4>
        <br/>
    <?php endif; ?>
    <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="text" name="search" placeholder="Keresés...">
        <input type="submit" value="Keresés">
    </form>
    <br/>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="text-center">
            <table>
                <tr>
                    <th>Felhasználó neve:&nbsp; &nbsp;</th>
                    <th>Jogkör: </th>
                    <th> </th>
                    <th> </th>
                    <th></th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['user_name']; ?></td>
                        <td>
                            <select name="new_role[<?php echo $row['idUsers']; ?>]">
                                <option value="1" <?php if ($row['status'] == 1) echo 'selected'; ?>>Vásárló</option>
                                <option value="2" <?php if ($row['status'] == 2) echo 'selected'; ?>>Kereskedő</option>
                            </select>
                        </td>
                        <td>
                            <form method="POST" action="<?php echo 'delete_user.php?user_id='.$row['idUsers']; ?>">
                                <input type="submit" name="delete_user_button[<?php echo $row['idUsers']; ?>]" value="Törlés">
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="<?php echo 'edit_user.php?edit_user_id='.$row['idUsers']; ?>">
                                <input type="submit" name="edit_user_button[<?php echo $row['idUsers']; ?>]" value="Szerkesztés">
                            </form>
                        </td>
                        <td>
                            <input type="submit" name="update_role[<?php echo $row['idUsers']; ?>]" value="Jogkör frissítése">
                            <input type="hidden" name="user_id" value="<?php echo $row['idUsers']; ?>">
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
        <br/>
        <div class="text-left">
        <button onclick="window.location.href='index.php'" class="btn btn-primary">Vissza</button>
        </div>
    </form>
</body>
</html>
<?php
$conn->close();
?>