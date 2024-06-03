<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['status'] != 2) {
    echo "Hiba, Önnek nincs jogosultsága az oldalhoz!";
    echo '<br><a href="admin.php">Vissza</a>';
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $conn = mysqli_connect("localhost", "root", "", "mydb") or die("Kapcsolati hiba" . mysqli_error($conn));
    mysqli_select_db($conn, "mydb");

    $query = "DELETE FROM Color WHERE idColor = '$id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header("Location: admin.php");
        exit;
    } else {
        echo "Hiba történt a törlés során.";
        echo '<br><a href="admin.php">Vissza</a>';
    }

    $conn->close();
} else {
    echo "Hiba történt a törlés során.";
    echo '<br><a href="admin.php">Vissza</a>';
}
?>