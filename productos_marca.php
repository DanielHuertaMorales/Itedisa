<?php
include 'includes/db.php';

$id_marca = isset($_GET['id_marca']) ? intval($_GET['id_marca']) : 0;

if ($id_marca === 0) {
  echo "ID de marca no válido.";
  exit;
}

$query = "SELECT nombre FROM marcas WHERE id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
  echo "Error al preparar la consulta.";
  exit;
}

$stmt->bind_param("i", $id_marca);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  echo "Marca no encontrada.";
  exit;
}

$marca = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- 🔁 Importante para responsive -->
  <title>Productos de <?php echo htmlspecialchars($marca['nombre']); ?> - ITEDISA</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
  <?php include 'menu.php'; ?>

  <div class="flex-grow max-w-7xl mx-auto px-4 pt-36 pb-8">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6 text-center sm:text-left">
      Productos de <?php echo htmlspecialchars($marca['nombre']); ?>
    </h1>

    <!-- Buscador -->
    <div class="mb-6">
      <input
        type="text"
        id="buscar"
        placeholder="Buscar productos..."
        class="w-full px-4 py-2 text-base sm:text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
        oninput="buscarProductosAjax(<?php echo $id_marca; ?>, this.value)"
      >
    </div>

    <!-- Contenedor de productos -->
    <div id="contenedor-productos" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <!-- Productos se cargan aquí por AJAX -->
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-gray-900 text-gray-300 py-10 px-4 sm:px-6">
    <div class="max-w-7xl mx-auto flex flex-col gap-8 md:flex-row justify-between items-center">
      
      <!-- Redes sociales -->
      <div class="flex space-x-6">
        <a href="https://www.facebook.com/people/Itedisa-SA-de-CV/100090168609896/?sk=about" target="_blank" rel="noopener noreferrer" aria-label="Facebook" class="hover:text-blue-600 transition">
          <svg class="w-7 h-7 sm:w-8 sm:h-8 fill-current" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54v-2.89h2.54V9.845c0-2.507 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.464h-1.26c-1.243 0-1.63.771-1.63 1.562v1.875h2.773l-.443 2.89h-2.33v6.987C18.343 21.128 22 16.99 22 12z"/></svg>
        </a>
        <a href="https://mx.linkedin.com/company/itedisasadecv" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn" class="hover:text-blue-400 transition">
          <svg class="w-7 h-7 sm:w-8 sm:h-8 fill-current" viewBox="0 0 24 24"><path d="M19 0h-14c-2.76 0-5 2.24-5 5v14c0 2.76 2.24 5 5 5h14c2.762 0 5-2.24 5-5v-14c0-2.76-2.238-5-5-5zm-11.75 20h-3v-11h3v11zm-1.5-12.22c-.967 0-1.75-.783-1.75-1.75s.783-1.75 1.75-1.75 1.75.783 1.75 1.75-.783 1.75-1.75 1.75zm13.25 12.22h-3v-5.5c0-1.38-.02-3.15-1.92-3.15-1.92 0-2.22 1.5-2.22 3.05v5.6h-3v-11h2.88v1.5h.04c.4-.75 1.38-1.54 2.84-1.54 3.04 0 3.6 2 3.6 4.6v6.44z"/></svg>
        </a>
      </div>

      <!-- Correo -->
      <div class="text-center md:text-left">
        <p class="font-semibold">Contacto:</p>
        <a href="mailto:contacto@itedisa.com" class="hover:text-red-600 transition break-all">contacto@itedisa.com</a>
      </div>

      <!-- Derechos -->
      <div class="text-center text-sm text-gray-500">
        &copy; <?php echo date('Y'); ?> ITEDISA. Hecho con <span class="text-red-600">❤️</span>.
      </div>
    </div>
  </footer>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      buscarProductosAjax(<?php echo $id_marca; ?>, '');
    });

    function buscarProductosAjax(id_marca, texto) {
      fetch('ajax_productos_marca.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id_marca=${id_marca}&buscar=${encodeURIComponent(texto)}&limite=20&offset=0`
      })
      .then(res => res.text())
      .then(html => {
        document.getElementById('contenedor-productos').innerHTML = html;
      });
    }
  </script>
</body>
</html>
