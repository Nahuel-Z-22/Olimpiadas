<?php 

$roles_permitidos = ['admin', 'cliente'];  // Ajusta según los roles que hayas definido en tipo_usuario

if(!array_key_exists('tipo_usuario', $_SESSION) || !in_array($_SESSION['tipo_usuario'], $roles_permitidos)){
   header("Location: index.php");
   exit(); // Asegura que el script se detenga después de redirigir
}
?>
