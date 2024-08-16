<?php 
require_once('config.php');
$id = $_POST['id'];
$pass = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

$query = "UPDATE usuarios SET contrasena = '$pass' WHERE id = $id";
$conexion->query($query);

header("Location: ../login.php?message=success_password");
?>
