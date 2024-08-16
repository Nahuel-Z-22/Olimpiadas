<?php
// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

// Iniciar la sesión si no está iniciada
session_start();

// Verificar si hay una sesión de usuario activa
if (isset($_SESSION['usuario_id'])) {
    // Consulta SQL para contar los productos en el carrito del usuario actual
    $sql_count = "SELECT COUNT(*) AS total_productos FROM carrito_compras WHERE usuario_id = ?";
    
    // Preparar la consulta
    $stmt_count = $conexion->prepare($sql_count);
    if ($stmt_count) {
        // Vincular parámetros
        $stmt_count->bind_param("i", $_SESSION['usuario_id']);

        // Ejecutar la consulta
        $stmt_count->execute();

        // Obtener el resultado
        $result_count = $stmt_count->get_result();
        
        // Obtener el número de productos
        $row_count = $result_count->fetch_assoc();
        $total_productos = $row_count['total_productos'];

        // Cerrar la declaración
        $stmt_count->close();
        
        // Enviar el número de productos como respuesta
        echo json_encode(array('success' => true, 'total_productos' => $total_productos));
    } else {
        // Si hay un error en la preparación de la consulta
        echo json_encode(array('success' => false, 'error' => 'Error al contar los productos del carrito.'));
    }
} else {
    // Si no hay sesión de usuario activa
    echo json_encode(array('success' => false, 'error' => 'No hay sesión de usuario activa.'));
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>
