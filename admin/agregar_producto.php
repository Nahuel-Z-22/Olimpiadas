<?php
// Conexión a la base de datos
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir y validar los datos del formulario
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $categoria_id = $_POST['categoria_id'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];

    // Obtener el nombre de la categoría específica
    $sql_categoria = "SELECT nombre FROM categorias WHERE id = $categoria_id";
    $resultado_categoria = mysqli_query($conexion, $sql_categoria);
    $categoria = mysqli_fetch_assoc($resultado_categoria);
    $nombre_categoria = strtolower($categoria['nombre']); // Convertir a minúsculas

    // Ajustar el nombre de la carpeta según las especificaciones dadas
    switch ($nombre_categoria) {
        case 'futbol':
            $nombre_carpeta = 'futbòl';
            break;
        default:
            // Si la categoría no coincide con ninguna de las anteriores, se usa el nombre original
            $nombre_carpeta = $nombre_categoria;
            break;
    }

    // Construir la ruta de la imagen con la subcarpeta específica
    $ruta_imagen = "/images/$nombre_carpeta/" . $_FILES['imagen']['name'];

    // Verificar si la carpeta específica existe, si no, crearla
    $ruta_carpeta_especifica = "../images/$nombre_carpeta";
    if (!file_exists($ruta_carpeta_especifica)) {
        mkdir($ruta_carpeta_especifica, 0777, true); // Se crea la carpeta con permisos de lectura y escritura
    }

    // Mover la imagen a la carpeta correspondiente
    move_uploaded_file($_FILES['imagen']['tmp_name'], "../$ruta_imagen");

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
