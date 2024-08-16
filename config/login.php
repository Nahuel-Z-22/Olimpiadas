<?php 
require_once('config.php');
$email = $_POST['email'];
$password = $_POST['password'];

// Consulta para verificar si el usuario existe y obtener sus datos
$query = "SELECT u.id, u.email, u.contrasena, u.tipo_usuario FROM usuarios u WHERE email = '$email'";
$result = $conexion->query($query);
$row = $result->fetch_assoc();

if($result->num_rows > 0){
    // Verificamos si la contraseña ingresada coincide con la almacenada en la base de datos
    if (password_verify($password, $row['contrasena'])) {
        session_start();
        $_SESSION['user'] = $email;
        $_SESSION['tipo_usuario'] = $row['tipo_usuario'];
        header("Location: ../index.php");
    } else {
        header("Location: ../index.php?error=invalid_password");
    }
} else {
    header("Location: ../index.php?error=user_not_found");
}
?>