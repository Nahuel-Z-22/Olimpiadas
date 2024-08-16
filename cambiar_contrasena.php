<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['nombre']) || !isset($_SESSION['apellido'])) {
    header("Location: login.php"); // Redireccionar a la página de inicio de sesión si no está autenticado
    exit;
}

// Conexión a la base de datos
include 'conexion.php';

// Inicializar variables
$error_contrasena = "";
$mensaje_exito = "";

// Verificar si se envió el formulario de cambio de contraseña
if (isset($_POST['cambiar_contrasena'])) {
    // Recibir datos del formulario
    $contrasena_actual = $_POST['contrasena_actual'];
    $nueva_contrasena = $_POST['nueva_contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    // Verificar la contraseña actual
    $usuario_id = $_SESSION['usuario_id'];
    $query = "SELECT contrasena FROM usuarios WHERE id = $usuario_id";
    $resultado = mysqli_query($conexion, $query);
    $usuario = mysqli_fetch_assoc($resultado);

    if (!password_verify($contrasena_actual, $usuario['contrasena'])) {
        $error_contrasena = "La contraseña actual es incorrecta";
    } elseif ($nueva_contrasena === $contrasena_actual) {
        $error_contrasena = "La nueva contraseña debe ser diferente a la contraseña actual";
    } elseif ($nueva_contrasena != $confirmar_contrasena) {
        $error_contrasena = "Las contraseñas nuevas no coinciden";
    } else {
        // Procesar el cambio de contraseña
        $hashed_nueva_contrasena = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
        $update_query = "UPDATE usuarios SET contrasena = '$hashed_nueva_contrasena' WHERE id = $usuario_id";
        if (mysqli_query($conexion, $update_query)) {
            $mensaje_exito = "¡La contraseña se ha cambiado correctamente!";
        } else {
            $error_contrasena = "Error al cambiar la contraseña. Por favor, inténtalo de nuevo.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Contraseña</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: url(./images/fondo.jpg) no-repeat;
            background-size: cover;
            background-position: center;
        }

        .wrapper {
            width: 420px;
            background: transparent;
            border: 2px solid rgba(255, 255, 255, .2);
            backdrop-filter: blur(9px);
            color: #fff;
            border-radius: 12px;
            padding: 30px 40px;
        }

        .wrapper h1 {
            font-size: 35px;
            text-align: center;
            margin-bottom: 30px;
        }

        .input-box {
            position: relative;
            width: 100%;
            margin-bottom: 30px;
        }

        .input-box input {
            width: 100%;
            height: 50px;
            background: transparent;
            border: none;
            outline: none;
            border: 2px solid rgba(255, 255, 255, .2);
            border-radius: 40px;
            font-size: 16px;
            color: #fff;
            padding: 0 20px;
        }

        .input-box input::placeholder {
            color: #fff;
        }

        .input-box i {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: rgba(255, 255, 255, 0.7);
        }

        .button-container {
            text-align: center;
        }

        .btn {
            width: 100%;
            height: 45px;
            background: #fff;
            border: none;
            outline: none;
            border-radius: 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, .1);
            cursor: pointer;
            font-size: 16px;
            color: #333;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .register-link {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            display: block;
            text-align: center;
        }

        .register-link:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }

        .success-message {
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <h1>Cambiar Contraseña</h1>

    <form method="post">
        <?php if (!empty($error_contrasena)) { ?>
            <p class="error-message"><?php echo $error_contrasena; ?></p>
        <?php } ?>
        <?php if (!empty($mensaje_exito)) { ?>
            <p class="success-message"><?php echo $mensaje_exito; ?></p>
        <?php } ?>
        <div class="input-box">
            <input type="password" id="contrasena_actual" name="contrasena_actual" placeholder="Contraseña Actual" required>
            <i class="fas fa-lock"></i>
        </div>
        <div class="input-box">
            <input type="password" id="nueva_contrasena" name="nueva_contrasena" placeholder="Nueva Contraseña" required>
            <i class="fas fa-lock"></i>
        </div>
        <div class="input-box">
            <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" placeholder="Confirmar Contraseña" required>
            <i class="fas fa-lock"></i>
        </div>
        <div class="button-container">
            <button type="submit" name="cambiar_contrasena" class="btn">Cambiar Contraseña</button>
            <a href="perfil.php" class="register-link">Volver al Perfil de Usuario</a>
        </div>
    </form>
</div>
</body>
</html>
