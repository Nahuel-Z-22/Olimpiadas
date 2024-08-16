<?php
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['credential'];
    $client_id = '687228683238-dcuren27i3njg7b6emtr6icrskgufdkr.apps.googleusercontent.com'; // El mismo client_id de tu aplicación

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
            // Usuario ya existe, iniciar sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['apellido'] = $usuario['apellido'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];
            header('Location: index.php');
            exit;
        } else {
            // Redirigir a la página de login con mensaje de error
            $_SESSION['error_message'] = 'La cuenta no existe. Por favor, regístrate primero.';
            header('Location: login.php');
            exit;
        }
    } else {
        $_SESSION['error_message'] = 'Error en la autenticación con Google';
        header('Location: login.php');
        exit;
    }
} else {
    echo 'Método no permitido';
}
?>
