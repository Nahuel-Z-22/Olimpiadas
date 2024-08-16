<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Verificar si el token existe en la base de datos
    $sql = "SELECT id FROM usuarios WHERE token_recuperacion = '$token'";
    $result = $conexion->query($sql);
    
    if ($result->num_rows > 0) {
        // El token es válido, mostrar el formulario para cambiar la contraseña
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Restablecer Contraseña</title>
            <link rel="stylesheet" href="./css/styles.css">
        </head>
        <body>
            <div class="wrapper">
                <h1>Restablecer Contraseña</h1>
                <form action="procesar_cambio.php" method="post">
                    <input type="hidden" name="token" value="<?php echo $token; ?>">
                    <div class="input-box">
                        <input type="password" name="password" placeholder="Nueva Contraseña" required>
                    </div>
                    <div class="input-box">
                        <input type="password" name="confirm_password" placeholder="Confirmar Contraseña" required>
                    </div>
                    <div class="button-container">
                        <button type="submit" class="btn">Restablecer Contraseña</button>
                    </div>
                </form>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "El token proporcionado no es válido.";
    }
} else {
    echo "Acceso no autorizado.";
}
?>
