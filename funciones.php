<?php
function obtenerProductoPorId($id) {
    // Conexión a la base de datos
    $conexion = new mysqli('localhost', 'root', '', 'sport_shop');
    
    // Comprobamos si hay algún error en la conexión
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Preparamos la consulta
    $stmt = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    // Obtenemos el resultado
    $resultado = $stmt->get_result();
    
    // Devolvemos el producto si existe, o NULL si no se encontró
    if ($resultado->num_rows > 0) {
        return $resultado->fetch_assoc();
    } else {
        return NULL;
    }
}
?>