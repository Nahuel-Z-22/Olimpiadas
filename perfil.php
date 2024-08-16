<?php
session_start();

if (!isset($_SESSION['nombre']) || !isset($_SESSION['apellido'])) {
    header("Location: login.php");
    exit;
}

$es_administrador = $_SESSION['tipo_usuario'] === 'admin' ?? false;
$mostrar_cambiar_contrasena = false;

try {
    $dsn = "mysql:host=localhost;dbname=sport_shop;charset=utf8mb4";
    $username = "root";
    $password = "";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $conexion = new PDO($dsn, $username, $password, $options);

    // Verificar si el usuario tiene una contraseña registrada
    $stmt = $conexion->prepare("SELECT contrasena FROM usuarios WHERE email = :email");
    $stmt->bindParam(':email', $_SESSION['email']);
    $stmt->execute();
    $resultado = $stmt->fetch();

    if ($resultado && !empty($resultado['contrasena'])) {
        $mostrar_cambiar_contrasena = true;
    }
} catch (PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cerrar_sesion'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="icon" type="image/png" href="images/icon.png">
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
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, .2);
            backdrop-filter: blur(9px);
            color: #fff;
            border-radius: 12px;
            padding: 30px 40px;
        }

        .wrapper h1 {
            font-size: 36px;
            text-align: center;
            margin-bottom: 30px;
        }

        .user-info {
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .user-info p {
            margin-bottom: 15px;
        }

        .button-container, .btn-container {
            text-align: center;
            margin-top: 20px;
        }

        .btn {
            width: 200px;
            height: 45px;
            background: #fff;
            border: none;
            border-radius: 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, .1);
            cursor: pointer;
            font-size: 16px;
            color: #333;
            font-weight: 600;
            display: block;
            margin: 10px auto;
        }

        .register-link {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            display: block;
            text-align: center;
            margin-top: 15px;
        }

        .register-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <h1>Sport Shop</h1>
    <div class="user-info">
        <p>Nombre: <?= htmlspecialchars($_SESSION['nombre'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p>Apellido: <?= htmlspecialchars($_SESSION['apellido'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p>Correo electrónico: <?= htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8'); ?></p>
    </div>

    <form method="post">
        <button type="submit" name="cerrar_sesion" class="btn">Cerrar Sesión</button>
    </form>

    <div class="btn-container">
        <form action="index.php" method="post">
            <button type="submit" class="btn btn-small">Volver al inicio</button>
        </form>
    </div>

    <?php if ($mostrar_cambiar_contrasena): ?>
        <div class="button-container">
            <a href="cambiar_contrasena.php" class="register-link">¿Quieres cambiar tu contraseña?</a>
        </div>
    <?php endif; ?>

    <?php if ($es_administrador): ?>
        <div class="button-container">
            <a href="admin/administrar.php" class="register-link">Administrar</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
