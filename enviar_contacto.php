<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'includes/PHPMailer/PHPMailer.php';
require 'includes/PHPMailer/SMTP.php';
require 'includes/PHPMailer/Exception.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = htmlspecialchars($_POST['nombre'] ?? '');
    $email = htmlspecialchars($_POST['correo'] ?? '');
    $mensaje = htmlspecialchars($_POST['mensaje'] ?? '');

    $mail = new PHPMailer(true);
    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com'; 
        $mail->SMTPAuth   = true;
        $mail->Username   = 'daniel.huerta@itedisa.com'; 
        $mail->Password   = 'Rchdhmc162419.'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Remitente y destinatario
        $mail->setFrom('daniel.huerta@itedisa.com', 'ITEDISA'); 
        $mail->addReplyTo($email, $nombre); // Para responder al remitente
        $mail->addAddress('daniel.huerta@itedisa.com', 'ITEDISA');

        // Contenido
        $mail->isHTML(true);
        $mail->Subject = "Nuevo mensaje de $nombre";
        $mail->Body    = "<strong>Nombre:</strong> $nombre <br>
                          <strong>Email:</strong> $email <br><br>
                          <strong>Mensaje:</strong><br>$mensaje";

        $mail->send();
        echo json_encode(["success" => true]);
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "error" => $mail->ErrorInfo
        ]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Método no permitido"]);
}

