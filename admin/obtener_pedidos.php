<?php
include '../conexion.php';

// Obtén los parámetros de la URL
$fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : '';
$fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : '';
$estado = isset($_GET['estado']) ? $_GET['estado'] : '';

// Construye la consulta SQL con condiciones basadas en la presencia de parámetros
$sql = "SELECT pedidos.id, usuarios.nombre AS nombre_usuario, pedidos.estado, pedidos.fecha_pedido 
        FROM pedidos 
        JOIN usuarios ON pedidos.usuario_id = usuarios.id 
        WHERE 1=1"; 

if (!empty($fechaInicio)) {
    $sql .= " AND pedidos.fecha_pedido >= '$fechaInicio'";
}
if (!empty($fechaFin)) {
    $sql .= " AND pedidos.fecha_pedido <= '$fechaFin'";
}
if (!empty($estado)) {
    $sql .= " AND pedidos.estado = '$estado'";
}

$result = $conexion->query($sql);

// Preparar los datos para la respuesta
$pedidos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pedidos[] = $row;
    }
}

// Envía los resultados como JSON
echo json_encode(['pedidos' => $pedidos]);

$conexion->close();
?>
