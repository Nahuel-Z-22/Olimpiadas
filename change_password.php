<?php
require 'conexion.php';  // Asegúrate de que este path es correcto

session_start();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = $_GET['id'];

    // Verificar si el ID es válido y si clave_activa es TRUE
    $checkQuery = "SELECT clave_activa FROM usuarios WHERE id = '$userId'";
    $result = $conexion->query($checkQuery);
    $user = $result->fetch_assoc();

    if ($result->num_rows == 0 || $user['clave_activa'] == 0) {
        // Si no se encuentra el usuario o clave_activa es FALSE, redireccionar con mensaje
        header("Location: login.php?message=link_invalid");
        exit;
    }
} else {
    header("Location: login.php?message=invalid_request");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_password'])) {
    $newPassword = $_POST['new_password'];
    $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
    $updatePasswordQuery = "UPDATE usuarios SET contrasena = '$newPasswordHash', clave_activa = FALSE WHERE id = '$userId'";
    if ($conexion->query($updatePasswordQuery)) {
        header("Location: login.php?message=reset_success");
        exit;
    } else {
        header("Location: change_password.php?id=$userId&error=unable_to_update");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="icon" type="image/png" href="images/icon.png">
    <link rel="stylesheet" href="./css/recuperar.css">
</head>
<body>
    <div class="wrapper">
        <h1>Recupera tu contraseña</h1>
        <?php
        if (isset($_GET['error'])) {
            echo "<p style='color: red;'>Error al actualizar la contraseña. Por favor, inténtalo de nuevo.</p>";
        }
        ?>
        <form action="" method="POST">
            <div class="input-box">
                <label for="new_password">Ingrese su nueva contraseña</label>
                <input type="password" id="new_password" name="new_password" placeholder="Introduce nueva contraseña" required>
                <input type="hidden" name="id" value="<?php echo $userId; ?>">
            </div>
            <div class="button-container">
                <button type="submit" class="btn">Recuperar contraseña</button>
            </div>
        </form>
    </div>
</body>
</html>
