<?php
session_start();
include 'conexion.php';

// Muestra el mensaje de error si existe
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : "";

// Limpia la variable de sesión para que no se muestre en futuros inicios de sesión
unset($_SESSION['error_message']);

// Captura los mensajes de recuperación de contraseña
$message = isset($_GET['message']) ? $_GET['message'] : "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email']) && isset($_POST['contrasena'])) {
        $email = $_POST['email'];
        $contrasena = $_POST['contrasena'];

        $query = "SELECT * FROM usuarios WHERE email = '$email'";
        $resultado = mysqli_query($conexion, $query);
        $usuario = mysqli_fetch_assoc($resultado);

        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            if ($usuario['clave_activa']) {
                // Si clave_activa es true, evitar el inicio de sesión y mostrar un mensaje
                $error_message = "Debes completar el proceso de recuperación de tu contraseña antes de iniciar sesión.";
            } else {
                // Usuario verificado y clave_activa es false, permitir el inicio de sesión
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['apellido'] = $usuario['apellido'];
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];
                header("Location: index.php");
                exit;
            }
        } else {
            $error_message = "Lo sentimos, las credenciales que estás usando no son válidas.";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['credential'])) {
    $token = $_POST['credential'];
    $client_id = '687228683238-dcuren27i3njg7b6emtr6icrskgufdkr.apps.googleusercontent.com'; // Reemplaza con tu Client ID de Google

    $url = 'https://oauth2.googleapis.com/tokeninfo?id_token=' . $token;
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if ($data['aud'] === $client_id) {
        $email = $data['email'];

        // Revisa si el usuario ya existe en tu base de datos
        $query = "SELECT * FROM usuarios WHERE email = '$email'";
        $resultado = mysqli_query($conexion, $query);
        $usuario = mysqli_fetch_assoc($resultado);

        if ($usuario) {
            if ($usuario['clave_activa']) {
                $_SESSION['error_message'] = 'Debes completar el proceso de recuperación de tu contraseña antes de iniciar sesión.';
                header('Location: login.php');
                exit;
            } else {
                // Usuario ya existe, iniciar sesión
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['apellido'] = $usuario['apellido'];
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];
                header('Location: index.php');
                exit;
            }
        } else {
            $_SESSION['error_message'] = 'La cuenta no existe. Por favor, regístrate primero.';
            header('Location: login.php');
            exit;
        }
    } else {
        $_SESSION['error_message'] = 'Error en la autenticación con Google';
        header('Location: login.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="website icon" type="png" href="images/icon.png">
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<body>
  <div class="wrapper">
    <form method="post" autocomplete="off"> 
      <h1>Sport-Shop</h1>

        <!-- Muestra el mensaje de recuperación de contraseña -->
        <?php if($message == 'ok') { ?>
            <p class="success-message">La recuperación de la contraseña ha sido exitosa. Por favor revisa tu correo.</p>
        <?php } elseif($message == 'error') { ?>
            <p class="error-message">Hubo un problema al enviar el correo de recuperación. Intenta nuevamente.</p>
        <?php } elseif($message == 'not_found') { ?>
            <p class="error-message">El correo no está registrado o la cuenta esta registrada con Google.</p>
        <?php } elseif($message == 'link_invalid') { ?>
            <p class="error-message">El link de cambio de contraseña es inválido. Por favor solicita un nuevo link.</p>
        <?php } ?>


        <!-- Muestra el mensaje de error de inicio de sesión -->
        <?php if(!empty($error_message)) { ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php } ?>

      <div class="input-box">
        <input type="email" name="email" id="email" placeholder="Enter your email" required>
        <i class='bx bxs-user'></i>
      </div>

      <div class="input-box">
        <input type="password" name="contrasena" id="contrasena" placeholder="Enter your password" required>
        <i class='bx bx-show'></i>
        <button type="button" onclick="togglePasswordVisibility()" class="toggle-visibility">
          <i class='bx bx-show'></i>
        </button>
      </div>
      <div class="forgot-password-link">
        <p><a href="recuperar.php" class="forgot-password-btn">Forgot Password?</a></p>
      </div>

      <script>
        function togglePasswordVisibility() {
          var passwordInput = document.getElementById('contrasena');
          var toggleButton = document.querySelector('.toggle-visibility i');
          if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleButton.className = 'bx bx-hide';
          } else {
            passwordInput.type = 'password';
            toggleButton.className = 'bx bx-show';
          }
        }
      </script>

      <div class="button-container">
        <button type="submit" class="btn">Iniciar sesión</button>
        <!-- OR Separator -->
        <div class="or-separator">
        <span>OR</span>
        </div>

        <div id="g_id_onload"
             data-client_id="687228683238-dcuren27i3njg7b6emtr6icrskgufdkr.apps.googleusercontent.com"
             data-login_uri="http://localhost/Sport_shop/google_login.php"
             data-auto_prompt="false">
        </div>

        <div class="g_id_signin"
            data-type="standard"
            data-shape="pill"
            data-theme="filled_blue"
            data-text="sign_in_with"
            data-size="large"
            data-logo_alignment="left">
        </div>

        <div class="register-link">
          <p>¿Aún no tienes una cuenta? <a href="register.php">Regístrate</a></p>
        </div>
      </div>

    </form>
  </div>
</body>
</html>
