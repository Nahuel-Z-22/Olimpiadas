<?php
// Conexión a la base de datos
include '../conexion.php';

// Obtener datos del formulario
$id = $_POST['id'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];

// Validar que los datos no estén vacíos
if(empty($nombre)) {
  $response = array('success' => false, 'message' => 'Por favor ingrese un nombre para el método de pago.');
  echo json_encode($response);
  exit();
}

// Preparar la consulta SQL para editar el método de pago
$query = "UPDATE metodos_pago SET nombre='$nombre', descripcion='$descripcion' WHERE id=$id";

// Ejecutar la consulta
if(mysqli_query($conexion, $query)) {
  $response = array('success' => true, 'message' => 'Método de pago editado correctamente.');
} else {
  $response = array('success' => false, 'message' => 'Error al editar método de pago: ' . mysqli_error($conexion));
}

// Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);

// Cerrar la conexión
mysqli_close($conexion);
?>
