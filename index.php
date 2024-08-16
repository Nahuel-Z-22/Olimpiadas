<?php
session_start();
include 'conexion.php';
if(isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];
    echo "<script>console.log('Usuario con ID $usuario_id conectado');</script>";
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
                <ul class="menu">
                    <li>
                        <button id="todos" class="boton-menu boton-categoria active"><i class="bi bi-hand-index-thumb-fill"></i> Todos los productos</button>
                    </li>
                    <li>
                        <button id="1" class="boton-menu boton-categoria"><i class="bi bi-hand-index-thumb"></i> Fútbol</button>
                    </li>
                    <li>
                        <button id="2" class="boton-menu boton-categoria"><i class="bi bi-hand-index-thumb"></i> Senderismo</button>
                    </li>
                    <li>
                        <button id="3" class="boton-menu boton-categoria"><i class="bi bi-hand-index-thumb"></i> Ciclismo</button>
                    </li>
                    <li>
                        <button id="4" class="boton-menu boton-categoria"><i class="bi bi-hand-index-thumb"></i> Escalada</button>
                    </li>
                    <li>
                        <button id="5" class="boton-menu boton-categoria"><i class="bi bi-hand-index-thumb"></i> Running</button>
                    </li>
                    <li>
                        <button id="6" class="boton-menu boton-categoria"><i class="bi bi-hand-index-thumb"></i> Tenis</button>
                    </li>
                    <li>
                        <a class="boton-menu boton-carrito" href="./carrito.php">
                            <i class="bi bi-cart-fill"></i> Carrito <span id="numerito" class="numerito">0</span>
                        </a>
                        <a class="boton-menu boton-carrito" href="./pagina_compras.php">
                            <i class="bi bi-bag-fill"></i> Compras
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
                <p class="texto-footer">© 2024 Kevin Lionel, Benjamin 7 IV
                </p>
            </footer>
        </aside>
        <main>
        <?php
            if (isset($_GET['categoria'])) {
                $categoria_id = $_GET['categoria'];
                $query = "SELECT * FROM productos WHERE categoria_id = $categoria_id";
                $query_categoria = "SELECT nombre FROM categorias WHERE id = $categoria_id";
                $resultado_categoria = mysqli_query($conexion, $query_categoria);
                $categoria = mysqli_fetch_assoc($resultado_categoria);
                $titulo_principal = "Productos de la categoría " . $categoria['nombre'];
            } else {
                $query = "SELECT * FROM productos";
                $titulo_principal = "Todas las categorias";
            }

            $resultado = mysqli_query($conexion, $query);
            $productos = array();
            while ($row = mysqli_fetch_assoc($resultado)) {
                $productos[] = $row;
            }
            ?>
            
            <h2 class="titulo-principal" id="titulo-principal">Todas las categorias</h2>
            <div id="contenedor-productos" class="contenedor-productos">
                <!-- Aquí se cargarán los productos dinámicamente con JavaScript -->
            </div>
        </main>
    </div>
    
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="./js/main.js"></script>
    <script src="./js/menu.js"></script>
</body>
</html>