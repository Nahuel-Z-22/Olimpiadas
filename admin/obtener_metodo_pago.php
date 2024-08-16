<?php
// Conexión a la base de datos
include '../conexion.php';

// Consulta para obtener todos los métodos de pago
$query = "SELECT id, nombre, descripcion FROM metodos_pago";
$resultado = mysqli_query($conexion, $query);

// Arreglo para almacenar los métodos de pago
$metodos_pago = array();

// Llenar el arreglo con los métodos de pago
while ($row = mysqli_fetch_assoc($resultado)) {
  $metodos_pago[] = $row;
}

// Devolver los métodos de pago en formato JSON
header('Content-Type: application/json');
echo json_encode($metodos_pago);

// Cerrar la conexión
mysqli_close($conexion);
?>
