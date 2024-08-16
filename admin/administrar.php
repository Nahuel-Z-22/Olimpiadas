<?php
session_start();
include '../conexion.php';
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
$usuario_id = $_SESSION['usuario_id'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electro-Shop - Administración</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="website icon" type="png" href="../images/icon.png">
</head>
<body>
    <div class="wrapper">
        <header class="header-mobile">
            <h1 class="logo">Electro-Shop - Admin</h1>
            <button class="open-menu" id="open-menu">
                <i class="bi bi-list"></i>
            </button>
        </header>
        <aside>
            <button class="close-menu" id="close-menu">
                <i class="bi bi-x"></i>
            </button>
            <header>
                <h1 class="logo">Electro-Shop - Admin</h1>
            </header>
            <nav>
                <ul class="menu">
                    <li>
                        <button id="agregar-productos" class="boton-menu active">
                            <i class="bi bi-box-seam"></i> Agregar producto
                        </button>
                    </li>
                    <li>
                        <button id="gestionar-usuarios" class="boton-menu">
                            <i class="bi bi-people"></i> Gestión de Usuarios
                        </button>
                    </li>
                    <li>
                        <button id="gestionar-categorias" class="boton-menu">
                            <i class="bi bi-grid"></i> Gestionar categorías
                        </button>
                    </li>
                    <li>
                        <button id="gestionar-metodopago" class="boton-menu">
                            <i class="bi bi-credit-card"></i> Gestionar metodo de pago
                        </button>
                    </li>
                    <li>
                        <button id="gestionar-transacciones" class="boton-menu">
                            <i class="bi bi-currency-exchange"></i> Ver transacciones
                        </button>
                    </li>
                    <li>
                        <button id="gestionar-pedidos" class="boton-menu">
                            <i class="bi bi-alarm"></i> Ver pedidos
                        </button>
                    </li>
                    <li>
                        <a class="boton-menu" href="../index.php">
                            <i class="bi bi-box-arrow-right"></i> Volver al principio
                        </a>
                    </li>
                    <ul class="menu">
                    <li>
                        <?php
                        if(isset($_SESSION['nombre']) && isset($_SESSION['apellido'])) {
                            echo '<span class="usuario-li">';
                            echo '<i class="bi bi-person-fill"></i>'; // Icono de persona
                            echo '<span class="nombre-usuario">' . $_SESSION['nombre'] . ' ' . $_SESSION['apellido'] . '</span>';
                            echo '</span>';
                        }
                        ?>
                    </li>
                    </ul>
                </ul>
            </nav>
            <footer>
                <p class="texto-footer">© 2024 Kevin Lionel, Benjamin 7 IV</p>
            </footer>
        </aside>
        <main>
            <h2 class="titulo-principal">Panel de Administración</h2>
            <div id="contenido-administracion"></div>
        </main>
    </div>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="../js/menu.js"></script>
    <script src="administrar.js"></script>
</body>
</html>