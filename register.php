<?php
// Conexión a la base de datos
include 'conexion.php';

// Inicializar la variable de mensaje de error
$error_message = "";

// Verificar si se envió el formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['credential'])) {
    // Recibir datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $contrasena = $_POST['contrasena'];

    // Verificar si el email ya está registrado
    $check_query = "SELECT * FROM usuarios WHERE email = '$email'";
    $check_result = mysqli_query($conexion, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $error_message = "El email ya está registrado. Por favor, inicia sesión.";
    } else {
        // Hash de la contraseña
        $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

        // Insertar usuario en la base de datos
        $query = "INSERT INTO usuarios (nombre, apellido, email, contrasena) VALUES ('$nombre', '$apellido', '$email', '$contrasena_hash')";
        $resultado = mysqli_query($conexion, $query);

        if ($resultado) {
            // Redireccionar al usuario a la página de inicio de sesión
            header("Location: login.php");
            exit;
        } else {
            $error_message = "Error al registrar el usuario: " . mysqli_error($conexion);
        }
    }
}

// Verificar si se envió el formulario de registro con Google
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['credential'])) {
    $token = $_POST['credential'];
    $client_id = '687228683238-dcuren27i3njg7b6emtr6icrskgufdkr.apps.googleusercontent.com'; // Reemplaza con tu Client ID de Google

    $url = 'https://oauth2.googleapis.com/tokeninfo?id_token=' . $token;
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if ($data['aud'] === $client_id) {
        $email = $data['email'];
        $nombre = $data['given_name'];
        $apellido = $data['family_name'];

        // Revisa si el usuario ya existe en tu base de datos
        $query = "SELECT * FROM usuarios WHERE email = '$email'";
        $resultado = mysqli_query($conexion, $query);
        $usuario = mysqli_fetch_assoc($resultado);

        if ($usuario) {
            // Usuario ya existe, redirigir a login.php
            header("Location: login.php");
            exit;
        } else {
            // Usuario no existe, crear nuevo registro
            $insert_query = "INSERT INTO usuarios (nombre, apellido, email, tipo_usuario) VALUES ('$nombre', '$apellido', '$email', 'usuario')";
            mysqli_query($conexion, $insert_query);

            // Redirigir a la página de inicio de sesión
            header("Location: login.php");
            exit;
        }
    } else {
        $error_message = 'Error en la autenticación con Google';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="website icon" type="png" href="images/icon.png">
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<body>
  <div class="wrapper">
    <form method="post" class="log-in" autocomplete="off"> 
      <h1>Registro de Usuario</h1>
      <?php if(!empty($error_message)) { ?>
            <p class="error-message"><?php echo $error_message; ?></p>
      <?php } ?>
      <div class="input-box">
        <input type="text" name="nombre" id="nombre" placeholder="Nombre" required>
        <i class='bx bxs-user'></i>
      </div>
      <div class="input-box">
        <input type="text" name="apellido" id="apellido" placeholder="Apellido" required>
        <i class='bx bxs-user'></i>
      </div>
      <div class="input-box">
        <input type="email" name="email" id="email" placeholder="Email" required>
        <i class='bx bx-envelope'></i>
      </div>
      <div class="input-box">
        <input type="password" name="contrasena" id="contrasena" placeholder="Contraseña" required>
        <i class='bx bxs-lock-alt'></i>
      </div>
      <div class="button-container">
        <button type="submit" class="btn">Registrar</button>
        <a href="index.php" class="register-link">Exit</a>
      </div>

        <!-- OR Separator -->
        <div class="or-separator">
        <span>OR</span>
        </div>

      <div class="g_id_signin"
            data-type="standard"
            data-shape="pill"
            data-theme="filled_blue"
            data-text="sign_in_with"
            data-size="large"
            data-logo_alignment="left">
        </div>
      
        <div class="button-container-row">
        <a href="login.php" class="btn-exit">Volver</a>
        <a href="index.php" class="btn-exit">Exit</a>
        </div>



      <div id="g_id_onload"
           data-client_id="687228683238-dcuren27i3njg7b6emtr6icrskgufdkr.apps.googleusercontent.com"
           data-login_uri="http://localhost/Sport_shop/register.php"
           data-auto_prompt="false">
      </div>
    </form>
  </div>
</body>
</html>