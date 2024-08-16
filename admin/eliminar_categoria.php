<?php
// Conexión a la base de datos
include '../conexion.php';

// Obtener ID de la categoría a eliminar
$id = $_POST['id'];

// Preparar la consulta SQL para eliminar la categoría
$query = "DELETE FROM categorias WHERE id=$id";

// Ejecutar la consulta
if(mysqli_query($conexion, $query)) {
  $response = array('success' => true, 'message' => 'Categoría eliminada correctamente.');
} else {
  $response = array('success' => false, 'message' => 'Error al eliminar categoría: ' . mysqli_error($conexion));
}

// Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);

// Cerrar la conexión
mysqli_close($conexion);
?>
