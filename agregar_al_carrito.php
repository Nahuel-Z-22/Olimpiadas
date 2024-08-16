<?php
include 'funciones.php';

// Asegurarse de que el usuario está autenticado
session_start();
if (!isset($_SESSION['usuario_id'])) {
    echo "<script>alert('Por favor, inicie sesión para continuar.'); window.location.href='login.php';</script>";
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$producto_id = $_POST['idProducto'];
$cantidad = $_POST['qty'];

// Conectar a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'sport_shop');
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Verificar si el producto ya está en el carrito del usuario
$stmt = $conexion->prepare("SELECT cantidad FROM carrito_compras WHERE usuario_id = ? AND producto_id = ?");
$stmt->bind_param("ii", $usuario_id, $producto_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    // Si el producto ya está, actualizar la cantidad
    $row = $result->fetch_assoc();
    $nueva_cantidad = $row['cantidad'] + $cantidad;
    $stmt = $conexion->prepare("UPDATE carrito_compras SET cantidad = ? WHERE usuario_id = ? AND producto_id = ?");
    $stmt->bind_param("iii", $nueva_cantidad, $usuario_id, $producto_id);
} else {
    // Si el producto no está, insertar nuevo registro
    $stmt = $conexion->prepare("INSERT INTO carrito_compras (usuario_id, producto_id, cantidad) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $usuario_id, $producto_id, $cantidad);
}

// Ejecutar la sentencia
if ($stmt->execute()) {
    echo "<script>window.location.href='carrito.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}

// Cerrar la conexión
$stmt->close();
$conexion->close();
?>
