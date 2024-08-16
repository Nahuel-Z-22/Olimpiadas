<?php
include 'conexion.php';

session_start();
$usuario_id = $_SESSION['usuario_id'];

if (isset($_POST['metodo_pago'])) {
    $metodo_pago = $_POST['metodo_pago'];

    $conexion->begin_transaction();

    try {
        $total_gastado = 0;

        // Insertar un nuevo pedido
        $sql_insert_pedido = "INSERT INTO pedidos (usuario_id, estado, fecha_pedido) VALUES ($usuario_id, 'pendiente', CURDATE())";
        $conexion->query($sql_insert_pedido);

        // Obtener el ID del pedido recién insertado
        $pedido_id = $conexion->insert_id;

        // Obtener productos del carrito
        $sql_carrito = "SELECT producto_id, cantidad FROM carrito_compras WHERE usuario_id = $usuario_id";
        $result_carrito = $conexion->query($sql_carrito);

        while ($row = $result_carrito->fetch_assoc()) {
            $producto_id = $row['producto_id'];
            $cantidad = $row['cantidad'];

            // Obtener precio y stock del producto
            $sql_precio = "SELECT precio, stock FROM productos WHERE id = $producto_id";
            $result_precio = $conexion->query($sql_precio);
            $row_precio = $result_precio->fetch_assoc();
            $precio = $row_precio['precio'];
            $stock_actual = $row_precio['stock'];

            if ($cantidad > $stock_actual) {
                throw new Exception("No hay suficiente stock disponible para el producto con ID: $producto_id");
            }

            $total_producto = $precio * $cantidad;

            // Insertar la transacción asociando el pedido_id
            $sql_insert_transaccion = "INSERT INTO transacciones (usuario_id, producto_id, cantidad, total_gastado, metodo_pago, pedido_id) VALUES ($usuario_id, $producto_id, $cantidad, $total_producto, '$metodo_pago', $pedido_id)";
            $conexion->query($sql_insert_transaccion);

            $total_gastado += $total_producto;

            // Actualizar el stock del producto
            $nuevo_stock = $stock_actual - $cantidad;
            $sql_update_stock = "UPDATE productos SET stock = $nuevo_stock WHERE id = $producto_id";
            $conexion->query($sql_update_stock);

            // Eliminar el producto del carrito
            $sql_delete_carrito = "DELETE FROM carrito_compras WHERE usuario_id = $usuario_id AND producto_id = $producto_id";
            $conexion->query($sql_delete_carrito);
        }

        $conexion->commit();

        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        $conexion->rollback();
        echo "Error al procesar la transacción: " . $e->getMessage();
    }
} else {
    header("Location: ticket.php");
    exit();
}

$conexion->close();
?>