<?php
// Conexión a la base de datos
include '../conexion.php';

// Obtener datos del formulario
$nombre = $_POST['nombre'];

// Validar que el nombre no esté vacío
if(empty($nombre)) {
  $response = array('success' => false, 'message' => 'Por favor ingrese un nombre para la categoría.');
  echo json_encode($response);
  exit();
}

// Preparar la consulta SQL para agregar la categoría
$query = "INSERT INTO categorias (nombre) VALUES ('$nombre')";

// Ejecutar la consulta
if(mysqli_query($conexion, $query)) {
  $response = array('success' => true, 'message' => 'Categoría agregada correctamente.');
} else {
  $response = array('success' => false, 'message' => 'Error al agregar categoría: ' . mysqli_error($conexion));
}

// Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);

// Cerrar la conexión
mysqli_close($conexion);
?>
