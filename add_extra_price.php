<?php
$conn = mysqli_connect("localhost", "root", "", "mydb") or die("Kapcsolati hiba" . mysqli_error($conn));
mysqli_select_db($conn, "mydb");

$id = $_GET['id'];

$carsQuery = "SELECT * FROM Cars WHERE idCars = $id";
$carsResult = mysqli_query($conn, $carsQuery);
$carsRow = mysqli_fetch_assoc($carsResult);

$carsHasExtraQuery = "SELECT Extra_idExtra FROM Cars_has_Extra WHERE Cars_idCars = $id";
$carsHasExtraResult = mysqli_query($conn, $carsHasExtraQuery);

$totalPrice = $carsRow['price'];

while ($row = mysqli_fetch_assoc($carsHasExtraResult)) {
    $extraId = $row['Extra_idExtra'];

    $extraQuery = "SELECT price FROM Extra WHERE idExtra = $extraId";
    $extraResult = mysqli_query($conn, $extraQuery);
    $extraRow = mysqli_fetch_assoc($extraResult);

    $extraPrice = $extraRow['price'];
    $totalPrice += $extraPrice;
}

$carsQuery = "UPDATE Cars SET price='$totalPrice' WHERE idCars='$id'";
$carsResult = mysqli_query($conn, $carsQuery);

header("Location: configurator.php");
$conn->close();
?>