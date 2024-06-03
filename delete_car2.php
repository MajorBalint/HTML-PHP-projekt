<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $conn = mysqli_connect("localhost", "root", "", "mydb") or die("Kapcsolati hiba" . mysqli_error($conn));
    mysqli_select_db($conn, "mydb");

    $deleteQuery = "DELETE FROM Cars_has_Extra WHERE Cars_idCars = $id";
    mysqli_query($conn, $deleteQuery);

    $query = "DELETE FROM Cars WHERE idCars = '$id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header("Location: configurator.php");
        exit;
    } else {
        echo "Hiba történt a törlés során.";
        echo '<br><a href="configurator.php">Vissza</a>';
    }

    $conn->close();
} else {
    echo "Hiba történt a törlés során.";
    echo '<br><a href="configurator.php">Vissza</a>';
}
?>