<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$success = '';
$error = '';

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

// Productos desde sesión
$carrito = $_SESSION['cotizacion'] ?? [];

// Manejo del envío
if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($carrito)) {
    $nombre = trim(htmlspecialchars($_POST['nombre']));
    $email = trim(htmlspecialchars($_POST['correo']));
    $comentarios = trim(htmlspecialchars($_POST['comentarios']));

    if (empty($nombre) || empty($email)) {
        $error = "Por favor completa tu nombre y correo.";
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
            $mail->Subject = "Solicitud de cotización de $nombre";

            // Construir lista de productos en HTML
            $productosHTML = "<ul style='padding-left:20px;'>";
            foreach ($carrito as $p) {
                $productosHTML .= "<li><strong>{$p['nombre']}</strong> - Cantidad: {$p['cantidad']}</li>";
            }
            $productosHTML .= "</ul>";

            $mail->Body = "
                <strong>Nombre:</strong> $nombre <br>
                <strong>Email:</strong> $email <br><br>
                <strong>Comentarios:</strong><br>" . nl2br($comentarios) . "<br><br>
                <strong>Productos solicitados:</strong><br>$productosHTML
            ";

            $mail->send();
            $success = "¡Cotización enviada con éxito! Te responderemos pronto.";

            // Limpiar carrito tras enviar
            unset($_SESSION['cotizacion']);
            $carrito = [];
        } catch (Exception $e) {
            $error = "Error al enviar la cotización: " . $mail->ErrorInfo;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Cotización</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen pt-[120px]">

  <?php include 'menu.php'; ?>
<div class="w-full h-full flex items-center justify-center p-6">
    <div class="w-full max-w-7xl h-full bg-white rounded-3xl shadow-xl p-8 grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- Columna izquierda: formulario + productos -->
        <div class="flex flex-col gap-6 h-full overflow-y-auto">
            <h1 class="text-4xl font-bold text-red-700 text-center lg:text-left">Tu Carrito de Cotización</h1>

            <?php if ($success): ?>
                <p class="text-green-600 font-semibold bg-green-100 p-3 rounded"><?= $success ?></p>
            <?php elseif ($error): ?>
                <p class="text-red-600 font-semibold bg-red-100 p-3 rounded"><?= $error ?></p>
            <?php endif; ?>

            <?php if(empty($carrito)): ?>
                <p class="text-gray-600">No hay productos en tu carrito.</p>
            <?php else: ?>
            <!-- Lista de productos en tarjetas -->
            <div id="productosContainer" class="flex flex-col gap-4 overflow-y-auto">
                <?php foreach($carrito as $i => $p): ?>
                <div class="flex justify-between items-center border p-4 rounded-lg shadow hover:shadow-md transition">
                    <div>
                        <p class="font-semibold text-gray-700"><?= htmlspecialchars($p['nombre']); ?></p>
                        <p class="text-gray-500 mt-1">Cantidad: 
                            <input type="number" min="1" value="<?= intval($p['cantidad']); ?>" 
                                   onchange="cambiarCantidad(<?= $i ?>, this.value)" 
                                   class="w-16 border rounded text-center p-1">
                        </p>
                    </div>
                    <button onclick="eliminarProducto(<?= $i ?>)" class="text-red-600 font-bold hover:text-red-800 transition text-xl">✕</button>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Formulario -->
            <form method="POST" onsubmit="return prepararEnvio()" class="flex flex-col gap-4 mt-4">
                <div>
                    <label class="block font-semibold mb-1">Nombre completo</label>
                    <input type="text" name="nombre" required class="w-full border p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Correo</label>
                    <input type="email" name="correo" required class="w-full border p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Comentarios (opcional)</label>
                    <textarea name="comentarios" class="w-full border p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                </div>

                <input type="hidden" name="productos_json" id="productosJson">

                <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-xl shadow hover:bg-green-700 transition mt-2">
                    Enviar Cotización
                </button>
            </form>
        </div>

        <!-- Columna derecha: imagen ilustrativa (oculta en móvil) -->
        <div class="hidden lg:flex items-center justify-center">
            <img src="assets/cotizar.png" alt="Mandar a cotizar" class="rounded-2xl shadow-lg w-full h-auto object-cover max-h-[600px]">
        </div>

    </div>
</div>

<script>
let carrito = <?php echo json_encode($carrito); ?>;

// Inicializar contador al cargar la página
window.cotizacionCantidad = carrito.length;
const contador = document.getElementById('contador-cotizacion');
if(contador) contador.textContent = window.cotizacionCantidad;

function renderProductos() {
    const container = document.getElementById("productosContainer");
    if(!container) return;
    container.innerHTML = "";
    carrito.forEach((p, i) => {
        container.innerHTML += `
        <div class="flex justify-between items-center border p-4 rounded-lg shadow hover:shadow-md transition" id="producto-${p.id}">
            <div>
                <p class="font-semibold text-gray-700">${p.nombre}</p>
                <p class="text-gray-500 mt-1">Cantidad: 
                    <input type="number" min="1" value="${p.cantidad}" onchange="cambiarCantidad(${i}, this.value)" class="w-16 border rounded text-center p-1">
                </p>
            </div>
            <button onclick="eliminarProducto(${i})" class="text-red-600 font-bold hover:text-red-800 transition text-xl">✕</button>
        </div>`;
    });
}

function cambiarCantidad(index, nuevaCantidad) {
    carrito[index].cantidad = parseInt(nuevaCantidad) || 1;
}

function eliminarProducto(index) {
    const idProducto = carrito[index].id;

    fetch('eliminar_cotizacion.php', {
        method: 'POST',
        body: new URLSearchParams({ id: idProducto })
    }).then(res => res.json())
      .then(data => {
          if(data.success) {
              // Eliminar del array y re-renderizar
              carrito.splice(index, 1);
              renderProductos();

              // Actualizar contador
              window.cotizacionCantidad = carrito.length;
              if(contador) contador.textContent = window.cotizacionCantidad;
          } else {
              alert("Error al eliminar el producto de la cotización");
          }
      }).catch(err => console.error(err));
}

function prepararEnvio() {
    document.getElementById("productosJson").value = JSON.stringify(carrito);
    return true;
}

// Render inicial
renderProductos();
</script>

</body>
</html>
