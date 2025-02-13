<?php
$host = "bcxokw4mgslh0aytwnrr-mysql.services.clever-cloud.com";
$dbname = "bcxokw4mgslh0aytwnrr";
$user = "uwdylhwtwffxoz1e";
$password = "UQmBbdBYPSG409VbJUkU";

try {
    $conexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
