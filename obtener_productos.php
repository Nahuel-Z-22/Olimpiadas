<?php
include 'conexion.php';

if(isset($_GET['categoria'])) {
    $categoria_id = $_GET['categoria'];
    
    $query = "SELECT productos.*, categorias.nombre AS nombre_categoria 
              FROM productos 
              INNER JOIN categorias ON productos.categoria_id = categorias.id 
              WHERE categoria_id = $categoria_id"; 
} else {
    $query = "SELECT * FROM productos";
}

$resultado = mysqli_query($conexion, $query);

$productos = array();
while ($row = mysqli_fetch_assoc($resultado)) {
    $productos[] = $row;
}

header('Content-Type: application/json');
echo json_encode($productos);
?>
