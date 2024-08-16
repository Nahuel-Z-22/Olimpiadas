<?php
// obtener_producto.php

include '../conexion.php';

$id = $_GET['id'];

$sql = "SELECT * FROM productos WHERE id='$id'";
$result = $conexion->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode(['error' => 'Producto no encontrado']);
}

$conexion->close();
?>