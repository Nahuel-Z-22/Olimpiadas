<?php
// Conexión a la base de datos
include '../conexion.php';

// Obtener datos del formulario
$id = $_POST['id'];
$nombre = $_POST['nombre'];

// Validar que el nombre no esté vacío
if(empty($nombre)) {
  $response = array('success' => false, 'message' => 'Por favor ingrese un nombre para la categoría.');
  echo json_encode($response);
  exit();
}

// Preparar la consulta SQL para editar la categoría
$query = "UPDATE categorias SET nombre='$nombre' WHERE id=$id";

// Ejecutar la consulta
if(mysqli_query($conexion, $query)) {
  $response = array('success' => true, 'message' => 'Categoría editada correctamente.');
} else {
  $response = array('success' => false, 'message' => 'Error al editar categoría: ' . mysqli_error($conexion));
}

// Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);

// Cerrar la conexión
mysqli_close($conexion);
?>
