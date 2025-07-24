<?php
include "includes/db.php";

// Obtener el nombre de la marca desde la URL
$marca = $_GET['marca'] ?? '';

// Consulta para traer todos los productos de esa marca
$sql = "SELECT productos.*, categorias.nombre AS categoria_nombre 
        FROM productos 
        JOIN marcas ON productos.id_marca = marcas.id 
        JOIN categorias ON productos.id_categoria = categorias.id 
        WHERE marcas.nombre = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $marca);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Productos de <?php echo htmlspecialchars($marca); ?></title>
  <link rel="stylesheet" href="css/custom.css" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 pt-[120px] min-h-screen flex flex-col">

  <?php include 'menu.php'; ?>

  <main class="flex-grow max-w-7xl mx-auto px-6 pb-16">
    <h1 class="text-2xl font-bold mb-4">Productos de la marca: <?php echo htmlspecialchars($marca); ?></h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="bg-white rounded shadow p-4">
          <img src="assets/img/productos/<?php echo $row['imagen']; ?>" alt="<?php echo $row['nombre']; ?>" class="w-full h-48 object-contain mb-4" />
          <h2 class="text-lg font-semibold"><?php echo $row['nombre']; ?></h2>
          <p class="text-sm text-gray-600 mb-2">Categoría: <?php echo $row['categoria_nombre']; ?></p>
          <p class="text-gray-700"><?php echo $row['descripcion']; ?></p>
        </div>
      <?php endwhile; ?>
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
    // Activar menú móvil
    document.addEventListener('DOMContentLoaded', () => {
      const menuBtn = document.getElementById('menuBtn');
      const mobileMenu = document.getElementById('mobileMenu');
      if (menuBtn) {
        menuBtn.addEventListener('click', () => {
          mobileMenu.classList.toggle('hidden');
        });
      }
    });
  </script>
</body>
</html>
