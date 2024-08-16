<?php
// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

// Obtener el ID de usuario de la solicitud GET
$usuario_id = $_GET['usuario_id'];

// Consulta SQL para obtener las transacciones del usuario actual, incluyendo el estado del pedido
$sql = "SELECT p.imagen, p.nombre AS titulo, t.cantidad, p.precio, (t.cantidad * p.precio) AS subtotal, ped.estado AS estado_pedido
        FROM transacciones t
        INNER JOIN productos p ON t.producto_id = p.id
        INNER JOIN pedidos ped ON t.pedido_id = ped.id
        WHERE t.usuario_id = ?";

// Preparar la consulta
$stmt = $conexion->prepare($sql);
if ($stmt) {
    // Vincular parámetros e ID de usuario
    $stmt->bind_param("i", $usuario_id);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener resultados
    $result = $stmt->get_result();

    // Crear un array para almacenar las transacciones del usuario
    $transacciones_usuario = array();

    // Iterar sobre los resultados y almacenar los detalles de cada transacción
    while ($row = $result->fetch_assoc()) {
        $transaccion = array(
            "imagen" => $row['imagen'],
            "titulo" => $row['titulo'],
            "cantidad" => $row['cantidad'],
            "precio" => $row['precio'],
            "subtotal" => $row['subtotal'],
            "estado_pedido" => $row['estado_pedido']  // Añadir el estado del pedido
        );
        // Agregar la transacción al array
        $transacciones_usuario[] = $transaccion;
    }

    // Liberar el resultado y cerrar la declaración
    $stmt->close();
} else {
    // Si hay un error en la preparación de la consulta
    echo "Error en la preparación de la consulta.";
}

// Convertir el array de transacciones del usuario a formato JSON y enviarlo como respuesta
echo json_encode($transacciones_usuario);

// Cerrar la conexión a la base de datos
$conexion->close();
?>
