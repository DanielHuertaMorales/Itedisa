<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$success = '';
$error = '';

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim(htmlspecialchars($_POST['nombre']));
    $email = trim(htmlspecialchars($_POST['correo']));
    $mensaje = trim(htmlspecialchars($_POST['mensaje']));

    // Validación en servidor
    if (empty($nombre) || empty($email) || empty($mensaje)) {
        $error = "Por favor, completa todos los campos.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "El correo no es válido.";
    } else {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'mail.itedisa.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'daniel.huerta@itedisa.com';
            $mail->Password   = 'Rchdhmc162419.';  
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom($email, $nombre);
            $mail->addAddress('daniel.huerta@itedisa.com', 'ITEDISA');

            $mail->isHTML(true);
            $mail->Subject = "Nuevo mensaje de $nombre";
            $mail->Body    = "<strong>Nombre:</strong> $nombre <br>
                              <strong>Email:</strong> $email <br><br>
                              <strong>Mensaje:</strong><br>$mensaje";

            $mail->send();
            $success = "¡Gracias por contactarnos! Te responderemos pronto.";
        } catch (Exception $e) {
            $error = "Error al enviar el mensaje: " . $mail->ErrorInfo;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Contáctanos - ITEDISA</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background: url('assets/fondoContacto.png') no-repeat center center fixed;
      background-size: cover;
    }
    .fade-in { animation: fadeIn 0.5s ease-in-out; }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body class="bg-gray-100 bg-opacity-90 pt-[120px] flex flex-col min-h-screen">

  <?php include 'menu.php'; ?>

  <main class="flex-grow flex justify-center items-center px-6 py-16">
    <!-- Formulario Grande -->
    <div class="bg-white shadow-xl rounded-2xl p-10 fade-in max-w-2xl w-full">
      <h2 class="text-3xl font-bold text-red-800 mb-6 text-center">Envíanos un mensaje</h2>

      <div id="result">
        <?php if ($success): ?>
          <p class="text-green-600 font-bold mb-4 text-center"><?= $success ?></p>
        <?php elseif ($error): ?>
          <p class="text-red-600 font-bold mb-4 text-center"><?= $error ?></p>
        <?php endif; ?>
      </div>

      <form id="contactForm" method="POST" class="space-y-4">
        <div>
          <label class="block text-gray-700 font-semibold">Nombre</label>
          <input type="text" name="nombre" required class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-red-600" />
        </div>
        <div>
          <label class="block text-gray-700 font-semibold">Correo</label>
          <input type="email" name="correo" required class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-red-600" />
        </div>
        <div>
          <label class="block text-gray-700 font-semibold">Mensaje</label>
          <textarea name="mensaje" rows="5" required class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-red-600"></textarea>
        </div>

        <p id="clientError" class="hidden text-red-600 font-bold"></p>

        <button type="submit" class="w-full bg-red-700 text-white py-3 rounded-lg hover:bg-red-800 transition">
          Enviar mensaje
        </button>
      </form>
    </div>
  </main>

  <!-- Footer --> 
  <footer class="bg-gray-900 text-gray-300 py-10 px-4 sm:px-6">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
      <div class="flex space-x-6">
        <a href="https://www.facebook.com/people/Itedisa-SA-de-CV/100090168609896/?sk=about" target="_blank" rel="noopener noreferrer" aria-label="Facebook" class="hover:text-blue-600 transition">
          <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54v-2.89h2.54V9.845c0-2.507 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.464h-1.26c-1.243 0-1.63.771-1.63 1.562v1.875h2.773l-.443 2.89h-2.33v6.987C18.343 21.128 22 16.99 22 12z"/></svg>
        </a>
        <a href="https://mx.linkedin.com/company/itedisasadecv" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn" class="hover:text-blue-400 transition">
          <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path d="M19 0h-14c-2.76 0-5 2.24-5 5v14c0 2.76 2.24 5 5 5h14c2.762 0 5-2.24 5-5v-14c0-2.76-2.238-5-5-5zm-11.75 20h-3v-11h3v11zm-1.5-12.22c-.967 0-1.75-.783-1.75-1.75s.783-1.75 1.75-1.75 1.75.783 1.75 1.75-.783 1.75-1.75 1.75zm13.25 12.22h-3v-5.5c0-1.38-.02-3.15-1.92-3.15-1.92 0-2.22 1.5-2.22 3.05v5.6h-3v-11h2.88v1.5h.04c.4-.75 1.38-1.54 2.84-1.54 3.04 0 3.6 2 3.6 4.6v6.44z"/></svg>
        </a>
      </div>
      <div class="text-center md:text-left">
        <p class="font-semibold">Contacto:</p>
        <a href="mailto:contacto@itedisa.com" class="hover:text-red-600 transition">contacto@itedisa.com</a>
      </div>
      <div class="text-center text-sm text-gray-500">
        &copy; <?php echo date('Y'); ?> ITEDISA. Hecho con <span class="text-red-600">❤️</span>.
      </div>
    </div>
  </footer> 

  <script>
    const form = document.getElementById('contactForm');
    const clientError = document.getElementById('clientError');

    form.addEventListener('submit', (e) => {
      const nombre = form.nombre.value.trim();
      const correo = form.correo.value.trim();
      const mensaje = form.mensaje.value.trim();

      if (!nombre || !correo || !mensaje) {
        e.preventDefault();
        clientError.textContent = "Por favor completa todos los campos.";
        clientError.classList.remove('hidden');
      } else if (!/\S+@\S+\.\S+/.test(correo)) {
        e.preventDefault();
        clientError.textContent = "Por favor ingresa un correo válido.";
        clientError.classList.remove('hidden');
      } else {
        clientError.classList.add('hidden');
      }
    });
  </script>

</body>
</html>
