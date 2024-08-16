<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
include 'funciones.php';

$idProducto = $_GET['id'];

$producto = obtenerProductoPorId($idProducto);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $producto ? $producto['nombre'] : 'Producto no encontrado'; ?></title>
    <link rel="website icon" type="png" href="images/icon.png">
        <style>
/* Reset y fuente */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
}

/* Cuerpo */
body {
    font-family: "Poppins", sans-serif;
    background-color: #f6f6f6;
    color: #333;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    justify-content: flex-start;
    align-items: center;
    background: url(../images/fondo.jpg) no-repeat center center/cover;
}

/* Header */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    background-color: rgba(255, 255, 255, 0.8);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 1200px;
    margin-bottom: 20px;
}

.header a img {
    max-height: 50px;
    width: auto;
    cursor: pointer;
}

/* Contenedor principal */
.wrapper {
    width: 100%;
    max-width: 1200px;
    padding: 20px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    text-align: left;
    margin-bottom: 20px;
}

/* Breadcrumb */
.breadcrumb {
    margin-bottom: 20px;
    font-size: 14px;
    color: #555;
}

.breadcrumb a {
    color: #555;
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.breadcrumb span {
    color: #777;
}

/* Detalles del producto */
.product-detail {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.product-images {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.product-images img {
    width: 100%;
    max-width: 400px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.product-info {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.product-info h1 {
    font-size: 24px;
    margin-bottom: 10px;
    color: #333;
}

.price {
    font-size: 22px;
    color: #e74c3c;
    margin-bottom: 20px;
}

.specifications {
    margin-bottom: 20px;
    color: #555;
}

.specifications li {
    margin-bottom: 5px;
}

/* Formulario de cantidad y botón */
form {
    display: flex;
    flex-direction: column;
    margin-bottom: 20px;
}

form label {
    margin-bottom: 5px;
    color: #555;
}

form input[type="number"] {
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    background: #fff;
    color: #333;
}

.add-to-cart {
    background-color: #6330B6;
    color: #fff;
    padding: 10px;
    border: none;
    border-radius: 7px;
    cursor: pointer;
    font-size: 16px;
    text-transform: uppercase;
    transition: background-color 0.3s ease;
}

.add-to-cart:hover {
    background-color: #555;
}

.stock-info {
    margin-top: -17px;
    font-size: 16px;
    color: #555;
}

/* Descripción */
.description {
    margin-top: 20px;
    margin-bottom: 40px;
    color: #555;
}

.description p {
    margin-bottom: 10px;
    line-height: 1.5;
}

/* Footer */
.footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    background-color: rgba(255, 255, 255, 0.9);
    color: #777;
    font-size: 14px;
    border-top: 1px solid #ddd;
    width: 100%;
    max-width: 1200px;
    margin-top: auto; /* Este margen asegura que el footer se mantenga al final del contenido */
}

.footer div {
    display: flex;
    align-items: center;
}

.footer img {
    width: 40px;
    margin-right: 10px;
    vertical-align: middle;
}

    </style>
</head>
<body>
        <!-- Header -->
        <div class="header">
        <a href="index.php">
            <img src="./images/imagen.png" alt="Logo Izquierda">
        </a>
        <a href="perfil.php">
            <img src="./images/person-circle.png" alt="Logo Perfil">
        </a>
    </div>
    <div class="wrapper">
        <?php if ($producto) { 
            $rutaImagen = "/Sport_shop" . $producto['imagen'];
        ?>

        <div class="breadcrumb">
            <a href="index.php">Home</a> / <span><?php echo $producto['nombre']; ?></span>
        </div>

        <div class="product-detail">
            <div class="product-images">
                <img src="<?php echo $rutaImagen; ?>" alt="<?php echo $producto['nombre']; ?>" onerror="this.onerror=null;this.src='/Sport_shop/images/imagen_default.jpg';">
            </div>
            <div class="product-info">
                <h1><?php echo $producto['nombre']; ?></h1>
                <h4 class="price">$<?php echo number_format($producto['precio'], 2); ?></h4>
                <ul class="specifications">
                    <li><strong>Marca:</strong> <?php echo $producto['marca']; ?></li>
                    <li><strong>Modelo:</strong> <?php echo $producto['modelo']; ?></li>
                </ul>

                <!-- Add to Cart Form -->
                <form action="agregar_al_carrito.php" method="POST">
                    <input type="hidden" name="idProducto" value="<?php echo $producto['id']; ?>">
                    <input type="hidden" name="usuario_id" value="ID_USUARIO"> <!-- Asegúrate de reemplazar ID_USUARIO con la ID del usuario actual -->
                    <label for="qty">Cantidad:</label>
                    <input type="number" id="qty" name="qty" value="1" min="1">
                    <button type="submit" class="add-to-cart">Añadir al carrito</button>
                </form>


                <!-- Stock Information -->
                <div class="stock-info">
                    Stock: <?php echo $producto['stock']; ?>
                </div>

                <!-- Description -->
                <div class="description">
                    <p><?php echo $producto['descripcion']; ?></p>
                </div>
            </div>
        </div>
        <?php } else { ?>
            <p>Producto no encontrado.</p>
        <?php } ?>
    </div>

    <div class="footer">
        © 2024 Kevin Lionel Iza Mendieta.
        <div>
            <img src="./images/visa.svg" alt="Visa">
            <img src="./images/mastercard.svg" alt="MasterCard">
            <img src="./images/paypal.svg" alt="PayPal">
        </div>
    </div>
</body>
</html>