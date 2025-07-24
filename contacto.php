<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require 'includes/PHPMailer/PHPMailer.php';
    require 'includes/PHPMailer/SMTP.php';
    require 'includes/PHPMailer/Exception.php';

    $nombre = htmlspecialchars($_POST['nombre']);
    $email = htmlspecialchars($_POST['email']);
    $mensaje = htmlspecialchars($_POST['mensaje']);

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'daniel.huerta@itedisa.com';
        $mail->Password = 'Rchdhmc162419.'; // Cambiar
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

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
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Contáctanos - ITEDISA</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .fade-in {
      animation: fadeIn 0.3s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body class="bg-gray-100 pt-[120px] flex flex-col min-h-screen">

  <?php include 'menu.php'; ?>

  <main class="flex-grow max-w-7xl mx-auto px-6 py-16">
    <div class="grid md:grid-cols-2 gap-8 items-center">
      
      <!-- Imagen + Texto -->
      <div class="relative">
        <img src="assets/Clientsupport.png" alt="Atención al cliente ITEDISA" class="rounded-2xl shadow-lg">
        <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center p-6 rounded-2xl">
          <h2 class="text-3xl font-bold text-white mb-4">Atención Personalizada</h2>
          <p class="text-gray-200 text-lg text-justify">
            En ITEDISA nos enorgullece ofrecer un servicio de atención al cliente rápido y eficaz. 
            Nuestro equipo está siempre listo para resolver tus dudas y brindarte la mejor experiencia.
          </p>
        </div>
      </div>

      <!-- Formulario -->
      <div class="bg-white shadow-lg rounded-2xl p-8 fade-in">
        <h2 class="text-2xl font-bold text-red-800 mb-6">Envíanos un mensaje</h2>
        <form id="contactForm" class="space-y-4">
          <div>
            <label class="block text-gray-700 font-semibold">Nombre</label>
            <input type="text" name="nombre" required class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-red-600" />
          </div>
          <div>
            <label class="block text-gray-700 font-semibold">Correo</label>
            <input type="email" name="correo" required class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-red-600" />
          </div>
          <div>
            <label class="block text-gray-700 font-semibold">Mensaje</label>
            <textarea name="mensaje" rows="4" required class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-red-600"></textarea>
          </div>

          <!-- Spinner de carga -->
          <div id="loading" class="hidden text-center">
            <div class="inline-block w-6 h-6 border-4 border-red-600 border-t-transparent rounded-full animate-spin"></div>
            <p class="text-gray-600 mt-2">Enviando mensaje...</p>
          </div>

          <!-- Mensajes de resultado -->
          <div id="result" class="hidden mt-4 text-center font-semibold"></div>

          <button type="submit" class="w-full bg-red-700 text-white py-2 rounded-lg hover:bg-red-800 transition">
            Enviar mensaje
          </button>
        </form>
      </div>
    </div>
  </main>

  <script>
    const form = document.getElementById('contactForm');
    const loading = document.getElementById('loading');
    const result = document.getElementById('result');

    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      loading.classList.remove('hidden');
      result.classList.add('hidden');
      
      const formData = new FormData(form);

      try {
        const response = await fetch('enviar_contacto.php', {
          method: 'POST',
          body: formData
        });

        const data = await response.json();
        loading.classList.add('hidden');
        result.classList.remove('hidden');

        if (data.success) {
          result.textContent = "¡Mensaje enviado correctamente!";
          result.className = "text-green-600 font-bold";
          form.reset();
        } else {
          result.textContent = "Hubo un error al enviar el mensaje.";
          result.className = "text-red-600 font-bold";
        }
      } catch (error) {
        loading.classList.add('hidden');
        result.classList.remove('hidden');
        result.textContent = "Error de conexión.";
        result.className = "text-red-600 font-bold";
      }
    });
  </script>

</body>
</html>

