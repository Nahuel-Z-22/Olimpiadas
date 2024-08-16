<?php
include '../conexion.php';

$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'];
$estado = $data['estado'];

$sql = "UPDATE pedidos SET estado='$estado' WHERE id='$id'";

if ($conexion->query($sql) === TRUE) {
    $response = array("success" => true);
} else {
    $response = array("success" => false, "message" => $conexion->error);
}

echo json_encode($response);

$conexion->close();
?>
