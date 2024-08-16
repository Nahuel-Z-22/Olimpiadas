<?php
include 'conexion.php';
session_start();

$response = array();

if(isset($_SESSION['usuario_id'])) {
    $response['iniciado'] = true;
} else {
    $response['iniciado'] = false;
}

header('Content-Type: application/json');
echo json_encode($response);
?>
