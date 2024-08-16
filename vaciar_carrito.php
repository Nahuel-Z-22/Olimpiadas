<?php
// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

// Iniciar la sesión si no está iniciada
session_start();

// Verificar si hay una sesión de usuario activa
if (isset($_SESSION['usuario_id'])) {
    // Consulta SQL para eliminar todos los productos del carrito del usuario actual
    $sql_delete = "DELETE FROM carrito_compras WHERE usuario_id = ?";

    // Preparar la consulta
    $stmt_delete = $conexion->prepare($sql_delete);
    if ($stmt_delete) {
        // Vincular parámetros
        $stmt_delete->bind_param("i", $_SESSION['usuario_id']);

        // Ejecutar la consulta
        $stmt_delete->execute();

        // Cerrar la declaración
        $stmt_delete->close();

        // Enviar una respuesta de éxito
        echo json_encode(array('success' => true));
    } else {
        // Si hay un error en la preparación de la consulta
        echo json_encode(array('success' => false, 'error' => 'Error al vaciar el carrito.'));
    }
} else {
    // Si no hay sesión de usuario activa
    echo json_encode(array('success' => false, 'error' => 'No hay sesión de usuario activa.'));
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>
