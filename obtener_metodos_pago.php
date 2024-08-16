<?php
include 'conexion.php';

$sql = "SELECT id, nombre FROM metodos_pago";

$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    $metodos_pago = array();

    while ($row = $resultado->fetch_assoc()) {
        $metodo_pago = array(
            "id" => $row['id'],
            "nombre" => $row['nombre']
        );
        $metodos_pago[] = $metodo_pago;
    }

    echo json_encode($metodos_pago);
} else {
    echo json_encode(array());
}

$conexion->close();
?>
