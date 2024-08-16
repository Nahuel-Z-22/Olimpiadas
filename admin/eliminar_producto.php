<?php
include '../conexion.php';

$data = json_decode(file_get_contents('php://input'), true);

$producto_id = $data['producto_id'];

// Obtener la ruta de la imagen del producto a eliminar
$sql_imagen = "SELECT imagen FROM productos WHERE id='$producto_id'";
$resultado_imagen = mysqli_query($conexion, $sql_imagen);
$imagen_producto = mysqli_fetch_assoc($resultado_imagen)['imagen'];

// Eliminar el registro del producto de la base de datos
$sql_delete = "DELETE FROM productos WHERE id='$producto_id'";
$response = array();

if ($conexion->query($sql_delete) === TRUE) {
    // Eliminar el archivo de imagen correspondiente en el sistema de archivos
    $ruta_imagen = "../" . $imagen_producto;
    if (file_exists($ruta_imagen)) {
        unlink($ruta_imagen); // Eliminar el archivo
    }
    
    $response['success'] = true;
    $response['message'] = "Producto eliminado correctamente";
} else {
    $response['success'] = false;
    $response['message'] = "Error al eliminar el producto: " . $conexion->error;
}

echo json_encode($response);

$conexion->close();
?>
