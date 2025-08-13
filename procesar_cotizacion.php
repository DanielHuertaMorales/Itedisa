<?php
session_start();

if (empty($_SESSION['cotizacion'])) {
    die("No hay productos en la cotización.");
}

$nombre = trim($_POST['nombre']);
$correo = trim($_POST['correo']);
$comentarios = trim($_POST['comentarios'] ?? '');

if (!$nombre || !$correo) {
    die("Faltan datos obligatorios.");
}

// Aquí podrías guardar en la base de datos o enviar por correo
// Ejemplo simple:
$mensaje = "Cotización de: $nombre <$correo>\n\n";
$mensaje .= "Comentarios: $comentarios\n\n";
$mensaje .= "Productos:\n";
foreach ($_SESSION['cotizacion'] as $prod) {
    $mensaje .= "- {$prod['nombre']} x {$prod['cantidad']}\n";
}

// mail("ventas@itedisa.com", "Nueva cotización", $mensaje); // Descomentar y ajustar

// Limpiar carrito
unset($_SESSION['cotizacion']);

echo "<p>Gracias, tu solicitud ha sido enviada.</p>";
?>
