<?php
// Conexión a la base de datos
include '../conexion.php';

// Obtener ID del método de pago a eliminar
$id = $_POST['id'];

// Preparar la consulta SQL para eliminar el método de pago
$query = "DELETE FROM metodos_pago WHERE id=$id";

// Ejecutar la consulta
if(mysqli_query($conexion, $query)) {
  $response = array('success' => true, 'message' => 'Método de pago eliminado correctamente.');
} else {
  $response = array('success' => false, 'message' => 'Error al eliminar método de pago: ' . mysqli_error($conexion));
}

// Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);

// Cerrar la conexión
mysqli_close($conexion);
?>
