<?php
include 'conexion.php';

$usuario_id = $_GET['usuario_id'];
$producto_id = $_GET['producto_id'];

// Verificar la cantidad actual del producto en el carrito
$sql = "SELECT cantidad FROM carrito_compras WHERE usuario_id = ? AND producto_id = ?";
$stmt = $conexion->prepare($sql);
if ($stmt) {
    $stmt->bind_param("ii", $usuario_id, $producto_id);
    $stmt->execute();
    $stmt->bind_result($cantidad);
    $stmt->fetch();
    $stmt->close();

    if ($cantidad > 1) {
        // Si la cantidad es mayor que 1, actualizar la cantidad reduciéndola en uno
        $sql_update = "UPDATE carrito_compras SET cantidad = cantidad - 1 WHERE usuario_id = ? AND producto_id = ?";
        $stmt_update = $conexion->prepare($sql_update);
        if ($stmt_update) {
            $stmt_update->bind_param("ii", $usuario_id, $producto_id);
            if ($stmt_update->execute()) {
                http_response_code(200);
            } else {
                http_response_code(500);
            }
            $stmt_update->close();
        } else {
            http_response_code(500);
            echo "Error en la preparación de la consulta de actualización.";
        }
    } else {
        // Si la cantidad es igual a 1, eliminar el producto del carrito
        $sql_delete = "DELETE FROM carrito_compras WHERE usuario_id = ? AND producto_id = ?";
        $stmt_delete = $conexion->prepare($sql_delete);
        if ($stmt_delete) {
            $stmt_delete->bind_param("ii", $usuario_id, $producto_id);
            if ($stmt_delete->execute()) {
                http_response_code(200);
            } else {
                http_response_code(500);
            }
            $stmt_delete->close();
        } else {
            http_response_code(500);
            echo "Error en la preparación de la consulta de eliminación.";
        }
    }
} else {
    http_response_code(500);
    echo "Error en la preparación de la consulta.";
}

$conexion->close();
?>
