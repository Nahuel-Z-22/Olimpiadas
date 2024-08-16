<?php
// Conexión a la base de datos
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir y validar los datos del formulario
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $categoria_id = $_POST['categoria_id'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];

    // Obtener la categoría del producto
    $sql_categoria = "SELECT nombre FROM categorias WHERE id = $categoria_id";
    $resultado_categoria = mysqli_query($conexion, $sql_categoria);
    $categoria = mysqli_fetch_assoc($resultado_categoria);
    $nombre_categoria = $categoria['nombre'];

    // Construir la ruta de la imagen con la subcarpeta de la categoría
    $ruta_imagen = "/images/$nombre_categoria/" . $_FILES['imagen']['name']; 

    // Mover la imagen a la carpeta correspondiente
    move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_imagen);

    // Insertar el producto en la base de datos
    $query = "INSERT INTO productos (nombre, descripcion, precio, stock, categoria_id, marca, modelo, imagen) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ssiiisss", $nombre, $descripcion, $precio, $stock, $categoria_id, $marca, $modelo, $ruta_imagen);

    if ($stmt->execute()) {
        // Producto agregado correctamente
        echo json_encode(array("success" => true));
    } else {
        // Error al agregar el producto
        echo json_encode(array("success" => false, "error" => "Error al agregar el producto."));
    }

    $stmt->close();
    $conexion->close();
}
?>