<?php
// Conexión a la base de datos
include '../conexion.php';

// Consulta para obtener todas las categorías
$query = "SELECT id, nombre FROM categorias";
$resultado = mysqli_query($conexion, $query);

// Arreglo para almacenar las categorías
$categorias = array();

// Llenar el arreglo con las categorías
while ($row = mysqli_fetch_assoc($resultado)) {
  $categorias[] = $row;
}

// Devolver las categorías en formato JSON
header('Content-Type: application/json');
echo json_encode($categorias);

// Cerrar la conexión
mysqli_close($conexion);
?>
