<?php
session_start();
include '../conexion.php';

//Verificar si el usuario es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$whereClause = '';
// Verificar si se proporcionaron fechas para filtrar


if (isset($_GET['fechaInicio']) && isset($_GET['fechaFin'])) {
    $fechaInicio = date('Y-m-d H:i:s', strtotime($_GET['fechaInicio'] . "00:00:00"));
    $fechaFin = date('Y-m-d H:i:s', strtotime($_GET['fechaFin'] . "23:59:59"));
    $whereClause = "WHERE t.fecha_compra BETWEEN '$fechaInicio' AND '$fechaFin'";
}


// Obtener transacciones de la base de datos, aplicando el filtro de fechas si es necesario
$stmt = $conexion->prepare("SELECT t.id, u.nombre AS nombre_usuario, u.apellido AS apellido_usuario, 
        p.nombre AS nombre_producto, t.cantidad, t.total_gastado, t.fecha_compra, 
        mp.nombre AS metodo_pago_nombre
        FROM transacciones t 
        INNER JOIN usuarios u ON t.usuario_id = u.id 
        INNER JOIN productos p ON t.producto_id = p.id
        INNER JOIN metodos_pago mp ON t.metodo_pago = mp.id
        $whereClause
        ORDER BY t.fecha_compra DESC");


$stmt->execute();
$resultado = $stmt->get_result();

$transacciones = array();
$totalGastado = 0; // Inicializar el total gastado

while ($fila = $resultado->fetch_assoc()) {
    $transacciones[] = $fila;
    $totalGastado += $fila['total_gastado']; // Sumar al total gastado
}

// Devolver las transacciones y el total gastado como JSON
echo json_encode(array('transacciones' => $transacciones, 'total_gastado' => $totalGastado));
?>
