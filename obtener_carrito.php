<?php
// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

// Obtener el ID de usuario de la solicitud GET
$usuario_id = $_GET['usuario_id'];

// Consulta SQL para obtener los productos en el carrito del usuario actual
$sql = "SELECT p.id, p.imagen, p.nombre AS titulo, p.precio, cc.cantidad
        FROM carrito_compras cc
        INNER JOIN productos p ON cc.producto_id = p.id
        WHERE cc.usuario_id = ?";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
if ($stmt) {
    // Vincular parámetros e ID de usuario
    $stmt->bind_param("i", $usuario_id);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener resultados
    $result = $stmt->get_result();

    // Crear un array para almacenar los productos del carrito
    $productos_carrito = array();

    // Iterar sobre los resultados y almacenar los detalles de cada producto en el carrito
    while ($row = $result->fetch_assoc()) {
        $producto = array(
            "id" => $row['id'],
            "imagen" => $row['imagen'],
            "titulo" => $row['titulo'],
            "precio" => $row['precio'],
            "cantidad" => $row['cantidad']
        );
        // Agregar el producto al array
        $productos_carrito[] = $producto;
    }

    // Liberar el resultado y cerrar la declaración
    $stmt->close();
} else {
    // Si hay un error en la preparación de la consulta
    echo "Error en la preparación de la consulta.";
}

// Convertir el array de productos del carrito a formato JSON y enviarlo como respuesta
echo json_encode($productos_carrito);

// Cerrar la conexión a la base de datos
$conexion->close();
?>
