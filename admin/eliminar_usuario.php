<?php
include '../conexion.php';

$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'];

$sql = "DELETE FROM usuarios WHERE id='$id'";

if ($conexion->query($sql) === TRUE) {
    $response = array("success" => true);
} else {
    $response = array("success" => false);
}

echo json_encode($response);

$conexion->close();
?>
