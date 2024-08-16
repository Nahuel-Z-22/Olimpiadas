<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sport-Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="./css/main.css">
    <script> var usuarioId = <?php echo isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 'null'; ?>; </script>
    <link rel="website icon" type="png" href="images\icon.png">
</head>
<body>
    <div class="wrapper">
        <header class="header-mobile">
            <h1 class="logo">Sport-Shop</h1>
            <button class="open-menu" id="open-menu">
                <i class="bi bi-list"></i>
            </button>
        </header>
        <aside>
            <button class="close-menu" id="close-menu">
                <i class="bi bi-x"></i>
            </button>
            <header>
                <h1 class="logo">Sport-Shop</h1>
            </header>
            <nav>
                <ul>
                    <li>
                        <a class="boton-menu boton-volver" href="./carrito.php">
                            <i class="bi bi-arrow-return-left"></i> Regresar al carrito
                        </a>
                    </li>
                    <li>
                        <a class="boton-menu boton-carrito active" href="./ticket.php">
                            <i class="bi bi-cart-fill"></i> Ticket
                        </a>
                    </li>
                    <li>
                        <?php
                        if(isset($_SESSION['nombre']) && isset($_SESSION['apellido'])) {
                            echo '<a class="boton-menu boton-iniciar-sesion" href="perfil.php">' . $_SESSION['nombre'] . ' ' . $_SESSION['apellido'] . '</a>';
                        } else {
                            echo '<a class="boton-menu boton-iniciar-sesion" href="./login.php"><i class="bi bi-person-circle"></i> Iniciar sesión</a>';
                        }
                        ?>
                    </li>
                </ul>
            </nav>
            <footer>
                <p class="texto-footer">© 2024 Kevin Lionel, Benjamin 7 IV</p>
            </footer>
        </aside>
        <main>
            <h2 class="titulo-principal">Detalle de ticket</h2>
            <div class="contenedor-carrito">
                <p id="carrito-vacio" class="carrito-vacio">Tu carrito está vacío. <i class="bi bi-emoji-frown"></i></p>

                <div id="carrito-productos" class="carrito-productos disabled">
                    <!-- Esto se va a completar con el JS -->
                </div>

                <div id="carrito-acciones" class="carrito-acciones disabled">
                    <div></div>
                    <div class="carrito-acciones-derecha">
                        <div class="carrito-acciones-total">
                            <p>Total:</p>
                            <p id="total">$3000</p>
                        </div>
                        <select id="metodo-pago" name="metodo-pago">
                            <option value="">Selecciona tu método de pago</option>
                            <!-- Las opciones se llenarán dinámicamente con JavaScript -->
                        </select>
                        <button id="procesar-transaccion">Procesar Transacción</button>
                    </div>
                </div>

                <p id="carrito-comprado" class="carrito-comprado disabled">Muchas gracias por tu compra. <i class="bi bi-emoji-laughing"></i></p>

            </div>
        </main>
    </div>
    
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./js/ticket.js"></script>
    <script src="./js/menu.js"></script>
</body>
</html>