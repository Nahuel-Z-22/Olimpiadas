<?php
include 'conexion.php';
include 'index.php';

if(isset($_POST["productoid"], $_POST["cantidad"])) {
    $productoid = $_POST["productoid"];
    $cantidad = $_POST["cantidad"];

    $productoid = mysqli_real_escape_string($conexion, $productoid);
    $cantidad = mysqli_real_escape_string($conexion, $cantidad);

    // Obtener el stock disponible del producto
    $consulta_stock = "SELECT stock FROM productos WHERE id = '$productoid'";
    $resultado_stock = $conexion->query($consulta_stock);

    if($resultado_stock && $resultado_stock->num_rows > 0) {
        $fila_stock = $resultado_stock->fetch_assoc();
        $stock_disponible = $fila_stock['stock'];

        // Verificar si la cantidad solicitada es menor o igual al stock disponible
        if ($cantidad <= $stock_disponible) {
            $consulta_existencia = "SELECT * FROM carrito_compras WHERE usuario_id = '$usuario_id' AND producto_id = '$productoid'";
            $resultado_existencia = $conexion->query($consulta_existencia);

            if($resultado_existencia && $resultado_existencia->num_rows > 0) {
                $fila = $resultado_existencia->fetch_assoc();
                $nueva_cantidad = $fila['cantidad'] + $cantidad;
                // Limitar la cantidad al stock disponible
                if ($nueva_cantidad > $stock_disponible) {
                    $nueva_cantidad = $stock_disponible;
                }
                $actualizar_sql = "UPDATE carrito_compras SET cantidad = '$nueva_cantidad' WHERE usuario_id = '$usuario_id' AND producto_id = '$productoid'";
                
                if ($conexion->query($actualizar_sql) === TRUE) {
                    echo json_encode(array("success" => true, "message" => "Cantidad actualizada correctamente"));
                } else {
                    echo json_encode(array("success" => false, "message" => "Error al actualizar la cantidad: " . mysqli_error($conexion)));
                }
            } else {
                // Limitar la cantidad al stock disponible
                if ($cantidad > $stock_disponible) {
                    $cantidad = $stock_disponible;
                }
                $insertar_sql = "INSERT INTO carrito_compras (usuario_id, producto_id, cantidad) VALUES ('$usuario_id', '$productoid', '$cantidad')";
                if ($conexion && $conexion->query($insertar_sql) === TRUE) {
                    echo json_encode(array("success" => true, "message" => "Producto insertado correctamente"));
                } else {
                    echo json_encode(array("success" => false, "message" => "Error al insertar producto: " . mysqli_error($conexion)));
                }
            }
        } else {
            echo json_encode(array("success" => false, "message" => "La cantidad solicitada supera el stock disponible"));
        }
    } else {
        echo json_encode(array("success" => false, "message" => "Error al obtener el stock del producto"));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Error: Parámetros incompletos"));
}

$conexion->close();
?>
