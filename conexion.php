<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "sport_shop";

// Crear conexión
$conexion = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>