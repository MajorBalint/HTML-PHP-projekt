<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['idUsers'] != 1) {
    echo "Hiba, Önnek nincs jogosultsága az oldalhoz!";
    echo '<br><a href="user_role.php">Vissza</a>';
    exit;
}

if (isset($_GET['user_id'])) {
    $id = $_GET['user_id'];

    if ($id == 1) {
        echo "Hiba, a rendszergazda törlése nem lehetséges!";
        echo '<br><a href="user_role.php">Vissza</a>';
        exit;
    }

    $conn = mysqli_connect("localhost", "root", "", "mydb") or die("Kapcsolati hiba" . mysqli_error($conn));
    mysqli_select_db($conn, "mydb");

    $query = "DELETE FROM Users WHERE idUsers = '$id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header("Location: user_role.php");
        exit;
    } else {
        echo "Hiba történt a törlés során. ";
        echo '<br><a href="user_role.php">Vissza</a>';
    }
    $conn->close();
} else {
    echo "Hiba történt a törlés során. ";
    echo '<br><a href="user_role.php">Vissza</a>';
}
?>