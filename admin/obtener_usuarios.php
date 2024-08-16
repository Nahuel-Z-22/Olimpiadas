<?php
include '../conexion.php';

$sql = "SELECT * FROM usuarios";
$resultado = $conexion->query($sql);

$usuarios = array();

if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $usuarios[] = $row;
    }
}

echo json_encode($usuarios);

$conexion->close();
?>
