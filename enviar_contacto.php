<?php
// Incluye PHPMailer
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $mensaje = $_POST['mensaje'] ?? '';

    $mail = new PHPMailer(true);
    try {
        // Configuración SMTP
        $mail->isSMTP();
        $mail->Host       = 'mail.tu-dominio.com'; // Cambia esto
        $mail->SMTPAuth   = true;
        $mail->Username   = 'contacto@itedisa.com'; // Cambia esto
        $mail->Password   = 'TU_PASSWORD';          // Cambia esto
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Remitente y destinatario
        $mail->setFrom('contacto@itedisa.com', 'ITEDISA');
        $mail->addAddress('contacto@itedisa.com'); // Destinatario

        // Contenido
        $mail->isHTML(true);
        $mail->Subject = "Nuevo mensaje de contacto";
        $mail->Body    = "
            <h3>Nuevo mensaje desde el formulario</h3>
            <p><b>Nombre:</b> {$nombre}</p>
            <p><b>Correo:</b> {$correo}</p>
            <p><b>Mensaje:</b><br>{$mensaje}</p>
        ";

        $mail->send();
        echo json_encode(['success' => true, 'message' => 'Mensaje enviado correctamente.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => "Error al enviar: {$mail->ErrorInfo}"]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
