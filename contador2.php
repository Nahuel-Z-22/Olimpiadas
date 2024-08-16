<?php
// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

// Obtener el ID de usuario de la solicitud GET
$usuario_id = $_GET['usuario_id'];

// Consulta SQL para obtener la suma de los productos en el carrito del usuario actual
$sql = "SELECT SUM(p.precio * cc.cantidad) AS total_productos
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

    // Obtener el total de productos en el carrito
    $total_productos = 0;

    // Verificar si se obtuvieron resultados
    if ($row = $result->fetch_assoc()) {
        $total_productos = $row['total_productos'];
    }

    // Crear un array para almacenar el resultado
    $response = array(
        "success" => true,
        "total_productos" => $total_productos
    );

    // Enviar la respuesta como JSON
    echo json_encode($response);

    // Liberar el resultado y cerrar la declaración
    $stmt->close();
} else {
    // Si hay un error en la preparación de la consulta
    $response = array(
        "success" => false,
        "error" => "Error en la preparación de la consulta."
    );
    echo json_encode($response);
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>
