<?php
include '../conexion.php';

$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'];
$nombre = $data['nombre'];
$apellido = $data['apellido'];
$email = $data['email'];
$direccion = $data['direccion'];
$tipo_usuario = $data['tipo_usuario'];

$sql = "UPDATE usuarios SET nombre='$nombre', apellido='$apellido', email='$email', direccion='$direccion', tipo_usuario='$tipo_usuario' WHERE id='$id'";

if ($conexion->query($sql) === TRUE) {
    $response = array("success" => true);
} else {
    $response = array("success" => false);
}

echo json_encode($response);

$conexion->close();
?>
