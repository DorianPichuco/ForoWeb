<?php
// conexion-doria.php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "doria";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>
