<?php
// actualizar_producto.php

include '../conexion.php';

// Obtener datos del formulario de edición
$id = $_POST['producto_id'];
$nombre = $_POST['editar_nombre'];
$descripcion = $_POST['editar_descripcion'];
$precio = $_POST['editar_precio'];
$stock = $_POST['editar_stock'];
$categoria_id = $_POST['editar_categoria_id'];
$marca = $_POST['editar_marca'];
$modelo = $_POST['editar_modelo'];

// Manejar la imagen si se está cargando una nueva
if(isset($_FILES['editar_imagen']) && $_FILES['editar_imagen']['error'] === UPLOAD_ERR_OK){
    $imagen = $_FILES['editar_imagen']['name'];
    $ruta = $_FILES['editar_imagen']['tmp_name'];
    $destino = "../imagenes/productos/".$imagen;
    move_uploaded_file($ruta, $destino);
} else {
    // Si no se cargó una nueva imagen, mantener la imagen existente en la base de datos
    $imagen = $_POST['imagen_existente']; // Asegúrate de tener un campo oculto en el formulario que almacene la ruta de la imagen actual
}


// Actualizar el producto en la base de datos
$sql = "UPDATE productos SET nombre='$nombre', descripcion='$descripcion', precio='$precio', stock='$stock', categoria_id='$categoria_id', marca='$marca', modelo='$modelo', imagen='$imagen' WHERE id='$id'";

$response = array();

if ($conexion->query($sql) === TRUE) {
    $response['success'] = true;
    $response['message'] = "Producto actualizado correctamente";
} else {
    $response['success'] = false;
    $response['message'] = "Error al actualizar el producto: " . $conexion->error;
}

echo json_encode($response);

$conexion->close();
?>
