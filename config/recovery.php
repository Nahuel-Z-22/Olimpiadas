<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

require_once('config.php');
$email = $_POST['email'];

$query = "SELECT * FROM usuarios WHERE email = '$email' AND contrasena IS NOT NULL AND contrasena != ''";
$result = $conexion->query($query);
$row = $result->fetch_assoc();

if($result->num_rows > 0){
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp-mail.outlook.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'Sport_Shop@outlook.com.ar';
        $mail->Password   = 'Account_Test_Mailer';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('Sport_Shop@outlook.com.ar', 'Sport Shop');
        $mail->addAddress($email, $row['nombre']); // Añadir el destinatario

        $mail->isHTML(true);
        $mail->Subject = 'Solicitud de Recuperacion de Cuenta - Sport Shop';
        $mail->Body    = '
            <p>Hola ' . $row['nombre'] . ',</p>
            <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta en <strong>Sport Shop</strong>.</p>
            <p>Para continuar con el proceso de recuperación de tu contraseña, por favor haz clic en el siguiente enlace:</p>
            <p><a href="http://localhost/sport_shop/change_password.php?id='.$row['id'].'" style="color: #007bff; text-decoration: none;">Restablecer Contraseña</a></p>
            <p>Si no solicitaste esta acción, ignora este correo y tu contraseña permanecerá segura.</p>
            <p>Gracias por confiar en <strong>Sport Shop</strong>.</p>
            <p>Atentamente,<br>El equipo de Sport Shop</p>
            <hr>
            <p style="font-size: 12px; color: #777;">Este correo ha sido generado automáticamente, por favor no respondas a este mensaje.</p>
        ';

        $mail->send();
        $updateQuery = "UPDATE usuarios SET clave_activa = TRUE WHERE email = '$email'";
        $conexion->query($updateQuery);
        header("Location: ../login.php?message=ok");
    } catch (Exception $e) {
        header("Location: ../login.php?message=error");
    }
} else {
    header("Location: ../login.php?message=not_found");
}
?>
