<?php
$servername = "localhost";
$username = "root";
$password = "";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
echo "Conexión correcta, con MySQL orientado a objetos </br>";
?>

<?php
$servername = "localhost";
$username = "root";
$password = "";

$conn = mysqli_connect($servername, $username, $password);

if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}
echo "Conexión exitosa, con MySQL orientado a procedimientos </br>";
?>

<?php
$servername = "localhost";
$username = "root";
$password = "";


try {
    $conn = new PDO("mysql:host=$servername;dbname=", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa, con PDO orientado a objetos, extensión de PHP </br>";
} catch(PDOException $e) {
    echo "Conexión fallida con PDO orientado a objetos, extensión de PHP </br> " . $e->getMessage();
}
?>
