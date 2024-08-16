<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="website icon" type="png" href="images/icon.png">
    <link rel="stylesheet" href="./css/recuperar.css">
</head>
<body>
    <div class="wrapper">
        <h1>¿Has olvidado la contraseña?</h1>
        <p class="instruction">Escribe el correo electrónico que usaste para registrarte. Te enviaremos un correo electrónico con un código para restablecer tu contraseña.</p>
        <form action="config/recovery.php" method="POST">
            <div class="input-box">
                <label for="email">Correo Electrónico</label>
                <input type="email" id="email" name="email" placeholder="name@example.com" required>
            </div>
            <div class="button-container">
                <button type="submit" class="btn">Enviar código</button>
            </div>
        </form>
    </div>
</body>
</html>
